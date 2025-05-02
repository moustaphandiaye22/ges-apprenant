<?php
require_once __DIR__ . '/../services/validator.service.php';
require_once __DIR__ . '/../services/session.service.php';
use App\Views\Fr\Messages;

function handleLogin($data)
{
    $error = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $login = $_POST['login'] ?? '';
        $password = $_POST['password'] ?? '';

        $error = validateRequiredFields([
            'login' => $login,
            'mot de passe' => $password,
        ]);

        if (!$error) {
            $user = authenticateUser($data['users'], $login, $password);

            if ($user) {
                setSession('user', $user); 
                header('Location: /ges-apprenant/public/dashboard');
                exit;
            } else {
                $error = Messages::ERROR_INVALID_CREDENTIALS;
            }
        }
    }

    ob_start();
    require_once __DIR__ . '/../views/auth/login.php';
    $content = ob_get_clean();
    echo $content;
}

function authenticateUser($users, $login, $password) {
    foreach ($users as $user) {
        if ($user['login'] === $login && $user['password'] === $password) {
            return $user;
        }
    }
    return false;
}

function handleChangePassword($data)
{
    $error = '';
    $success = '';
    $jsonPath = __DIR__ . '/../data/data.json';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email = $_POST['email'] ?? '';
        $oldPassword = $_POST['old_password'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        // Validation des champs requis
        $error = validateRequiredFields([
            'email' => $email,
            'ancien mot de passe' => $oldPassword,
            'nouveau mot de passe' => $newPassword,
            'confirmation du mot de passe' => $confirmPassword,
        ]);

        if (!$error) {
            // Validation des mots de passe
            $error = validatePasswordMatch($newPassword, $confirmPassword);
        }

        if (!$error) {
            $users = json_decode(file_get_contents($jsonPath), true);

            // Recherche de l'utilisateur
            $found = false;
            foreach ($users as &$user) {
                if ($user['email'] === $email && validateOldPassword($oldPassword, $user['password'])) {
                    $user['password'] = password_hash($newPassword, PASSWORD_DEFAULT);

                    if (file_put_contents($jsonPath, json_encode($users, JSON_PRETTY_PRINT))) {
                        $success = Messages::SUCCESS_PASSWORD_CHANGED;
                    } else {
                        $error = Messages::ERROR_SAVE_FAILED;
                    }
                    $found = true;
                    break;
                }
            }

            if (!$found) {
                $error = Messages::ERROR_PASSWORD_MISMATCH;
            }
        }
    }

    ob_start();
    require_once __DIR__ . '/../views/auth/change.password.php';
    $content = ob_get_clean();
    echo $content;
}

