<?php
require_once __DIR__ . '/../../services/session.service.php';

$url = "http://" . $_SERVER["HTTP_HOST"];
$model = require '/var/www/ges-apprenant/app/models/referentiel.model.php';
$referentiels = $model['all']();

$promotionsFile = '/var/www/ges-apprenant/data/data.json';
$promotionsData = json_decode(file_get_contents($promotionsFile), true);
$promotions = $promotionsData['promotions'] ?? [];

// Identifier la promotion active ou terminée
$promotionActive = array_filter($promotions, fn($promo) => $promo['etat'] === 'active' || $promo['statut'] === 'terminée');
$promotionActive = reset($promotionActive);

// Filtrer les référentiels associés à la promotion active ou terminée
$filteredReferentiels = [];
if ($promotionActive) {
    $filteredReferentiels = array_filter($referentiels, function ($ref) use ($promotionActive) {
        return in_array($ref['id'], $promotionActive['referentiels']);
    });
}

// Pagination
$itemsPerPage = 4; 
$totalItems = count($filteredReferentiels); 
$totalPages = ceil($totalItems / $itemsPerPage); 
$currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1; 
$currentPage = max(1, min($currentPage, $totalPages)); 

$startIndex = ($currentPage - 1) * $itemsPerPage;
$paginatedReferentiels = array_slice($filteredReferentiels, $startIndex, $itemsPerPage);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['affecter'])) {
    $referentielIds = $_POST['referentiel_ids'] ?? []; 

    if ($promotionActive && $promotionActive['etat'] === 'active' && $promotionActive['statut'] === 'en cours') {
        // Référentiels déjà affectés
        $currentReferentiels = $promotionActive['referentiels'];

        // Référentiels à ajouter
        $toAdd = array_diff($referentielIds, $currentReferentiels);

        // Référentiels à retirer
        $toRemove = array_diff($currentReferentiels, $referentielIds);

        // Ajouter les nouveaux référentiels
        foreach ($toAdd as $referentielId) {
            $promotionActive['referentiels'][] = $referentielId;
        }

        // Retirer les référentiels décochés
        $promotionActive['referentiels'] = array_values(array_diff($promotionActive['referentiels'], $toRemove));

        foreach ($promotions as &$promo) {
            if ($promo['id'] === $promotionActive['id']) {
                $promo['referentiels'] = $promotionActive['referentiels'];
                break;
            }
        }

        file_put_contents($promotionsFile, json_encode($promotionsData, JSON_PRETTY_PRINT));

        setSession('success', "Les référentiels ont été mis à jour avec succès.");
        header("Location: " . $_SERVER['REQUEST_URI']);
        exit;
    } else {
        $errors = getSession('errors', []);
        $errors[] = "La promotion est terminée ou inactive. Vous ne pouvez pas modifier les référentiels.";
        setSession('errors', $errors);
    }
}

