<?php

function validateRequiredFields($fields)
{
    foreach ($fields as $field => $value) {
        if (empty($value)) {
            return "Le champ $field est obligatoire.";
        }
    }
    return null;
}

function validateDateOrder($startDate, $endDate)
{
    if (strtotime($startDate) > strtotime($endDate)) {
        return "La date de début doit être antérieure à la date de fin.";
    }
    return null;
}

function validateUniquePromotionName($promotions, $name)
{
    foreach ($promotions as $promo) {
        if (strcasecmp($promo['nom'], $name) === 0) {
            return "Ce nom de promotion existe déjà.";
        }
    }
    return null;
}

function validatePositiveInteger($field, $value)
{
    if (!filter_var($value, FILTER_VALIDATE_INT, ["options" => ["min_range" => 1]])) {
        return "Le champ $field doit être un entier positif.";
    }
    return null;
}

function validateImage($photo, $allowedTypes = ['image/jpeg', 'image/png'], $maxSize = 2 * 1024 * 1024)
{
    if ($photo['error'] !== UPLOAD_ERR_OK) {
        return "Erreur lors de l'upload de l'image.";
    }

    if (!in_array($photo['type'], $allowedTypes)) {
        return "L'image doit être au format JPG ou PNG.";
    }

    if ($photo['size'] > $maxSize) {
        return "L'image ne doit pas dépasser 2MB.";
    }

    if (!getimagesize($photo['tmp_name'])) {
        return "Le fichier uploadé n'est pas une image valide.";
    }

    $extension = pathinfo($photo['name'], PATHINFO_EXTENSION);
    $allowedExtensions = ['jpg', 'jpeg', 'png'];
    if (!in_array(strtolower($extension), $allowedExtensions)) {
        return "Extension de fichier non autorisée.";
    }

    return null;
}

function validatePasswordMatch($password, $confirmPassword)
{
    if ($password !== $confirmPassword) {
        return "Les mots de passe ne correspondent pas.";
    }
    return null;
}

function validateOldPassword($oldPassword, $hashedPassword)
{
    return password_verify($oldPassword, $hashedPassword);
}

function validateEmail($email)
{
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return "L'adresse email n'est pas valide.";
    }
    return null;
}

function validateUniqueEmail($email, $emailExistsCallback)
{
    if ($emailExistsCallback($email)) {
        return "Cet email est déjà utilisé par un autre apprenant.";
    }
    return null;
}

function validateUniqueTelephone($telephone, $telephoneExistsCallback)
{
    if ($telephoneExistsCallback($telephone)) {
        return "Ce numéro de téléphone est déjà utilisé par un autre apprenant.";
    }
    return null;
}

function validateFileUpload($file, $allowedTypes = ['application/pdf'], $maxSize = 2 * 1024 * 1024)
{
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return "Erreur lors de l'upload du fichier.";
    }

    if (!in_array($file['type'], $allowedTypes)) {
        return "Le fichier doit être au format PDF.";
    }

    if ($file['size'] > $maxSize) {
        return "Le fichier ne doit pas dépasser 2MB.";
    }

    return null;
}