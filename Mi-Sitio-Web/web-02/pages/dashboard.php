<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../login.php");
    exit();
}

$nombre_usuario = $_SESSION['usuario_nombre'] ?? 'Preceptor';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CEN - Proyecto en Desarrollo</title>
    <!-- Iconos de FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', system-ui, -apple-system, BlinkMacSystemFont, Roboto, sans-serif;
        }

        body {
            background: linear-gradient(135deg, #0f241a 0%, #1b3b2b 60%, #2e5942 100%);
            color: #2c3531;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        /* Cartel de Desarrollo Centrado */
        .dev-banner {
            background: rgba(255, 255, 255, 0.96);
            border: 2px dashed #d4a359;
            border-radius: 24px;
            padding: 50px 35px;
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            max-width: 580px;
            width: 100%;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
            backdrop-filter: blur(10px);
        }

        .dev-icon-container {
            width: 90px;
            height: 90px;
            background-color: #1b3b2b;
            color: #d4a359;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 38px;
            margin-bottom: 24px;
            box-shadow: 0 8px 20px rgba(27, 59, 43, 0.3);
            border: 2px solid #d4a359;
        }

        .dev-banner h2 {
            font-size: 26px;
            color: #1b3b2b;
            margin-bottom: 14px;
            font-weight: 700;
            line-height: 1.3;
        }

        .dev-banner p {
            font-size: 15px;
            color: #4a6656;
            line-height: 1.6;
            margin-bottom: 20px;
        }

        .team-members {
            font-size: 14px;
            color: #1b3b2b;
            background-color: #edf4f0;
            padding: 10px 18px;
            border-radius: 12px;
            margin-bottom: 25px;
            border: 1px solid #dbe7e0;
        }

        .dev-badge {
            background: linear-gradient(135deg, #d4a359 0%, #b8863b 100%);
            color: #ffffff;
            font-size: 13px;
            font-weight: 700;
            padding: 10px 22px;
            border-radius: 20px;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 4px 12px rgba(212, 163, 89, 0.35);
        }

        .logout-link {
            margin-top: 25px;
            color: #c0392b;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: color 0.2s;
        }

        .logout-link:hover {
            color: #e74c3c;
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <div class="dev-banner">
        <div class="dev-icon-container">
            <i class="fa-solid fa-code"></i>
        </div>
        
        <h2>Tengo 22 razones por las que este proyecto sigue en desarrollo</h2>
        
        <p>Estamos trabajando activamente para perfeccionar cada funcionalidad y garantizar la mejor experiencia en la gestión académica.</p>

        <div class="team-members">
            <i class="fa-solid fa-laptop-code" style="color: #d4a359; margin-right: 6px;"></i>
            Desarrollado por <strong> Sr Cexár, Sr 22, Sr 0.2 y Sr 15</strong>
        </div>

        <div class="team-members">
            <i class="fa-solid fa-laptop-code" style="color: #d4a359; margin-right: 6px;"></i>
            General a Cargo <strong>Sr 138</strong>
        </div>

        <span class="dev-badge">
            <i class="fa-solid fa-screwdriver-wrench"></i> Próximamente disponible
        </span>

        <a href="../controllers/logout.php" class="logout-link">
            <i class="fa-solid fa-right-from-bracket"></i> Cerrar sesión
        </a>
    </div>

</body>
</html>