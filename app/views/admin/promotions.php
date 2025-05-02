<div class="promotions-container">
    <h1>Liste des Promotions</h1>
    
    <div class="actions">
        <a href="/ges-apprenant/public/add-promotion" class="btn btn-primary">Ajouter une promotion</a>
        <div class="search-box">
            <input type="text" placeholder="Rechercher une promotion...">
            <button class="btn">Rechercher</button>
        </div>
    </div>
    
    <div class="view-options">
        <button class="btn active">Grille</button>
        <button class="btn">Liste</button>
    </div>
    
    <div class="promotions-grid">
        <?php foreach ($promotions as $promotion): ?>
        <div class="promotion-card">
            <div class="promotion-photo">
                <img src="/ges-apprenant/public/assets/images/<?= $promotion['photo'] ?>" alt="<?= $promotion['nom'] ?>">
            </div>
            <div class="promotion-info">
                <h3><?= $promotion['nom'] ?></h3>
                <p><?= $promotion['date_debut'] ?> - <?= $promotion['date_fin'] ?></p>
                <p>Apprenants: <?= count($promotion['apprenants']) ?></p>
                <p>Référentiels: <?= count($promotion['referentiels']) ?></p>
                <div class="promotion-actions">
                    <button class="btn btn-primary">Voir</button>
                    <button class="btn <?= $promotion['active'] ? 'btn-danger' : 'btn-primary' ?>">
                        <?= $promotion['active'] ? 'Désactiver' : 'Activer' ?>
                    </button>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>