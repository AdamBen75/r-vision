<?php

declare(strict_types=1);

interface StockableInterface
{
    public function addStocks(int $stock): self;
    public function removeStocks(int $stock): self;
}


class Clothing extends AbstractProduct implements StockableInterface
{
    private string $size;
    private string $color;
    private string $type;
    private int $material_fee;

    public function __construct(
        int $id = 0,
        string $name = "",
        array $photos = [],
        int $price = 0,
        string $description = "",
        int $quantity = 0,
        ?DateTime $createdAt = null,
        ?DateTime $updatedAt = null,
        int $category_id = 0,
        string $size = "",
        string $color = "",
        string $type = "",
        int $material_fee = 0
    ) {
        parent::__construct(
            $id,
            $name,
            $photos,
            $price,
            $description,
            $quantity,
            $createdAt ?? new DateTime(),
            $updatedAt ?? new DateTime(),
            $category_id
        );

        $this->size = $size;
        $this->color = $color;
        $this->type = $type;
        $this->material_fee = $material_fee;
    }
    // Getters & Setters
    public function getSize(): string
    {
        return $this->size;
    }
    public function setSize(string $size): void
    {
        $this->size = $size;
    }

    public function getColor(): string
    {
        return $this->color;
    }
    public function setColor(string $color): void
    {
        $this->color = $color;
    }

    public function getType(): string
    {
        return $this->type;
    }
    public function setType(string $type): void
    {
        $this->type = $type;
    }

    public function getMaterialFee(): int
    {
        return $this->material_fee;
    }
    public function setMaterialFee(int $fee): void
    {
        $this->material_fee = $fee;
    }


    // Implémentation de StockableInterface
    public function addStocks(int $stock): self
    {
        $this->quantity += $stock;
        return $this;
    }

    public function removeStocks(int $stock): self
    {
        $this->quantity -= $stock;
        if ($this->quantity < 0) {
            $this->quantity = 0;
        }
        return $this;
    }

    // Méthodes abstraites de AbstractProduct
    public function findOneById(int $id)
    {
        $conn = new mysqli("localhost", "root", "", "draft_shop");
        if ($conn->connect_error) die("Erreur de connexion : " . $conn->connect_error);

        $stmt = $conn->prepare("SELECT * FROM clothing WHERE product_id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();
        if (!$data) return false;

        $productData = parent::findOneById($id);
        if (!$productData) return false;

        return new Clothing(
            $productData->getId(),
            $productData->getName(),
            $productData->getPhotos(),
            $productData->getPrice(),
            $productData->getDescription(),
            $productData->getQuantity(),
            $productData->getCreatedAt(),
            $productData->getUpdatedAt(),
            $productData->getCategoryId(),
            $data['size'],
            $data['color'],
            $data['type'],
            $data['material_fee']
        );
    }

    public function findAll(): array
    {
        $conn = new mysqli("localhost", "root", "", "draft_shop");
        if ($conn->connect_error) die("Erreur de connexion : " . $conn->connect_error);

        $stmt = $conn->prepare("SELECT * FROM clothing");
        $stmt->execute();
        $result = $stmt->get_result();

        $clothes = [];
        while ($row = $result->fetch_assoc()) {
            $productData = parent::findOneById($row['product_id']);
            if ($productData) {
                $clothes[] = new Clothing(
                    $productData->getId(),
                    $productData->getName(),
                    $productData->getPhotos(),
                    $productData->getPrice(),
                    $productData->getDescription(),
                    $productData->getQuantity(),
                    $productData->getCreatedAt(),
                    $productData->getUpdatedAt(),
                    $productData->getCategoryId(),
                    $row['size'],
                    $row['color'],
                    $row['type'],
                    $row['material_fee']
                );
            }
        }
        return $clothes;
    }

    public function create()
    {
        $product = parent::create();
        if (!$product) return false;

        $conn = new mysqli("localhost", "root", "", "draft_shop");
        if ($conn->connect_error) die("Erreur de connexion : " . $conn->connect_error);

        $stmt = $conn->prepare("
            INSERT INTO clothing (product_id, size, color, type, material_fee)
            VALUES (?, ?, ?, ?, ?)
        ");
        $stmt->bind_param(
            "isssi",
            $product->getId(),
            $this->size,
            $this->color,
            $this->type,
            $this->material_fee
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
            UPDATE clothing
            SET size = ?, color = ?, type = ?, material_fee = ?
            WHERE product_id = ?
        ");
        $stmt->bind_param(
            "sssii",
            $this->size,
            $this->color,
            $this->type,
            $this->material_fee,
            $this->getId()
        );

        return $stmt->execute();
    }
}

class Electronic extends AbstractProduct implements StockableInterface
{
    private string $brand;
    private int $warranty_fee;

    public function __construct(
        int $id = 0,
        string $name = "",
        array $photos = [],
        int $price = 0,
        string $description = "",
        int $quantity = 0,
        ?DateTime $createdAt = null,
        ?DateTime $updatedAt = null,
        int $category_id = 0,
        string $brand = "",
        int $warranty_fee = 0
    ) {
        parent::__construct(
            $id,
            $name,
            $photos,
            $price,
            $description,
            $quantity,
            $createdAt ?? new DateTime(),
            $updatedAt ?? new DateTime(),
            $category_id
        );

        $this->brand = $brand;
        $this->warranty_fee = $warranty_fee;
    }

    // Getters & Setters
    public function getBrand(): string
    {
        return $this->brand;
    }

    public function setBrand(string $brand): void
    {
        $this->brand = $brand;
    }

    public function getWarrantyFee(): int
    {
        return $this->warranty_fee;
    }

    public function setWarrantyFee(int $fee): void
    {
        $this->warranty_fee = $fee;
    }

    // Implémentation de StockableInterface
    public function addStocks(int $stock): self
    {
        $this->quantity += $stock;
        return $this;
    }

    public function removeStocks(int $stock): self
    {
        $this->quantity -= $stock;
        if ($this->quantity < 0) {
            $this->quantity = 0;
        }
        return $this;
    }

    // Méthodes abstraites à implémenter
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
            $data['warranty_fee']
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
                    $row['warranty_fee']
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
            INSERT INTO electronic (product_id, brand, warranty_fee)
            VALUES (?, ?, ?)
        ");
        $stmt->bind_param(
            "isi",
            $product->getId(),
            $this->brand,
            $this->warranty_fee
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
            SET brand = ?, warranty_fee = ?
            WHERE product_id = ?
        ");
        $stmt->bind_param(
            "sii",
            $this->brand,
            $this->warranty_fee,
            $this->getId()
        );

        return $stmt->execute();
    }
}
