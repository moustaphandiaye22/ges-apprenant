<?php
require_once __DIR__ . '/../../services/session.service.php';
require '/var/www/ges-apprenant/app/models/apprenant.model.php';

// Charger les référentiels
$referentielsFilePath = '/var/www/ges-apprenant/data/data.json';
$referentiels = json_decode(file_get_contents($referentielsFilePath), true)['referentiels'];

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("ID apprenant manquant.");
}

$id = intval($_GET['id']);
$apprenant = getApprenantById($id);

if (!$apprenant) {
    die("Apprenant introuvable.");
}

// Trouver le référentiel correspondant à l'ID
$referentiel = array_filter($referentiels, fn($r) => $r['id'] == $apprenant['referentiel_id']);
$referentiel = reset($referentiel);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Détails Apprenant</title>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Poppins', sans-serif;
      background-color: #fafafa;
      padding: 20px;
      margin-left: 15%;
    }

    .container {
      display: flex;
      gap: 20px;
    }

    .profile {
      background: white;
      border-radius: 15px;
      padding: 20px;
      width: 300px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
      display: flex;
      flex-direction: column;
      align-items: center;
    }

    .profile img {
      width: 120px;
      height: 120px;
      border-radius: 50%;
      object-fit: cover;
      margin-bottom: 15px;
    }

    .profile h2 {
      font-size: 20px;
      color: #333;
      margin-bottom: 8px;
      text-align: center;
    }

    .profile .role {
      background: #e0f8f0;
      color: #008060;
      padding: 5px 15px;
      border-radius: 20px;
      font-size: 13px;
      margin-bottom: 10px;
    }

    .profile .status {
      background: #d4edda;
      color: #28a745;
      padding: 5px 10px;
      border-radius: 12px;
      font-size: 12px;
      margin-bottom: 20px;
    }

    .contact-info {
      width: 100%;
      font-size: 14px;
      color: #555;
    }

    .contact-info p {
      margin-bottom: 10px;
    }

    .right-side {
      flex: 1;
      display: flex;
      flex-direction: column;
      gap: 20px;
    }

    .stats {
      display: flex;
      gap: 20px;
    }

    .stat-card {
      background: white;
      border-radius: 15px;
      padding: 20px;
      flex: 1;
      text-align: center;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .stat-card h3 {
      font-size: 22px;
      margin-bottom: 5px;
      color: #333;
    }

    .stat-card p {
      font-size: 14px;
      color: #888;
    }

    .modules {
      background: white;
      border-radius: 15px;
      padding: 20px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .modules-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 20px;
    }

    .modules-header h2 {
      font-size: 18px;
      color: #333;
    }

    .card-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
      gap: 20px;
    }

    .module-card {
      background: #fafafa;
      border-radius: 15px;
      padding: 20px;
      box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }

    .module-card .duration {
      background: black;
      color: white;
      padding: 4px 10px;
      border-radius: 10px;
      font-size: 12px;
      display: inline-block;
      margin-bottom: 10px;
    }

    .module-card h3 {
      font-size: 16px;
      color: #333;
      margin-bottom: 10px;
    }

    .module-card p {
      font-size: 13px;
      color: #777;
      margin-bottom: 15px;
    }

    .module-card .details {
      display: flex;
      justify-content: space-between;
      align-items: center;
      font-size: 12px;
      color: #666;
    }

    .module-card .details div {
      display: flex;
      align-items: center;
      gap: 5px;
    }

    .badge {
      background: #e0f8f0;
      color: #008060;
      padding: 3px 8px;
      border-radius: 10px;
      font-size: 10px;
      margin-bottom: 10px;
      display: inline-block;
    }
    .header input {
      padding: 10px 15px;
      border-radius: 10px;
      border: 1px solid #ccc;
      width: 300px;
    }
    .user-info {
      display: flex;
      align-items: center;
      gap: 10px;
    }
    .user-info span {
      font-size: 14px;
    }
    .user-avatar {
      background: #ff7900;
      color: white;
      width: 35px;
      height: 35px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: bold;
    }

    /* Conteneur principal du QR code */
    .qr-code-container {
        text-align: center;
        margin-top: 30px;
        padding: 20px;
        background-color: #ffffff;
        border-radius: 15px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        max-width: 300px;
        margin-left: 0%;
        margin-right: auto;
    }

    /* Titre du QR code */
    .qr-code-container h3 {
        font-size: 18px;
        color: #333;
        margin-bottom: 15px;
        font-weight: bold;
    }

    /* Image du QR code */
    .qr-code img {
        width: 200px;
        height: 200px;
        border-radius: 10px;
        border: 2px solid #00b398;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
    }

  </style>
