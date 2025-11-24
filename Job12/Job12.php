<?php

declare(strict_types=1);

class Clothing extends Product
{
    public function __construct(
        int $id = 0,
        string $name = "",
        array $photos = [],
        int $price = 0,
        string $description = "",
        int $quantity = 0,
        DateTime $createdAt = new DateTime(),
        DateTime $updatedAt = new DateTime(),
        int $category_id = 0,
        private string $size = "",
        private string $color = "",
        private string $type = "",
        private int $material_fee = 0
    ) {
        parent::__construct($id, $name, $photos, $price, $description, $quantity, $createdAt, $updatedAt, $category_id);
    }

    // 🔎 Redéfinition
    public function findOneById(int $id): Clothing|false
    {
        $conn = new mysqli("localhost", "root", "", "draft_shop");
        if ($conn->connect_error) die("Erreur connexion : " . $conn->connect_error);

        $stmt = $conn->prepare("
            SELECT p.*, c.size, c.color, c.type, c.material_fee
            FROM product p
            JOIN clothing c ON p.id = c.product_id
            WHERE p.id = ?
        ");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();

        if (!$data) return false;

        return new Clothing(
            $data['id'],
            $data['name'],
            json_decode($data['photos'], true),
            $data['price'],
            $data['description'],
            $data['quantity'],
            new DateTime($data['createdAt']),
            new DateTime($data['updatedAt']),
            $data['category_id'],
            $data['size'],
            $data['color'],
            $data['type'],
            $data['material_fee']
        );
    }
}
