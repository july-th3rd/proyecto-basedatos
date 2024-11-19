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
        $nombre_producto = $_POST['nombre_producto'];
        $tipo_producto = $_POST['tipo_producto'];
        $precio_producto = $_POST['precio_producto'];
        $stmt = $conn->prepare("BEGIN MVCD_C_PRODUCTO(:nombre_producto,:tipo_producto,:precio_producto); END;");
        $stmt->bindParam(':nombre_producto', $nombre_producto);
        $stmt->bindParam(':tipo_producto', $tipo_producto);
        $stmt->bindParam(':precio_producto', $precio_producto);
        $stmt->execute();
        echo "Registro insertado exitosamente.";
    } elseif ($action === 'listar') {
        $stmt = $conn->query("SELECT ID_PRODUCTO, NOMBRE_PRODUCTO, TIPO_PRODUCTO, PRECIO_PRODUCTO FROM MVCD_INVENTARIO");
        echo "<table><tr><th>ID</th><th>NOMBRE PRODUCTO</th><th>TIPO PRODUCTO</th><th><th>PRECIO PRODUCTO</th></tr>";
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr><td>{$row['ID_PRODUCTO']}</td><td>{$row['NOMBRE_PRODUCTO']}</td><td>{$row['TIPO_PRODUCTO']}</td><td>{$row['PRECIO_PRODUCTO']}</td></tr>";
        }
        echo "</table>";
    } elseif ($action === 'borrar') {
        $id_producto = $_POST['id_producto'];
        $stmt = $conn->prepare("BEGIN MVCD_D_PRODUCTO(:id_producto); END;");
        $stmt->bindParam(':id_producto', $id_producto);
        $stmt->execute();
        echo "Registro borrado exitosamente.";

    } elseif ($action === 'actualizar') {
        $id_producto = $_POST['id_producto'];
        $nombre_producto = $_POST['nombre_producto'];
        $tipo_producto = $_POST['tipo_producto'];
        $precio_producto = $_POST['precio_producto'];
        $stmt = $conn->prepare("BEGIN MVCD_U_PRODUCTO(:id_producto,:nombre_producto,:tipo_producto,:precio_producto); END;");
        $stmt->bindParam(':id_producto', $id_producto);
        $stmt->bindParam(':nombre_producto', $nombre_producto);
        $stmt->bindParam(':tipo_producto', $tipo_producto);
        $stmt->bindParam(':precio_producto', $precio_producto);
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
    <title>Gestión de Productos</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            margin: 20px;
        }
        .button-container button {
            margin: 10px;
            padding: 10px 20px;
            font-size: 16px;
        }
        form {
            margin-top: 20px;
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
    </style>
</head>
<body>
    <h1>Gestión de Producto</h1>
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
                    <form method="POST" action="productos.php">
                        <input type="hidden" name="action" value="insertar">
                        <label>Nombre del Producto:</label>
                        <input type="text" name="nombre_producto" required>
                        <label>Tipo de Producto:</label>
                        <input type="text" name="tipo_producto" required>
                        <label>Precio del Producto:</label>
                        <input type="number" name="precio_producto" required>
                        <button type="submit">Insertar</button>
                    </form>
                `;
            } else if (action === 'borrar') {
                html = `
                    <form method="POST" action="productos.php">
                        <input type="hidden" name="action" value="borrar">
                        <label>ID del Producto a borrar:</label>
                        <input type="number" name="id_producto" required>
                        <button type="submit">Borrar</button>
                    </form>
                `;
            } else if (action === 'actualizar') {
                html = `
                    <form method="POST" action="productos.php">
                        <input type="hidden" name="action" value="actualizar">
                        <label>ID del Producto a actualizar:</label>
                        <input type="number" name="id_producto" required>
                        <label>Nuevo Nombre del Producto:</label>
                        <input type="text" name="nombre_producto" required>
                        <label>Nuevo Tipo de Producto:</label>
                        <input type="text" name="tipo_producto" required>
                        <label>Nuevo Precio del Producto:</label>
                        <input type="text" name="precio_producto" required>
                        <button type="submit">Actualizar</button>
                    </form>
                `;
            }
            container.innerHTML = html;
        }

        function submitForm(action) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = 'productos.php';

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