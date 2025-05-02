<?php

namespace App\Views\Fr;

class Messages
{
    // Messages de succès
    const SUCCESS_ADD_APPRENANT = "Apprenant ajouté avec succès.";
    const SUCCESS_EMAIL_SENT = "Email envoyé avec succès.";
    const SUCCESS_PASSWORD_CHANGED = "Mot de passe modifié avec succès.";
    const SUCCESS_PROMOTION_UPDATED = "Les statuts des promotions ont été mis à jour avec succès.";
    const SUCCESS_PROMOTION_ADDED = "Promotion ajoutée avec succès.";
    const SUCCESS_REFERENTIEL_ADDED = "Référentiel ajouté avec succès.";

    // Messages d'erreurs
    const ERROR_ADD_APPRENANT = "Une erreur s'est produite lors de l'ajout de l'apprenant.";
    const ERROR_EMAIL_NOT_SENT = "L'email n'a pas pu être envoyé.";
    const ERROR_FILE_UPLOAD = "Échec de l'upload du fichier.";
    const ERROR_INVALID_CREDENTIALS = "Identifiants incorrects.";
    const ERROR_PASSWORD_MISMATCH = "Ancien mot de passe ou email incorrect.";
    const ERROR_SAVE_FAILED = "Erreur lors de la sauvegarde des modifications.";
    const ERROR_PROMOTION_FILE_NOT_FOUND = "Fichier data.json introuvable.";
    const ERROR_PROMOTION_READ_FAILED = "Impossible de lire les données des promotions.";
    const ERROR_REFERENTIEL_NOT_FOUND = "Aucun référentiel trouvé.";
}