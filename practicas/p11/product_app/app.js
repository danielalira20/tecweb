// JSON BASE A MOSTRAR EN FORMULARIO
var baseJSON = {
    "precio": 0.0,
    "unidades": 1,
    "modelo": "XX-000",
    "marca": "NA",
    "detalles": "NA",
    "imagen": "img/default.png"
};

// Variable global para saber si estamos editando
let edit = false;

// Se ejecuta cuando el documento está listo
$(document).ready(function() {
    init();
    listarProductos();
    
    // Búsqueda en tiempo real
    $('#search').keyup(function() {
        let search = $(this).val();
        if(search.length > 0) {
            buscarProducto(search);
        } else {
            listarProductos();
            $('#product-result').addClass('d-none');
        }
    });
});

function init() {
    var JsonString = JSON.stringify(baseJSON, null, 2);
    $('#description').val(JsonString);
}

// Listar todos los productos
function listarProductos() {
    $.ajax({
        url: './backend/product-list.php',
        type: 'GET',
        success: function(response) {
            let productos = JSON.parse(response);
            
            if(Object.keys(productos).length > 0) {
                let template = '';
                
                productos.forEach(producto => {
                    let descripcion = '';
                    descripcion += '<li>precio: ' + producto.precio + '</li>';
                    descripcion += '<li>unidades: ' + producto.unidades + '</li>';
                    descripcion += '<li>modelo: ' + producto.modelo + '</li>';
                    descripcion += '<li>marca: ' + producto.marca + '</li>';
                    descripcion += '<li>detalles: ' + producto.detalles + '</li>';
                    
                    template += `
                        <tr productId="${producto.id}">
                            <td>${producto.id}</td>
                            <td>${producto.nombre}</td>
                            <td><ul>${descripcion}</ul></td>
                            <td>
                                <button class="product-edit btn btn-warning btn-sm">
                                    Editar
                                </button>
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

// Buscar productos
function buscarProducto(search) {
    $.ajax({
        url: './backend/product-search.php',
        type: 'GET',
        data: { search: search },
        success: function(response) {
            let productos = JSON.parse(response);
            
            if(Object.keys(productos).length > 0) {
                let template = '';
                let template_bar = '';
                
                productos.forEach(producto => {
                    let descripcion = '';
                    descripcion += '<li>precio: ' + producto.precio + '</li>';
                    descripcion += '<li>unidades: ' + producto.unidades + '</li>';
                    descripcion += '<li>modelo: ' + producto.modelo + '</li>';
                    descripcion += '<li>marca: ' + producto.marca + '</li>';
                    descripcion += '<li>detalles: ' + producto.detalles + '</li>';
                    
                    template += `
                        <tr productId="${producto.id}">
                            <td>${producto.id}</td>
                            <td>${producto.nombre}</td>
                            <td><ul>${descripcion}</ul></td>
                            <td>
                                <button class="product-edit btn btn-warning btn-sm">
                                    Editar
                                </button>
                                <button class="product-delete btn btn-danger btn-sm">
                                    Eliminar
                                </button>
                            </td>
                        </tr>
                    `;
                    
                    template_bar += `<li>${producto.nombre}</li>`;
                });
                
                $('#product-result').removeClass('d-none').addClass('d-block');
                $('#container').html(template_bar);
                $('#products').html(template);
            }
        }
    });
}

// Evento click en el botón Editar
$(document).on('click', '.product-edit', function() {
    let element = $(this)[0].parentElement.parentElement;
    let id = $(element).attr('productId');
    
    $.ajax({
        url: './backend/product-single.php',
        type: 'GET',
        data: { id: id },
        success: function(response) {
            let producto = JSON.parse(response);
            
            // Llenar el formulario con los datos del producto
            $('#name').val(producto.nombre);
            
            // Crear el JSON sin el nombre y el id
            let productoJSON = {
                precio: parseFloat(producto.precio),
                unidades: parseInt(producto.unidades),
                modelo: producto.modelo,
                marca: producto.marca,
                detalles: producto.detalles,
                imagen: producto.imagen
            };
            
            $('#description').val(JSON.stringify(productoJSON, null, 2));
            $('#productId').val(producto.id);
            
            // Cambiar el botón a modo edición
            edit = true;
            $('#product-submit').text('Actualizar Producto');
        }
    });
});

// Agregar o Editar producto
$(document).on('submit', '#product-form', function(e) {
    e.preventDefault();
    
    let productoJsonString = $('#description').val();
    let finalJSON;
    
    try {
        finalJSON = JSON.parse(productoJsonString);
    } catch (error) {
        alert('Error: El JSON no es válido. Verifica el formato.');
        console.error('JSON inválido:', error);
        return;
    }
    
    finalJSON['nombre'] = $('#name').val();
    
    if (!finalJSON['nombre'] || finalJSON['nombre'].trim() === '') {
        alert('Error: El nombre del producto es obligatorio.');
        return;
    }
    
    // Si estamos editando, agregar el ID
    let url = './backend/product-add.php';
    if(edit) {
        finalJSON['id'] = $('#productId').val();
        url = './backend/product-edit.php';
    }
    
    productoJsonString = JSON.stringify(finalJSON);
    
    $.ajax({
        url: url,
        type: 'POST',
        contentType: 'application/json',
        data: productoJsonString,
        success: function(response) {
            console.log(response);
            let respuesta = JSON.parse(response);
            
            let template_bar = `
                <li style="list-style: none;">status: ${respuesta.status}</li>
                <li style="list-style: none;">message: ${respuesta.message}</li>
            `;
            
            $('#product-result').removeClass('d-none').addClass('d-block');
            $('#container').html(template_bar);
            
            listarProductos();
            
            // Limpiar formulario y resetear modo edición
            $('#name').val('');
            $('#productId').val('');
            init();
            edit = false;
            $('#product-submit').text('Agregar Producto');
        },
        error: function(xhr, status, error) {
            alert('Error: ' + error);
            console.error('Error:', error);
        }
    });
});

// Eliminar producto
$(document).on('click', '.product-delete', function() {
    if(confirm('¿De verdad deseas eliminar el Producto?')) {
        let element = $(this)[0].parentElement.parentElement;
        let id = $(element).attr('productId');
        
        $.ajax({
            url: './backend/product-delete.php',
            type: 'GET',
            data: { id: id },
            success: function(response) {
                console.log(response);
                let respuesta = JSON.parse(response);
                
                let template_bar = `
                    <li style="list-style: none;">status: ${respuesta.status}</li>
                    <li style="list-style: none;">message: ${respuesta.message}</li>
                `;
                
                $('#product-result').removeClass('d-none').addClass('d-block');
                $('#container').html(template_bar);
                
                listarProductos();
            }
        });
    }
});