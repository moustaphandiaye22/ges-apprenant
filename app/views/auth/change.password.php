<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Changer mot de passe</title>
    <link rel="stylesheet" href="<?= $url ?>/assets/css/style.css">
    <style>
        form {
            display: flex;
            flex-direction: column;
            gap: 20px;
            width: 300px;
            margin: 50px auto;
        }

        input, button {
            height: 40px;
            padding: 5px 10px;
            font-size: 14px;
        }

        button {
            background: var(--degrader-boutton);
            color: white;
            border: none;
            cursor: pointer;
        }

        .message {
            text-align: center;
            margin-bottom: 15px;
            color: red;
        }

        .success {
            color: green;
        }
    </style>
</head>
<body>
    <h2 style="text-align:center">Changer votre mot de passe</h2>

    <!-- Affichage des messages d'erreur ou de succès -->
    <?php if (!empty($error)) echo "<div class='message'>$error</div>"; ?>
    <?php if (!empty($success)) echo "<div class='message success'>$success</div>"; ?>

    <form method="post">
        <input type="password" name="old_password" placeholder="Ancien mot de passe" required>
        <input type="password" name="new_password" placeholder="Nouveau mot de passe" required>
        <button type="submit">Valider</button>
    </form>
</body>
</html>
