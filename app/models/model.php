<?php

namespace App\Models;


function jsonToArray(string $filePath): array
{
    if (!file_exists($filePath)) {
        return [];
    }

    $content = file_get_contents($filePath);
    if ($content === false) {
        throw new \RuntimeException("Erreur lors de la lecture du fichier : $filePath");
    }

    $data = json_decode($content, true);
    if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
        throw new \RuntimeException("Erreur lors du décodage JSON : " . json_last_error_msg());
    }

    return $data;
}


function arrayToJson(string $filePath, array $data): bool
{
    $directory = dirname($filePath);

    if (!file_exists($directory) && !mkdir($directory, 0777, true) && !is_dir($directory)) {
        throw new \RuntimeException("Impossible de créer le dossier : $directory");
    }

    $jsonData = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    if ($jsonData === false) {
        throw new \RuntimeException("Erreur lors de l'encodage JSON : " . json_last_error_msg());
    }

    return file_put_contents($filePath, $jsonData) !== false;
}