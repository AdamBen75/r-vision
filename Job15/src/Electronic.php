<?php

declare(strict_types=1);

namespace App;

use App\Abstract\AbstractProduct;
use App\Interface\StockableInterface;
use DateTime;
use mysqli;

class Electronic extends AbstractProduct implements StockableInterface
{
    private string $brand;
    private int $warranty; // en mois
    private string $power;

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
        string $brand = "",
        int $warranty = 0,
        string $power = ""
    ) {
        parent::__construct($id, $name, $photos, $price, $description, $quantity, $createdAt, $updatedAt, $category_id);
        $this->brand = $brand;
        $this->warranty = $warranty;
        $this->power = $power;
    }

    // Getters et Setters
    public function getBrand(): string
    {
        return $this->brand;
    }
    public function setBrand(string $brand): void
    {
        $this->brand = $brand;
    }

    public function getWarranty(): int
    {
        return $this->warranty;
    }
    public function setWarranty(int $warranty): void
    {
        $this->warranty = $warranty;
    }

    public function getPower(): string
    {
        return $this->power;
    }
    public function setPower(string $power): void
    {
        $this->power = $power;
    }

    // StockableInterface
    public function addStocks(int $stock): self
    {
        $this->quantity += $stock;
        return $this;
    }

    public function removeStocks(int $stock): self
    {
        $this->quantity -= $stock;
        if ($this->quantity < 0) $this->quantity = 0;
        return $this;
    }

    // Méthodes abstraites
    public function findOneById(int $id)
    {
        $conn = new mysqli("localhost", "root", "", "draft_shop");
        if ($conn->connect_error) die("Erreur de connexion : " . $conn->connect_error);

        $stmt = $conn->prepare("SELECT * FROM electronic WHERE product_id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();
        if (!$data) return false;

        $productData = parent::findOneById($id);
        if (!$productData) return false;

        return new Electronic(
            $productData->getId(),
            $productData->getName(),
            $productData->getPhotos(),
            $productData->getPrice(),
            $productData->getDescription(),
            $productData->getQuantity(),
            $productData->getCreatedAt(),
            $productData->getUpdatedAt(),
            $productData->getCategoryId(),
            $data['brand'],
            $data['warranty'],
            $data['power']
        );
    }

    public function findAll(): array
    {
        $conn = new mysqli("localhost", "root", "", "draft_shop");
        if ($conn->connect_error) die("Erreur de connexion : " . $conn->connect_error);

        $stmt = $conn->prepare("SELECT * FROM electronic");
        $stmt->execute();
        $result = $stmt->get_result();

        $electronics = [];
        while ($row = $result->fetch_assoc()) {
            $productData = parent::findOneById($row['product_id']);
            if ($productData) {
                $electronics[] = new Electronic(
                    $productData->getId(),
                    $productData->getName(),
                    $productData->getPhotos(),
                    $productData->getPrice(),
                    $productData->getDescription(),
                    $productData->getQuantity(),
                    $productData->getCreatedAt(),
                    $productData->getUpdatedAt(),
                    $productData->getCategoryId(),
                    $row['brand'],
                    $row['warranty'],
                    $row['power']
                );
            }
        }
        return $electronics;
    }

    public function create()
    {
        $product = parent::create();
        if (!$product) return false;

        $conn = new mysqli("localhost", "root", "", "draft_shop");
        if ($conn->connect_error) die("Erreur de connexion : " . $conn->connect_error);

        $stmt = $conn->prepare("
            INSERT INTO electronic (product_id, brand, warranty, power)
            VALUES (?, ?, ?, ?)
        ");
        $stmt->bind_param(
            "isis",
            $product->getId(),
            $this->brand,
            $this->warranty,
            $this->power
        );

        if ($stmt->execute()) return $this;
        return false;
    }

    public function update(): bool
    {
        $parentUpdate = parent::update();
        if (!$parentUpdate) return false;

        $conn = new mysqli("localhost", "root", "", "draft_shop");
        if ($conn->connect_error) die("Erreur de connexion : " . $conn->connect_error);

        $stmt = $conn->prepare("
            UPDATE electronic
            SET brand = ?, warranty = ?, power = ?
            WHERE product_id = ?
        ");
        $stmt->bind_param(
            "sisi",
            $this->brand,
            $this->warranty,
            $this->power,
            $this->getId()
        );

        return $stmt->execute();
    }
}