$errors = getSession('errors', []);
$success = getSession('success');
removeSession('errors');
removeSession('success');
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Référentiels - ODC</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    /* Style global */
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
      font-family: 'Inter', sans-serif;
    }
    
    body {
      display: flex;
      background: #f7f9fc;
      transition: margin-left 0.3s ease;
    }

    .main {
      flex: 1;
      padding: 20px 30px;
      transition: margin-left 0.3s ease;
      margin-left: 220px; 
    }
    
    

    /* Header */
    .header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 20px;
    }
    
    .header input {
      width: 40%;
      min-width: 150px;
      max-width: 300px;
      padding: 10px;
      border-radius: 8px;
      border: 1px solid #ddd;
    }

    .user-info {
      display: flex;
      align-items: center;
      gap: 10px;
    }
    
    .user-info span {
      font-size: 14px;
      text-align: right;
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
    
    /* Title Bar */
    .title-bar {
      margin: 30px 0 20px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    
    .title-bar h1 {
      font-size: 22px;
    }
    
    .title-bar button {
      border: none;
      padding: 10px 15px;
      border-radius: 8px;
      cursor: pointer;
      font-weight: 600;
    }
    
    /* Cards */
    .cards {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
      gap: 20px;
    }
    
    .card {
      background: white;
      border-radius: 12px;
      box-shadow: 0 2px 5px rgba(0,0,0,0.05);
      padding: 15px;
      display: flex;
      flex-direction: column;
    }
    
    .card img {
      width: 100%;
      height: 120px;
      object-fit: cover;
      border-radius: 10px;
      margin-bottom: 10px;
    }
    
    .card h4 {
      color: #00b398;
      margin-bottom: 5px;
    }
    
    .card small {
      color: #888;
    }
    
    .card p {
      margin: 8px 0;
      font-size: 14px;
      color: #555;
    }
    
    .card .footer {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-top: auto;
    }
    
    .switches {
      display: flex;
      gap: 5px;
    }
    
    .switches div {
      width: 10px;
      height: 10px;
      background: #ccc;
      border-radius: 50%;
    }
    
    .apprenants {
      color: #00b398;
      font-size: 14px;
    }
    
    
    /* Cacher l'input file */
    input[type="file"] {
      display: none;
    }
    
    /* Responsivité */
    @media (max-width: 992px) {
      .sidebar {
        width: 200px;
      }

      .main {
        padding: 20px;
      }

      .header input {
        width: 200px;
      }

      .title-bar {
        flex-direction: column;
        align-items: flex-start;
        gap: 15px;
      }

      .title-bar button {
        margin-right: 0;
      }

      .user-info {
        flex-direction: column;
        align-items: flex-end;
        gap: 5px;
      }
    }

    @media (max-width: 768px) {
      .sidebar {
        position: fixed;
        transform: translateX(-100%);
      }

      #sidebar-toggle:checked ~ .sidebar {
        transform: translateX(0);
      }

      .sidebar-toggle-btn {
        display: block;
      }

      .header {
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
      }

      .cards {
        grid-template-columns: 1fr;
      }

      .modal-content {
        width: 90%;
        margin: 10% auto;
      }
    }

    @media (max-width: 480px) {
      .sidebar h2,
      .sidebar ul li {
        font-size: 14px;
      }

      .header input {
        width: 100%;
      }

      .card h4,
      .card p,
      .card small {
        font-size: 14px;
      }

      .title-bar h1 {
        font-size: 18px;
      }

      .btn-ajouter, .submit-btn {
        padding: 10px;
        font-size: 14px;
      }

      .form-row {
        flex-direction: column;
      }
    }

    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0,0,0,0.3);
        justify-content: center;
        align-items: center;
    }

    .modal-content {
        background-color: #ffffff;
        padding: 25px;
        border-radius: 10px;
        width: 400px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        position: relative;
    }

    .modal-content h2 {
        margin-top: 0;
    }

    label {
        display: block;
        margin-top: 15px;
        font-size: 14px;
        font-weight: 500;
    }

    select {
        width: 100%;
        padding: 12px;
        border: 1px solid #ddd;
        border-radius: 8px;
        font-size: 14px;
        margin-top: 8px;
    }

    .btn-submit {
        margin-top: 20px;
        background-color: #088d84;
        color: #ffffff;
        border: none;
        padding: 10px 18px;
        border-radius: 6px;
        cursor: pointer;
        font-weight: bold;
        float: right;
    }

    .close {
        position: absolute;
        top: 10px;
        right: 15px;
        font-size: 20px;
        cursor: pointer;
        color: #999;
    }

    :root {
        --color-bg-modal: #ffffff;
        --color-shadow-modal: rgba(0, 0, 0, 0.1);
        --color-input-bg: #f5f5f5;
        --color-input-border: #e0e0e0;
        --color-input-text: #333333;
        --color-label: #555555;
        --color-btn-submit-bg: #088d84;
        --color-btn-submit-text: #ffffff;
        --color-btn-open-bg: #9de2d3;
        --color-btn-open-text: #000;
    }

    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.3);
        justify-content: center;
        align-items: center;
    }

    .modal-content {
        background-color: var(--color-bg-modal);
        padding: 25px;
        border-radius: 10px;
        width: 400px;
        box-shadow: 0 0 10px var(--color-shadow-modal);
        position: relative;
    }

    .modal-content h2 {
        margin-top: 0;
        font-size: 18px;
        color: var(--color-label);
    }

    label {
        display: block;
        margin-top: 15px;
        color: var(--color-label);
        font-size: 14px;
        font-weight: 500;
    }

    select {
        width: 100%;
        padding: 12px;
        border: 1px solid var(--color-input-border);
        background-color: var(--color-input-bg);
        border-radius: 8px;
        font-size: 14px;
        color: var(--color-input-text);
        margin-top: 8px;
    }

    .btn-submit {
        margin-top: 20px;
        background-color: var(--color-btn-submit-bg);
        color: var(--color-btn-submit-text);
        border: none;
        padding: 10px 18px;
        border-radius: 6px;
        cursor: pointer;
        font-weight: bold;
        float: right;
    }

    .close {
        position: absolute;
        top: 10px;
        right: 15px;
        font-size: 20px;
        cursor: pointer;
        color: #999;
    }

    .btn-ajouter {
        padding: 10px 20px;
        background-color: var(--color-btn-open-bg);
        color: var(--color-btn-open-text);
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-weight: bold;
    }

    .pagination ul li a {
      display: inline-block;
      padding: 8px 12px;
      margin: 0 5px;
      text-decoration: none;
      color: #333;
      border: 1px solid #ddd;
      border-radius: 5px;
      transition: background-color 0.3s, color 0.3s;
    }

    .pagination ul li a:hover {
      background-color: #088d84;
      color: white;
    }

    .pagination ul li a.active {
      background-color: #088d84;
      color: white;
    }

    input[type="checkbox"] {
        margin-right: 10px;
        transform: scale(1.2);
    }

    label {
        font-size: 14px;
        color: #555;
    }
  </style>
