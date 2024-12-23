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

// If there's a search query, filter the products by name
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $searchQuery = strtolower($_GET['search']);  // Convert search query to lowercase for case-insensitive search
    $products = array_filter($products, function ($product) use ($searchQuery) {
        return strpos(strtolower($product['name']), $searchQuery) !== false; // Check if the product name contains the search query
    });
}

// Calculate total price for filtered products
$totalPrice = array_sum(array_column($products, "price"));

// Return the filtered products and total price as JSON
echo json_encode([
    'products' => array_values($products),  // Reset array keys after filtering
    'total_price' => $totalPrice
]);
