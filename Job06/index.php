<?php

declare(strict_types=1);

// Inclure la classe Product
require_once __DIR__ . '/../Job04/index.php';


        $conn = new mysqli("localhost", "root", "", "draft_shop");
        if ($conn->connect_error) die("Erreur de connexion : " . $conn->connect_error);
        $stmt = $conn->prepare("SELECT * FROM product WHERE id = ?"); 
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $productData = $result->fetch_assoc();

$product = new Product(
    $productData['id'],
    $productData['name'],
    json_decode($productData['photos'], true),
    $productData['price'],
    $productData['description'],
    $productData['quantity'],
    new DateTime($productData['createdAt']),
    new DateTime($productData['updatedAt']),
    $productData['category_id']
);
// récup id
$stmt = $conn->prepare('SELECT * FROM category WHERE id = ?');
$categoryId = $product->getCategoryId();
$stmt->bind_param("i", $categoryId);
$stmt->execute();
$result = $stmt->get_result();
$categoryData = $result->fetch_assoc();

$category = null;
if ($categoryData) {
    $category = new Category(
        (int)$categoryData['id'],
        $categoryData['name'],
        $categoryData['description'],
        new DateTime($categoryData['createdAt']),
        new DateTime($categoryData['updatedAt'])
    );
}

echo " <br><br><br> Job06 <br><br><br>";
$products = $category->getProducts();

echo "<h2>Catégorie : " . $category->GetName() . "</h2>";
echo "<strong>Description :</strong> " . $category->GetDescription() . "<br><br>";

if (empty($products)) {
    echo "Aucun produit trouvé dans cette catégorie.";
} else {
    echo "<h3>Produits :</h3>";
    foreach ($products as $product) {
        echo "<div style='border:1px solid #ccc; padding:10px; margin-bottom:10px;'>";
        echo "<strong>Nom :</strong> " . $product->GetName() . "<br>";
        echo "<strong>Prix :</strong> " . $product->GetPrice() . " €<br>";
        echo "<strong>Quantité :</strong> " . $product->GetQuantity() . "<br>";
        echo "<strong>Description :</strong> " . $product->GetDescription() . "<br>";
        echo "<strong>Photos :</strong><br>";
        foreach ($product->GetPhotos() as $photo) {
            echo "<img src='images/$photo' alt='$photo' style='width:100px;margin:5px;'>";
        }
        echo "<br><strong>Date création :</strong> " . $product->GetCreatedAt()->format('Y-m-d H:i:s') . "<br>";
        echo "<strong>Date maj :</strong> " . $product->GetUpdatedAt()->format('Y-m-d H:i:s') . "<br>";
        echo "</div>";
    }
}
