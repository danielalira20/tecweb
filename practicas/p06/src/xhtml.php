<?php

header("Content-Type: application/xhtml+xml; charset=UTF-8");

if ($_SERVER["REQUEST_METHOD"] === "POST" && !empty($_POST["edad"]) && !empty($_POST["sexo"])) {
    
  
    $edad = intval(trim($_POST["edad"])); 
    $sexo = mb_strtolower(trim($_POST["sexo"]), "UTF-8"); 

   
    echo '<?xml version="1.0" encoding="UTF-8"?>';
    ?>
    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" 
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml" xml:lang="es">
    <head>
        <title>Resultado de Validación</title>
        <meta charset="UTF-8" />
    </head>
    <body>
        <?php if ($sexo === "femenino" && $edad >= 18 && $edad <= 35): ?>
            <div style="color:green; font-weight:bold;">
                ✅ Bienvenida, usted cumple con los requisitos establecidos.
            </div>
        <?php else: ?>
            <div style="color:red; font-weight:bold;">
                ❌ Lo sentimos, usted no cumple con los requisitos necesarios.
            </div>
        <?php endif; ?>
    </body>
    </html>
    <?php
} else {
    
    echo '<?xml version="1.0" encoding="UTF-8"?>';
    ?>
    <!DOCTYPE html>
    <html xmlns="http://www.w3.org/1999/xhtml" xml:lang="es">
    <head>
        <title>Error</title>
    </head>
    <body>
        <p style="color:orange;">⚠️ Error: No se recibieron correctamente los datos del formulario.</p>
    </body>
    </html>
    <?php
}
?>
