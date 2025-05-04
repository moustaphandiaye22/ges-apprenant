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
    <h2 style="text-align:center">Changer le mot de passe</h2>

    <?php if (!empty($error)): ?>
        <div style="color: red;"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <?php if (!empty($success)): ?>
        <div style="color: green;"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <form method="post">
        <input type="email" name="email" placeholder="Email" >
        <input type="password" name="new_password" placeholder="Nouveau mot de passe" >
        <button type="submit">Mettre à jour</button>
    </form>
</body>
</html>
