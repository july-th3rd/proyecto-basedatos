<?php
// Configuración de conexión a Oracle
$host = 'localhost';
$port = '1521';
$dbname = 'XE'; // Cambia según tu configuración
$username = 'HR';
$password = '123';

try {
    $dsn = "oci:dbname=//$host:$port/$dbname;charset=UTF8";
    $conn = new PDO($dsn, $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}

// Manejar la acción
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];

    if ($action === 'insertar') {
        $cantidad_inventario = $_POST['cantidad_inventario'];
        $id_producto = $_POST['id_producto'];
        $stmt = $conn->prepare("BEGIN MVCD_C_INVENTARIO(:cantidad_inventario,:id_producto); END;");
        $stmt->bindParam(':cantidad_inventario', $cantidad_inventario);
        $stmt->bindParam(':id_producto', $id_producto);
        $stmt->execute();
        echo "Registro insertado exitosamente.";
    } elseif ($action === 'listar') {
        $stmt = $conn->query("SELECT ID_INVENTARIO, CANTIDAD_INVENTARIO,ID_PRODUCTO FROM MVCD_INVENTARIO");
        echo "<table><tr><th>ID</th><th>CANTIDAD INVENTARIO</th><th>ID PRODUCTO</th></tr>";
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr><td>{$row['ID_INVENTARIO']}</td><td>{$row['CANTIDAD_INVENTARIO']}</td><td>{$row['ID_PRODUCTO']}</td> </tr>";
        }
        echo "</table>";
    } elseif ($action === 'borrar') {
        $id_inventario = $_POST['id_inventario'];
        $stmt = $conn->prepare("BEGIN MVCD_D_INVENTARIO(:id_inventario); END;");
        $stmt->bindParam(':id_inventario', $id_inventario);
        $stmt->execute();
        echo "Registro borrado exitosamente.";
        
    } elseif ($action === 'actualizar') {
        $id_inventario = $_POST['id_inventario'];
        $cantidad_inventario = $_POST['cantidad_inventario'];
        $stmt = $conn->prepare("BEGIN MVCD_U_INVENTARIO(:id_inventario,:cantidad_inventario); END;");
        $stmt->bindParam(':id_inventario', $id_inventario);
        $stmt->bindParam(':cantidad_inventario', $cantidad_inventario);
        $stmt->execute();
        echo "Registro actualizado exitosamente.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Inventario</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            margin: 20px;
            justify-content: center;
            align-items: center;
            height: 100vh; /* Altura completa de la ventana */
            margin: 0; /* Elimina márgenes del body */
        }
        .button-container button {
            margin: 10px;
            padding: 10px 20px;
            font-size: 16px;
        }
        form {  
            display: flex;
            flex-direction: column;
            max-width: 300px;
            margin-top: 20px;
            width: 100%; /* Asegura que el formulario no supere el ancho máximo */
            padding: 20px;
            border: 1px solid #ddd; /* Borde suave */
            border-radius: 8px; /* Bordes redondeados */
            background-color: #fff; /* Fondo blanco */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Sombra ligera */
        }
        label {
            margin: 10px 0 5px; 
        }
        table {
            margin: 20px auto;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px 12px;
        }
        button {
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <h1>Gestión de Inventario</h1>
    <div class="button-container">
        <button onclick="showForm('insertar')">Insertar</button>
        <button onclick="submitForm('listar')">Listar</button>
        <button onclick="showForm('borrar')">Borrar</button>
        <button onclick="showForm('actualizar')">Actualizar</button>
    </div>
    <div id="formContainer"></div>
    <div id="output"></div>

    <script>
        function showForm(action) {
            const container = document.getElementById('formContainer');
            let html = '';
            if (action === 'insertar') {
                html = `
                    <form method="POST" action="inventario.php">
                        <input type="hidden" name="action" value="insertar">
                        <label>Cantidad de Inventario:</label>
                        <input type="number" name="cantidad_inventario" required>
                        <label>ID del Producto a añadir:</label>
                        <input type="number" name="id_producto" required>
                        <button type="submit">Insertar</button>
                    </form>
                `;
            } else if (action === 'borrar') {
                html = `
                    <form method="POST" action="inventario.php">
                        <input type="hidden" name="action" value="borrar">
                        <label>ID del Inventario a borrar:</label>
                        <input type="number" name="id_inventario" required>
                        <button type="submit">Borrar</button>
                    </form>
                `;
            } else if (action === 'actualizar') {
                html = `
                    <form method="POST" action="inventario.php">
                        <input type="hidden" name="action" value="actualizar">
                        <label>ID del Inventario a actualizar:</label>
                        <input type="number" name="id_inventario" required>
                        <label>Nueva Cantidad de Inventario:</label>
                        <input type="number" name="cantidad_inventario" required>
                        <button type="submit">Actualizar</button>
                    </form>
                `;
            }
            container.innerHTML = html;
        }

        function submitForm(action) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = 'inventario.php';

            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'action';
            input.value = action;
            form.appendChild(input);

            document.body.appendChild(form);
            form.submit();
        }
    </script>
</body>
</html>