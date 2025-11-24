<?php

declare(strict_types=1);

require_once __DIR__ . '/../Job01/Job01.php';
require_once __DIR__ . '/../Job02/Job02.php';

$conn = new mysqli("localhost", "root", "", "draft_shop");
if ($conn->connect_error) {
    die("Erreur de connexion : " . $conn->connect_error);
}

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

/* recup by id
    public function findOneById(int $id): Product|false
    {
        $pdo = Database::getConnexion();
        $stmt = $pdo->prepare('SELECT * FROM product WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return false;
        }

        // Hydratation instance
        $hydrated = self::fromArray($row);
        $this->id = $hydrated->id;
        $this->name = $hydrated->name;
        $this->photos = $hydrated->photos;
        $this->price = $hydrated->price;
        $this->description = $hydrated->description;
        $this->quantity = $hydrated->quantity;
        $this->createdAt = $hydrated->createdAt;
        $this->updatedAt = $hydrated->updatedAt;
        $this->category_id = $hydrated->category_id;

        return $this;
    }