</head>
<body>
<?php
include __DIR__ . '/../layouts/sidebar.php';
?>
<div class="header">
  <input type="text" id="search-input" placeholder="Rechercher une promotion...">
  <div class="user-info">
    <div class="user-avatar">
      <?= getSession('user') ? strtoupper(substr(getSession('user')['email'], 0, 1)) : '?' ?>
    </div>
    <span>
      <?= getSession('user') ? htmlspecialchars(getSession('user')['email']) : 'Invité' ?>
    </span>
  </div>
</div>
  <h1 style="margin-bottom: 20px; color: #008060;">Apprenants <span style="color: orange;">/ Détails</span></h1>

  <div class="container">

  <div class="profile">
      <a href="<?= $url ?>/ges-apprenant/public/apprenants" style="align-self: start; margin-bottom: 10px; color: #555;">&larr; Retour à la liste</a>
       <img src="<?= !empty($apprenant['document']) ? htmlspecialchars($apprenant['document']) : '/uploads/photos/default.jpg' ?>" alt="Profile Picture">
 

      <h2><?= htmlspecialchars($apprenant['prenom'] . ' ' . $apprenant['nom']) ?></h2>
      <div class="role">
        <?php
        $referentiel = array_filter($referentiels, fn($r) => $r['id'] == $apprenant['referentiel_id']);
        $referentiel = reset($referentiel);
        ?>
        <?= htmlspecialchars($referentiel['nom'] ?? 'Référentiel inconnu') ?>
      </div>
      <div class="status"><?= $apprenant['statut']?></div>

      <div class="contact-info">
        <p><strong>Tel:</strong> <?= htmlspecialchars($apprenant['telephone'] ?? 'Non défini') ?></p>
        <p><strong>Email:</strong> <?= htmlspecialchars($apprenant['email'] ?? 'Non défini') ?></p>
        <p><strong>Adresse:</strong> <?= htmlspecialchars($apprenant['adresse'] ?? 'Non définie') ?></p>
      </div>
    </div>

    <div class="right-side">
      <div class="stats">
        <div class="stat-card" style="background: #e0f8f0;">
          <h3><?= intval($apprenant['presences'] ?? 0) ?></h3>
          <p>Présence(s)</p>
        </div>
        <div class="stat-card" style="background: #fff8e1;">
          <h3><?= intval($apprenant['retards'] ?? 0) ?></h3>
          <p>Retard(s)</p>
        </div>
        <div class="stat-card" style="background: #fdecea;">
          <h3><?= intval($apprenant['absences'] ?? 0) ?></h3>
          <p>Absence(s)</p>
        </div>
      </div>

      <div class="modules">
        <div class="modules-header">
          <h2>Programme & Modules</h2>
          <span>Total absences par étudiant</span>
        </div>

        <div class="card-grid">
          <?php foreach ($apprenant['modules'] as $module): ?>
            <div class="module-card">
              <div class="duration"><?= htmlspecialchars($module['duree']) ?></div>
              <h3><?= htmlspecialchars($module['nom']) ?></h3>
              <p><?= htmlspecialchars($module['description']) ?></p>
              <div class="badge"><?= htmlspecialchars($module['niveau']) ?></div>
              <div class="details">
                <div>📅 <?= htmlspecialchars($module['date_debut']) ?></div>
                <div>⏰ <?= htmlspecialchars($module['heure']) ?></div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>

    </div>
  </div>
  <br><br>
 <?php
$qrData = sprintf(
    "%s %s %s %s %s",
    
    $apprenant['nom'] ?? '',
    $apprenant['prenom'] ?? '',
    $apprenant['email'] ?? '',
    $apprenant['telephone'] ?? '',
    // $promotion['nom'] ?? '',
    $referentiel['nom'] ?? ''
);
// Générer l'URL du QR code
$qrCodeUrl = "https://api.qrserver.com/v1/create-qr-code/?data=" . urlencode($qrData) . "&size=200x200";
?>
<div class="qr-code-container">
    <h3>QR Code de l'Apprenant</h3>
    <div class="qr-code">
        <img src="<?= $qrCodeUrl ?>" alt="QR Code">
    </div>
</div>
  </body>
</html>