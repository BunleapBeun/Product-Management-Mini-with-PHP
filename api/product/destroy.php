<?php

$selectedID = $_GET['id'];

// Get the list of products from the storage file
$products = json_decode(file_get_contents('../../storage/data/products.json'), true);

// Iterate through the products to find the one to delete
foreach ($products as $index => $item) {
    if ($item['id'] == $selectedID) {
        // If the product has an image, delete it from the storage directory
        $photoPath = '../../storage/img/' . $item['photo'];
        if ($item['photo'] && file_exists($photoPath)) {
            unlink($photoPath);  // Delete the image file
        }
        // Remove the product from the array
        array_splice($products, $index, 1);
        break;
    }
}

// If no products are left, delete the JSON file
if (count($products) == 0) {
    unlink('../../storage/data/products.json');
} else {
    // Otherwise, update the products.json file with the remaining products
    file_put_contents('../../storage/data/products.json', json_encode($products));
}

// Return a response indicating the deletion was successful
echo json_encode([
    'result' => true,
    'message' => 'Product deleted successfully.'
]);
