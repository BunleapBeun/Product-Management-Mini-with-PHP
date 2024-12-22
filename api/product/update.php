<?php
header('Content-Type: application/json');

$id = intval($_POST['id']);
$name = strval($_POST['name']);
$brand = strval($_POST['brand']);
$price = floatval($_POST['price']);
$quantity = intval($_POST['quantity']);

$photo = null;
if (isset($_FILES['photo'])) {
    $photo = $_FILES['photo'];
}

$filename = '';
if ($photo) {
    $path = pathinfo($photo['name']);
    $filename = uniqid() . '.' . $path['extension'];
    copy($photo['tmp_name'], '../../storage/img/' . $filename);
}

$products = json_decode(file_get_contents('../../storage/data/products.json'), true);
foreach ($products as $index => $product) {
    if ($product['id'] == $id) {
        $products[$index]['name'] = $name;
        $products[$index]['brand'] = $brand;
        $products[$index]['price'] = $price;
        $products[$index]['quantity'] = $quantity;
        if ($photo) {
            unlink('../../storage/img/' . $product['photo']);
            $products[$index]['photo'] = $filename;
        }
        break;
    }
}

file_put_contents('../../storage/data/products.json', json_encode($products));

echo json_encode(['result' => true, 'message' => 'Product updated successfully']);
