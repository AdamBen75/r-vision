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
}


class Electronic extends Product
{
    public function __construct(
        int $id = 0,
        string $name = "",
        array $photos = [],
        int $price = 0,
        string $description = "",
        int $quantity = 0,
        int $category_id = 0,
        private string $brand = "",
        private int $warranty_fee = 0
    ) {
        parent::__construct($id, $name, $photos, $price, $description, $quantity, new DateTime(), new DateTime(), $category_id);
    }
}
