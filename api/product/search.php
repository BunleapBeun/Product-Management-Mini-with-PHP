<?php
$file_path = "../../storage/data/products.json";
$searchQuery = isset($_GET['name']) ? strtolower($_GET['name']) : '';

if (file_exists($file_path)) {
    $json_data = file_get_contents($file_path);
    $data = json_decode($json_data, true);

    $filtered_products = array_filter($data['products'], function ($product) use ($searchQuery) {
        return strpos(strtolower($product['name']), $searchQuery) !== false;
    });

    if (count($filtered_products) > 0) {
        echo json_encode(array("products" => array_values($filtered_products)));
    } else {
        echo json_encode(array("message" => "No products found"));
    }
} else {
    echo json_encode(array("message" => "Product data not found"));
}
