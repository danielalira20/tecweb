// Configuración inicial del producto por defecto
var productoBase = {
    "precio": 0.0,
    "unidades": 1,
    "modelo": "XX-000",
    "marca": "NA",
    "detalles": "NA",
    "imagen": "img/default.png"
};

// Estado de validación de campos del formulario
let estadoValidacion = {
    nombre: false,
    marca: false,
    modelo: false,
    precio: false,
    unidades: false,
    detalles: false,
    imagen: false
};

let temporizadorNombre = null;

$(document).ready(function(){
    let modoEdicion = false;

    $('#product-result').hide();
    cargarListaProductos();
    
    // Establecer valores predeterminados en el formulario
    $('#precio').val(99.99);
    $('#unidades').val(1);
    $('#modelo').val('XX-000');
    $('#marca').val('NA');
    $('#detalles').val('NA');
    $('#imagen').val('img/default.png');

    function cargarListaProductos() {
        $.ajax({
            url: './backend/product-list.php',
            type: 'GET',
            success: function(response) {
                const productos = JSON.parse(response);
            
                if(Object.keys(productos).length > 0) {
                    let htmlTemplate = '';

                    productos.forEach(producto => {
                        let infoProducto = '';
                        infoProducto += '<li>precio: '+producto.precio+'</li>';
                        infoProducto += '<li>unidades: '+producto.unidades+'</li>';
                        infoProducto += '<li>modelo: '+producto.modelo+'</li>';
                        infoProducto += '<li>marca: '+producto.marca+'</li>';
                        infoProducto += '<li>detalles: '+producto.detalles+'</li>';
                    
                        htmlTemplate += `
                            <tr productId="${producto.id}">
                                <td>${producto.id}</td>
                                <td><a href="#" class="product-item">${producto.nombre}</a></td>
                                <td><ul>${infoProducto}</ul></td>
                                <td>
                                    <button class="product-delete btn btn-danger" onclick="eliminarProducto()">
                                        Eliminar
                                    </button>
                                </td>
                            </tr>
                        `;
                    });
                    $('#products').html(htmlTemplate);
                }
            }
        });
    }

    // Validación en tiempo real del nombre
    $('#name').on('input', function() {
        clearTimeout(temporizadorNombre);
        let nombreProducto = $(this).val().trim();
        
        if(nombreProducto.length > 0) {
            temporizadorNombre = setTimeout(function() {
                $.ajax({
                    url: './backend/product-validate-name.php',
                    type: 'POST',
                    data: { nombre: nombreProducto },
                    success: function(response) {
                        let resultado = JSON.parse(response);
                        if(resultado.existe && !modoEdicion) {
                            actualizarEstadoCampo('name', false, 'ERROR: Este nombre ya está registrado');
                            estadoValidacion.nombre = false;
                        } else {
                            actualizarEstadoCampo('name', true, 'OK: Nombre disponible');
                            estadoValidacion.nombre = true;
                        }
                        mostrarResumenValidacion();
                    }
                });
            }, 500);
        } else {
            actualizarEstadoCampo('name', false, 'ERROR: El nombre es obligatorio');
            estadoValidacion.nombre = false;
            mostrarResumenValidacion();
        }
    });

    // Eventos de pérdida de foco para validaciones
    $('#name').blur(function() {
        verificarNombre();
    });

    $('#marca').blur(function() {
        verificarMarca();
    });

    $('#modelo').blur(function() {
        verificarModelo();
    });

    $('#precio').blur(function() {
        verificarPrecio();
    });

    $('#unidades').blur(function() {
        verificarUnidades();
    });

    $('#detalles').blur(function() {
        verificarDetalles();
    });

    $('#imagen').blur(function() {
        verificarImagen();
    });

    // Funciones de validación
    function verificarNombre() {
        let nombre = $('#name').val().trim();
        if(nombre === '') {
            actualizarEstadoCampo('name', false, 'ERROR: El nombre es obligatorio');
            estadoValidacion.nombre = false;
            return false;
        } else if(nombre.length > 100) {
            actualizarEstadoCampo('name', false, 'ERROR: Máximo 100 caracteres');
            estadoValidacion.nombre = false;
            return false;
        } else {
            actualizarEstadoCampo('name', true, 'OK: Nombre correcto');
            estadoValidacion.nombre = true;
            return true;
        }
    }

    function verificarMarca() {
        let marca = $('#marca').val().trim();
        if(marca === '') {
            actualizarEstadoCampo('marca', false, 'ERROR: La marca es obligatoria');
            estadoValidacion.marca = false;
            return false;
        } else if(marca.length > 50) {
            actualizarEstadoCampo('marca', false, 'ERROR: Máximo 50 caracteres');
            estadoValidacion.marca = false;
            return false;
        } else {
            actualizarEstadoCampo('marca', true, 'OK: Marca correcta');
            estadoValidacion.marca = true;
            return true;
        }
    }

    function verificarModelo() {
        let modelo = $('#modelo').val().trim();
        let formatoValido = /^[a-zA-Z0-9\-]+$/;
        if(modelo === '') {
            actualizarEstadoCampo('modelo', false, 'ERROR: El modelo es obligatorio');
            estadoValidacion.modelo = false;
            return false;
        } else if(!formatoValido.test(modelo)) {
            actualizarEstadoCampo('modelo', false, 'ERROR: Solo letras, números y guiones');
            estadoValidacion.modelo = false;
            return false;
        } else if(modelo.length > 25) {
            actualizarEstadoCampo('modelo', false, 'ERROR: Máximo 25 caracteres');
            estadoValidacion.modelo = false;
            return false;
        } else {
            actualizarEstadoCampo('modelo', true, 'OK: Modelo correcto');
            estadoValidacion.modelo = true;
            return true;
        }
    }

    function verificarPrecio() {
        let precio = parseFloat($('#precio').val());
        if(isNaN(precio) || precio <= 0) {
            actualizarEstadoCampo('precio', false, 'ERROR: Precio debe ser mayor a 0');
            estadoValidacion.precio = false;
            return false;
        } else if(precio > 99999999.99) {
            actualizarEstadoCampo('precio', false, 'ERROR: Precio demasiado alto');
            estadoValidacion.precio = false;
            return false;
        } else {
            actualizarEstadoCampo('precio', true, 'OK: Precio correcto');
            estadoValidacion.precio = true;
            return true;
        }
    }

    function verificarUnidades() {
        let unidades = parseInt($('#unidades').val());
        if(isNaN(unidades) || unidades < 0) {
            actualizarEstadoCampo('unidades', false, 'ERROR: Mínimo 0 unidades');
            estadoValidacion.unidades = false;
            return false;
        } else {
            actualizarEstadoCampo('unidades', true, 'OK: Unidades correctas');
            estadoValidacion.unidades = true;
            return true;
        }
    }

    function verificarDetalles() {
        let detalles = $('#detalles').val().trim();
        if(detalles === '') {
            actualizarEstadoCampo('detalles', false, 'ERROR: Los detalles son obligatorios');
            estadoValidacion.detalles = false;
            return false;
        } else if(detalles.length > 250) {
            actualizarEstadoCampo('detalles', false, 'ERROR: Máximo 250 caracteres');
            estadoValidacion.detalles = false;
            return false;
        } else {
            actualizarEstadoCampo('detalles', true, 'OK: Detalles correctos');
            estadoValidacion.detalles = true;
            return true;
        }
    }

    function verificarImagen() {
        let imagen = $('#imagen').val().trim();
        if(imagen === '') {
            actualizarEstadoCampo('imagen', false, 'ERROR: Ruta de imagen obligatoria');
            estadoValidacion.imagen = false;
            return false;
        } else {
            actualizarEstadoCampo('imagen', true, 'OK: Ruta correcta');
            estadoValidacion.imagen = true;
            return true;
        }
    }

    // Actualizar mensaje de estado de un campo
    function actualizarEstadoCampo(campo, esValido, mensaje) {
        let elementoEstado = $(`#${campo}-status`);
        if(esValido) {
            elementoEstado.removeClass('text-danger').addClass('text-success').text(mensaje);
        } else {
            elementoEstado.removeClass('text-success').addClass('text-danger').text(mensaje);
        }
    }

    function mostrarResumenValidacion() {
        let htmlResumen = '<ul style="list-style: none; padding: 0;">';
        for(let campo in estadoValidacion) {
            let simbolo = estadoValidacion[campo] ? '[OK]' : '[X]';
            let claseCSS = estadoValidacion[campo] ? 'text-success' : 'text-danger';
            let nombreCampo = campo.charAt(0).toUpperCase() + campo.slice(1);
            htmlResumen += `<li class="${claseCSS}">${simbolo} ${nombreCampo}</li>`;
        }
        htmlResumen += '</ul>';
        $('#validation-summary').html(htmlResumen);
        $('#general-status').show();
    }

    $('#search').keyup(function() {
        if($('#search').val()) {
            let terminoBusqueda = $('#search').val();
            $.ajax({
                url: './backend/product-search.php?search='+$('#search').val(),
                data: {search: terminoBusqueda},
                type: 'GET',
                success: function (response) {
                    if(!response.error) {
                        const productos = JSON.parse(response);
                        
                        if(Object.keys(productos).length > 0) {
                            let htmlTabla = '';
                            let htmlBarra = '';

                            productos.forEach(producto => {
                                let infoProducto = '';
                                infoProducto += '<li>precio: '+producto.precio+'</li>';
                                infoProducto += '<li>unidades: '+producto.unidades+'</li>';
                                infoProducto += '<li>modelo: '+producto.modelo+'</li>';
                                infoProducto += '<li>marca: '+producto.marca+'</li>';
                                infoProducto += '<li>detalles: '+producto.detalles+'</li>';
                            
                                htmlTabla += `
                                    <tr productId="${producto.id}">
                                        <td>${producto.id}</td>
                                        <td><a href="#" class="product-item">${producto.nombre}</a></td>
                                        <td><ul>${infoProducto}</ul></td>
                                        <td>
                                            <button class="product-delete btn btn-danger">
                                                Eliminar
                                            </button>
                                        </td>
                                    </tr>
                                `;

                                htmlBarra += `
                                    <li>${producto.nombre}</il>
                                `;
                            });
                            $('#product-result').show();
                            $('#container').html(htmlBarra);
                            $('#products').html(htmlTabla);    
                        }
                    }
                }
            });
        }
        else {
            $('#product-result').hide();
        }
    });

    // Manejo del envío del formulario
    $('#product-form').submit(e => {
        e.preventDefault();

        // Ejecutar todas las validaciones
        let nombreOK = verificarNombre();
        let marcaOK = verificarMarca();
        let modeloOK = verificarModelo();
        let precioOK = verificarPrecio();
        let unidadesOK = verificarUnidades();
        let detallesOK = verificarDetalles();
        let imagenOK = verificarImagen();

        // Verificar si todas las validaciones pasaron
        if(!nombreOK || !marcaOK || !modeloOK || !precioOK || 
           !unidadesOK || !detallesOK || !imagenOK) {
            alert('Por favor, corrige los errores en el formulario antes de continuar.');
            mostrarResumenValidacion();
            return;
        }

        let datosProducto = {
            nombre: $('#name').val().trim(),
            marca: $('#marca').val().trim(),
            modelo: $('#modelo').val().trim(),
            precio: parseFloat($('#precio').val()),
            unidades: parseInt($('#unidades').val()),
            detalles: $('#detalles').val().trim(),
            imagen: $('#imagen').val().trim(),
            id: $('#productId').val()
        };

        const urlBackend = modoEdicion === false ? './backend/product-add.php' : './backend/product-edit.php';
        
        $.post(urlBackend, datosProducto, (response) => {
            let respuesta = JSON.parse(response);
            let htmlRespuesta = '';
            htmlRespuesta += `
                        <li style="list-style: none;">status: ${respuesta.status}</li>
                        <li style="list-style: none;">message: ${respuesta.message}</li>
                    `;
            
            // Limpiar formulario
            $('#name').val('');
            $('#marca').val('NA');
            $('#modelo').val('XX-000');
            $('#precio').val(99.99);
            $('#unidades').val(1);
            $('#detalles').val('NA');
            $('#imagen').val('img/default.png');
            $('#productId').val('');
            
            // Limpiar mensajes de validación
            $('.form-text').text('');
            
            // Reiniciar estado de validaciones
            for(let campo in estadoValidacion) {
                estadoValidacion[campo] = false;
            }
            
            $('#product-result').show();
            $('#container').html(htmlRespuesta);
            cargarListaProductos();
            modoEdicion = false;
            
            // Cambiar texto del botón
            $('button.btn-primary').text("Agregar Producto");
        });
    });

    $(document).on('click', '.product-delete', (e) => {
        if(confirm('¿Realmente deseas eliminar el producto?')) {
            const elemento = $(this)[0].activeElement.parentElement.parentElement;
            const idProducto = $(elemento).attr('productId');
            $.post('./backend/product-delete.php', {id: idProducto}, (response) => {
                $('#product-result').hide();
                cargarListaProductos();
            });
        }
    });

    $(document).on('click', '.product-item', (e) => {
        const elemento = $(this)[0].activeElement.parentElement.parentElement;
        const idProducto = $(elemento).attr('productId');
        $.post('./backend/product-single.php', {id: idProducto}, (response) => {
            let productoSeleccionado = JSON.parse(response);
            
            // Cargar datos en el formulario
            $('#name').val(productoSeleccionado.nombre);
            $('#marca').val(productoSeleccionado.marca);
            $('#modelo').val(productoSeleccionado.modelo);
            $('#precio').val(productoSeleccionado.precio);
            $('#unidades').val(productoSeleccionado.unidades);
            $('#detalles').val(productoSeleccionado.detalles);
            $('#imagen').val(productoSeleccionado.imagen);
            $('#productId').val(productoSeleccionado.id);
            
            // Validar todos los campos cargados
            verificarNombre();
            verificarMarca();
            verificarModelo();
            verificarPrecio();
            verificarUnidades();
            verificarDetalles();
            verificarImagen();
            mostrarResumenValidacion();
            
            modoEdicion = true;
            
            // Cambiar texto del botón
            $('button.btn-primary').text("Modificar Producto");
        });
        e.preventDefault();
    });    
});