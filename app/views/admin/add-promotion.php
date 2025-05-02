<?php
require_once __DIR__ . '/../../services/session.service.php';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Créer une nouvelle promotion</title>
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
    .form-container input[type="date"],
    .form-container input[type="file"],
    .form-container select {
      width: 100%;
      padding: 10px;
      margin-bottom: 15px;
      border-radius: 8px;
      border: 1px solid #ccc;
    }
    .form-container .date-group {
      display: flex;
      gap: 10px;
    }
    .form-container .drop-zone {
      border: 2px dashed #ccc;
      border-radius: 10px;
      padding: 20px;
      text-align: center;
      color: #999;
      margin-bottom: 15px;
      cursor: pointer;
    }
    .form-container .drop-zone:hover {
      background-color: #f9f9f9;
    }
    .form-actions {
      display: flex;
      justify-content: flex-end;
      gap: 10px;
    }
    .cancel-btn {
      background-color: white;
      border: 1px solid #ccc;
      padding: 10px 15px;
      border-radius: 6px;
      cursor: pointer;
    }
    .submit-btn {
      background-color: #ff6600;
      color: white;
      padding: 10px 15px;
      border: none;
      border-radius: 6px;
      cursor: pointer;
    }
    .submit-btn:hover {
      background-color: #e65c00;
    }
  </style>
</head>
<body>
<?php
include __DIR__ . '/../layouts/sidebar.php'; 
?>
<div style="margin-left: 250px; padding: 20px;">
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
    <h2>Créer une nouvelle promotion</h2>
    <p>Remplissez les informations ci-dessous pour créer une nouvelle promotion.</p>

    <form method="POST" action="/ges-apprenant/public/add-promotion" enctype="multipart/form-data">
      <label for="nom">Nom de la promotion</label>
      <input type="text" id="nom" name="nom" placeholder="Ex: Promotion 2025" value="<?= htmlspecialchars(getSession('old')['nom'] ?? '') ?>" />
      <?php if (!empty(getSession('field_errors')['nom'])): ?>
        <div style="color: red;"><?= htmlspecialchars(getSession('field_errors')['nom']) ?></div>
      <?php endif; ?>

      <div class="date-group">
        <div style="flex: 1;">
          <label for="dateDebut">Date de début</label>
          <input type="text" id="dateDebut" name="date_debut" value="<?= htmlspecialchars(getSession('old')['date_debut'] ?? '') ?>" />
          <?php if (!empty(getSession('field_errors')['date_debut'])): ?>
            <div style="color: red;"><?= htmlspecialchars(getSession('field_errors')['date_debut']) ?></div>
          <?php endif; ?>
        </div>
        <div style="flex: 1;">
          <label for="dateFin">Date de fin</label>
          <input type="text" id="dateFin" name="date_fin" value="<?= htmlspecialchars(getSession('old')['date_fin'] ?? '') ?>" />
          <?php if (!empty(getSession('field_errors')['date_fin'])): ?>
            <div style="color: red;"><?= htmlspecialchars(getSession('field_errors')['date_fin']) ?></div>
          <?php endif; ?>
        </div>
      </div>

      <label for="photo">Photo de la promotion</label>
      <div class="drop-zone">
        <input type="file" id="photo" name="photo" accept="image/jpeg,image/png">
        <small>Format JPG, PNG. Taille max 2MB</small>
      </div>
      <?php if (!empty(getSession('field_errors')['photo'])): ?>
        <div style="color: red;"><?= htmlspecialchars(getSession('field_errors')['photo']) ?></div>
      <?php endif; ?>

      <label for="referentiel">Référentiels</label>
      <select name="referentiels[]" id="referentiel" multiple>
        <?php foreach ($data['referentiels'] as $referentiel): ?>
          <option value="<?= $referentiel['id'] ?>" <?= in_array($referentiel['id'], getSession('old')['referentiels'] ?? []) ? 'selected' : '' ?>>
            <?= htmlspecialchars($referentiel['nom']) ?>
          </option>
        <?php endforeach; ?>
      </select>
      <?php if (!empty(getSession('field_errors')['referentiels'])): ?>
        <div style="color: red;"><?= htmlspecialchars(getSession('field_errors')['referentiels']) ?></div>
      <?php endif; ?>

      <div class="form-actions">
        <button type="button" class="cancel-btn" onclick="window.history.back()">Annuler</button>
        <button type="submit" class="submit-btn">Créer la promotion</button>
      </div>
    </form>
  </div>
</div>
</body>
</html>