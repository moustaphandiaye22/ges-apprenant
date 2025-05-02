<?php 

require_once __DIR__ . '/../services/validator.service.php';
require_once __DIR__ . '/../services/session.service.php';
use App\Views\Fr\Messages;

function handleAddReferentiel($data = []) {
    $model = require __DIR__ . '/../models/referentiel.model.php';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nom = trim($_POST['nom'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $capacite = (int) ($_POST['capacite'] ?? 0);
        $sessions = trim($_POST['sessions'] ?? '');

        $errors = [];

        $errors[] = validateRequiredFields([
            'nom' => $nom,
            'description' => $description,
            'sessions' => $sessions,
        ]);

        $errors[] = validatePositiveInteger('capacite', $capacite);

        if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
            $errors[] = validateImage($_FILES['photo']);
        }

        $errors = array_filter($errors);

        if (!empty($errors)) {
            setSession('errors', $errors);
            setSession('old', $_POST);
            header('Location: /ges-apprenant/public/add-referentiel');
            exit;
        }

        $imagePath = 'promo_6801248866dbd6.44466840.jpeg'; // Par défaut
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '/../../public/uploads/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

            $extension = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
            $filename = uniqid('ref_', true) . '.' . $extension;
            $uploadPath = $uploadDir . $filename;

            if (move_uploaded_file($_FILES['photo']['tmp_name'], $uploadPath)) {
                $imagePath = $filename;
            } else {
                $errors[] = Messages::ERROR_FILE_UPLOAD;
            }
        }

        $model['add']([
            'nom' => $nom,
            'description' => $description,
            'capacite' => $capacite,
            'sessions' => $sessions,
            'photo' => $imagePath,
            'modules_count' => 1,
            'apprenants_count' => 1,
        ]);

        setSession('success', Messages::SUCCESS_REFERENTIEL_ADDED);
        header('Location: /referentiels');
        exit;
    }

    ob_start();
    require_once __DIR__ . '/../views/admin/add-referentiel.php';
    $content = ob_get_clean();
}

function handleListReferentiels() {
    $model = require __DIR__ . '/../models/referentiel.model.php';
    $referentiels = $model['all']();

    if (empty($referentiels)) {
        $error = Messages::ERROR_REFERENTIEL_NOT_FOUND;
    }

    $data = [
        'referentiels' => $referentiels,
        'error' => $error ?? null
    ];

    require_once __DIR__ . '/../../views/layouts/base.layout.php';
    ob_start();
    require_once __DIR__ . '/../../views/admin/referentiels.php';
    ?>
    <?php $errors = getSession('errors', []); ?>
    <?php if (!empty($errors)): ?>
        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?= htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
        </ul>
        <?php removeSession('errors'); ?>
    <?php endif; ?>
    <?php
    $content = ob_get_clean();
    exit;
}