</head>
<body>
  <!-- <input type="checkbox" id="sidebar-toggle" hidden>
<label for="sidebar-toggle" class="sidebar-toggle-btn">
  <i class="fas fa-bars"></i> -->
</label>
<?php include __DIR__ . '/../layouts/sidebar.php'; ?>


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

  <div class="title-bar">
    <div>
      <h1 style="margin-bottom: 5px;">Référentiels</h1>
      <span style="font-size:14px;color:#888;">Gérer les référentiels des promotions</span>
    </div>
    <div style="display: flex; align-items: center;">
      <form method="GET" action="" style="margin-right: 10px;">
        <input type="text" name="search" placeholder="Rechercher un référentiel..." 
               value="<?= htmlspecialchars($_GET['search'] ?? '') ?>" 
               style="padding: 8px; border: 1px solid #ddd; border-radius: 5px; font-size: 14px;">
        <button type="submit" style="padding: 8px 12px; background-color: #f4f4f4; color: #333; border: none; border-radius: 5px; cursor: pointer;">
          Rechercher
        </button>
      </form>
      <button id="btn-tous-referentiels" style="background-color: #f4f4f4; color: #333; margin-right: 10px;">Tous les référentiels</button>
      <a href="<?= $url ?>/ges-apprenant/public/add-referentiel" style="text-decoration: none;">
        <button style="background-color: #9de2d3;" class="btn-ajouter">+ Créer un référentiel</button>
      </a>
    </div>
  </div>

  <?php if (!empty($errors)): ?>
      <div style="background-color: #f8d7da; color: #721c24; padding: 10px; margin-bottom: 15px; border: 1px solid #f5c6cb;">
          <ul style="margin: 0; padding-left: 20px;">
              <?php foreach ($errors as $error): ?>
                  <li><?= htmlspecialchars($error) ?></li>
              <?php endforeach; ?>
          </ul>
      </div>
  <?php endif; ?>

  <?php if ($success): ?>
      <div style="background-color: #d4edda; color: #155724; padding: 10px; margin-bottom: 15px; border: 1px solid #c3e6cb;">
          <?= htmlspecialchars($success) ?>
      </div>
  <?php endif; ?>

  <?php if ($promotionActive && $promotionActive['statut'] === 'terminée'): ?>
    <div style="background-color: #f8d7da; color: #721c24; padding: 10px; margin-bottom: 15px; border: 1px solid #f5c6cb;">
        La promotion est terminée. Vous ne pouvez pas modifier ses référentiels, mais vous pouvez les consulter.
    </div>
<?php elseif ($promotionActive && $promotionActive['etat'] === 'active' && $promotionActive['statut'] === 'en cours'): ?>
    <button class="btn-ajouter" id="openModal">Affecter un référentiel</button>
<?php else: ?>
    <div style="background-color: #f8d7da; color: #721c24; padding: 10px; margin-bottom: 15px; border: 1px solid #f5c6cb;">
        Aucune promotion active ou terminée n'est disponible.
    </div>
