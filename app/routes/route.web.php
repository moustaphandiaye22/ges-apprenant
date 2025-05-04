<?php
require_once __DIR__ . '/../controllers/auth.controller.php';
require_once __DIR__ . '/../controllers/referentiel.controller.php';
require_once __DIR__ . '/../controllers/promotion.controller.php';
require_once __DIR__ . '/../controllers/admin.controller.php';
require_once __DIR__ . '/../controllers/error.controller.php';
require_once __DIR__ . '/../controllers/apprenant.controller.php';

function handleRoute($route, $data) {
    switch ($route) {
        case '/':
        case '/login':
            handleLogin($data);
            break;

        case '/logout':
            session_start();
            session_destroy();
            header('Location: /ges-apprenant/public/login');
            exit;

        case '/change-password':
            handleChangePassword([
                'users' => json_decode(file_get_contents(__DIR__.'/../data/data.json'), true)
            ]);
            break;

        case '/activate-promotion':
            handleActivatePromotion();
            break;

        case '/toggle-promotion':
            handleActivatePromotion();
            break;

        case '/dashboard':
            handleDashboard($data);
            break;

        case '/promotions':
            handlePromotions($data);
            break;

        case '/add-promotion':
            require_once __DIR__ . '/../../app/views/admin/add-promotion.php';
            handleAddPromotion($data);
            break;

        case '/referentiels':
            $data = [
                'referentiels' => handleAddReferentiel($data)
            ];
            require_once __DIR__ . '/../../app/views/admin/referentiels.php';
            break;

        case '/add-referentiel':
            require_once __DIR__ . '/../../app/views/admin/add-referentiel.php';
            handleAddReferentiel($data);
            break;

        case '/apprenants':
            require_once __DIR__ . '/../../app/views/admin/apprenants.php';
            handleListApprenants($data);
            break;

        case '/add-apprenant':
            require_once __DIR__ . '/../../app/views/admin/add-apprenant.php';
            handleAddApprenant($data);
            break;

        case '/apprenant-details':
            require_once __DIR__ . '/../../app/views/admin/apprenant-details.php';
            handleApprenantDetails($data);
            break;

        case '/download': 
            handleDownloadApprenants();
            break;
        case '/apprenant-dashboard':
                require_once __DIR__ . '/../../app/views/apprenant/apprenant-dashboard.php';
        default:
            // handle404();
            break;
    }
}
