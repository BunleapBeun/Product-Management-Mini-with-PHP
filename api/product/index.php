<?php
header("Content-Type: application/json");

$productsFile = "../../storage/data/products.json";

// If the products file doesn't exist, return empty data
if (!file_exists($productsFile)) {
    echo json_encode([
        'products' => [],
        'total_price' => 0
    ]);
    exit();
}

// Read the products data
$products = json_decode(file_get_contents($productsFile), true);

// Calculate total price
$totalPrice = array_sum(array_column($products, "price"));

// Return the products and total price as JSON
echo json_encode([
    'products' => $products,
    'total_price' => $totalPrice
]);
