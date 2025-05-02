<?php
$url = "http://" . $_SERVER["HTTP_HOST"];
?>
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

    /* ========== SIDEBAR ========== */
    .sidebar-toggle-btn {
      position: fixed;
      top: 20px;
      left: 20px;
      font-size: 22px;
      cursor: pointer;
      background: #fff;
      border-radius: 6px;
      padding: 10px;
      z-index: 1001;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }

    #sidebar-toggle:not(:checked) ~ .sidebar {
      transform: translateX(-100%);
    }

    #sidebar-toggle:checked ~ .sidebar {
      transform: translateX(0);
    }

    .sidebar {
      width: 220px;
      background-color: white;
      padding: 20px;
      border-right: 1px solid #eee;
      height: 100vh;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      position: fixed;
      left: 0;
      top: 0;
      bottom: 0;
      z-index: 1000;
      transition: transform 0.3s ease;
    }

    .sidebar h2 {
      margin-bottom: 20px;
      color: #f57c00;
    }

    .sidebar ul {
      list-style: none;
    }

    .sidebar ul li {
      margin-bottom: 15px;
      font-weight: 500;
      color: #333;
      cursor: pointer;
    }

    .sidebar ul li.active {
      color: #ff6f00;
      font-weight: bold;
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
  </style>
</head>
<body>
<input type="checkbox" id="sidebar-toggle" hidden>
<label for="sidebar-toggle" class="sidebar-toggle-btn">
  <i class="fas fa-bars"></i>
</label>


<div class="sidebar">
  <div>
    <img src="<?= $url ?>/assets/images/logo_odc.png" alt="Logo" width="100%">
    <ul>
      <li><i class="fas fa-tachometer-alt"></i> Tableau de bord</li><br>
      <li class="active"><i class="fas fa-graduation-cap"></i> Promotions</li><br>
      <li><i class="fas fa-book"></i> Référentiels</li><br>
      <li><i class="fas fa-users"></i> Apprenants</li><br>
      <li><i class="fas fa-calendar-check"></i> Présences</li><br>
      <li><i class="fas fa-laptop"></i> Kits & Laptops</li><br>
      <li><i class="fas fa-chart-line"></i> Rapports & Stats</li>
    </ul>
  </div>
  <button style="padding: 10px; background-color: #ffe6e6; border: none; border-radius: 5px;">
    <i class="fas fa-sign-out-alt"></i> <a href="/views/auth/login.php">Déconnexion</a>
  </button>
</div>

<div class="main">
    
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      margin: 0;
      background-color: #f2f2f2;
    }

    .modal-overlay {
      position: fixed;
      top: 0; left: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0, 0, 0, 0.5);
      display: none; /* masqué par défaut */
      align-items: center;
      justify-content: center;
      z-index: 1000;
    }

    .modal {
      background-color: white;
      width: 500px;
      border-radius: 10px;
      padding: 25px;
      box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    }

    .modal h2 {
      font-size: 22px;
      margin-bottom: 5px;
    }

    .modal p {
      font-size: 14px;
      color: #555;
      margin-bottom: 20px;
    }

    .modal label {
      font-weight: 600;
      display: block;
      margin-bottom: 5px;
    }

    .modal input[type="text"],
    .modal input[type="date"],
    .modal input[type="file"],
    .modal select {
      width: 100%;
      padding: 10px;
      margin-bottom: 15px;
      border-radius: 8px;
      border: 1px solid #ccc;
    }

    .modal .date-group {
      display: flex;
      gap: 10px;
    }

    .modal .drop-zone {
      border: 2px dashed #ccc;
      border-radius: 10px;
      padding: 20px;
      text-align: center;
      color: #999;
      margin-bottom: 15px;
      cursor: pointer;
    }

    .modal .drop-zone:hover {
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


  <div class="header" style="display: flex; justify-content: space-between; align-items: center;">
  <div>
    <h1 style="margin-bottom: 5px;">Promotions</h1>
    <span style="font-size:14px;color:#888;">Gérer les promotions de l'école</span>
  </div>
  <button onclick="openModal()" style="padding: 10px 20px; background-color:  #009966; color: white; border: none; border-radius: 6px; cursor: pointer;">
    + Nouvelle Promotion
  </button>
</div>


<!-- Modal -->
    <div class="modal-overlay" id="modal">
      <div class="modal">
        <h2>Créer une nouvelle promotion</h2>
        <p>Remplissez les informations ci-dessous pour créer une nouvelle promotion.</p>

    <form method="POST" action="/ges-apprenant/public/add-promotion" enctype="multipart/form-data">
    <label for="nom">Nom de la promotion</label>
      <input type="text" id="nom" name="nom" placeholder="Ex: Promotion 2025" />

      <div class="date-group">
        <div style="flex: 1;">
          <label for="dateDebut">Date de début</label>
          <input type="date" id="dateDebut" name="date_debut" />
        </div>
        <div style="flex: 1;">
          <label for="dateFin">Date de fin</label>
          <input type="date" id="dateFin" name="date_fin" />
        </div>
      </div>

      <label for="photo">Photo de la promotion</label>
      <div class="drop-zone">
        <input type="file" id="photo" name="photo" accept="image/jpeg,image/png">
        <small>Format JPG, PNG. Taille max 2MB</small>
      </div>

      <label for="referentiel">Référentiels</label>
      <select name="referentiel" id="referentiel">
        <?php foreach ($data['referentiels'] as $referentiel): ?>
          <option value="<?= $referentiel['id'] ?>"><?= $referentiel['nom'] ?></option>
        <?php endforeach; ?>
      </select>

      <div class="form-actions">
        <button type="button" class="cancel-btn" onclick="closeModal()">Annuler</button>
        <button type="submit" class="submit-btn">Créer la promotion</button>
      </div>
    </form>
  </div>
</div>

<script>
  function openModal() {
    document.getElementById("modal").style.display = "flex";
  }

  function closeModal() {
    document.getElementById("modal").style.display = "none";
  }
</script>
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
  <button id="btn-list" class="active">Liste</button>
  <button id="btn-grid">Grille</button>
</div>

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
    <tbody>
      <?php foreach ($data['promotions'] as $promo): ?>
        <tr>
          <td><img src="<?= $url ?>/uploads/<?= htmlspecialchars($promo['photo']) ?>" class="photo" alt="Photo promo"></td>
          <td><?= htmlspecialchars($promo['nom']) ?></td>
          <td><?= htmlspecialchars($promo['date_debut']) ?></td>
          <td><?= htmlspecialchars($promo['date_fin']) ?></td>
          <td class="tags">
            <?php foreach ($promo['referentiels'] ?? [] as $ref): ?>
              <?php
                $class = '';
                if (stripos($ref, 'dev') !== false) $class = 'tag-dev';
                elseif (stripos($ref, 'ref') !== false) $class = 'tag-ref';
                elseif (stripos($ref, 'data') !== false) $class = 'tag-data';
                elseif (stripos($ref, 'aws') !== false) $class = 'tag-aws';
                elseif (stripos($ref, 'hack') !== false) $class = 'tag-hackeuse';
              ?>
              <span class="<?= $class ?>"><?= htmlspecialchars($ref) ?></span>
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

<div id="view-grid" class="view-section" style="display: none">
  <div class="promotions-grid">
    <?php foreach ($data['promotions'] as $promo): ?>
      <div class="promotion-card">
        <img src="<?= $url ?>/uploads/<?= htmlspecialchars($promo['photo']) ?>" alt="Promotion" class="photo">
        <h4><?= htmlspecialchars($promo['nom']) ?></h4>
        <p><?= htmlspecialchars($promo['date_debut']) ?> - <?= htmlspecialchars($promo['date_fin']) ?></p>
        <div class="tags">
          <?php foreach ($promo['referentiels'] ?? [] as $ref): ?>
            <?php
              $class = '';
              if (stripos($ref, 'dev') !== false) $class = 'tag-dev';
              elseif (stripos($ref, 'ref') !== false) $class = 'tag-ref';
              elseif (stripos($ref, 'data') !== false) $class = 'tag-data';
              elseif (stripos($ref, 'aws') !== false) $class = 'tag-aws';
              elseif (stripos($ref, 'hack') !== false) $class = 'tag-hackeuse';
            ?>
            <span class="<?= $class ?>"><?= htmlspecialchars($ref) ?></span>
          <?php endforeach; ?>
        </div>
        <p class="status <?= $promo['etat'] === 'active' ? 'active' : 'inactive' ?>">
          ● <?= ucfirst($promo['etat']) ?>
        </p>
      </div>
    <?php endforeach; ?>
  </div>
</div>

<style>
  .view-toggle {
    display: flex;
    justify-content: space-between;
    margin-bottom: 20px;
    gap: 10px;
  }

  .view-toggle button {
    padding: 8px 15px;
    border: none;
    border-radius: 5px;
    background-color: #eee;
    cursor: pointer;
  }

  .view-toggle .active {
    background-color: #ff7900;
    color: white;
  }
</style>

<script>
  const btnList = document.getElementById("btn-list");
  const btnGrid = document.getElementById("btn-grid");
  const viewList = document.getElementById("view-list");
  const viewGrid = document.getElementById("view-grid");

  btnList.addEventListener("click", () => {
    btnList.classList.add("active");
    btnGrid.classList.remove("active");
    viewList.style.display = "block";
    viewGrid.style.display = "none";
  });

  btnGrid.addEventListener("click", () => {
    btnGrid.classList.add("active");
    btnList.classList.remove("active");
    viewList.style.display = "none";
    viewGrid.style.display = "block";
  });
</script>

  </div>
</div>

</body>
</html>
