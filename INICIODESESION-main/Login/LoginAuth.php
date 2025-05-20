<?php
session_start();
include_once('../Config/Conexion.php');

if (isset($_POST['Usuario']) && isset($_POST['Clave'])) {
    function Validar($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    $usuario = Validar($_POST['Usuario']);
    $clave = Validar($_POST['Clave']);

    if (empty($usuario)) {
        header('location: ../Index.php?error=El usuario es requerido');
        exit();
    } elseif (empty($clave)) {
        header('location: ../Index.php?error=La clave es requerida');
        exit();
    } else {
        // Cambiamos NombreUsuario por Usuario
        $Sql = "SELECT * FROM usuarios WHERE Usuario = ?";
        $stmt = $conexion->prepare($Sql);
        $stmt->bind_param("s", $usuario);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $usuarioQ = $result->fetch_assoc();

            $Id = $usuarioQ['Id'];
            $NombreUsuario = $usuarioQ['Usuario']; // Cambiamos NombreUsuario por Usuario
            $Clave = $usuarioQ['Contraseña']; // Cambiamos Clave por Contraseña
            $NombreCompleto = $usuarioQ['NombreCompleto'];

            if (password_verify($clave, $Clave)) {
                $_SESSION['Id'] = $Id;
                $_SESSION['Usuario'] = $NombreUsuario; // Cambiamos NombreUsuario por Usuario
                $_SESSION['NombreCompleto'] = $NombreCompleto;

                echo "<script>
                    alert('Bienvenido $NombreCompleto');
                    location.href = '../(0)proyecto.html'
                </script>";
            } else {
                header('Location:../Index.php?error=Usuario o clave incorrecta');
            }
        } else {
            header('Location:../Index.php?error=Usuario o clave incorrecta');
        }
    }
}