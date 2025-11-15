// JSON BASE A MOSTRAR EN FORMULARIO
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

    let JsonString = JSON.stringify(baseJSON,null,2);
    $('#description').val(JsonString);
    $('#product-result').hide();
    listarProductos();

    function listarProductos() {
        $.ajax({
            url: './backend/product-list.php',
            type: 'GET',
            success: function(response) {
                console.log(response);
                // SE OBTIENE EL OBJETO DE DATOS A PARTIR DE UN STRING JSON
                const productos = typeof response === "string" ? JSON.parse(response) : response;

            
                // SE VERIFICA SI EL OBJETO JSON TIENE DATOS
                if(Object.keys(productos).length > 0) {
                    // SE CREA UNA PLANTILLA PARA CREAR LAS FILAS A INSERTAR EN EL DOCUMENTO HTML
                    let template = '';

                    productos.forEach(producto => {
                        // SE CREA UNA LISTA HTML CON LA DESCRIPCIÓN DEL PRODUCTO
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
                    });
                    // SE INSERTA LA PLANTILLA EN EL ELEMENTO CON ID "productos"
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
                        // SE OBTIENE EL OBJETO DE DATOS A PARTIR DE UN STRING JSON
                        const productos = typeof response === "string" ? JSON.parse(response) : response;

                        
                        // SE VERIFICA SI EL OBJETO JSON TIENE DATOS
                        if(Object.keys(productos).length > 0) {
                            // SE CREA UNA PLANTILLA PARA CREAR LAS FILAS A INSERTAR EN EL DOCUMENTO HTML
                            let template = '';
                            let template_bar = '';

                            productos.forEach(producto => {
                                // SE CREA UNA LISTA HTML CON LA DESCRIPCIÓN DEL PRODUCTO
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
                            // SE HACE VISIBLE LA BARRA DE ESTADO
                            $('#product-result').show();
                            // SE INSERTA LA PLANTILLA PARA LA BARRA DE ESTADO
                            $('#container').html(template_bar);
                            // SE INSERTA LA PLANTILLA EN EL ELEMENTO CON ID "productos"
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

        // SE CONVIERTE EL JSON DE STRING A OBJETO
        let postData = JSON.parse( $('#description').val() );
        // SE AGREGA AL JSON EL NOMBRE DEL PRODUCTO
        postData['nombre'] = $('#name').val();
        postData['id'] = $('#productId').val();

        /**
         * AQUÍ DEBES AGREGAR LAS VALIDACIONES DE LOS DATOS EN EL JSON
         * --> EN CASO DE NO HABER ERRORES, SE ENVIAR EL PRODUCTO A AGREGAR
         **/

        const url = edit === false ? './backend/product-add.php' : './backend/product-edit.php';

        $.ajax({
        url: url,
        type: "POST",
        data: JSON.stringify(postData),
        contentType: "application/json",
        dataType: "json", // 👈 jQuery ya te lo entrega como objeto
        success: function(response) {
            console.log("Respuesta: ", response);

            let template_bar = `
                <li style="list-style: none;">status: ${response.status}</li>
                <li style="list-style: none;">message: ${response.message}</li>
            `;

            $('#name').val('');
            $('#description').val(JsonString);

            $('#product-result').show();
            $('#container').html(template_bar);

            listarProductos();
            edit = false;
        }
    });
   
    });

    $(document).on('click', '.product-delete', function () {

        if (confirm('¿Realmente deseas eliminar el producto?')) {

            const element = $(this).closest('tr');
            const id = element.attr('productId');

            $.post('./backend/product-delete.php', { id }, function (response) {
                console.log("DELETE RESPUESTA:", response);

                let r = typeof response === "string" ? JSON.parse(response) : response;

                let template_bar = `
                    <li style="list-style: none;">status: ${r.status}</li>
                    <li style="list-style: none;">message: ${r.message}</li>
                `;

                $('#product-result').show();
                $('#container').html(template_bar);

                listarProductos();
            });

        }

    });

    $(document).on('click', '.product-item', function (e) {
    e.preventDefault();

    // AQUÍ SÍ USAMOS "this" correctamente
    const element = $(this).closest('tr');
    const id = element.attr('productId');

    console.log("EDITAR ID:", id);

    $.post('./backend/product-single.php', { id: id }, function (response) {

        console.log("SINGLE RESPONSE:", response);

        let product = typeof response === "string" ? JSON.parse(response) : response;

        if (!product || product.status === "error") {
            alert("No se pudo cargar el producto.");
            return;
        }

        // Insertamos valores
        $('#name').val(product.nombre);
        $('#productId').val(product.id);

        // Quitamos campos que no van en el JSON del textarea
        delete product.nombre;
        delete product.id;
        delete product.eliminado;

        let JsonString = JSON.stringify(product, null, 2);
        $('#description').val(JsonString);

        edit = true;
    });
});
});