<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Tableau de Bord</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
  <style>
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      font-family: 'Inter', sans-serif;
      background: #f8fafc;
      padding: 20px;
    }

    .header {
      background-color: #d64507;
      color: white;
      padding: 20px 30px;
      font-size: 1.5rem;
      font-weight: 700;
      border-radius: 15px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
      margin-bottom: 30px;
      width: 100%;
      max-width: 1200px;
      margin-left: auto;
      margin-right: auto;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .logout form {
      display: inline;
    }

    .btn-logout {
      background-color: white;
      color: #d64507;
      border: none;
      padding: 10px 20px;
      border-radius: 5px;
      cursor: pointer;
      font-weight: 600;
    }

    .dashboard {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(340px, 1fr));
      gap: 20px;
      max-width: 1200px;
      margin: 0 auto;
    }

    .card {
      background: white;
      border-radius: 15px;
      padding: 20px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }

    .profile {
      display: flex;
      align-items: center;
      gap: 20px;
    }

    .profile img {
      width: 80px;
      height: 80px;
      border-radius: 50%;
      object-fit: cover;
    }

    .info h2 {
      font-size: 1.2rem;
      font-weight: 700;
    }

    .info .role {
      color: #d64507;
      margin: 5px 0;
    }

    .contact {
      display: flex;
      align-items: center;
      gap: 8px;
      font-size: 0.9rem;
      color: #444;
    }

    .contact .icon {
      font-size: 1.1rem;
    }

    .qr {
      text-align: center;
      background: #fff7ed;
    }

    .qr .qr-icon {
      background: #ffedd5;
      color: #d64507;
      width: 40px;
      height: 40px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.2rem;
      border-radius: 50%;
      margin: 0 auto 10px;
    }

    .qr h3 {
      font-weight: 700;
      font-size: 1rem;
      margin-bottom: 15px;
      color: #0f172a;
    }

    .qr img {
      width: 150px;
      height: 150px;
      margin: 10px 0;
    }

    .code-label {
      color: #d64507;
      font-size: 0.9rem;
      margin-top: 10px;
    }

    .code-value {
      font-weight: 600;
      margin-top: 5px;
    }

    .presences h3 {
      display: flex;
      align-items: center;
      gap: 8px;
      font-size: 1rem;
      margin-bottom: 15px;
    }

    .stats {
      display: flex;
      justify-content: space-around;
    }

    .stat {
      text-align: center;
      font-weight: 700;
      font-size: 1.3rem;
      padding: 10px;
      border-radius: 12px;
      width: 80px;
    }

    .stat span {
      display: block;
      font-size: 0.8rem;
      font-weight: 400;
      margin-top: 5px;
    }

    .green {
      background: #dcfce7;
      color: #22c55e;
    }

    .orange {
      background: #fef3c7;
      color: #f59e0b;
    }

    .red {
      background: #fee2e2;
      color: #ef4444;
    }

    .repartition h3 {
      display: flex;
      align-items: center;
      gap: 8px;
      font-size: 1rem;
      margin-bottom: 15px;
    }

    .chart {
      width: 100%;
      max-width: 220px;
      margin: 0 auto;
      display: block;
    }

    .legend {
      display: flex;
      justify-content: space-around;
      font-size: 0.85rem;
      margin-top: 10px;
    }
  </style>
</head>
<body>

  <div class="header">
    Tableau de Bord
    <div class="logout">
      <form action="/ges-apprenant/public/logout" method="POST">
        <button type="submit" class="btn-logout">Se déconnecter</button>
      </form>
    </div>
  </div>

  <div class="dashboard">
    <!-- Profil -->
    <div class="card profile">
      <img src="https://via.placeholder.com/80" alt="Avatar" />
      <div class="info">
        <h2><?= htmlspecialchars(getSession('user')['prenom'] . ' ' . getSession('user')['nom']) ?></h2>
        <p class="role">Apprenant</p>
        <div class="contact">
          <span class="icon">📧</span> <?= htmlspecialchars(getSession('user')['email']) ?>
        </div>
        <div class="contact">
          <span class="icon">🆔</span> <?= htmlspecialchars(getSession('user')['matricule'] ?? 'N/A') ?>
        </div>
      </div>
    </div>

    <div class="card qr">
      <div class="qr-icon">🔳</div>
      <h3>Scanner pour la présence</h3>
      <img src="https://api.qrserver.com/v1/create-qr-code/?data=<?= urlencode(getSession('user')['prenom'] . ' ' . getSession('user')['nom'] . ' - ' . getSession('user')['email']) ?>&size=150x150" alt="QR Code" />
      <div class="code-value"><?= htmlspecialchars(getSession('user')['prenom'] . ' ' . getSession('user')['nom']) ?></div>
    </div>

    <div class="card presences">
      <h3>📅 Présences</h3>
      <div class="stats">
        <div class="stat green">40<span>Présent</span></div>
        <div class="stat orange">1<span>Retard</span></div>
        <div class="stat red">2<span>Absent</span></div>
      </div>
    </div>

    <div class="card repartition">
      <h3>⏰ Répartition</h3>
      <img class="chart" src="https://quickchart.io/chart?c={type:'doughnut',data:{labels:['Présents','Retardés','Absents'],datasets:[{data:[40,1,2],backgroundColor:['%2322c55e','%23facc15','%23ef4444']}]},options:{cutout:70}}" alt="Répartition" />
      <div class="legend">
        <span class="green">Présents</span>
        <span class="orange">Retardés</span>
        <span class="red">Absents</span>
      </div>
    </div>
  </div>

</body>
</html>