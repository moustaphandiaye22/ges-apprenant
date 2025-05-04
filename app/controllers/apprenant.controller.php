<?php

require_once __DIR__ . '/../services/validator.service.php';
require_once __DIR__ . '/../services/session.service.php';
require_once __DIR__ . '/../models/model.php';
require_once __DIR__ . '/../services/mail.service.php';

use App\Views\Fr\Messages;
use function App\Models\jsonToArray;
use function App\Models\arrayToJson;

function handleListApprenants() {
    $model = require __DIR__ . '/../models/apprenant.model.php';

    $apprenants = $model['all']();

    $apprenants = array_map(function ($apprenant) {
        if (!isset($apprenant['statut'])) {
            $apprenant['statut'] = 'Actif';
        }
        return $apprenant;
    }, $apprenants);

    $data = [
        'apprenants' => $apprenants,
    ];

    require_once __DIR__ . '/../../views/layouts/base.layout.php';
    ob_start();
    require_once __DIR__ . '/../views/admin/apprenants.php';
    $content = ob_get_clean();
    exit;
}

function handleAddApprenant() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        require_once __DIR__ . '/../models/apprenant.model.php';
        require_once __DIR__ . '/../services/session.service.php';

        $errors = getSession('errors', []);
        removeSession('errors');

        $prenom = trim($_POST['prenom'] ?? '');
        $nom = trim($_POST['nom'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $date_naissance = trim($_POST['date_naissance'] ?? '');
        $lieu_naissance = trim($_POST['lieu_naissance'] ?? '');
        $adresse = trim($_POST['adresse'] ?? '');
        $telephone = trim($_POST['telephone'] ?? '');
        $tuteur_nom = trim($_POST['tuteur_nom'] ?? '');
        $tuteur_lien = trim($_POST['tuteur_lien'] ?? '');
        $tuteur_adresse = trim($_POST['tuteur_adresse'] ?? '');
        $tuteur_telephone = trim($_POST['tuteur_telephone'] ?? '');
        $referentiel_id = trim($_POST['referentiel_id'] ?? '');
        $errors = [];

        $requiredFields = [
            'Prénom' => $prenom,
            'Nom' => $nom,
            'Date de naissance' => $date_naissance,
            'Lieu de naissance' => $lieu_naissance,
            'Adresse' => $adresse,
            'Téléphone' => $telephone,
            'Email' => $email,
            'Nom du tuteur' => $tuteur_nom,
            'Lien de parenté' => $tuteur_lien,
            'Adresse du tuteur' => $tuteur_adresse,
            'Téléphone du tuteur' => $tuteur_telephone,
            'Référentiel' => $referentiel_id,
        ];

        $errors = array_reduce(array_keys($requiredFields), function ($carry, $field) use ($requiredFields) {
            if (empty($requiredFields[$field])) {
                $carry[] = "Le champ $field est requis.";
            }
            return $carry;
        }, []);

        $errors[] = validateEmail($email);

        $errors[] = validateUniqueEmail($email, 'emailExists');

        $errors[] = validateUniqueTelephone($telephone, 'telephoneExists');

        if (isset($_FILES['document']) && $_FILES['document']['error'] === UPLOAD_ERR_OK) {
            $errors[] = validateImage($_FILES['document']);
        }

        $errors = array_filter($errors);

        if (!empty($errors)) {
            session_start();
            $_SESSION['errors'] = $errors;
            $_SESSION['old'] = $_POST;
            header('Location: /ges-apprenant/public/add-apprenant');
            exit;
        }

        $document = null;
        if (isset($_FILES['document']) && $_FILES['document']['error'] === UPLOAD_ERR_OK) {
            $document = '/uploads/' . basename($_FILES['document']['name']);
            if (!move_uploaded_file($_FILES['document']['tmp_name'], __DIR__ . '/../../public' . $document)) {
                $_SESSION['errors'] = ["Échec de l'upload du fichier."];
                header('Location: /ges-apprenant/public/add-apprenant.php');
                exit;
            }
        }

        

        $result = addApprenant($prenom, $nom, $_POST['date_naissance'], $_POST['lieu_naissance'], $_POST['adresse'], $_POST['telephone'], $email, $_POST['tuteur_nom'], $_POST['tuteur_lien'], $_POST['tuteur_adresse'], $_POST['tuteur_telephone'], $_FILES['document']['name'], $_POST['referentiel_id'], $login, $hashedPassword);

        if ($result) {
            $promotionFilePath = __DIR__ . '/../../data/data.json';
            $data = jsonToArray($promotionFilePath);

            $promotionActive = array_filter($data['promotions'], function ($promotion) {
                return $promotion['etat'] === 'active' && $promotion['statut'] === 'en cours';
            });

            if (!empty($promotionActive)) {
                $promotionActive = reset($promotionActive); 
                $promotionId = $promotionActive['id'];

                // Ajouter l'apprenant à la promotion
                $data['promotions'] = array_map(function ($promotion) use ($promotionId, $result) {
                    if ($promotion['id'] === $promotionId) {
                        $promotion['apprenants'][] = $result['id'];
                    }
                    return $promotion;
                }, $data['promotions']);
                arrayToJson($promotionFilePath, $data);
            }

           

            if (mail($email, $subject, $message, $headers)) {
                setSession('success', Messages::SUCCESS_ADD_APPRENANT . " " . Messages::SUCCESS_EMAIL_SENT);
            } else {
                $errors = getSession('errors', []);
                $errors[] = Messages::SUCCESS_ADD_APPRENANT . ", mais " . Messages::ERROR_EMAIL_NOT_SENT;
                setSession('errors', $errors);
            }
        } else {
            $_SESSION['errors'][] = Messages::ERROR_ADD_APPRENANT;
        }

        header('Location: /ges-apprenant/public/apprenants');
        exit;
    }
}

function handleApprenantDetails() {
    if (!isset($_GET['id']) || empty($_GET['id'])) {
        die("ID apprenant manquant.");
    }

    $id = intval($_GET['id']); 
    require_once __DIR__ . '/../models/apprenant.model.php';

    $apprenant = getApprenantById($id);

    if (!$apprenant) {
        die("Apprenant introuvable.");
    }

    $apprenant = array_filter($apprenants, fn($a) => $a['id'] === $id);
    $apprenant = reset($apprenant); // Récupérer le premier élément trouvé

    $data = [
        'apprenant' => $apprenant,
    ];

    require_once __DIR__ . '/../../views/layouts/base.layout.php';
    ob_start();
    require_once __DIR__ . '/../views/admin/apprenant-details.php';
    $content = ob_get_clean();
    exit;
}

