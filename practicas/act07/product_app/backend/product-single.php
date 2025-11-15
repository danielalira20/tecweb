<?php
    use TECWEB\MYAPI\Products as Products;
    require_once __DIR__ . '/myapi/Products.php';

    $productos = new Products('marketzone');
    
    if(isset($_POST['id'])) {
        $idProducto = $_POST['id'];
        $productos->single($idProducto);
    }

    echo $productos->getData();
?>