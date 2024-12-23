<?php

$selectedID = $_GET['id'];

// Get the list of products from the storage file
$products = json_decode(file_get_contents('../../storage/data/products.json'), true);

// Iterate through the products to find the one to delete
$productFound = false;
foreach ($products as $index => $item) {
    if ($item['id'] == $selectedID) {
        $productFound = true;

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

if (!$productFound) {
    // If no product with the specified ID was found
    echo json_encode([
        'result' => false,
        'message' => 'Product not found.'
    ]);
    exit;
}

// If no products are left, delete the 'storage' directory
if (count($products) === 0) {
    // Delete the products.json file first
    unlink('../../storage/data/products.json');

    // Now, delete the entire 'storage' directory and its contents
    deleteDirectory('../../storage');
} else {
    // Otherwise, update the products.json file with the remaining products
    file_put_contents('../../storage/data/products.json', json_encode($products));
}

// Return a response indicating the deletion was successful
echo json_encode([
    'result' => true,
    'message' => 'Product deleted successfully.'
]);

// Function to delete the directory and all its contents
function deleteDirectory($dir)
{
    // Ensure the directory exists
    if (!is_dir($dir)) {
        return;
    }

    // Get all files and directories in the given directory
    $files = array_diff(scandir($dir), ['.', '..']);

    // Loop through and delete each file or directory
    foreach ($files as $file) {
        $filePath = $dir . DIRECTORY_SEPARATOR . $file;
        if (is_dir($filePath)) {
            deleteDirectory($filePath); // Recursively delete subdirectories
        } else {
            unlink($filePath); // Delete files
        }
    }

    // Finally, delete the empty directory itself
    rmdir($dir);
}
