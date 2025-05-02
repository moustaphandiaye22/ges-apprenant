<?php
namespace App\Models;

require_once __DIR__ . '/model.php';

use function App\Models\jsonToArray;
use function App\Models\arrayToJson;

$dataFile = __DIR__ . '/../../data/data.json';

$getData = fn() => jsonToArray($dataFile);

$saveData = fn($data) => arrayToJson($dataFile, $data);

$getPromotions = fn() => ($getData())['promotions'] ?? [];

$addPromotion = function ($promotion) use ($getData, $saveData) {
    $data = $getData();
    $data['promotions'][] = $promotion;
    $saveData($data);
};

$findPromotionById = function ($id) use ($getPromotions) {
    foreach ($getPromotions() as $promo) {
        if ($promo['id'] == $id) return $promo;
    }
    return null;
};

return [
    'getAll' => fn() => $getData(),
    'search' => fn($term) => array_filter(
        $getPromotions(),
        fn($promo) => stripos($promo['nom'], $term) !== false
    )
];
