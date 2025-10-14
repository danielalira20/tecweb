<?php
    // Habilitar reporte de errores para debugging
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    // Inicializar variables
    $id = '';
    $nombre = '';
    $marca = '';
    $modelo = '';
    $precio = '';
    $detalles = '';
    $unidades = '';
    $imagen = '';
    $edicion = false;

    // Verificar si se están recibiendo datos para edición
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
        // Datos enviados por POST desde get_productos_xhtml_v2.php
        $edicion = true;
        $id = $_POST['id'];
        $nombre = isset($_POST['nombre']) ? $_POST['nombre'] : '';
        $marca = isset($_POST['marca']) ? $_POST['marca'] : '';
        $modelo = isset($_POST['modelo']) ? $_POST['modelo'] : '';
        $precio = isset($_POST['precio']) ? $_POST['precio'] : '';
        $unidades = isset($_POST['unidades']) ? $_POST['unidades'] : '';
        $detalles = isset($_POST['detalles']) ? $_POST['detalles'] : '';
        $imagen = isset($_POST['imagen']) ? $_POST['imagen'] : '';
    } elseif (isset($_GET['id'])) {
        // Datos enviados por GET desde get_productos_vigentes_v2.php
        $edicion = true;
        
        // Conectar a la base de datos para obtener los datos del producto
        $link = new mysqli('localhost', 'root', 'daniela20', 'marketzone');
        
        if ($link->connect_errno) {
            die('Falló la conexión: '.$link->connect_error.'<br/>');
        }
        
        $link->set_charset("utf8");
        
        $id = intval($_GET['id']);
        $sql = "SELECT * FROM productos WHERE id = {$id}";
        
        if ($result = $link->query($sql)) {
            $producto = $result->fetch_assoc();
            
            if ($producto) {
                $nombre = $producto['nombre'];
                $marca = $producto['marca'];
                $modelo = $producto['modelo'];
                $precio = $producto['precio'];
                $unidades = $producto['unidades'];
                $detalles = $producto['detalles'];
                $imagen = $producto['imagen'];
            }
            
            $result->free();
        }
        
        $link->close();
    }
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $edicion ? 'Modificar' : 'Registro de'; ?> Productos - Maquillaje</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 50%, #feada6 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }
        
        .container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            padding: 40px;
            max-width: 600px;
            width: 100%;
        }
        
        h1 {
            color: #d81b60;
            margin-bottom: 10px;
            text-align: center;
            font-size: 28px;
        }
        
        .subtitle {
            text-align: center;
            color: #666;
            margin-bottom: 30px;
            font-size: 14px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        label {
            display: block;
            margin-bottom: 8px;
            color: #555;
            font-weight: 600;
            font-size: 14px;
        }
        
        input[type="text"],
        input[type="number"],
        textarea,
        input[type="file"],
        select {
            width: 100%;
            padding: 12px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 14px;
            transition: border-color 0.3s;
        }
        
        input[type="text"]:focus,
        input[type="number"]:focus,
        textarea:focus,
        select:focus {
            outline: none;
            border-color: #d81b60;
        }
        
        textarea {
            resize: vertical;
            min-height: 80px;
            font-family: Arial, sans-serif;
        }
        
        input[type="file"] {
            padding: 8px;
        }
        
        select {
            cursor: pointer;
        }
        
        button {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, #d81b60 0%, #c2185b 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
            margin-top: 10px;
        }
        
        button:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(216, 27, 96, 0.4);
        }
        
        button:active {
            transform: translateY(0);
        }
        
        .required {
            color: #e74c3c;
        }
        
        .note {
            font-size: 12px;
            color: #888;
            margin-top: 5px;
        }
        
        .error {
            color: #e74c3c;
            font-size: 12px;
            margin-top: 5px;
            display: none;
        }
        
        .error.show {
            display: block;
        }
        
        .input-error {
            border-color: #e74c3c !important;
        }

        .imagen-actual {
            max-width: 200px;
            margin-top: 10px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .btn-cancelar {
            background: linear-gradient(135deg, #757575 0%, #616161 100%);
            margin-top: 10px;
        }

        .btn-cancelar:hover {
            box-shadow: 0 5px 20px rgba(117, 117, 117, 0.4);
        }
    </style>
</head>
<body>
    <div class="container">
        <h1><?php echo $edicion ? ' Modificar Producto' : 'Registro de Productos'; ?></h1>
        <p class="subtitle">Tienda de Maquillaje y Cosméticos</p>
        
        <form id="productoForm" action="<?php echo $edicion ? 'http://localhost/tecweb/practicas/p09/update_producto.php' : 'http://localhost/tecweb/practicas/p08/set_producto_v2.php'; ?>" method="POST" enctype="multipart/form-data">
            
            <?php if ($edicion): ?>
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">
            <input type="hidden" name="imagen_actual" value="<?php echo htmlspecialchars($imagen); ?>">
            <?php endif; ?>

            <div class="form-group">
                <label for="nombre">Nombre del Producto <span class="required">*</span></label>
                <input type="text" id="nombre" name="nombre" placeholder="Ej: Labial Mate de Larga Duración" value="<?php echo htmlspecialchars($nombre); ?>">
                <span class="error" id="errorNombre">El nombre es requerido y debe tener 100 caracteres o menos.</span>
            </div>
            
            <div class="form-group">
                <label for="marca">Marca <span class="required">*</span></label>
                <select id="marca" name="marca">
                    <option value="">-- Seleccione una marca --</option>
                    <option value="MAC" <?php echo $marca === 'MAC' ? 'selected' : ''; ?>>MAC</option>
                    <option value="Maybelline" <?php echo $marca === 'Maybelline' ? 'selected' : ''; ?>>Maybelline</option>
                    <option value="L'Oréal" <?php echo $marca === "L'Oréal" ? 'selected' : ''; ?>>L'Oréal</option>
                    <option value="Revlon" <?php echo $marca === 'Revlon' ? 'selected' : ''; ?>>Revlon</option>
                    <option value="NYX" <?php echo $marca === 'NYX' ? 'selected' : ''; ?>>NYX</option>
                    <option value="Fenty Beauty" <?php echo $marca === 'Fenty Beauty' ? 'selected' : ''; ?>>Fenty Beauty</option>
                    <option value="Urban Decay" <?php echo $marca === 'Urban Decay' ? 'selected' : ''; ?>>Urban Decay</option>
                    <option value="Anastasia Beverly Hills" <?php echo $marca === 'Anastasia Beverly Hills' ? 'selected' : ''; ?>>Anastasia Beverly Hills</option>
                    <option value="Too Faced" <?php echo $marca === 'Too Faced' ? 'selected' : ''; ?>>Too Faced</option>
                    <option value="Clinique" <?php echo $marca === 'Clinique' ? 'selected' : ''; ?>>Clinique</option>
                    <option value="Estée Lauder" <?php echo $marca === 'Estée Lauder' ? 'selected' : ''; ?>>Estée Lauder</option>
                    <option value="NARS" <?php echo $marca === 'NARS' ? 'selected' : ''; ?>>NARS</option>
                </select>
                <span class="error" id="errorMarca">Debe seleccionar una marca.</span>
            </div>
            
            <div class="form-group">
                <label for="modelo">Modelo <span class="required">*</span></label>
                <input type="text" id="modelo" name="modelo" placeholder="Ej: RVL-001" value="<?php echo htmlspecialchars($modelo); ?>">
                <span class="error" id="errorModelo">El modelo es requerido, debe ser alfanumérico y tener 25 caracteres o menos.</span>
            </div>
            
            <div class="form-group">
                <label for="precio">Precio <span class="required">*</span></label>
                <input type="number" id="precio" name="precio" step="0.01" min="0" placeholder="0.00" value="<?php echo htmlspecialchars($precio); ?>">
                <p class="note">El precio debe ser mayor a $99.99</p>
                <span class="error" id="errorPrecio">El precio es requerido y debe ser mayor a 99.99</span>
            </div>
            
            <div class="form-group">
                <label for="detalles">Detalles</label>
                <textarea id="detalles" name="detalles" placeholder="Descripción del producto, ingredientes, beneficios... (opcional)"><?php echo htmlspecialchars($detalles); ?></textarea>
                <p class="note">Opcional - Máximo 250 caracteres</p>
                <span class="error" id="errorDetalles">Los detalles deben tener 250 caracteres o menos.</span>
            </div>
            
            <div class="form-group">
                <label for="unidades">Unidades <span class="required">*</span></label>
                <input type="number" id="unidades" name="unidades" min="0" placeholder="0" value="<?php echo htmlspecialchars($unidades); ?>">
                <span class="error" id="errorUnidades">Las unidades son requeridas y deben ser mayor o igual a 0.</span>
            </div>
            
            <div class="form-group">
                <label for="imagen">Imagen del Producto</label>
                <?php if ($edicion && !empty($imagen)): ?>
                    <p class="note" style="margin-bottom: 10px;"><strong>Imagen actual:</strong></p>
                    <img src="img/<?php echo htmlspecialchars($imagen); ?>" alt="Imagen actual" class="imagen-actual">
                    <p class="note" style="margin-top: 10px; margin-bottom: 10px;">Seleccione una nueva imagen solo si desea cambiarla</p>
                <?php endif; ?>
                <input type="file" id="imagen" name="imagen" accept="image/*">
                <p class="note"><?php echo $edicion ? 'Opcional - Dejar vacío para mantener la imagen actual. ' : 'Opcional - Si no se carga, se usará una imagen por defecto. '; ?>Formatos: JPG, PNG, GIF</p>
            </div>
            
            <button type="submit"><?php echo $edicion ? 'Actualizar Producto' : 'Registrar Producto'; ?></button>
            
            <?php if ($edicion): ?>
            <button type="button" class="btn-cancelar" onclick="window.history.back()">Cancelar</button>
            <?php endif; ?>
            
        </form>
    </div>

    <script>
        const IMAGEN_POR_DEFECTO = 'src/imagen.png';
        const esEdicion = <?php echo $edicion ? 'true' : 'false'; ?>;

        document.getElementById('productoForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            let valido = true;
            
            document.querySelectorAll('.error').forEach(error => error.classList.remove('show'));
            document.querySelectorAll('input, select, textarea').forEach(input => input.classList.remove('input-error'));
            
            // Validar nombre
            const nombre = document.getElementById('nombre').value.trim();
            if (nombre === '' || nombre.length > 100) {
                mostrarError('nombre', 'errorNombre');
                valido = false;
            }
            
            // Validar marca
            const marca = document.getElementById('marca').value;
            if (marca === '') {
                mostrarError('marca', 'errorMarca');
                valido = false;
            }
            
            // Validar modelo (alfanumérico y <= 25 caracteres)
            const modelo = document.getElementById('modelo').value.trim();
            const regexAlfanumerico = /^[a-zA-Z0-9\s]+$/;
            if (modelo === '' || !regexAlfanumerico.test(modelo) || modelo.length > 25) {
                mostrarError('modelo', 'errorModelo');
                valido = false;
            }
            
            // Validar precio
            const precio = parseFloat(document.getElementById('precio').value);
            if (isNaN(precio) || precio <= 99.99) {
                mostrarError('precio', 'errorPrecio');
                valido = false;
            }
            
            // Validar detalles (opcional, pero si existe debe ser <= 250)
            const detalles = document.getElementById('detalles').value.trim();
            if (detalles.length > 250) {
                mostrarError('detalles', 'errorDetalles');
                valido = false;
            }
            
            // Validar unidades
            const unidades = document.getElementById('unidades').value;
            if (unidades === '' || parseInt(unidades) < 0 || isNaN(parseInt(unidades))) {
                mostrarError('unidades', 'errorUnidades');
                valido = false;
            }
            
            // Validar imagen (opcional en edición, en registro se usa imagen por defecto)
            const imagen = document.getElementById('imagen');
            if (!esEdicion && (!imagen.files || imagen.files.length === 0)) {
                console.log('Se usará la imagen por defecto: ' + IMAGEN_POR_DEFECTO);
            }
            
            // Si todo es válido, enviar formulario
            if (valido) {
                alert(esEdicion ? 'Actualizando producto...' : 'Formulario válido. Enviando datos...');
                this.submit();
            } else {
                alert('Por favor, corrija los errores en el formulario.');
            }
        });
        
        function mostrarError(inputId, errorId) {
            document.getElementById(inputId).classList.add('input-error');
            document.getElementById(errorId).classList.add('show');
        }
        
        // Validación en tiempo real para mejorar experiencia de usuario
        document.getElementById('nombre').addEventListener('input', function() {
            if (this.value.trim() !== '' && this.value.length <= 100) {
                this.classList.remove('input-error');
                document.getElementById('errorNombre').classList.remove('show');
            }
        });
        
        document.getElementById('marca').addEventListener('change', function() {
            if (this.value !== '') {
                this.classList.remove('input-error');
                document.getElementById('errorMarca').classList.remove('show');
            }
        });
        
        document.getElementById('modelo').addEventListener('input', function() {
            const regexAlfanumerico = /^[a-zA-Z0-9\s]+$/;
            if (this.value.trim() !== '' && regexAlfanumerico.test(this.value) && this.value.length <= 25) {
                this.classList.remove('input-error');
                document.getElementById('errorModelo').classList.remove('show');
            }
        });
        
        document.getElementById('precio').addEventListener('input', function() {
            if (parseFloat(this.value) > 99.99) {
                this.classList.remove('input-error');
                document.getElementById('errorPrecio').classList.remove('show');
            }
        });
        
        document.getElementById('detalles').addEventListener('input', function() {
            if (this.value.length <= 250) {
                this.classList.remove('input-error');
                document.getElementById('errorDetalles').classList.remove('show');
            }
        });
        
        document.getElementById('unidades').addEventListener('input', function() {
            if (parseInt(this.value) >= 0) {
                this.classList.remove('input-error');
                document.getElementById('errorUnidades').classList.remove('show');
            }
        });
    </script>
</body>
</html>