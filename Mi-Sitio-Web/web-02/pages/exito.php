<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscripción Exitosa - CEN</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../css/dashboard.css">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f4f7f6;
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .success-card {
            background: white;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            text-align: center;
            max-width: 400px;
            width: 100%;
        }
        .success-icon {
            font-size: 60px;
            color: #2e5942;
            margin-bottom: 20px;
        }
        .success-card h2 {
            color: #1b3b2b;
            margin-bottom: 10px;
        }
        .success-card p {
            color: #6a8375;
            font-size: 14px;
            margin-bottom: 25px;
        }
        .btn-dashboard {
            display: inline-block;
            background: linear-gradient(135deg, #1b3b2b 0%, #2e5942 100%);
            color: white;
            padding: 12px 25px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 600;
            transition: opacity 0.2s;
        }
        .btn-dashboard:hover {
            opacity: 0.9;
        }
    </style>
    <!-- Redirigir automáticamente al dashboard después de 4 segundos (4000 milisegundos) -->
    <script>
        setTimeout(function(){
            window.location.href = 'dashboard.php';
        }, 4000);
    </script>
</head>
<body>

    <div class="success-card">
        <div class="success-icon">
            <i class="fa-solid fa-circle-check"></i>
        </div>
        <h2>¡22 Razones por las que la inscripción fue Exitosa!</h2>
        <p>Los datos del alumno y sus tutores fueron guardados correctamente en el sistema.</p>
        <a href="dashboard.php" class="btn-dashboard">Volver al Dashboard ahora</a>
    </div>

</body>
</html>