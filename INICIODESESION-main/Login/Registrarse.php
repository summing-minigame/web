<?php
session_start();

include_once('../Config/Conexion.php');

if (isset($_POST['Usuario']) && isset($_POST['NombreCompleto']) && isset($_POST['Clave']) && isset($_POST['RClave'])) {
    function validar($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    $usuario = validar($_POST['Usuario']);
    $nombreCompleto = validar($_POST['NombreCompleto']);
    $clave = validar($_POST['Clave']);
    $rClave = validar($_POST['RClave']);

    $usuario_Datos = 'Usuario=' . $usuario . '&NombreCompleto=' . $nombreCompleto;

    if (empty($usuario)) {
        header("location: ../Registrarse.php?error=El usuario es requerido&$usuario_Datos");
        exit();
    } elseif (empty($nombreCompleto)) {
        header("location: ../Registrarse.php?error=El nombre completo es requerido&$usuario_Datos");
        exit();
    } elseif (empty($clave)) {
        header("location: ../Registrarse.php?error=La clave es requerida&$usuario_Datos");
        exit();
    } elseif (empty($rClave)) {
        header("location: ../Registrarse.php?error=Repetir la clave es requerida&$usuario_Datos");
        exit();
    } elseif ($clave !== $rClave) {
        header("location: ../Registrarse.php?error=Las claves no coinciden&$usuario_Datos");
        exit();
    } else {
        // Hashing
        $clave = password_hash($clave, PASSWORD_DEFAULT);

        // Usando sentencias preparadas
        $sql = "SELECT * FROM usuarios WHERE Usuario = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("s", $usuario);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            header("location: ../Registrarse.php?error=El nombre de usuario ya existe&$usuario_Datos");
            exit();
        } else {
            $sql2 = "INSERT INTO usuarios(Usuario, Contraseña, NombreCompleto) VALUES (?, ?, ?)";
            $stmt2 = $conexion->prepare($sql2);
            $stmt2->bind_param("sss", $usuario, $clave, $nombreCompleto);

            if ($stmt2->execute()) {
                header("location: ../Registrarse.php?success=Usuario creado con éxito!&$usuario_Datos");
                exit();
            } else {
                header("location: ../Registrarse.php?error=Error desconocido&$usuario_Datos");
                exit();
            }
        }
    }
} else {
    header("location: ../Registrarse.php");
    exit();
}