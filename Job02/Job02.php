<?php

declare(strict_types=1);

require_once __DIR__ . '/../Job01/Job01.php';

class Category
{
    public function __construct(
        private int $id = 0,
        private string $name = "",
        private string $description = "",
        private DateTime $createdAt = new DateTime(),
        private DateTime $updatedAt = new DateTime()
    ) {}

    // Getters
    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): DateTime
    {
        return $this->updatedAt;
    }
    public function getProducts(): array
    {
        $conn = new mysqli("localhost", "root", "", "draft_shop");
        if ($conn->connect_error) {
            die("Erreur de connexion : " . $conn->connect_error);
        }

        $stmt = $conn->prepare("SELECT * FROM product WHERE category_id = ?");
        $category_id = $this->id;
        $stmt->bind_param("i", $category_id);
        $stmt->execute();
        $result = $stmt->get_result();

        $products = [];

        while ($row = $result->fetch_assoc()) {
            $products[] = new Product(
                (int)$row['id'],
                $row['name'],
                json_decode($row['photos'], true),
                (int)$row['price'],
                $row['description'],
                (int)$row['quantity'],
                new DateTime($row['createdAt']),
                new DateTime($row['updatedAt']),
                (int)$row['category_id']
            );
        }

        return $products;
    }


    // Setters
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function setCreatedAt(DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function setUpdatedAt(DateTime $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }
}


