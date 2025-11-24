<?php

declare(strict_types=1);

require_once 'src/Abstract/AbstractProduct.php';
require_once 'src/Interface/StockableInterface.php';
require_once 'src/Clothing.php';


$vetement = new  \App\Clothing(
    id: 1,
    name: "T-shirt Taylor Swift",
    photos: ["tshirt1.jpg", "tshirt2.jpg"],
    price: 25,
    description: "Un T-shirt officiel de Taylor Swift",
    quantity: 10,
    category_id: 2,
    size: "M",
    color: "Noir",
    type: "T-shirt",
    material_fee: 3
);

// Affichage de quelques infos
echo "<h2>" . $vetement->getName() . "</h2>";
echo "<p>Prix : " . $vetement->getPrice() . " €</p>";
echo "<p>Couleur : " . $vetement->getColor() . "</p>";
echo "<p>Taille : " . $vetement->getSize() . "</p>";
echo "<p>Stock : " . $vetement->getQuantity() . "</p>";

// On ajoute du stock
$vetement->addStocks(5);
echo "<p>Stock après ajout : " . $vetement->getQuantity() . "</p>";
