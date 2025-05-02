<?php
function handleDashboard($data) {
    if (!isset($_SESSION['user'])) {
        header('Location: /ges-apprenant/public/login');
        exit;
    }
    
    
    $stats = [
        'promotions' => count($data['promotions']),
        'promotions_actives' => count(array_filter($data['promotions'], function($p) { return $p['active']; })),
        'apprenants' => array_sum(array_map(function($p) { return count($p['apprenants']); }, $data['promotions'])),
        'referentiels' => count($data['referentiels'])
    ];
    
    require_once __DIR__ . '/../views/layouts/base.layout.php';

    ob_start();
    require_once __DIR__ . '/../views/admin/dashboard.php';
    $content = ob_get_clean();
    
    // renderLayout($content, 'Dashboard');
}