<?php endif; ?>

  

  <div class="cards" id="referentiels-container">
    <?php if (!empty($paginatedReferentiels)): ?>
        <?php foreach ($paginatedReferentiels as $ref): ?>
            <div class="card">
                <img src="<?= $url . '/uploads/' . htmlspecialchars($ref['photo'] ?? 'default-ref.jpg') ?>" 
                     alt="<?= htmlspecialchars($ref['nom']) ?>" 
                     onerror="this.src='<?= $url ?>/uploads/default-ref.jpg'">
                <h4><?= htmlspecialchars($ref['nom']) ?></h4>
                <small><?= intval($ref['modules'] ?? 0) ?> modules</small>
                <p><?= htmlspecialchars($ref['description'] ?? 'Aucune description') ?></p>
                <div class="footer">
                    <div class="switches">
                        <div></div><div></div><div></div>
                    </div>
                    <div class="apprenants"><?= intval($ref['capacite'] ?? 0) ?> apprenants</div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Aucun référentiel associé à la promotion active ou terminée.</p>
    <?php endif; ?>
</div>

<!-- <div class="pagination">
  <?php if ($totalPages > 1): ?>
    <ul style="list-style: none; display: flex; gap: 10px; padding: 0; justify-content: center; margin-top: 20px;">
      <?php if ($currentPage > 1): ?>
        <li>
          <a href="?page=<?= $currentPage - 1 ?>" 
             style="padding: 8px 12px; text-decoration: none; border: 1px solid #ddd; border-radius: 5px; color: #333;">
            &laquo; Précédent
          </a>
        </li>
      <?php endif; ?>
      <?php for ($page = 1; $page <= $totalPages; $page++): ?>
        <li>
          <a href="?page=<?= $page ?>" 
             style="padding: 8px 12px; text-decoration: none; border: 1px solid #ddd; border-radius: 5px; <?= $page == $currentPage ? 'background-color: #088d84; color: white;' : 'color: #333;' ?>">
            <?= $page ?>
          </a>
        </li>
      <?php endfor; ?>
      <?php if ($currentPage < $totalPages): ?>
        <li>
          <a href="?page=<?= $currentPage + 1 ?>" 
             style="padding: 8px 12px; text-decoration: none; border: 1px solid #ddd; border-radius: 5px; color: #333;">
            Suivant &raquo;
          </a>
        </li>
      <?php endif; ?>
    </ul>
  <?php endif; ?>
</div> -->

</div>

<div class="modal" id="referentielModal">
    <div class="modal-content">
        <span class="close" id="closeModal">&times;</span>
        <h2>Affecter ou retirer des référentiels</h2>

        <form method="POST" action="">
            <label for="referentiel-select-modal">Sélectionner un ou plusieurs référentiels :</label>
            <div>
                <?php foreach ($referentiels as $ref): ?>
                    <div style="margin-bottom: 10px;">
                        <input type="checkbox" 
                               name="referentiel_ids[]" 
                               value="<?= htmlspecialchars($ref['id']) ?>" 
                               id="ref-<?= htmlspecialchars($ref['id']) ?>"
                               <?= in_array($ref['id'], $promotionActive['referentiels']) ? 'checked disabled' : '' ?>>
                        <label for="ref-<?= htmlspecialchars($ref['id']) ?>"><?= htmlspecialchars($ref['nom']) ?></label>
                    </div>
                <?php endforeach; ?>
            </div>
            <button type="submit" name="affecter" class="btn-submit">Mettre à jour</button>
        </form>
    </div>
</div>

<script>
document.getElementById('btn-tous-referentiels').addEventListener('click', function () {
    const container = document.getElementById('referentiels-container');
    container.innerHTML = ''; // Vider le conteneur

    <?php foreach ($referentiels as $ref): ?>
        container.innerHTML += `
            <div class="card">
                <img src="<?= $url . '/uploads/' . htmlspecialchars($ref['photo'] ?? 'default-ref.jpg') ?>" 
                     alt="<?= htmlspecialchars($ref['nom']) ?>" 
                     onerror="this.src='<?= $url ?>/uploads/default-ref.jpg'">
                <h4><?= htmlspecialchars($ref['nom']) ?></h4>
                <small><?= intval($ref['modules'] ?? 0) ?> modules</small>
                <p><?= htmlspecialchars($ref['description'] ?? 'Aucune description') ?></p>
                <div class="footer">
                    <div class="switches">
                        <div></div><div></div><div></div>
                    </div>
                    <div class="apprenants"><?= intval($ref['capacite'] ?? 0) ?> apprenants</div>
                </div>
            </div>
        `;
    <?php endforeach; ?>
});

const openBtn = document.getElementById("openModal");
const modal = document.getElementById("referentielModal");
const closeBtn = document.getElementById("closeModal");

openBtn.onclick = () => modal.style.display = "flex";
closeBtn.onclick = () => modal.style.display = "none";
window.onclick = (e) => { if (e.target === modal) modal.style.display = "none"; };
</script>

</body>
</html>