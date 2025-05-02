<?php
require_once __DIR__ . '/../../services/session.service.php';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Ajouter un Référentiel</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  
  <style>
    body {
      font-family: 'Inter', sans-serif;
      background-color: #f9f9f9;
      padding: 20px;
    }
    .form-container {
      max-width: 800px;
      margin: 0 auto;
      background-color: white;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
    }
    .form-container h2 {
      font-size: 22px;
      margin-bottom: 20px;
      color: #333;
    }
    .form-container label {
      font-weight: 600;
      display: block;
      margin-bottom: 5px;
    }
    .form-container input[type="text"],
    .form-container input[type="number"],
    .form-container textarea,
    .form-container select {
      width: 100%;
      padding: 10px;
      margin-bottom: 15px;
      border-radius: 8px;
      border: 1px solid #ccc;
    }
    .form-container .image-upload {
      border: 2px dashed #ccc;
      text-align: center;
      padding: 20px;
      margin-bottom: 20px;
      cursor: pointer;
      border-radius: 10px;
    }
    .form-container .image-upload img {
      width: 40px;
      opacity: 0.5;
    }
    .form-container .image-upload p {
      font-size: 14px;
      color: #777;
    }
    .form-container .form-row {
      display: flex;
      gap: 10px;
    }
    .form-container .form-row > div {
      flex: 1;
    }
    .form-container .form-actions {
      display: flex;
      justify-content: flex-end;
      gap: 10px;
    }
    .form-container .cancel-btn {
      background-color: transparent;
      border: none;
      color: #777;
      cursor: pointer;
    }
    .form-container .submit-btn {
      background-color: #9de2d3;
      border: none;
      padding: 10px 20px;
      border-radius: 8px;
      cursor: pointer;
      font-weight: bold;
    }
  </style>
</head>
<body>
<?php
include __DIR__ . '/../layouts/sidebar.php'; // Inclure le layout principal
?>

<?php $errors = getSession('errors', []); ?>
<?php if (!empty($errors)): ?>
  <div style="color: red; margin-bottom: 20px;">
    <ul>
      <?php foreach ($errors as $error): ?>
        <li><?= htmlspecialchars($error) ?></li>
      <?php endforeach; ?>
    </ul>
    <?php removeSession('errors'); ?>
  </div>
<?php endif; ?>

  <div class="form-container">
    <h2>Créer un nouveau référentiel</h2>
    <form method="POST" action="/ges-apprenant/public/add-referentiel" enctype="multipart/form-data">
      <div class="image-upload">
        <label for="photoUpload">
          <img src="https://img.icons8.com/ios/50/image--v1.png" alt="Ajouter une image">
          <p>Cliquez pour ajouter une photo</p>
        </label>
        <input type="file" id="photoUpload" name="photo" accept="image/*">
      </div>
      
      <label>Nom*</label>
      <input type="text" placeholder="Nom du référentiel" name="nom" value="<?= htmlspecialchars(getSession('old')['nom'] ?? '') ?>">

      <label>Description</label>
      <textarea placeholder="Description" name="description" rows="3"><?= htmlspecialchars(getSession('old')['description'] ?? '') ?></textarea>

      <div class="form-row">
        <div>
          <label>Capacité*</label>
          <input type="number" value="<?= htmlspecialchars(getSession('old')['capacite'] ?? '30') ?>" name="capacite" min="1">
        </div>
        <div>
          <label>Nombre de sessions*</label>
          <select name="sessions" required>
            <option value="1" <?= (getSession('old')['sessions'] ?? '') == '1' ? 'selected' : '' ?>>1 session</option>
            <option value="2" <?= (getSession('old')['sessions'] ?? '') == '2' ? 'selected' : '' ?>>2 sessions</option>
            <option value="3" <?= (getSession('old')['sessions'] ?? '') == '3' ? 'selected' : '' ?>>3 sessions</option>
          </select>
        </div>
      </div>

      <div class="form-actions">
        <button type="button" class="cancel-btn" onclick="window.history.back()">Annuler</button>
        <button type="submit" class="submit-btn">Créer</button>
      </div>
    </form>
  </div>
  
</body>
</html>