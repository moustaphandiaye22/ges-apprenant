<!-- filepath: /var/www/ges-apprenant/public/add_apprenant.php -->
<?php
require_once __DIR__ . '/../../services/session.service.php';
$url = "http://" . $_SERVER["HTTP_HOST"];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Ajout Apprenant</title>
  <link rel="stylesheet" href="<?= $url ?>/ges-apprenant/assets/css/add_apprenant.css">
  <style>

:root {
  --color-bg: #ffffff;
  --color-border: #e0e0e0;
  --color-input-bg: #f8f8f8;
  --color-text-dark: #333;
  --color-label: #555;
  --color-accent: #088d84;
  --color-cancel: #999;
  --color-outline: #d9d9d9;
  --color-dashed-border: #88a2ff;
  --color-upload-bg: #eef1ff;
  --color-upload-text: #1559d0;
  --color-orange: #ff8800;
}

* {
  box-sizing: border-box;
}

body {
  font-family: "Segoe UI", sans-serif;
  margin: 0;
  background-color: #f4f4f4;
  padding: 2rem;
}

.container {
  max-width: 1200px;
  margin: auto;
  background-color: var(--color-bg);
  border: 1px solid var(--color-outline);
  border-radius: 10px;
  padding: 2rem;
  border: #333 solid 5px;
}

h2 {
  text-align: center;
  margin-bottom: 2rem;
  color: var(--color-text-dark);
}

.section-title {
  font-weight: bold;
  margin: 2rem 0 1rem;
  display: flex;
  align-items: center;
  gap: 8px;
  color: var(--color-text-dark);
}

.section-title::after {
  content: '✎';
  font-size: 16px;
  color: var(--color-accent);
}

.form-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
  gap: 1rem 2rem;
}

label {
  font-size: 14px;
  color: var(--color-label);
  margin-bottom: 6px;
  display: block;
}

input {
  width: 100%;
  padding: 12px;
  border: 1px solid var(--color-border);
  background-color: var(--color-input-bg);
  border-radius: 8px;
  font-size: 14px;
  color: var(--color-text-dark);
}

.form-group {
  margin-bottom: 1rem;
}

.date-picker-icon {
  position: relative;
}

.date-picker-icon::after {
  content: '📅';
  position: absolute;
  right: 10px;
  top: 61%;
  transform: translateY(-50%);
  font-size: 20px;
  color: var(--color-orange);
}

.upload-box {
  border: 2px dashed var(--color-dashed-border);
  background-color: var(--color-upload-bg);
  border-radius: 8px;
  padding: 1.5rem;
  text-align: center;
  color: var(--color-upload-text);
  font-weight: 500;
  font-size: 14px;
  height: 100%;
  display: flex;
  justify-content: center;
  align-items: center;
}

.upload-box::before {
  content: "📁";
  display: block;
  font-size: 22px;
  margin-bottom: 0.5rem;
}

.actions {
  margin-top: 2rem;
  display: flex;
  justify-content: flex-end;
  gap: 1rem;
}

.btn {
  padding: 10px 20px;
  border-radius: 6px;
  font-weight: bold;
  border: none;
  cursor: pointer;
  font-size: 14px;
}

.btn.cancel {
  background: transparent;
  color: var(--color-cancel);
}

.btn.save {
  background-color: var(--color-accent);
  color: #fff;
}

.alert-danger {
  color: #fff;
  background-color: #dc3545;
  padding: 10px;
  border-radius: 5px;
  margin-bottom: 15px;
}
.alert-danger p {
  margin: 0;
}

.alert-success {
  color: #fff;
  background-color: #28a745;
  padding: 10px;
  border-radius: 5px;
  margin-bottom: 15px;
}

@media (max-width: 768px) {
  .form-grid {
    grid-template-columns: 1fr;
  }
}
  </style>
</head>
<body>

  <div class="container">
    <h2>Ajout apprenant</h2>

    <?php $errors = getSession('errors', []); ?>
    <?php if (!empty($errors)): ?>
      <div class="alert alert-danger">
        <?php foreach ($errors as $error): ?>
          <p><?= htmlspecialchars($error) ?></p>
        <?php endforeach; ?>
        <?php removeSession('errors'); ?>
      </div>
    <?php endif; ?>

    <?php $success = getSession('success'); ?>
    <?php if ($success): ?>
      <div class="alert alert-success">
        <p><?= htmlspecialchars($success) ?></p>
        <?php removeSession('success'); ?>
      </div>
    <?php endif; ?>

    <div class="section-title">Informations de l’apprenant</div>
    <form action="/ges-apprenant/public/add-apprenant" method="POST" enctype="multipart/form-data">
      <div class="form-grid">
        <div class="form-group">
          <label>Prénom(s)</label>
          <input type="text" name="prenom" >
        </div>
        <div class="form-group">
          <label>Nom</label>
          <input type="text" name="nom" >
        </div>
        <div class="form-group date-picker-icon">
          <label>Date de naissance</label>
          <input type="date" name="date_naissance" >
        </div>
        <div class="form-group">
          <label>Lieu de naissance</label>
          <input type="text" name="lieu_naissance" >
        </div>
        <div class="form-group" style="grid-column: span 2;">
          <label>Adresse</label>
          <input type="text" name="adresse" >
        </div>
        <div class="form-group">
          <label>Téléphone</label>
          <input type="text" name="telephone" >
        </div>
        <div class="form-group">
          <label>Email</label>
          <input type="email" name="email" >
        </div>
        <div class="form-group upload-wrapper">
          <input type="file" id="upload-file" name="document" accept="image/*" hidden>
          <label for="upload-file" class="upload-box">
            Ajouter des documents
          </label>
        </div>
        <div class="form-group">
          <label>Référentiel</label>
          <select name="referentiel_id" >
            <option value="">-- Sélectionnez un référentiel --</option>
            <?php
            $referentiels = json_decode(file_get_contents('/var/www/ges-apprenant/data/data.json'), true)['referentiels'];
            foreach ($referentiels as $referentiel): ?>
              <option value="<?= htmlspecialchars($referentiel['id']) ?>">
                <?= htmlspecialchars($referentiel['nom']) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>

      <div class="section-title">Informations du tuteur</div>
      <div class="form-grid">
        <div class="form-group">
          <label>Prénom(s) & nom</label>
          <input type="text" name="tuteur_nom" >
        </div>
        <div class="form-group">
          <label>Lien de parenté</label>
          <input type="text" name="tuteur_lien" >
        </div>
        <div class="form-group">
          <label>Adresse</label>
          <input type="text" name="tuteur_adresse" >
        </div>
        <div class="form-group">
          <label>Téléphone</label>
          <input type="text" name="tuteur_telephone" >
        </div>
      </div>

      <div class="actions">
        <button type="button" class="btn cancel" onclick="window.history.back()">Annuler</button>
        <button type="submit" class="btn save">Enregistrer</button>
      </div>
    </form>
  </div>
  <?php
include __DIR__ . '/../layouts/sidebar.php'; 
?>
</body>
</html>