<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestor de Cuentas</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #111;
            color: white;
            text-align: center;
            margin: 0;
            padding: 0;
        }

        h1 {
            background-color: black;
            padding: 20px;
            margin: 0;
            color: yellow;
        }

        form {
            margin-top: 30px;
        }

        input[type="text"], input[type="submit"] {
            padding: 10px;
            margin: 8px;
            width: 250px;
            border-radius: 5px;
        }

        input[type="submit"] {
            width: 150px;
            cursor: pointer;
        }

        table {
            margin: 20px auto;
            border-collapse: collapse;
            background-color: #fff;
            color: #000;
        }

        th, td {
            border: 1px solid black;
            padding: 8px 15px;
        }

        th {
            background-color: #ccc;
        }
    </style>
</head>
<body>

    <h1>Gestor de Cuentas</h1>

    <form method="POST">
        <input type="text" name="id" placeholder="ID (para actualizar/eliminar)">
        <br>
        <input type="text" name="nombrecompleto" placeholder="Nombre completo">
        <br>
        <input type="text" name="usuario" placeholder="Usuario">
        <br>
        <input type="text" name="contraseña" placeholder="Contraseña">
        <br>
        <input type="submit" name="insertar" value="INSERTAR">
        <input type="submit" name="consultar" value="CONSULTAR">
        <input type="submit" name="actualizar" value="ACTUALIZAR">
        <input type="submit" name="eliminar" value="ELIMINAR">
    </form>

    <?php
    // CONEXIÓN
    $conexion = new mysqli('localhost', 'root', '', 'sena');

    if ($conexion->connect_error) {
        die("Conexión fallida: " . $conexion->connect_error);
    }

    // Variables protegidas con operador de fusión nula
    $id = $_POST['id'] ?? '';
    $nombrecompleto = $_POST['nombrecompleto'] ?? '';
    $usuario = $_POST['usuario'] ?? '';
    $contraseña = $_POST['contraseña'] ?? '';

    // INSERTAR
    if (isset($_POST['insertar'])) {
        $sql = "INSERT INTO usuarios (nombrecompleto, usuario, contraseña) 
                VALUES ('$nombrecompleto', '$usuario', '$contraseña')";
        if ($conexion->query($sql)) {
            echo "<p>✅ Cuenta insertada correctamente.</p>";
        } else {
            echo "<p>❌ Error al insertar: " . $conexion->error . "</p>";
        }
    }

    // CONSULTAR
    if (isset($_POST['consultar'])) {
        $sql = "SELECT * FROM usuarios";
        $resultado = $conexion->query($sql);
        if ($resultado->num_rows > 0) {
            echo "<table>
                    <tr>
                        <th>ID</th>
                        <th>Nombre completo</th>
                        <th>Usuario</th>
                        <th>Contraseña</th>
                    </tr>";
            while ($fila = $resultado->fetch_assoc()) {
                echo "<tr>
                        <td>{$fila['id']}</td>
                        <td>{$fila['nombrecompleto']}</td>
                        <td>{$fila['usuario']}</td>
                        <td>{$fila['contraseña']}</td>
                    </tr>";
            }
            echo "</table>";
        } else {
            echo "<p>No hay cuentas registradas.</p>";
        }
    }

    // ACTUALIZAR
    if (isset($_POST['actualizar'])) {
        $sql = "UPDATE usuarios SET 
                    nombrecompleto='$nombrecompleto', 
                    usuario='$usuario', 
                    contraseña='$contraseña' 
                WHERE id='$id'";
        if ($conexion->query($sql)) {
            echo "<p>✅ Cuenta actualizada correctamente.</p>";
        } else {
            echo "<p>❌ Error al actualizar: " . $conexion->error . "</p>";
        }
    }

    // ELIMINAR
    if (isset($_POST['eliminar'])) {
        $sql = "DELETE FROM usuarios WHERE id='$id'";
        if ($conexion->query($sql)) {
            echo "<p>✅ Cuenta eliminada correctamente.</p>";
        } else {
            echo "<p>❌ Error al eliminar: " . $conexion->error . "</p>";
        }
    }

    $conexion->close();
    ?>

</body>
</html>

