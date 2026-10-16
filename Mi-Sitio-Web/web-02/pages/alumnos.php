<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

require_once '../config/conexion.php';

$nombre_usuario = $_SESSION['usuario_nombre'] ?? 'Silvana';

// Consultar lista de alumnos
$sql = "SELECT id, dni, apellido, nombre, telefono, email FROM alumnos ORDER BY apellido ASC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CEN - Listado de Alumnos</title>
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
        .table-responsive { overflow-x: auto; margin-top: 20px; }
        table { width: 100%; border-collapse: collapse; text-align: left; font-size: 14px; }
        th { background-color: #edf4f0; color: #1b3b2b; padding: 12px; font-weight: 700; border-bottom: 2px solid #dbe7e0; }
        td { padding: 12px; border-bottom: 1px solid #e1ebe5; color: #4a6656; }
        tr:hover { background-color: #f7faf8; }
        .btn-add { background-color: #d4a359; color: white; padding: 8px 16px; border-radius: 10px; text-decoration: none; font-weight: bold; font-size: 13px; }
    </style>
</head>
<body>
    <header class="top-header">
        <div class="brand-title">
            <h1>CEN - Gestión de Alumnos</h1>
            <p>Portafolio de seguimiento — <?= htmlspecialchars($nombre_usuario) ?></p>
        </div>
    </header>

    <div class="main-container">
        <aside class="sidebar">
            <div class="user-card">
                <ul class="nav-menu">
                    <li class="nav-item"><a href="dashboard.php"><i class="fa-solid fa-house"></i> Inicio</a></li>
                    <li class="nav-item"><a href="formulario.php"><i class="fa-solid fa-file-signature"></i> Formulario</a></li>
                    <li class="nav-item active"><a href="alumnos.php"><i class="fa-solid fa-users"></i> Alumnos</a></li>
                    <li class="nav-item"><a href="controllers/logout.php" style="color: #c0392b;"><i class="fa-solid fa-right-from-bracket"></i> Cerrar sesión</a></li>
                </ul>
            </div>
        </aside>

        <main class="content-area">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <h2>Alumnos Registrados</h2>
                    <p style="color: #6a8375;">Listado completo de estudiantes en la base de datos.</p>
                </div>
                <a href="formulario.php" class="btn-add"><i class="fa-solid fa-plus"></i> Nuevo Alumno</a>
            </div>

            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>DNI</th>
                            <th>Apellido y Nombre</th>
                            <th>Teléfono</th>
                            <th>Email</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result && $result->num_rows > 0): ?>
                            <?php while($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?= htmlspecialchars($row['dni']) ?></td>
                                    <td><strong><?= htmlspecialchars($row['apellido']) ?></strong>, <?= htmlspecialchars($row['nombre']) ?></td>
                                    <td><?= htmlspecialchars($row['telefono'] ?: '-') ?></td>
                                    <td><?= htmlspecialchars($row['email'] ?: '-') ?></td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" style="text-align: center; padding: 20px;">Ndaipóri alumnos registrádo gueteri. (No hay alumnos registrados).</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</body>
</html>