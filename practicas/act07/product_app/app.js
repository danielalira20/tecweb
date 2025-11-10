// JSON BASE A MOSTRAR EN FORMULARIO (Ya no se usa pero lo dejamos por compatibilidad)
var baseJSON = {
    "precio": 0.0,
    "unidades": 1,
    "modelo": "XX-000",
    "marca": "NA",
    "detalles": "NA",
    "imagen": "img/default.png"
};

// OBJETO PARA GUARDAR ESTADO DE VALIDACIONES
let validaciones = {
    nombre: false,
    marca: false,
    modelo: false,
    precio: false,
    unidades: false,
    detalles: false,
    imagen: false
};


let nombreTimeout = null;

$(document).ready(function(){
    let edit = false;

    $('#product-result').hide();
    listarProductos();
    
    
    $('#precio').val(99.99);
    $('#unidades').val(1);
    $('#modelo').val('XX-000');
    $('#marca').val('NA');
    $('#detalles').val('NA');
    $('#imagen').val('img/default.png');

    function listarProductos() {
        $.ajax({
            url: './backend/product-list.php',
            type: 'GET',
            success: function(response) {
                const productos = JSON.parse(response);
            
                if(Object.keys(productos).length > 0) {
                    let template = '';

                    productos.forEach(producto => {
                        let descripcion = '';
                        descripcion += '<li>precio: '+producto.precio+'</li>';
                        descripcion += '<li>unidades: '+producto.unidades+'</li>';
                        descripcion += '<li>modelo: '+producto.modelo+'</li>';
                        descripcion += '<li>marca: '+producto.marca+'</li>';
                        descripcion += '<li>detalles: '+producto.detalles+'</li>';
                    
                        template += `
                            <tr productId="${producto.id}">
                                <td>${producto.id}</td>
                                <td><a href="#" class="product-item">${producto.nombre}</a></td>
                                <td><ul>${descripcion}</ul></td>
                                <td>
                                    <button class="product-delete btn btn-danger" onclick="eliminarProducto()">
                                        Eliminar
                                    </button>
                                </td>
                            </tr>
                        `;
                    });
                    $('#products').html(template);
                }
            }
        });
    }

    
    $('#name').on('input', function() {
        clearTimeout(nombreTimeout);
        let nombre = $(this).val().trim();
        
        if(nombre.length > 0) {
            
            nombreTimeout = setTimeout(function() {
                $.ajax({
                    url: './backend/product-validate-name.php',
                    type: 'POST',
                    data: { nombre: nombre },
                    success: function(response) {
                        let resultado = JSON.parse(response);
                        if(resultado.existe && !edit) {
                            mostrarEstadoCampo('name', false, '❌ Este nombre ya existe en la base de datos');
                            validaciones.nombre = false;
                        } else {
                            mostrarEstadoCampo('name', true, '✓ Nombre disponible');
                            validaciones.nombre = true;
                        }
                        actualizarBarraEstado();
                    }
                });
            }, 500);
        } else {
            mostrarEstadoCampo('name', false, '❌ El nombre es requerido');
            validaciones.nombre = false;
            actualizarBarraEstado();
        }
    });

    
    $('#name').blur(function() {
        validarNombre();
    });

    $('#marca').blur(function() {
        validarMarca();
    });

    $('#modelo').blur(function() {
        validarModelo();
    });

    $('#precio').blur(function() {
        validarPrecio();
    });

    $('#unidades').blur(function() {
        validarUnidades();
    });

    $('#detalles').blur(function() {
        validarDetalles();
    });

    $('#imagen').blur(function() {
        validarImagen();
    });

    
    function validarNombre() {
        let nombre = $('#name').val().trim();
        if(nombre === '') {
            mostrarEstadoCampo('name', false, '❌ El nombre es requerido');
            validaciones.nombre = false;
            return false;
        } else if(nombre.length > 100) {
            mostrarEstadoCampo('name', false, '❌ El nombre no puede exceder 100 caracteres');
            validaciones.nombre = false;
            return false;
        } else {
            mostrarEstadoCampo('name', true, '✓ Nombre válido');
            validaciones.nombre = true;
            return true;
        }
    }

    function validarMarca() {
        let marca = $('#marca').val().trim();
        if(marca === '') {
            mostrarEstadoCampo('marca', false, '❌ La marca es requerida');
            validaciones.marca = false;
            return false;
        } else if(marca.length > 50) {
            mostrarEstadoCampo('marca', false, '❌ La marca no puede exceder 50 caracteres');
            validaciones.marca = false;
            return false;
        } else {
            mostrarEstadoCampo('marca', true, '✓ Marca válida');
            validaciones.marca = true;
            return true;
        }
    }

    function validarModelo() {
        let modelo = $('#modelo').val().trim();
        let regex = /^[a-zA-Z0-9\-]+$/;
        if(modelo === '') {
            mostrarEstadoCampo('modelo', false, '❌ El modelo es requerido');
            validaciones.modelo = false;
            return false;
        } else if(!regex.test(modelo)) {
            mostrarEstadoCampo('modelo', false, '❌ El modelo solo puede contener letras, números y guiones');
            validaciones.modelo = false;
            return false;
        } else if(modelo.length > 25) {
            mostrarEstadoCampo('modelo', false, '❌ El modelo no puede exceder 25 caracteres');
            validaciones.modelo = false;
            return false;
        } else {
            mostrarEstadoCampo('modelo', true, '✓ Modelo válido');
            validaciones.modelo = true;
            return true;
        }
    }

    function validarPrecio() {
        let precio = parseFloat($('#precio').val());
        if(isNaN(precio) || precio <= 0) {
            mostrarEstadoCampo('precio', false, '❌ El precio debe ser mayor a 0');
            validaciones.precio = false;
            return false;
        } else if(precio > 99999999.99) {
            mostrarEstadoCampo('precio', false, '❌ El precio es demasiado alto');
            validaciones.precio = false;
            return false;
        } else {
            mostrarEstadoCampo('precio', true, '✓ Precio válido');
            validaciones.precio = true;
            return true;
        }
    }

    function validarUnidades() {
        let unidades = parseInt($('#unidades').val());
        if(isNaN(unidades) || unidades < 0) {
            mostrarEstadoCampo('unidades', false, '❌ Las unidades deben ser 0 o mayor');
            validaciones.unidades = false;
            return false;
        } else {
            mostrarEstadoCampo('unidades', true, '✓ Unidades válidas');
            validaciones.unidades = true;
            return true;
        }
    }

    function validarDetalles() {
        let detalles = $('#detalles').val().trim();
        if(detalles === '') {
            mostrarEstadoCampo('detalles', false, '❌ Los detalles son requeridos');
            validaciones.detalles = false;
            return false;
        } else if(detalles.length > 250) {
            mostrarEstadoCampo('detalles', false, '❌ Los detalles no pueden exceder 250 caracteres');
            validaciones.detalles = false;
            return false;
        } else {
            mostrarEstadoCampo('detalles', true, '✓ Detalles válidos');
            validaciones.detalles = true;
            return true;
        }
    }

    function validarImagen() {
        let imagen = $('#imagen').val().trim();
        if(imagen === '') {
            mostrarEstadoCampo('imagen', false, '❌ La ruta de imagen es requerida');
            validaciones.imagen = false;
            return false;
        } else {
            mostrarEstadoCampo('imagen', true, '✓ Ruta de imagen válida');
            validaciones.imagen = true;
            return true;
        }
    }

   
    function mostrarEstadoCampo(campo, valido, mensaje) {
        let statusElement = $(`#${campo}-status`);
        if(valido) {
            statusElement.removeClass('text-danger').addClass('text-success').text(mensaje);
        } else {
            statusElement.removeClass('text-success').addClass('text-danger').text(mensaje);
        }
    }

    function actualizarBarraEstado() {
        let template = '<ul style="list-style: none; padding: 0;">';
        for(let campo in validaciones) {
            let icono = validaciones[campo] ? '✓' : '❌';
            let clase = validaciones[campo] ? 'text-success' : 'text-danger';
            template += `<li class="${clase}">${icono} ${campo.charAt(0).toUpperCase() + campo.slice(1)}</li>`;
        }
        template += '</ul>';
        $('#validation-summary').html(template);
        $('#general-status').show();
    }

    $('#search').keyup(function() {
        if($('#search').val()) {
            let search = $('#search').val();
            $.ajax({
                url: './backend/product-search.php?search='+$('#search').val(),
                data: {search},
                type: 'GET',
                success: function (response) {
                    if(!response.error) {
                        const productos = JSON.parse(response);
                        
                        if(Object.keys(productos).length > 0) {
                            let template = '';
                            let template_bar = '';

                            productos.forEach(producto => {
                                let descripcion = '';
                                descripcion += '<li>precio: '+producto.precio+'</li>';
                                descripcion += '<li>unidades: '+producto.unidades+'</li>';
                                descripcion += '<li>modelo: '+producto.modelo+'</li>';
                                descripcion += '<li>marca: '+producto.marca+'</li>';
                                descripcion += '<li>detalles: '+producto.detalles+'</li>';
                            
                                template += `
                                    <tr productId="${producto.id}">
                                        <td>${producto.id}</td>
                                        <td><a href="#" class="product-item">${producto.nombre}</a></td>
                                        <td><ul>${descripcion}</ul></td>
                                        <td>
                                            <button class="product-delete btn btn-danger">
                                                Eliminar
                                            </button>
                                        </td>
                                    </tr>
                                `;

                                template_bar += `
                                    <li>${producto.nombre}</il>
                                `;
                            });
                            $('#product-result').show();
                            $('#container').html(template_bar);
                            $('#products').html(template);    
                        }
                    }
                }
            });
        }
        else {
            $('#product-result').hide();
        }
    });

   
    $('#product-form').submit(e => {
        e.preventDefault();

       
        let nombreValido = validarNombre();
        let marcaValida = validarMarca();
        let modeloValido = validarModelo();
        let precioValido = validarPrecio();
        let unidadesValidas = validarUnidades();
        let detallesValidos = validarDetalles();
        let imagenValida = validarImagen();

        
        if(!nombreValido || !marcaValida || !modeloValido || !precioValido || 
           !unidadesValidas || !detallesValidos || !imagenValida) {
            alert('Por favor, corrige los errores en el formulario antes de continuar.');
            actualizarBarraEstado();
            return;
        }

        let postData = {
            nombre: $('#name').val().trim(),
            marca: $('#marca').val().trim(),
            modelo: $('#modelo').val().trim(),
            precio: parseFloat($('#precio').val()),
            unidades: parseInt($('#unidades').val()),
            detalles: $('#detalles').val().trim(),
            imagen: $('#imagen').val().trim(),
            id: $('#productId').val()
        };

        const url = edit === false ? './backend/product-add.php' : './backend/product-edit.php';
        
        $.post(url, postData, (response) => {
            let respuesta = JSON.parse(response);
            let template_bar = '';
            template_bar += `
                        <li style="list-style: none;">status: ${respuesta.status}</li>
                        <li style="list-style: none;">message: ${respuesta.message}</li>
                    `;
            
            $('#name').val('');
            $('#marca').val('NA');
            $('#modelo').val('XX-000');
            $('#precio').val(99.99);
            $('#unidades').val(1);
            $('#detalles').val('NA');
            $('#imagen').val('img/default.png');
            $('#productId').val('');
            
            
            $('.form-text').text('');
            
            
            for(let campo in validaciones) {
                validaciones[campo] = false;
            }
            
            $('#product-result').show();
            $('#container').html(template_bar);
            listarProductos();
            edit = false;
            
            
            $('button.btn-primary').text("Agregar Producto");
        });
    });

    $(document).on('click', '.product-delete', (e) => {
        if(confirm('¿Realmente deseas eliminar el producto?')) {
            const element = $(this)[0].activeElement.parentElement.parentElement;
            const id = $(element).attr('productId');
            $.post('./backend/product-delete.php', {id}, (response) => {
                $('#product-result').hide();
                listarProductos();
            });
        }
    });

    $(document).on('click', '.product-item', (e) => {
        const element = $(this)[0].activeElement.parentElement.parentElement;
        const id = $(element).attr('productId');
        $.post('./backend/product-single.php', {id}, (response) => {
            let product = JSON.parse(response);
            
            // INSERTAR DATOS EN LOS CAMPOS
            $('#name').val(product.nombre);
            $('#marca').val(product.marca);
            $('#modelo').val(product.modelo);
            $('#precio').val(product.precio);
            $('#unidades').val(product.unidades);
            $('#detalles').val(product.detalles);
            $('#imagen').val(product.imagen);
            $('#productId').val(product.id);
            
            // Validar todos los campos
            validarNombre();
            validarMarca();
            validarModelo();
            validarPrecio();
            validarUnidades();
            validarDetalles();
            validarImagen();
            actualizarBarraEstado();
            
            edit = true;
            

            $('button.btn-primary').text("Modificar Producto");
        });
        e.preventDefault();
    });    
});