// JSON BASE A MOSTRAR EN FORMULARIO (ya no se usa en textarea, pero se mantiene como referencia)
var baseJSON = {
    "precio": 0.0,
    "unidades": 1,
    "modelo": "XX-000",
    "marca": "NA",
    "detalles": "NA",
    "imagen": "img/default.png"
};

$(document).ready(function(){
    let edit = false;
    let nombreValido = true; // Para validación asíncrona del nombre

    $('#product-result').hide();
    listarProductos();

    $('#name').on('blur', function() {
        validarNombre();
    });

    $('#name').on('keyup', function() {
        // Validación asíncrona solo si no estamos editando
        if (!edit) {
            validarNombreAsync();
        }
    });

    // Validación de marca
    $('#marca').on('blur', function() {
        validarMarca();
    });

    // Validación de modelo
    $('#modelo').on('blur', function() {
        validarModelo();
    });

    // Validación de precio
    $('#precio').on('blur', function() {
        validarPrecio();
    });

    // Validación de unidades
    $('#unidades').on('blur', function() {
        validarUnidades();
    });

    // Validación de detalles
    $('#detalles').on('blur', function() {
        validarDetalles();
    });


    function validarNombre() {
        const nombre = $('#name').val().trim();
        const errorElement = $('#name-error');
        
        if (nombre === '') {
            $('#name').addClass('is-invalid').removeClass('is-valid');
            errorElement.text('El nombre es obligatorio');
            mostrarEstado('Error: El nombre es obligatorio', 'danger');
            return false;
        }
        
        if (nombre.length > 100) {
            $('#name').addClass('is-invalid').removeClass('is-valid');
            errorElement.text('El nombre no puede exceder 100 caracteres');
            mostrarEstado('Error: Nombre muy largo', 'danger');
            return false;
        }
        
        $('#name').removeClass('is-invalid').addClass('is-valid');
        errorElement.text('');
        mostrarEstado('Nombre válido', 'success');
        return true;
    }

    function validarNombreAsync() {
        const nombre = $('#name').val().trim();
        
        if (nombre === '' || edit) {
            return;
        }

        // Validación asíncrona: verificar si el nombre ya existe
        $.ajax({
            url: './backend/product-search.php',
            type: 'GET',
            data: { search: nombre },
            success: function(response) {
                try {
                    const productos = JSON.parse(response);
                    
                    // Verificar si hay coincidencia exacta
                    const coincidenciaExacta = productos.find(p => 
                        p.nombre.toLowerCase() === nombre.toLowerCase()
                    );
                    
                    if (coincidenciaExacta) {
                        $('#name').addClass('is-invalid').removeClass('is-valid');
                        $('#name-error').text('⚠️ Este nombre ya existe en la base de datos');
                        mostrarEstado('Advertencia: El nombre del producto ya existe', 'warning');
                        nombreValido = false;
                    } else {
                        nombreValido = true;
                        if ($('#name').val().trim() !== '') {
                            $('#name').removeClass('is-invalid').addClass('is-valid');
                            $('#name-error').text('');
                        }
                    }
                } catch (e) {
                    console.error('Error al validar nombre:', e);
                }
            }
        });
    }

    function validarMarca() {
        const marca = $('#marca').val().trim();
        const errorElement = $('#marca-error');
        
        if (marca === '') {
            $('#marca').addClass('is-invalid').removeClass('is-valid');
            errorElement.text('La marca es obligatoria');
            mostrarEstado('Error: La marca es obligatoria', 'danger');
            return false;
        }
        
        if (marca.length > 50) {
            $('#marca').addClass('is-invalid').removeClass('is-valid');
            errorElement.text('La marca no puede exceder 50 caracteres');
            mostrarEstado('Error: Marca muy larga', 'danger');
            return false;
        }
        
        $('#marca').removeClass('is-invalid').addClass('is-valid');
        errorElement.text('');
        mostrarEstado('Marca válida', 'success');
        return true;
    }

    function validarModelo() {
        const modelo = $('#modelo').val().trim();
        const errorElement = $('#modelo-error');
        const patron = /^[a-zA-Z0-9\-]+$/;
        
        if (modelo === '') {
            $('#modelo').addClass('is-invalid').removeClass('is-valid');
            errorElement.text('El modelo es obligatorio');
            mostrarEstado('Error: El modelo es obligatorio', 'danger');
            return false;
        }
        
        if (!patron.test(modelo)) {
            $('#modelo').addClass('is-invalid').removeClass('is-valid');
            errorElement.text('El modelo solo puede contener letras, números y guiones');
            mostrarEstado('Error: Formato de modelo inválido', 'danger');
            return false;
        }
        
        if (modelo.length > 25) {
            $('#modelo').addClass('is-invalid').removeClass('is-valid');
            errorElement.text('El modelo no puede exceder 25 caracteres');
            mostrarEstado('Error: Modelo muy largo', 'danger');
            return false;
        }
        
        $('#modelo').removeClass('is-invalid').addClass('is-valid');
        errorElement.text('');
        mostrarEstado('Modelo válido', 'success');
        return true;
    }

    function validarPrecio() {
        const precio = parseFloat($('#precio').val());
        const errorElement = $('#precio-error');
        
        if (isNaN(precio) || precio <= 0) {
            $('#precio').addClass('is-invalid').removeClass('is-valid');
            errorElement.text('El precio debe ser mayor a 0');
            mostrarEstado('Error: Precio inválido', 'danger');
            return false;
        }
        
        if (precio > 99999999.99) {
            $('#precio').addClass('is-invalid').removeClass('is-valid');
            errorElement.text('El precio es demasiado alto');
            mostrarEstado('Error: Precio fuera de rango', 'danger');
            return false;
        }
        
        $('#precio').removeClass('is-invalid').addClass('is-valid');
        errorElement.text('');
        mostrarEstado('Precio válido', 'success');
        return true;
    }

    function validarUnidades() {
        const unidades = parseInt($('#unidades').val());
        const errorElement = $('#unidades-error');
        
        if (isNaN(unidades) || unidades < 0) {
            $('#unidades').addClass('is-invalid').removeClass('is-valid');
            errorElement.text('Las unidades deben ser un número positivo');
            mostrarEstado('Error: Unidades inválidas', 'danger');
            return false;
        }
        
        $('#unidades').removeClass('is-invalid').addClass('is-valid');
        errorElement.text('');
        mostrarEstado('Unidades válidas', 'success');
        return true;
    }

    function validarDetalles() {
        const detalles = $('#detalles').val().trim();
        
        if (detalles.length > 250) {
            $('#detalles').addClass('is-invalid').removeClass('is-valid');
            mostrarEstado('Error: Detalles demasiado largos', 'danger');
            return false;
        }
        
        $('#detalles').removeClass('is-invalid');
        mostrarEstado('Detalles válidos', 'success');
        return true;
    }

    function validarTodosLosCampos() {
        const nombreOk = validarNombre();
        const marcaOk = validarMarca();
        const modeloOk = validarModelo();
        const precioOk = validarPrecio();
        const unidadesOk = validarUnidades();
        const detallesOk = validarDetalles();
        
        return nombreOk && marcaOk && modeloOk && precioOk && unidadesOk && detallesOk && nombreValido;
    }

    // Función para mostrar estado en la barra (punto 6)
    function mostrarEstado(mensaje, tipo = 'info') {
        const statusBar = $('#status-bar');
        const statusMessage = $('#status-message');
        
        // Colores según el tipo
        const colores = {
            'success': '#28a745',
            'danger': '#dc3545',
            'warning': '#ffc107',
            'info': '#17a2b8'
        };
        
        statusBar.css('background-color', colores[tipo] || colores['info']);
        statusMessage.text(mensaje);
        statusBar.fadeIn(300);
        
        // Ocultar después de 3 segundos
        setTimeout(() => {
            statusBar.fadeOut(300);
        }, 3000);
    }
    
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
                                    <button class="product-delete btn btn-danger btn-sm">
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
                                            <button class="product-delete btn btn-danger btn-sm">
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
            listarProductos();
        }
    });

    
    $('#product-form').submit(e => {
        e.preventDefault();

        if (!validarTodosLosCampos()) {
            mostrarEstado('Por favor, corrige los errores en el formulario', 'danger');
            return;
        }

        // Crear objeto con los datos del formulario
        let postData = {
            nombre: $('#name').val().trim(),
            marca: $('#marca').val().trim(),
            modelo: $('#modelo').val().trim(),
            precio: parseFloat($('#precio').val()),
            unidades: parseInt($('#unidades').val()),
            detalles: $('#detalles').val().trim() || 'NA',
            imagen: $('#imagen').val().trim() || 'img/default.png'
        };

        // Si estamos editando, agregar el ID
        if (edit) {
            postData['id'] = $('#productId').val();
        }

        const url = edit === false ? './backend/product-add.php' : './backend/product-edit.php';
        
        $.post(url, postData, (response) => {
            let respuesta = JSON.parse(response);
            let template_bar = '';
            template_bar += `
                <li style="list-style: none;">status: ${respuesta.status}</li>
                <li style="list-style: none;">message: ${respuesta.message}</li>
            `;
            
            // Limpiar formulario
            limpiarFormulario();
            
            $('#product-result').show();
            $('#container').html(template_bar);
            listarProductos();
            
            // Cambiar texto del botón de vuelta a "Agregar Producto" 
            $('button.btn-primary').text("Agregar Producto");
            
            edit = false;
            nombreValido = true;
            
            mostrarEstado(respuesta.message, respuesta.status === 'success' ? 'success' : 'danger');
        });
    });
    
    $(document).on('click', '.product-delete', (e) => {
        if(confirm('¿Realmente deseas eliminar el producto?')) {
            const element = $(this)[0].activeElement.parentElement.parentElement;
            const id = $(element).attr('productId');
            $.post('./backend/product-delete.php', {id}, (response) => {
                $('#product-result').hide();
                listarProductos();
                mostrarEstado('Producto eliminado correctamente', 'success');
            });
        }
    });

    
    $(document).on('click', '.product-item', (e) => {
        e.preventDefault();
        
        const element = $(this)[0].activeElement.parentElement.parentElement;
        const id = $(element).attr('productId');
        
        $.post('./backend/product-single.php', {id}, (response) => {
            let product = JSON.parse(response);
            
            // Llenar los campos del formulario
            $('#name').val(product.nombre);
            $('#marca').val(product.marca);
            $('#modelo').val(product.modelo);
            $('#precio').val(product.precio);
            $('#unidades').val(product.unidades);
            $('#detalles').val(product.detalles);
            $('#imagen').val(product.imagen);
            $('#productId').val(product.id);
            
            // Limpiar validaciones visuales
            $('.form-control').removeClass('is-invalid is-valid');
            $('.validation-message').text('');
            
            // Cambiar texto del botón a "Modificar Producto"
            $('button.btn-primary').text("Modificar Producto");
            
            edit = true;
            nombreValido = true; // Al editar, el nombre actual es válido
            
            mostrarEstado('Editando producto: ' + product.nombre, 'info');
        });
    });


    
    function limpiarFormulario() {
        $('#name').val('');
        $('#marca').val('');
        $('#modelo').val('');
        $('#precio').val('');
        $('#unidades').val('');
        $('#detalles').val('');
        $('#imagen').val('');
        $('#productId').val('');
        
        // Limpiar estilos de validación
        $('.form-control').removeClass('is-invalid is-valid');
        $('.validation-message').text('');
    }
});