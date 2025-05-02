<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Liste des promotions</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://fonts.googleapis.com/css2?family=Inter&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    /* ========== GLOBAL ========== */
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Inter', sans-serif;
    }

    body {
      display: flex;
      background-color: #f9f9f9;
    }

   
    /* ========== MAIN SECTION ========== */
    .main {
      margin-left: 220px;
      flex: 1;
      padding: 30px;
      transition: margin-left 0.3s ease;
    }

    #sidebar-toggle:not(:checked) ~ .main {
      margin-left: 0;
    }

    .header {
      margin-bottom: 30px;
    }

    /* ========== STATS CARDS ========== */
    .stats {
      display: flex;
      gap: 20px;
      margin-bottom: 30px;
    }

    .stat-card {
      background-color: #ff7900;
      color: white;
      padding: 20px;
      border-radius: 10px;
      flex: 1;
      display: flex;
      flex-direction: column;
      align-items: center;
      font-weight: bold;
    }

    .stat-card i {
      font-size: 24px;
      margin-bottom: 10px;
    }

    /* ========== TABLE STYLES ========== */
    .promotion-table {
      background-color: white;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 0 5px rgba(0,0,0,0.05);
    }

    .promotion-table h2 {
      margin-bottom: 15px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
    }

    table thead {
      background-color: #ff7900;
      color: white;
    }

    table th, table td {
      padding: 12px;
      border: 1px solid #ddd;
      text-align: left;
    }

    table tbody tr:nth-child(even) {
      background-color: #f9f9f9;
    }

    /* ========== SEARCH + VIEW MODE ========== */
    .search-section {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 20px;
    }

    .search-section input {
      padding: 10px;
      width: 300px;
      border: 1px solid #ccc;
      border-radius: 5px;
    }

    .search-section .view-mode {
      display: flex;
      gap: 10px;
    }

    .view-mode button {
      padding: 8px 15px;
      border: none;
      border-radius: 5px;
      background-color: #eee;
      cursor: pointer;
    }

    .view-mode .active {
      background-color: #ff7900;
      color: white;
    }

    /* ========== UTILITIES ========== */
    .photo {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      object-fit: cover;
    }

    .tags span {
      padding: 3px 6px;
      border-radius: 5px;
      font-size: 12px;
      margin-right: 5px;
      color: white;
    }

    .tag-dev { background: #2ecc71; }
    .tag-ref { background: #3498db; }
    .tag-data { background: #9b59b6; }
    .tag-aws { background: #f39c12; }
    .tag-hackeuse { background: #e91e63; }

    .status.active {
      color: green;
      font-weight: bold;
    }

    .status.inactive {
      color: red;
      font-weight: bold;
    }

    .actions i {
      color: #333;
      cursor: pointer;
    }

    /* ========== RESPONSIVE ========== */
    @media (max-width: 1024px) {
      .sidebar {
        display: none;
      }

      .main {
        padding: 15px;
      }

      .stats {
        flex-direction: column;
      }

      .search-section {
        flex-direction: column;
        gap: 10px;
      }

      table thead {
        display: none;
      }

      table, table tbody, table tr, table td {
        display: block;
        width: 100%;
      }

      table tr {
        margin-bottom: 15px;
        border-bottom: 1px solid #ccc;
      }

      table td {
        text-align: right;
        padding-left: 50%;
        position: relative;
      }

      table td::before {
        content: attr(data-label);
        position: absolute;
        left: 10px;
        width: 45%;
        padding-right: 10px;
        white-space: nowrap;
        font-weight: bold;
        text-align: left;
      }
    }
    .promotions-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
      gap: 20px;
    }

    .promotion-card {
      background-color: white;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 0 5px rgba(0,0,0,0.05);
    }

    .promotion-card h4 {
      margin-bottom: 10px;
    }

    .promotion-card p {
      font-size: 14px;
      color: #555;
    }

    .promotion-card .status {
      display: inline-block;
      padding: 5px 10px;
      border-radius: 15px;
      font-size: 12px;
      margin-bottom: 10px;
    }

    .status.active {
      background-color: #d4f4dc;
      color: #2e7d32;
    }

    .status.inactive {
      background-color: #fdecea;
      color: #c62828;
    }

    .details-btn {
      margin-top: 15px;
      display: inline-block;
      color: #ff7900;
      text-decoration: none;
      font-weight: 500;
    }
   

    
    /* pour le user  */
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

  /* Style des cartes de promotion */
  .promotions-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 20px;
    margin-top: 20px;
  }

  .promotion-card {
    background-color: white;
    border-radius: 10px;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    padding: 20px;
    position: relative;
    display: flex;
    flex-direction: column;
    height: 100%;
  }

  .promo-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 15px;
  }

  .promo-avatar {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid #f2f2f2;
  }

  /* Pour l'image dans la vue grille */
  .promotion-card .promo-avatar {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    object-fit: cover;
  }

  /* Si vous avez besoin d'ajouter une image complète en haut de la carte */
  .promotion-card img.promo-image {
    width: 100%;
    height: 150px;
    object-fit: cover;
    border-radius: 8px 8px 0 0;
    margin-bottom: 15px;
  }

  .promo-status {
    display: flex;
    align-items: center;
    gap: 8px;
  }

  .badge {
    font-size: 12px;
    font-weight: 500;
    padding: 5px 10px;
    border-radius: 20px;
    text-transform: capitalize;
  }

  .badge.active {
    background-color: #e6f4ea;
    color: #2e7d32;
  }

  .badge.inactive {
    background-color: #fdecea;
    color: #c62828;
  }

  .power-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f5f5f5;
    border-radius: 50%;
    width: 30px;
    height: 30px;
    color: #888;
    text-decoration: none;
    transition: all 0.2s;
  }

  .power-btn:hover {
    background-color: #ddd;
    color: #000;
  }

  .promotion-card h4 {
    font-size: 18px;
    margin-bottom: 10px;
    color: #333;
  }

  .promotion-card p {
    font-size: 14px;
    color: #666;
    margin-bottom: 15px;
  }

  .apprenants-count {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 14px;
    background: #f5f5f5;
    padding: 6px 12px;
    border-radius: 8px;
    margin-bottom: 15px;
  }

  .details-btn {
    margin-top: auto;
    color: #ff7900;
    text-decoration: none;
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 5px;
  }

  /* Boutons de changement de vue */
  .view-toggle {
    display: flex;
    justify-content: flex-end;
    margin-bottom: 20px;
    gap: 10px;
  }

  .view-toggle button {
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    background-color: #eee;
    cursor: pointer;
    font-weight: 500;
  }

  .view-toggle .active {
    background-color: #ff7900;
    color: white;
  }

  .hidden {
    display: none !important;
  }

  .pagination {
    display: flex;
    justify-content: center;
    margin-top: 20px;
    gap: 10px;
  }

  .pagination button {
    padding: 10px 15px;
    border: none;
    border-radius: 5px;
    background-color: #eee;
    cursor: pointer;
    font-weight: 500;
  }

  .pagination button.active {
    background-color: #ff7900;
    color: white;
  }

  .pagination button:disabled {
    background-color: #ccc;
    cursor: not-allowed;
  }

  .active-promotion {
    margin-top: 20px;
    padding: 10px;
    background-color: #f9f9f9;
    border-radius: 8px;
    text-align: center;
  }

  .active-promotion h4 {
    margin-bottom: 5px;
    color: #ff7900;
  }

  .active-promotion p {
    font-size: 14px;
    color: #333;
  }

  .active-promotion p i {
    color: #666;
  }
  </style>
</head>
<body>
<?php
// filepath: /var/www/ges-apprenant/app/views/layouts/base.layout.php

require_once __DIR__ . '/../../services/session.service.php';

$url = "http://" . $_SERVER["HTTP_HOST"];

// Récupérer les valeurs de session
$searchTerm = isset($_GET['search']) ? trim($_GET['search']) : '';
$statusFilter = isset($_GET['status']) ? $_GET['status'] : 'all';

$filteredPromotions = array_filter($data['promotions'], function ($promo) use ($searchTerm, $statusFilter) {
    $matchesSearch = stripos($promo['nom'], $searchTerm) !== false;
    $matchesStatus = $statusFilter === 'all' || $promo['etat'] === $statusFilter;

    return $matchesSearch && $matchesStatus;
});

$apprenants = $data['apprenants'] ?? [];
$promotions = $data['promotions'] ?? [];
$referentiels = $data['referentiels'] ?? []; 

$itemsPerPage = 6; 
$totalItems = count($filteredPromotions); 
$totalPages = ceil($totalItems / $itemsPerPage); 
$currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1; 
$currentPage = max(1, min($currentPage, $totalPages)); 

$startIndex = ($currentPage - 1) * $itemsPerPage; 
$paginatedPromotions = array_slice($filteredPromotions, $startIndex, $itemsPerPage); 
?>
<!-- <input type="checkbox" id="sidebar-toggle" hidden>
<label for="sidebar-toggle" class="sidebar-toggle-btn">
  <i class="fas fa-bars"></i> -->
</label>

<?php
include __DIR__ . '/../layouts/sidebar.php'; 
?>
<div class="main">
<div class="header">
  <input type="text" id="search-input" placeholder="Rechercher une promotion...">
  <div class="user-info">
    <div class="user-avatar">
      <?= $user ? strtoupper(substr($user['email'], 0, 1)) : '?' ?>
    </div>
    <span>
      <?= $user ? htmlspecialchars($user['email']) : 'Invité' ?>
    </span>
  </div>
</div>

<div class="title-bar" style="display: flex; justify-content: space-between; ">
      <div>
        <h1 style="margin-bottom: 5px;">Promotions</h1>
        <span style="font-size:14px;color:#888;">Gérer les promotions de l'ecole </span>
      </div>
      

    <div>
      <a href="<?= $url ?>/ges-apprenant/public/add-promotion" style="padding: 10px 20px; background-color: #009966; color: white; border: none; border-radius: 6px; text-decoration: none; cursor: pointer;">
        + Nouvelle Promotion
      </a>
    </div>
  </div>
<br>
  <?php
  $errors = $_SESSION['errors'] ?? [];
  $old = $_SESSION['old'] ?? [];
  unset($_SESSION['errors'], $_SESSION['old']);
  ?>
  <div class="stats">
    <div class="stat-card">
      <i class="fas fa-user-graduate"></i>
      <div><?= $stats['apprenants'] ?><br><small>Apprenants</small></div>
    </div>
    <div class="stat-card">
      <i class="fas fa-book"></i>
      <div><?= $stats['referentiels'] ?><br><small>Référentiels</small></div>
    </div>
    <div class="stat-card">
      <i class="fas fa-check-circle fa-2x"></i>
      <div><br><small>Promotions actives</small></div>
    </div>
    <div class="stat-card">
      <i class="fas fa-folder fa-2x"></i>
      <div><?= $stats['promotions'] ?><br><small>Promotions</small></div>
    </div>
  </div>
  
  <div class="view-toggle">
<!-- Formulaire de recherche -->
<form method="GET" action="" style="margin-bottom: 20px;">
    <input 
        type="text" 
        name="search" 
        placeholder="Rechercher une promotion..." 
        value="<?= htmlspecialchars($_GET['search'] ?? '') ?>"
        style="padding: 5px; width: 760px; border-radius: 5px; border: 1px solid #ccc; height: 50px;"
    />
    <select name="status" style="width: 200px; padding: 10px; border-radius: 5px; border: 1px solid #ccc; height: 50px;">
        <option value="all" <?= (isset($_GET['status']) && $_GET['status'] === 'all') ? 'selected' : '' ?>>Tous</option>
        <option value="active" <?= (isset($_GET['status']) && $_GET['status'] === 'active') ? 'selected' : '' ?>>Actif</option>
        <option value="inactive" <?= (isset($_GET['status']) && $_GET['status'] === 'inactive') ? 'selected' : '' ?>>Inactif</option>
    </select>
    <button type="submit" style="padding: 10px 20px; background-color: #ff7900; color: white; border: none; border-radius: 5px; cursor: pointer;">🔍</button>
</form>

    <div > 
     
    </div>
        <button id="btn-list" class="active">Liste</button>
        <button id="btn-grid">Grille</button>
      </div>
  <!-- Vue Liste -->
  <div id="view-list" class="view-section">
    <table>
      <thead>
        <tr>
          <th>Photo</th>
          <th>Promotion</th>
          <th>Date de début</th>
          <th>Date de fin</th>
          <th>Référentiel</th>
          <th>Statut</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody id="promotion-list-body">
        <?php foreach ($paginatedPromotions as $promo): ?>
<tr class="promotion-item" data-name="<?= strtolower(htmlspecialchars($promo['nom'])) ?>">
    <td><img src="<?= $url ?>/uploads/<?= htmlspecialchars($promo['photo']) ?>" class="photo" alt="Photo promo"></td>
    <td><?= htmlspecialchars($promo['nom']) ?></td>
    <td><?= htmlspecialchars($promo['date_debut']) ?></td>
    <td><?= htmlspecialchars($promo['date_fin']) ?></td>
    <td class="tags">
        <?php foreach ($promo['referentiels'] ?? [] as $refId): ?>
            <?php
            $referentiel = array_filter($referentiels, fn($r) => $r['id'] == $refId);
            $referentiel = reset($referentiel);

    $class = '';
    if ($referentiel) {
        if (stripos($referentiel['nom'], 'dev') !== false) $class = 'tag-dev';
        elseif (stripos($referentiel['nom'], 'ref') !== false) $class = 'tag-ref';
        elseif (stripos($referentiel['nom'], 'data') !== false) $class = 'tag-data';
        elseif (stripos($referentiel['nom'], 'aws') !== false) $class = 'tag-aws';
        elseif (stripos($referentiel['nom'], 'hack') !== false) $class = 'tag-hackeuse';
        elseif (stripos($referentiel['nom'], 'cyber') !== false) $class = 'tag-hackeuse';

    }
            ?>
            <span class="<?= $class ?>"><?= $referentiel ? htmlspecialchars($referentiel['nom']) : 'Référentiel inconnu' ?></span>
        <?php endforeach; ?>
    </td>
    <td class="status <?= $promo['etat'] === 'active' ? 'active' : 'inactive' ?>">
        ● <?= ucfirst($promo['etat']) ?>
    </td>
    <td class="actions"><i class="fas fa-ellipsis-h"></i></td>
</tr>
<?php endforeach; ?>
      </tbody>
    </table>
  </div>

  <div id="view-grid" class="view-section hidden">
    <div class="promotions-grid" id="promotion-grid">
      <?php foreach ($paginatedPromotions as $promo): ?>
        <div class="promotion-card promotion-item" data-name="<?= strtolower(htmlspecialchars($promo['nom'])) ?>">
          <div class="promo-header">
            <img src="<?= $url ?>/uploads/<?= htmlspecialchars($promo['photo']) ?>" alt="Promotion" class="promo-avatar">
            <div class="promo-status">
              <span class="badge <?= $promo['etat'] === 'active' ? 'active' : 'inactive' ?>">
                <?= ucfirst($promo['etat']) ?>
              </span>
              <a href="/ges-apprenant/public/toggle-promotion?id=<?= $promo['id'] ?>" class="power-btn" title="Activer/Désactiver">
                <i class="fas fa-power-off"></i>
              </a>
            </div>
          </div>

          <h4><?= htmlspecialchars($promo['nom']) ?></h4>
          <p><i class="far fa-calendar-alt"></i> <?= htmlspecialchars($promo['date_debut']) ?> - <?= htmlspecialchars($promo['date_fin']) ?></p>
          <div class="apprenants-count">
            <i class="fas fa-user"></i> <?= $promo['nb_apprenants'] ?? 0 ?> apprenants
          </div>
          <a href="/ges-apprenant/public/promotion/<?= $promo['id'] ?>" class="details-btn">
            Voir détails <i class="fas fa-angle-right"></i>
          </a>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
  <div id="pagination" class="pagination"></div>
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
</div>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    const btnList = document.getElementById("btn-list");
    const btnGrid = document.getElementById("btn-grid");
    const viewList = document.getElementById("view-list");
    const viewGrid = document.getElementById("view-grid");
    const promotionItems = document.querySelectorAll(".promotion-item");

    btnList.addEventListener("click", () => {
        btnList.classList.add("active");
        btnGrid.classList.remove("active");
        viewList.classList.remove("hidden");
        viewGrid.classList.add("hidden");
    });

    btnGrid.addEventListener("click", () => {
        btnGrid.classList.add("active");
        btnList.classList.remove("active");
        viewList.classList.add("hidden");
        viewGrid.classList.remove("hidden");
    });

    const itemsPerPage = 6;
    const paginationContainer = document.getElementById('pagination');
    let currentPage = 1;
    const totalPages = Math.ceil(promotionItems.length / itemsPerPage);

   
  });

  
</script>
</body>
</html>