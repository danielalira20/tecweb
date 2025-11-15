<?php
    use TECWEB\MYAPI\Products as Products;
    require_once __DIR__ . '/myapi/Products.php';

    $productos = new Products('marketzone');
    
    if(isset($_GET['search'])) {
        $terminoBusqueda = $_GET['search'];
        $productos->search($terminoBusqueda);
    }

    echo $productos->getData();
?>