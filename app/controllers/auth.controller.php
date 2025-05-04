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

                // Rediriger en fonction du profil
                if (isset($user['profil']) && $user['profil'] === 'Apprenant') {
                    header('Location: /ges-apprenant/public/apprenant-dashboard');
                } else {
                    header('Location: /ges-apprenant/public/dashboard');
                }
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
    $user = array_filter($users, fn($user) => $user['login'] === $login && $user['password'] === $password);
    if (!empty($user)) {
        return reset($user); 
    }

    $jsonPath = __DIR__ . '/../data/data.json';
    $data = json_decode(file_get_contents($jsonPath), true);
    $apprenants = $data['apprenants'] ?? [];

    $apprenant = array_filter($apprenants, fn($apprenant) => $apprenant['email'] === $login && password_verify($password, $apprenant['password']));
    if (!empty($apprenant)) {
        $apprenant = reset($apprenant); 
        if ($apprenant['must_change_password']) {
            $_SESSION['user'] = $apprenant;
            header('Location: /ges-apprenant/public/change-password.php');
            exit;
        }
        return $apprenant;
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
        $newPassword = $_POST['new_password'] ?? '';

        // Validation des champs requis
        if (empty($email) || empty($newPassword)) {
            $error = "L'email et le nouveau mot de passe sont requis.";
        }

        if (!$error) {

            $users = json_decode(file_get_contents($jsonPath), true);

            if (!$users) {
                $error = "Erreur lors du chargement des utilisateurs.";
            } else {

                $found = false;
                foreach ($users as &$user) {
                    if ($user['email'] === $email) {

                        $user['password'] = password_hash($newPassword, PASSWORD_DEFAULT);


                        if (file_put_contents($jsonPath, json_encode($users, JSON_PRETTY_PRINT))) {
                            $success = Messages::SUCCESS_PASSWORD_CHANGED;
                        } else {
                            $error = Messages::ERROR_PASSWORD_MISMATCH;
                        }
                        $found = true;
                        break;
                    }
                }

                if (!$found) {
                    $error = "L'utilisateur avec cet email n'existe pas.";
                }
            }
        }
    }


    ob_start();
    require_once __DIR__ . '/../views/auth/change.password.php';
    $content = ob_get_clean();
    echo $content;
}

