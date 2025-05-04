<?php
require_once __DIR__ . '/../../services/session.service.php';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <link href="https://fonts.googleapis.com/css2?family=Inter&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <title>Liste des Apprenants</title>
  <style>
    :root {
      --orange: #f77f00;
      --vert: #4CAF50;
      --rouge: #e74c3c;
      --gris: #f5f5f5;
      --gris-clair: #fafafa;
      --noir: #333;
      --bleu: #007bff;
      --font: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    body {
      font-family: var(--font);
      margin: 0;
      padding: 2%;
      background-color: white;
      margin-left: 13%;
    }

    header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      flex-wrap: wrap;
      margin-bottom: 2%;
    }

    .title {
      font-size: 1.8em;
      font-weight: bold;
      color: var(--bleu);
    }

    .actions {
      display: flex;
      gap: 1em;
      flex-wrap: wrap;
      margin-top: 1em;
    }

    .btn {
      padding: 0.5em 1em;
      border: none;
      border-radius: 0.5em;
      font-weight: bold;
      cursor: pointer;
      color: white;
      font-size: 0.95em;
    }

    .btn-black {
      background-color: #000;
    }

    .btn-green {
      background-color: var(--vert);
    }

    .btn-red {
      background-color: var(--rouge);
    }

    .dropdown {
      position: relative;
      display: inline-block;
    }

    .dropdown-toggle {
      cursor: pointer;
    }

    .dropdown-menu {
      display: none;
      position: absolute;
      background-color: white;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      border-radius: 5px;
      margin-top: 5px;
      z-index: 1000;
      min-width: 150px;
    }

    .dropdown-menu .dropdown-item {
      padding: 10px 15px;
      text-decoration: none;
      color: #333;
      display: block;
    }

    .dropdown-menu .dropdown-item:hover {
      background-color: #f5f5f5;
    }

    .dropdown:hover .dropdown-menu {
      display: block;
    }

    .search-bar {
      display: flex;
      flex-wrap: wrap;
      gap: 1em;
      margin-bottom: 2%;
    }

    input[type="text"],
    select {
      padding: 0.6em 1em;
      border: 1px solid #ccc;
      border-radius: 0.4em;
      width: 100%;
      max-width: 18%;
      min-width: 160px;
    }

    .tabs {
      display: flex;
      border-bottom: 0.3em solid var(--orange);
      margin-bottom: 1em;
    }

    .tab {
      padding: 0.7em 1.5em;
      font-weight: bold;
      cursor: pointer;
    }

    .tab.active {
      color: var(--orange);
      border-bottom: 0.3em solid var(--orange);
    }

    table {
      width: 100%;
      border-collapse: collapse;
      font-size: 0.95em;
    }

    thead {
      background-color: var(--orange);
      color: white;
    }

    th, td {
      padding: 1em;
      text-align: left;
      word-wrap: break-word;
    }

    tbody tr:nth-child(even) {
      background-color: var(--gris-clair);
    }

    .photo img {
      width: 2em;
      height: 2em;
      border-radius: 50%;
      object-fit: cover;
    }

    .badge {
      padding: 0.3em 0.8em;
      border-radius: 1em;
      color: white;
      font-size: 0.8em;
      font-weight: bold;
    }

    .badge-vert { background-color: var(--vert); }
    .badge-rouge { background-color: var(--rouge); }

    .referentiel {
      font-weight: bold;
      color: var(--bleu);
    }

    .pagination {
      display: flex;
      flex-wrap: wrap;
      justify-content: space-between;
      align-items: center;
      margin-top: 2%;
      gap: 1em;
    }

    .pages {
      display: flex;
      gap: 0.5em;
    }

    .page-btn {
      padding: 0.5em 1em;
      background: #ddd;
      border: none;
      border-radius: 0.4em;
      font-size: 0.9em;
    }

    .page-btn.active {
      background-color: var(--orange);
      color: white;
    }

    @media screen and (max-width: 768px) {
      .search-bar input,
      .search-bar select {
        max-width: 100%;
        flex: 1 1 100%;
      }

      table, thead, tbody, th, td, tr {
        display: block;
      }

      thead {
        display: none;
      }

      tr {
        margin-bottom: 1em;
        background-color: var(--gris-clair);
        padding: 1em;
        border-radius: 0.5em;
      }

      td {
        padding: 0.5em 0;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid #ccc;
      }

      td::before {
        content: attr(data-label);
        font-weight: bold;
        flex-basis: 45%;
      }

      td:last-child {
        border: none;
      }

      .photo img {
        width: 2.5em;
        height: 2.5em;
      }
    } /* pour le user  */
    .main {
      flex: 1;
      padding: 20px 30px;
    }
    .header {
      display: flex;
      justify-content: space-between;
      align-items: center;
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


  </style>
</head>
<body>
<?php $url = "http://" . $_SERVER["HTTP_HOST"]; ?>

<?php
include __DIR__ . '/../layouts/sidebar.php';
?>
<div class="main">
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

<?php $success = getSession('success'); ?>
<?php if ($success): ?>
  <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
  <?php removeSession('success'); ?>
<?php endif; ?>

<?php $errors = getSession('errors', []); ?>
<?php if (!empty($errors)): ?>
  <?php foreach ($errors as $error): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
  <?php endforeach; ?>
  <?php removeSession('errors'); ?>
<?php endif; ?>

  <header>
    <div class="title">Apprenants <span style="color:gray; font-weight: normal;">(<?= count($data['apprenants']) ?> apprenants)</span></div>
    <div class="actions">
      <div class="dropdown">
        <button class="btn btn-black dropdown-toggle">Télécharger la liste</button>
        <div class="dropdown-menu">
          <a href="<?= $url ?>/public/download.php?format=pdf" class="dropdown-item">Télécharger en PDF</a>
          <a href="<?= $url ?>/ges-apprenant/public/download.php?format=excel" class="dropdown-item">Télécharger en Excel</a>
        </div>
      </div>
      <button class="btn btn-green" onclick="window.location.href='<?= $url ?>/ges-apprenant/public/add-apprenant'">+ Ajouter apprenant</button>
    </div>
  </header>

  <div class="search-bar">
  <form method="GET" action="">
    <input type="text" name="search_query" placeholder="Rechercher par nom ou matricule..." value="<?= htmlspecialchars($_GET['search_query'] ?? '') ?>">
    <select name="referentiel_filter" onchange="this.form.submit()">
      <option value="">-- Filtrer par référentiel --</option>
      <?php
      $referentiels = json_decode(file_get_contents('/var/www/ges-apprenant/data/data.json'), true)['referentiels'];
      foreach ($referentiels as $referentiel): ?>
        <option value="<?= htmlspecialchars($referentiel['nom']) ?>" 
          <?= isset($_GET['referentiel_filter']) && $_GET['referentiel_filter'] === $referentiel['nom'] ? 'selected' : '' ?>>
          <?= htmlspecialchars($referentiel['nom']) ?>
        </option>
      <?php endforeach; ?>
    </select>
    <select name="statut_filter" onchange="this.form.submit()">
      <option value="">-- Filtrer par statut --</option>
      <option value="Actif" <?= isset($_GET['statut_filter']) && $_GET['statut_filter'] === 'Actif' ? 'selected' : '' ?>>Actif</option>
      <option value="Inactif" <?= isset($_GET['statut_filter']) && $_GET['statut_filter'] === 'Inactif' ? 'selected' : '' ?>>Inactif</option>
    </select>
    <button type="submit" class="btn btn-black">Rechercher</button>
</form>
</div>

  <div class="tabs">
    <div class="tab active">Liste des retenues</div>
    <div class="tab">Liste d'attente</div>
  </div>

<?php
$itemsPerPage = 10;
$apprenants = $data['apprenants'];

if (isset($_GET['search_query']) && !empty($_GET['search_query'])) {
    $searchQuery = strtolower(trim($_GET['search_query']));
    $apprenants = array_filter($apprenants, function ($apprenant) use ($searchQuery) {
        return strpos(strtolower($apprenant['prenom'] . ' ' . $apprenant['nom']), $searchQuery) !== false ||
               strpos(strtolower($apprenant['matricule']), $searchQuery) !== false;
    });
}

if (isset($_GET['referentiel_filter']) && !empty($_GET['referentiel_filter'])) {
    $referentielFilter = $_GET['referentiel_filter'];
    $apprenants = array_filter($apprenants, function ($apprenant) use ($referentielFilter, $referentiels) {
        $referentiel = array_filter($referentiels, fn($r) => $r['nom'] === $referentielFilter);
        $referentiel = reset($referentiel);

        return $referentiel && $apprenant['referentiel_id'] == $referentiel['id'];
    });
}

if (isset($_GET['statut_filter']) && !empty($_GET['statut_filter'])) {
    $statutFilter = $_GET['statut_filter'];
    $apprenants = array_filter($apprenants, function ($apprenant) use ($statutFilter) {
        return $apprenant['statut'] === $statutFilter;
    });
}

$totalItems = count($apprenants);
$totalPages = ceil($totalItems / $itemsPerPage);
$currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$currentPage = max(1, min($currentPage, $totalPages));

$startIndex = ($currentPage - 1) * $itemsPerPage;
$paginatedApprenants = array_slice($apprenants, $startIndex, $itemsPerPage);
?>

<table>
  <thead>
    <tr>
      <th>Photo</th>
      <th>Matricule</th>
      <th>Prénom et Nom</th>
      <th>Adresse</th>
      <th>Téléphone</th>
      <th>Référentiel</th>
      <th>Statut</th>
      <th>Actions</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($paginatedApprenants as $apprenant): ?>
      <tr>
        <td data-label="Photo" class="photo">
          <img src="<?= !empty($apprenant['document']) ? htmlspecialchars($apprenant['document']) : '/uploads/photos/default.jpg' ?>" 
               alt="Photo de <?= htmlspecialchars($apprenant['prenom'] . ' ' . $apprenant['nom']) ?>">
        </td>
        <td data-label="Matricule"><?= htmlspecialchars($apprenant['matricule']) ?></td>
        <td data-label="Prénom et Nom"><?= htmlspecialchars($apprenant['prenom'] . ' ' . $apprenant['nom']) ?></td>
        <td data-label="Adresse"><?= htmlspecialchars($apprenant['adresse']) ?></td>
        <td data-label="Téléphone"><?= htmlspecialchars($apprenant['telephone']) ?></td>
        <td data-label="Référentiel">
  <?php

$referentiel = array_filter($referentiels, fn($r) => $r['id'] == $apprenant['referentiel_id']);
  $referentiel = reset($referentiel);


  echo $referentiel ? htmlspecialchars($referentiel['nom']) : 'Référentiel inconnu';
  ?>
</td>
        <td data-label="Statut">
          <span class="badge <?= $apprenant['statut'] === 'Actif' ? 'badge-vert' : 'badge-rouge' ?>">
            <?= htmlspecialchars($apprenant['statut']) ?>
          </span>
        </td>
        <td data-label="Actions">
          <?php 
            $detailsUrl = $url . '/ges-apprenant/public/apprenant-details';
            $apprenantId = $apprenant['id'];
          ?>
          <a href="<?= $detailsUrl ?>?id=<?= $apprenantId ?>" class="btn btn-black">Détails</a>
          <?php if ($apprenant['statut'] === 'Actif'): ?>
            <a href="?action=change-statut&id=<?= $apprenantId ?>&statut=Inactif" 
               class="btn btn-red" 
               >
               Désactiver
            </a>
          <?php else: ?>
            <a href="?action=change-statut&id=<?= $apprenantId ?>&statut=Actif" 
               class="btn btn-green" 
               >
               Activer
            </a>
          <?php endif; ?>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<div class="pagination">
  <?php if ($currentPage > 1): ?>
    <a href="?page=<?= $currentPage - 1 ?>" class="page-btn">Précédent</a>
  <?php endif; ?>

  <?php for ($i = 1; $i <= $totalPages; $i++): ?>
    <a href="?page=<?= $i ?>" class="page-btn <?= $i === $currentPage ? 'active' : '' ?>">
      <?= $i ?>
    </a>
  <?php endfor; ?>

  <?php if ($currentPage < $totalPages): ?>
    <a href="?page=<?= $currentPage + 1 ?>" class="page-btn">Suivant</a>
  <?php endif; ?>
</div>

</body>
</html>
<?php
// filepath: /var/www/ges-apprenant/app/views/admin/apprenants.php

$filePath = '/var/www/ges-apprenant/data/data.json';
$data = json_decode(file_get_contents($filePath), true);
$apprenants = $data['apprenants'] ?? [];


$data = json_decode(file_get_contents($filePath), true);
$apprenants = $data['apprenants'] ?? [];
?>