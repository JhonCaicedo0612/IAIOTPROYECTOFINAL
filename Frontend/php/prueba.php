<!DOCTYPE html>
<html>

<head>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <div id="tabla-container">
        <!-- La tabla se cargará aquí -->
    </div>

    <script>
        // Función para actualizar la tabla
        function actualizarTabla() {
            $.ajax({
                url: 'http://localhost:1880/datos',
                method: 'GET',
                success: function(data) {
                    construirTabla(data);
                },
                error: function() {
                    console.log('Error al obtener los datos.');
                }
            });
        }

        // Función para construir la tabla a partir de los datos
        function construirTabla(datos) {
            let tablaHTML = '<table>';
            tablaHTML += '<tr><th>ID</th><th>ID Nodo</th><th>Ac_x</th><th>Ac_y</th><th>Ac_z</th><th>Rot_x</th><th>Rot_y</th><th>Rot_z</th><th>Temperatura</th><th>Fecha</th></tr>';

            // Elimina las filas anteriores con el mismo data-id
            datos.forEach(function(fila) {
                $('#tabla-container tr[data-id="' + fila.id + '"]').remove();
            });

            datos.forEach(function(fila) {
                tablaHTML += '<tr data-id="' + fila.id + '">';
                tablaHTML += '<td>' + fila.id + '</td>';
                tablaHTML += '<td>' + fila.idnodo + '</td>';
                tablaHTML += '<td>' + fila.ac_x + '</td>';
                tablaHTML += '<td>' + fila.ac_y + '</td>';
                tablaHTML += '<td>' + fila.ac_z + '</td>';
                tablaHTML += '<td>' + fila.rot_x + '</td>';
                tablaHTML += '<td>' + fila.rot_y + '</td>';
                tablaHTML += '<td>' + fila.rot_z + '</td>';
                tablaHTML += '<td>' + fila.temperatura + '</td>';
                tablaHTML += '<td>' + fila.fecha + '</td>';
                tablaHTML += '</tr>';
            });

            tablaHTML += '</table';
            $('#tabla-container').html(tablaHTML);
        }

        // Llama a la función de actualización en un intervalo
        setInterval(actualizarTabla, 100); // Actualizar cada 5 segundos (5000 ms)
    </script>
</body>

</html>