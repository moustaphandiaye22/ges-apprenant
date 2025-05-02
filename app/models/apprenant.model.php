<?php

require_once __DIR__ . '/model.php';

use function App\Models\jsonToArray;
use function App\Models\arrayToJson;

return [
    'all' => function () {
        $filePath = __DIR__ . '/../../data/data.json';
        $data = jsonToArray($filePath);
        return $data['apprenants'] ?? [];
    }
];

function addApprenant($prenom, $nom, $date_naissance, $lieu_naissance, $adresse, $telephone, $email, $tuteur_nom, $tuteur_lien, $tuteur_adresse, $tuteur_telephone, $document, $referentiel_id) {
    $filePath = __DIR__ . '/../../data/data.json';

    $data = jsonToArray($filePath);
    $apprenants = $data['apprenants'] ?? [];

    if (emailExists($email)) {
        return false; 
    }

    $lastId = count($apprenants) > 0 ? end($apprenants)['id'] : 0;
    $matricule = 'ODC' . date('Y') . str_pad($lastId + 1, 4, '0', STR_PAD_LEFT);

    $newApprenant = [
        'id' => $lastId + 1,
        'matricule' => $matricule,
        'prenom' => $prenom,
        'nom' => $nom,
        'date_naissance' => $date_naissance,
        'lieu_naissance' => $lieu_naissance,
        'adresse' => $adresse,
        'telephone' => $telephone,
        'email' => $email,
        'referentiel_id' => $referentiel_id, 
        'tuteur_nom' => $tuteur_nom,
        'tuteur_lien' => $tuteur_lien,
        'tuteur_adresse' => $tuteur_adresse,
        'tuteur_telephone' => $tuteur_telephone,
        'document' => $document,
        'statut' => 'Actif',
        'must_change_password' => true 
    ];

    $apprenants[] = $newApprenant;
    $data['apprenants'] = $apprenants;

    if (!arrayToJson($filePath, $data)) {
        return false; 
    }

    $temporaryPassword = bin2hex(random_bytes(4)); 

    // URL de connexion
    $loginUrl = 'http://ndiaye.moustapha.sa.edu.sn:9001/ges-apprenant/public/login';

    try {
        sendEmail(
            $newApprenant['email'],
            $newApprenant['prenom'],
            $temporaryPassword,
            $loginUrl
        );
    } catch (Exception $e) {
        error_log("Erreur d'envoi d'email: " . $e->getMessage());
    }

    return $newApprenant; 
}

function emailExists($email) {
    $filePath = __DIR__ . '/../../data/data.json';
    $data = jsonToArray($filePath);
    $apprenants = $data['apprenants'] ?? [];

    foreach ($apprenants as $apprenant) {
        if ($apprenant['email'] === $email) {
            return true; 
        }
    }
    return false; 
}

function telephoneExists($telephone) {
    $filePath = __DIR__ . '/../../data/data.json';
    $data = jsonToArray($filePath);
    $apprenants = $data['apprenants'] ?? [];

    foreach ($apprenants as $apprenant) {
        if ($apprenant['telephone'] === $telephone) {
            return true;
        }
    }

    return false;
}

function getApprenantById($id) {
    $filePath = __DIR__ . '/../../data/data.json';
    $data = jsonToArray($filePath);
    $apprenants = $data['apprenants'] ?? [];

    foreach ($apprenants as $apprenant) {
        if ($apprenant['id'] == $id) {
            return $apprenant; 
        }
    }

    return null; 
}

function getAllApprenants() {
    $filePath = __DIR__ . '/../../data/data.json';
    $data = jsonToArray($filePath);
    return $data['apprenants'] ?? []; 
}