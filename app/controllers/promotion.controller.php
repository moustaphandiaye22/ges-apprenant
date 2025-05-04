<?php
require_once __DIR__ . '/../services/validator.service.php';
require_once __DIR__ . '/../services/session.service.php';

use App\Views\Fr\Messages;

function handleAddPromotion($data = [])
{
    if (!getSession('user')) {
        header('Location: /ges-apprenant/public/login');
        exit;
    }

    $filePath = __DIR__ . '/../../data/data.json';

    if (empty($data) && file_exists($filePath)) {
        $json = file_get_contents($filePath);
        $data = json_decode($json, true);
    }

    $data['promotions'] = $data['promotions'] ?? [];
    $errors = [];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nom = trim($_POST['nom'] ?? '');
        $dateDebut = $_POST['date_debut'] ?? '';
        $dateFin = $_POST['date_fin'] ?? '';
        $referentiels = $_POST['referentiels'] ?? [];
        $photo = $_FILES['photo'] ?? null;

        $errors[] = validateRequiredFields([
            'nom' => $nom,
            'date_debut' => $dateDebut,
            'date_fin' => $dateFin,
            'referentiels' => $referentiels,
        ]);

        $errors[] = validateDateOrder($dateDebut, $dateFin);

        $errors[] = validateUniquePromotionName($data['promotions'], $nom);

        if ($photo) {
            $errors[] = validateImage($photo);
        } else {
            $errors[] = "La photo est obligatoire.";
        }

        $errors = array_filter($errors);

        if (!empty($errors)) {
            setSession('errors', $errors);
            setSession('old', $_POST);
            header('Location: /ges-apprenant/public/promotions');
            exit;
        }

        $photoName = 'default.jpg';
        if ($photo && empty($errors)) {
            $uploadDir = __DIR__ . '/../../public/uploads/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

            $extension = pathinfo($photo['name'], PATHINFO_EXTENSION);
            $filename = uniqid('ref_', true) . '.' . $extension;
            $uploadPath = $uploadDir . $filename;

            if (move_uploaded_file($photo['tmp_name'], $uploadPath)) {
                $photoName = $filename;
            } else {
                $errors[] = "Échec de l'upload de l'image.";
            }
        }

        $newPromotion = [
            'id' => count($data['promotions']) + 1,
            'nom' => $nom,
            'date_debut' => $dateDebut,
            'date_fin' => $dateFin,
            'photo' => $photoName,
            'etat' => 'inactive',
            'statut' => 'pas encore commencer',
            'referentiels' => $referentiels,
            'apprenants' => []
        ];

        $data['promotions'][] = $newPromotion;

        file_put_contents($filePath, json_encode($data, JSON_PRETTY_PRINT));

        updatePromotionStatus();

        setSession('success', Messages::SUCCESS_PROMOTION_ADDED);
        header('Location: /ges-apprenant/public/promotions');
        exit;
    }

    require_once __DIR__ . '/../../views/layouts/base.layout.php';
    ob_start();
    $content = ob_get_clean();
    exit;
}

function handleActivatePromotion() {
    if (!getSession('user')) {
        echo json_encode(['success' => false, 'message' => 'Utilisateur non authentifié.']);
        exit;
    }

    if (!isset($_GET['id'])) {
        echo json_encode(['success' => false, 'message' => 'ID de promotion manquant.']);
        exit;
    }

    $filePath = __DIR__ . '/../../data/data.json';
    if (!file_exists($filePath)) {
        echo json_encode(['success' => false, 'message' => 'Fichier data.json introuvable.']);
        exit;
    }

    $data = json_decode(file_get_contents($filePath), true);
    if (!$data) {
        echo json_encode(['success' => false, 'message' => 'Impossible de lire le fichier JSON.']);
        exit;
    }

    $idToActivate = $_GET['id'];
    $promotionExists = false;
    $newState = '';

    $data['promotions'] = array_map(function ($promotion) use ($idToActivate, &$newState, &$promotionExists) {
        if ($promotion['id'] == $idToActivate) {
            $promotion['etat'] = 'active';
            $newState = 'active';
            $promotionExists = true;
        } else {
            $promotion['etat'] = 'inactive';
        }
        return $promotion;
    }, $data['promotions']);

    if (!$promotionExists) {
        echo json_encode(['success' => false, 'message' => 'Promotion introuvable.']);
        exit;
    }

    usort($data['promotions'], function ($a, $b) {
        return $b['etat'] === 'active' ? 1 : -1;
    });

    if (!file_put_contents($filePath, json_encode($data, JSON_PRETTY_PRINT))) {
        echo json_encode(['success' => false, 'message' => 'Impossible de sauvegarder les modifications.']);
        exit;
    }

    echo json_encode(['success' => true, 'newState' => $newState]);
    exit;
}

function showPromotions() {
    $term = isset($_GET['search']) ? trim($_GET['search']) : '';
    $filePath = __DIR__ . '/../../data/data.json';

    if (!file_exists($filePath)) {
        die('Fichier data.json introuvable.');
    }

    updatePromotionStatus();

    $data = json_decode(file_get_contents($filePath), true);
    if (!$data) {
        die('Impossible de lire le fichier JSON.');
    }

    $promotions = $data['promotions'];

    usort($promotions, function ($a, $b) {
        return $b['etat'] === 'active' ? 1 : -1;
    });

    if ($term) {
        $promotions = array_filter($promotions, function ($promo) use ($term) {
            return stripos($promo['nom'], $term) !== false;
        });
    }

    include __DIR__ . '/../../views/admin/promotions.php';
    exit;
}

function updatePromotionStatus() {
    $filePath = __DIR__ . '/../../data/data.json';

    if (!file_exists($filePath)) {
        setSession('errors', [Messages::ERROR_PROMOTION_FILE_NOT_FOUND]);
        header('Location: /ges-apprenant/public/promotions');
        exit;
    }

    $data = json_decode(file_get_contents($filePath), true);

    if (!$data || !isset($data['promotions'])) {
        setSession('errors', [Messages::ERROR_PROMOTION_READ_FAILED]);
        header('Location: /ges-apprenant/public/promotions');
        exit;
    }

    $currentYear = date('Y');

    $data['promotions'] = array_map(function ($promotion) use ($currentYear) {
        $yearFin = date('Y', strtotime($promotion['date_fin']));

        if ($yearFin < $currentYear) {
            $promotion['statut'] = 'terminé';
        } elseif ($yearFin == $currentYear) {
            $promotion['statut'] = 'en cours';
        } else {
            $promotion['statut'] = 'pas encore commencer';
        }

        return $promotion;
    }, $data['promotions']);

    if (!file_put_contents($filePath, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE))) {
        setSession('errors', [Messages::ERROR_SAVE_FAILED]);
        header('Location: /ges-apprenant/public/promotions');
        exit;
    }

    setSession('success', Messages::SUCCESS_PROMOTION_UPDATED);
}
