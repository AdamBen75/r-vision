<?php
require_once __DIR__ . '/../Job02/Job02.php';

$category = new Category(11, "Électronique", "Appareils et gadgets high-tech");
$product = new Product(1, "iPhone 15", ["iphone15.jpg"], 1200, "Smartphone Apple", 10, new DateTime(), new DateTime(), $category->getId());

// crée une catégorie
$category = new Category(
    1, 
    'Électronique', 
    'Tous les produits électroniques', 
    new DateTime('2025-10-13 10:00:00'), 
    new DateTime('2025-10-13 10:00:00')
);

// crée un produit rattaché à la catégorie
$product = new Product(5,'téléphone',['pIX1.jpg', 'pIX2.jpg'],599,'Smartphone SYMPA',10,new DateTime('2025-10-13 10:05:00'),new DateTime('2025-10-13 10:05:00'),$category->GetId() );

// test des getters
echo "Catégorie : " . $category->GetName() . " (ID : " . $category->GetId() . ")" . PHP_EOL;
echo "Produit : " . $product->GetName() . " (ID : " . $product->GetId() . ")" . PHP_EOL;
echo "Produit appartient à la catégorie ID : " . $product->GetCategoryId() . PHP_EOL;

// test des setters
$product->setCategoryId(3);
$category->setName('Informatique');

echo "Après modification : " . PHP_EOL;
echo "Nouvelle catégorie : " . $category->GetName() . PHP_EOL;
echo "Nouvelle catégorie ID du produit : " . $product->GetCategoryId() . PHP_EOL;