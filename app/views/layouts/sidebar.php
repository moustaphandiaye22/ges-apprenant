<style>
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
</style>

<!-- <input type="checkbox" id="sidebar-toggle" hidden>
<label for="sidebar-toggle" class="sidebar-toggle-btn">
  <i class="fas fa-bars"></i> -->
</label>

<!-- filepath: /var/www/ges-apprenant/app/views/layouts/sidebar.php -->
<div class="sidebar">
  <div>
    <img src="<?= $url ?>/assets/images/logo_odc.png" alt="Logo" width="100%">

    <div class="active-promotion" style="margin-top: 20px; padding: 10px; background-color: #f9f9f9; border-radius: 8px; text-align: center;">
      <?php
      $activePromotion = null;
      foreach ($data['promotions'] as $promo) {
          if ($promo['etat'] === 'active') {
              $activePromotion = $promo;
              break;
          }
      }
      ?>
      <?php if ($activePromotion): ?>
        <p style="margin-bottom: 5px; color: #ff7900;"><?= htmlspecialchars($activePromotion['nom']) ?></p>
      <?php else: ?>
        <p style="font-size: 14px; color: #666;">Aucune promotion active</p>
      <?php endif; ?>
    </div>
    <br><br>
    <ul>
      <li>
        <a href="/ges-apprenant/public/base.layout.php" style="text-decoration: none; color: inherit;">
          <i class="fas fa-tachometer-alt"></i> Tableau de bord
        </a>
      </li><br>
      <li class="active">
        <a href="/ges-apprenant/public/dashboard" style="text-decoration: none; color: inherit;">
          <i class="fas fa-graduation-cap"></i> Promotions
        </a>
      </li><br>
      <li>
        <a href="/ges-apprenant/public/referentiels" style="text-decoration: none; color: inherit;">
          <i class="fas fa-book"></i> Référentiels
        </a>
      </li><br>
      <li>
        <a href="/ges-apprenant/public/apprenants" style="text-decoration: none; color: inherit;">
          <i class="fas fa-users"></i> Apprenants
        </a>
      </li><br>
      <li><i class="fas fa-calendar-check"></i> Présences</li><br>
      <li><i class="fas fa-laptop"></i> Kits & Laptops</li><br>
      <li><i class="fas fa-chart-line"></i> Rapports & Stats</li>
    </ul>
  </div>
  <li>
    <a href="/ges-apprenant/public/logout" style="text-decoration: none; color: inherit;">
      <i class="fas fa-sign-out-alt"></i> Déconnexion
    </a>
  </li>
</div>
