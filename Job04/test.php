<?php
require_once __DIR__ . '/../Job04/index.php';

        $conn = new mysqli("localhost", "root", "", "draft_shop");
        if ($conn->connect_error) die("Erreur de connexion : " . $conn->connect_error);
        $stmt = $conn->prepare("SELECT * FROM product WHERE id = ?");
        $id = 7;    
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

// Produit
echo "<h2>Produit ID 7</h2>";
echo "<strong>Nom :</strong> " . $product->getName() . "<br>";
echo "<strong>Prix :</strong> " . $product->getPrice() . " €<br>";
echo "<strong>Quantité :</strong> " . $product->getQuantity() . "<br>";
echo "<strong>Description :</strong> " . $product->getDescription() . "<br>";
echo "<strong>Photos :</strong><br>";
foreach ($product->getPhotos() as $photo) {
    echo "<img src='images/$photo' alt='$photo' style='width:100px;margin:5px;'>";
}
echo "<br><strong>Date création :</strong> " . $product->getCreatedAt()->format('Y-m-d H:i:s') . "<br>";
echo "<strong>Date maj :</strong> " . $product->getUpdatedAt()->format('Y-m-d H:i:s') . "<br>";

if ($category) {
    echo "<strong>Catégorie :</strong> " . $category->getName() . " (" . $category->getDescription() . ")<br>";
} else {
    echo "<strong>Catégorie :</strong> Non définie<br>";
}