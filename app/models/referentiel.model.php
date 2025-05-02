<?php

namespace Models\Referentiel;

require_once __DIR__ . '/model.php';

use function App\Models\jsonToArray;
use function App\Models\arrayToJson;

$filePath = __DIR__ . '/../../data/data.json';

$getAllReferentiels = fn() => (
    file_exists($filePath)
        ? (jsonToArray($filePath)['referentiels'] ?? [])
        : []
);

$addReferentiel = function(array $newRef) use ($filePath) {
    $data = jsonToArray($filePath);

    $data['referentiels'] = $data['referentiels'] ?? [];

    $newRef['id'] = uniqid('ref_');
    $data['referentiels'][] = $newRef;

    if (arrayToJson($filePath, $data) === false) {
        throw new \Exception("Erreur lors de l'écriture dans le fichier JSON.");
    }
};

return [
    'all' => $getAllReferentiels,
    'add' => $addReferentiel,
];
