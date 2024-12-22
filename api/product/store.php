<?php
header('Content-Type: application/json');

$name = strval($_POST['name']);
$brand = strval($_POST['brand']);
$price = floatval($_POST['price']);
$quantity = intval($_POST['quantity']);

$photo = null;
if (isset($_FILES['photo'])) {
    $photo = $_FILES['photo'];
}

if (!is_dir('../../storage')) mkdir('../../storage');
if (!is_dir('../../storage/img')) mkdir('../../storage/img');
if (!is_dir('../../storage/data')) mkdir('../../storage/data');

$filename = '';
if ($photo) {
    if (!in_array($photo['type'], ['image/jpeg', 'image/png', 'image/webp'])) {
        echo json_encode(['result' => false, 'message' => 'Photo must be jpg, png or webp']);
        exit();
    }

    if ($photo['size'] > 1048576) {
        echo json_encode(['result' => false, 'message' => 'Max file size is 1MB']);
        exit();
    }

    $path = pathinfo($photo['name']);
    $filename = uniqid() . '.' . $path['extension'];
    copy($photo['tmp_name'], '../../storage/img/' . $filename);
}

$products = [];
$id = 1;
if (file_exists('../../storage/data/products.json')) {
    $products = json_decode(file_get_contents('../../storage/data/products.json'), true);
    $id = max(array_column($products, 'id')) + 1;
}

array_push($products, [
    'id' => $id,
    'name' => $name,
    'brand' => $brand,
    'price' => $price,
    'quantity' => $quantity,
    'photo' => $photo ? $filename : null
]);

file_put_contents('../../storage/data/products.json', json_encode($products));

echo json_encode(['result' => true, 'message' => 'Product saved successfully']);
