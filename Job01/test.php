<?php
 
 declare(strict_types=1);
 
 require_once __DIR__ . '/../Job01/Job01.php';
$produit1 = new Product(1, 'T-shirt', ['img1.jpg'], 25, 'Beau t-shirt', 10);

$produit1->setName('Taylor Swift');
$produit1->setPrice(30);

echo "Job01: <br><br><br>";
echo "Nom : " . $produit1->getName() . "<br>";
echo "id : " . $produit1->getId() . "<br>";
echo "Prix : " . $produit1->getPrice() . "<br>";
echo "description : " . $produit1->getDescription() . "<br>";
echo "Créé le : " . $produit1->getCreatedAt()->format('Y-m-d H:i:s');