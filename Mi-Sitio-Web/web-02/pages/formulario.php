<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

require_once '../config/conexion.php';

$nombre_usuario = $_SESSION['usuario_nombre'] ?? 'Silvana';
$rol_usuario    = $_SESSION['usuario_rol'] ?? 'Preceptora • CEN';
$mensaje = '';
$tipo_mensaje = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dni = trim($_POST['dni'] ?? '');
    $nombre = trim($_POST['nombre'] ?? '');
    $apellido = trim($_POST['apellido'] ?? '');
    $fecha_nac = $_POST['fecha_nacimiento'] ?? '';
    $genero = $_POST['genero'] ?? 'M';
    $direccion = trim($_POST['direccion'] ?? '');
    $telefono = trim($_POST['telefono'] ?? '');
    $email = trim($_POST['email'] ?? '');

    if (!empty($dni) && !empty($nombre) && !empty($apellido)) {
        $stmt = $conn->prepare("INSERT INTO alumnos (dni, nombre, apellido, fecha_nacimiento, genero, direccion, telefono, email) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssss", $dni, $nombre, $apellido, $fecha_nac, $genero, $direccion, $telefono, $email);
        
        if ($stmt->execute()) {
            $mensaje = "¡Alumno guardado correctamente!";
            $tipo_mensaje = "success";
        } else {
            $mensaje = "Error al guardar: " . $conn->error;
            $tipo_mensaje = "error";
        }
        $stmt->close();
    } else {
        $mensaje = "Por favor, completa los campos obligatorios.";
        $tipo_mensaje = "error";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CEN - Formulario de Inscripción</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Segoe UI', system-ui, sans-serif; }
        body { background-color: #f3f6f3; color: #2c3531; min-height: 100vh; display: flex; flex-direction: column; }
        .top-header { background-color: #1b3b2b; color: #ffffff; padding: 22px 50px; display: flex; justify-content: space-between; align-items: center; }
        .brand-title h1 { font-size: 22px; font-weight: 700; }
        .brand-title p { font-size: 13px; color: #a3c9b4; }
        .main-container { display: flex; max-width: 1250px; width: 100%; margin: -25px auto 40px auto; padding: 0 20px; gap: 25px; }
        .sidebar { width: 280px; flex-shrink: 0; }
        .user-card { background: #ffffff; border-radius: 20px; padding: 25px; border: 1px solid #e1ebe5; }
        .nav-menu { list-style: none; display: flex; flex-direction: column; gap: 8px; margin-top: 15px; }
        .nav-item a { display: flex; align-items: center; gap: 12px; padding: 12px 16px; border-radius: 12px; text-decoration: none; font-size: 14px; font-weight: 600; color: #486354; }
        .nav-item.active a { background-color: #d4a359; color: #ffffff; }
        .nav-item a:hover:not(.active) { background-color: #edf4f0; color: #1b3b2b; }
        .content-area { flex-grow: 1; background: #ffffff; border-radius: 20px; padding: 35px; border: 1px solid #e1ebe5; }
        .form-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; margin-top: 20px; }
        .form-group { display: flex; flex-direction: column; gap: 6px; }
        .form-group.full { grid-column: span 2; }
        .form-group label { font-size: 13px; font-weight: 600; color: #1b3b2b; }
        .form-group input, .form-group select { padding: 12px; border: 1px solid #dbe7e0; border-radius: 10px; font-size: 14px; outline: none; background-color: #f7faf8; }
        .btn-save { background-color: #1b3b2b; color: white; border: none; padding: 14px 28px; border-radius: 12px; font-weight: bold; cursor: pointer; margin-top: 20px; }
        .btn-save:hover { background-color: #2e5942; }
        .alert { padding: 12px; border-radius: 10px; font-size: 14px; margin-bottom: 20px; }
        .alert.success { background-color: #d4edda; color: #155724; }
        .alert.error { background-color: #f8d7da; color: #721c24; }
    </style>
</head>
<body>
    <header class="top-header">
        <div class="brand-title">
            <h1>CEN - Formulario de Inscripción</h1>
            <p>Registro de estudiantes — <?= htmlspecialchars($nombre_usuario) ?></p>
        </div>
    </header>

    <div class="main-container">
        <aside class="sidebar">
            <div class="user-card">
                <ul class="nav-menu">
                    <li class="nav-item"><a href="dashboard.php"><i class="fa-solid fa-house"></i> Inicio</a></li>
                    <li class="nav-item active"><a href="formulario.php"><i class="fa-solid fa-file-signature"></i> Formulario</a></li>
                    <li class="nav-item"><a href="alumnos.php"><i class="fa-solid fa-users"></i> Alumnos</a></li>
                    <li class="nav-item"><a href="controllers/logout.php" style="color: #c0392b;"><i class="fa-solid fa-right-from-bracket"></i> Cerrar sesión</a></li>
                </ul>
            </div>
        </aside>

        <main class="content-area">
            <h2>Registrar Nuevo Alumno</h2>
            <p style="color: #6a8375; margin-bottom: 20px;">Completa los datos correspondientes para realizar la inscripción.</p>

            <?php if (!empty($mensaje)): ?>
                <div class="alert <?= $tipo_mensaje ?>"><?= htmlspecialchars($mensaje) ?></div>
            <?php endif; ?>

            <form action="formulario.php" method="POST">
                <div class="form-grid">
                    <div class="form-group">
                        <label>DNI / Documento *</label>
                        <input type="text" name="dni" required placeholder="Ingresá el DNI">
                    </div>
                    <div class="form-group">
                        <label>Género</label>
                        <select name="genero">
                            <option value="M">Masculino</option>
                            <option value="F">Femenino</option>
                            <option value="Otro">Otro</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Nombre *</label>
                        <input type="text" name="nombre" required placeholder="Nombre">
                    </div>
                    <div class="form-group">
                        <label>Apellido *</label>
                        <input type="text" name="apellido" required placeholder="Apellido">
                    </div>
                    <div class="form-group">
                        <label>Fecha de Nacimiento</label>
                        <input type="date" name="fecha_nacimiento">
                    </div>
                    <div class="form-group">
                        <label>Teléfono</label>
                        <input type="text" name="telefono" placeholder="Ej: 3825 123456">
                    </div>
                    <div class="form-group full">
                        <label>Correo Electrónico</label>
                        <input type="email" name="email" placeholder="ejemplo@gmail.com">
                    </div>
                    <div class="form-group full">
                        <label>Dirección / Domicilio</label>
                        <input type="text" name="direccion" placeholder="Calle, Número, Barrio">
                    </div>
                </div>
                <button type="submit" class="btn-save"><i class="fa-solid fa-floppy-disk"></i> Guardar Alumno</button>
            </form>
        </main>
    </div>
</body>
</html>