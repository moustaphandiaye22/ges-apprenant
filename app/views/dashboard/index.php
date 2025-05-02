<?php

// Vérification de l'authentification et du profil
if (!isset($_SESSION['user']) || $_SESSION['user']['profil'] !== 'Admin') {
    header('Location: /login.php');
    exit;
}

// Récupération des données
$promotions = json_decode(file_get_contents('data/promotions.json'), true) ?? [];
$apprenants = json_decode(file_get_contents('data/apprenants.json'), true) ?? [];
$referentiels = json_decode(file_get_contents('data/referentiels.json'), true) ?? [];

// Calcul des statistiques
$stats = [
    'current_students' => count(array_filter($apprenants, fn($a) => $a['statut'] === 'actif')),
    'active_referentiels' => count(array_filter($referentiels, fn($r) => $r['active'])),
    'active_promotions' => count(array_filter($promotions, fn($p) => $p['active'])),
    'insertion_rate' => 75, // Valeur statique pour l'exemple
    'feminization_rate' => 35 // Valeur statique pour l'exemple
];

// Mois pour le sélecteur
$months = [];
for ($i = 1; $i <= 12; $i++) {
    $months[$i] = DateTime::createFromFormat('!m', $i)->format('F');
}
$currentMonth = $_GET['month'] ?? date('n');
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord - Admin ODC</title>
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/dashboard.css" rel="stylesheet">
</head>
<body>
    <?php include '../includes/header.php'; ?>

    <div class="container-fluid">
        <div class="row">
            <?php include '../includes/sidebar.php'; ?>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Tableau de Bord</h1>
                </div>

                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="card text-white bg-primary mb-3">
                            <div class="card-header">Apprenants Actuels</div>
                            <div class="card-body">
                                <h5 class="card-title"><?= $stats['current_students'] ?></h5>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card text-white bg-success mb-3">
                            <div class="card-header">Référentiels Actifs</div>
                            <div class="card-body">
                                <h5 class="card-title"><?= $stats['active_referentiels'] ?></h5>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card text-white bg-info mb-3">
                            <div class="card-header">Promotions Actives</div>
                            <div class="card-body">
                                <h5 class="card-title"><?= $stats['active_promotions'] ?></h5>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5>Présences / Absences - <?= $months[$currentMonth] ?> <?= date('Y') ?></h5>
                            </div>
                            <div class="card-body">
                                <form method="GET">
                                    <select name="month" class="form-select mb-3" onchange="this.form.submit()">
                                        <?php foreach ($months as $num => $name): ?>
                                            <option value="<?= $num ?>" <?= $num == $currentMonth ? 'selected' : '' ?>>
                                                <?= $name ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </form>
                                <div class="chart-container">
                                    <canvas id="presenceChart" height="200"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5>Taux d'Insertion Professionnelle</h5>
                            </div>
                            <div class="card-body">
                                <div class="text-center">
                                    <div class="progress-circle" data-value="<?= $stats['insertion_rate'] ?>">
                                        <span class="progress-value"><?= $stats['insertion_rate'] ?>%</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header">
                                <h5>Taux de Féminisation</h5>
                            </div>
                            <div class="card-body">
                                <div class="text-center">
                                    <div class="progress-circle" data-value="<?= $stats['feminization_rate'] ?>">
                                        <span class="progress-value"><?= $stats['feminization_rate'] ?>%</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <?php include '../includes/footer.php'; ?>
    <script src="../assets/js/chart.min.js"></script>
    <script src="../assets/js/dashboard.js"></script>
</body>
</html>