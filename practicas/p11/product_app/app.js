// JSON BASE A MOSTRAR EN FORMULARIO
var baseJSON = {
    "precio": 0.0,
    "unidades": 1,
    "modelo": "XX-000",
    "marca": "NA",
    "detalles": "NA",
    "imagen": "img/default.png"
};

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
                                <button class="product-delete btn btn-danger">
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
                                <button class="product-delete btn btn-danger">
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

// Agregar producto
$(document).on('submit', '#product-form', function(e) {
    e.preventDefault();
    
    // SE OBTIENE DESDE EL FORMULARIO EL JSON A ENVIAR
    let productoJsonString = $('#description').val();
    // SE CONVIERTE EL JSON DE STRING A OBJETO
    let finalJSON = JSON.parse(productoJsonString);
    // SE AGREGA AL JSON EL NOMBRE DEL PRODUCTO
    finalJSON['nombre'] = $('#name').val();
    // SE OBTIENE EL STRING DEL JSON FINAL
    productoJsonString = JSON.stringify(finalJSON, null, 2);
    
    $.ajax({
        url: './backend/product-add.php',
        type: 'POST',
        contentType: 'application/json',
        data: productoJsonString,
        success: function(response) {
            console.log(response);
            let respuesta = JSON.parse(response);
            
            // SE CREA UNA PLANTILLA PARA CREAR INFORMACIÓN DE LA BARRA DE ESTADO
            let template_bar = `
                <li style="list-style: none;">status: ${respuesta.status}</li>
                <li style="list-style: none;">message: ${respuesta.message}</li>
            `;
            
            // SE HACE VISIBLE LA BARRA DE ESTADO
            $('#product-result').removeClass('d-none').addClass('d-block');
            // SE INSERTA LA PLANTILLA PARA LA BARRA DE ESTADO
            $('#container').html(template_bar);
            
            // SE LISTAN TODOS LOS PRODUCTOS
            listarProductos();
            
            // Limpiar formulario
            $('#name').val('');
            init();
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
                
                // SE CREA UNA PLANTILLA PARA CREAR INFORMACIÓN DE LA BARRA DE ESTADO
                let template_bar = `
                    <li style="list-style: none;">status: ${respuesta.status}</li>
                    <li style="list-style: none;">message: ${respuesta.message}</li>
                `;
                
                // SE HACE VISIBLE LA BARRA DE ESTADO
                $('#product-result').removeClass('d-none').addClass('d-block');
                // SE INSERTA LA PLANTILLA PARA LA BARRA DE ESTADO
                $('#container').html(template_bar);
                
                // SE LISTAN TODOS LOS PRODUCTOS
                listarProductos();
            }
        });
    }
});