<?php
/*
by: Elavarasan
on: 07/02/2022
*/

$productsJson = file_get_contents("products.json"); // It will get product list from products.json
$products = json_decode($productsJson,true); //It will convert JSON data to a PHP array
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://unpkg.com/spectre.css/dist/spectre.min.css">
    <link rel="stylesheet" href="https://unpkg.com/spectre.css/dist/spectre-icons.min.css">
    <title>ecom</title>
</head>
<body>
    <div class="container p-0">
        <header class="navbar bg-secondary px-1">
            <section class="navbar-center">
                <b style="font-size: 1.5rem;">Ecom</b>
            </section>
            <section class="navbar-section">
                <button class="btn btn-primary m-1" onClick="window.location.reload()">Reload</button>
                <a href="/admin" class="btn btn-primary m-1">Admin site</a>
            </section>
        </header>
        <div class="columns m-2">
            <div class="col-8 col-mx-auto">
            <h4>Products</h4>
            <div class="col-12 columns col-mx-auto">
                <?php
                // It will attach the product info to script
                $productCount = 0;
                foreach($products as $product){
                    $productCount++;
                    echo "<div class='card column col-5 p-0 m-2' style='cursor: pointer;'>
                    <div class='card-image bg-gray' style='text-align:center;padding: 30px 0px'>
                    <i class='icon icon-2x icon-photo'></i>
                    </div>
                    <div class='card-header columns'>
                    <div class='card-title col-10 h6'>"
                    .$productCount.". ".$product["title"].
                    "</div>
                    <div class='col-2' style='height:1rem;width:1rem;background-color:"
                    .$product["color"].
                    ";'>
                    </div>
                    <div class='text-primary col-12'><b>₹"
                    .$product["price"].
                    "</b></div>
                    </div>
                    <div class='card-footer'>"
                    .$product["subject"].
                    "</div>
                    </div>";
                }
                ?>
            </div>
            </div>
        </div>
    </div>
</body>
</html>