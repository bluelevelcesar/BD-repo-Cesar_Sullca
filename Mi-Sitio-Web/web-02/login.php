<?php
session_start();
require_once 'config/conexion.php'; // Ajusta la ruta si es necesario

$mensaje_error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (!empty($email) && !empty($password)) {
        // Consulta del usuario en la base de datos
        $stmt = $conn->prepare("SELECT id, nombre, email, password FROM usuarios WHERE email = ? LIMIT 1");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($usuario = $resultado->fetch_assoc()) {
            if (password_verify($password, $usuario['password']) || $password === $usuario['password']) {
                $_SESSION['usuario_id'] = $usuario['id'];
                $_SESSION['usuario_nombre'] = $usuario['nombre'];
                
                header("Location: pages/dashboard.php");
                exit();
            } else {
                $mensaje_error = "La contraseña ingresada es incorrecta.";
            }
        } else {
            $mensaje_error = "El correo electrónico no está registrado.";
        }
        $stmt->close();
    } else {
        $mensaje_error = "Por favor, completa todos los campos.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CEN - Portal de Acceso</title>
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', system-ui, -apple-system, BlinkMacSystemFont, sans-serif;
        }

        body {
            background: linear-gradient(135deg, #0f241a 0%, #1b3b2b 60%, #2e5942 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
            position: relative;
            overflow: hidden;
        }

        /* Adornos de fondo decorativos */
        body::before {
            content: '';
            position: absolute;
            width: 350px;
            height: 350px;
            border-radius: 50%;
            background: rgba(212, 163, 89, 0.12);
            top: -80px;
            right: -80px;
            filter: blur(50px);
        }

        body::after {
            content: '';
            position: absolute;
            width: 300px;
            height: 300px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.05);
            bottom: -60px;
            left: -60px;
            filter: blur(40px);
        }

        /* Tarjeta Central */
        .login-card {
            background: rgba(255, 255, 255, 0.96);
            width: 100%;
            max-width: 440px;
            border-radius: 24px;
            padding: 40px 35px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.35);
            position: relative;
            z-index: 10;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        /* Encabezado del Formulario */
        .brand-header {
            text-align: center;
            margin-bottom: 28px;
        }

        .brand-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #edf4f0;
            color: #1b3b2b;
            padding: 5px 14px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.5px;
            margin-bottom: 15px;
            border: 1px solid #d0e2d7;
        }

        .logo-ring {
            width: 68px;
            height: 68px;
            border-radius: 50%;
            background: linear-gradient(135deg, #1b3b2b, #2e5942);
            color: #d4a359;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px auto;
            font-size: 26px;
            box-shadow: 0 6px 16px rgba(27, 59, 43, 0.25);
            border: 2px solid #d4a359;
        }

        .brand-header h1 {
            font-size: 22px;
            color: #1b3b2b;
            font-weight: 700;
        }

        .brand-header p {
            font-size: 13px;
            color: #6a8375;
            margin-top: 4px;
        }

        /* Alerta de Error */
        .alert-error {
            background-color: #fce8e6;
            color: #a83226;
            border: 1px solid #f5c2c0;
            padding: 12px 14px;
            border-radius: 12px;
            font-size: 13px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        /* Grupos de Entradas */
        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-size: 12px;
            font-weight: 700;
            color: #1b3b2b;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .input-box {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-box i.input-icon {
            position: absolute;
            left: 15px;
            color: #8fa89b;
            font-size: 15px;
            transition: color 0.2s;
        }

        .input-box input {
            width: 100%;
            padding: 14px 42px 14px 42px;
            border: 1.5px solid #dbe7e0;
            border-radius: 14px;
            background-color: #f8faf9;
            font-size: 14px;
            color: #1b3b2b;
            outline: none;
            transition: all 0.25s ease;
        }

        .input-box input:focus {
            border-color: #d4a359;
            background-color: #ffffff;
            box-shadow: 0 0 0 4px rgba(212, 163, 89, 0.15);
        }

        .input-box input:focus ~ i.input-icon {
            color: #d4a359;
        }

        .toggle-password {
            position: absolute;
            right: 15px;
            color: #8fa89b;
            cursor: pointer;
            font-size: 15px;
            padding: 4px;
            transition: color 0.2s;
        }

        .toggle-password:hover {
            color: #1b3b2b;
        }

        /* Enlace Contraseña Olvidada */
        .forgot-link {
            display: inline-block;
            float: right;
            font-size: 12px;
            color: #6a8375;
            text-decoration: none;
            margin-top: 6px;
            transition: color 0.2s;
        }

        .forgot-link:hover {
            color: #d4a359;
            text-decoration: underline;
        }

        /* Botón Iniciar Sesión */
        .btn-submit {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, #d4a359 0%, #b8863b 100%);
            border: none;
            border-radius: 14px;
            color: #ffffff;
            font-weight: 700;
            font-size: 15px;
            cursor: pointer;
            margin-top: 10px;
            box-shadow: 0 6px 18px rgba(212, 163, 89, 0.35);
            transition: all 0.25s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 22px rgba(212, 163, 89, 0.45);
            background: linear-gradient(135deg, #e0ae63 0%, #c49144 100%);
        }

        .btn-submit:active {
            transform: translateY(0);
        }

        /* Pie de página de Seguridad */
        .card-footer {
            margin-top: 25px;
            padding-top: 18px;
            border-top: 1px solid #edf4f0;
            text-align: center;
            font-size: 11px;
            color: #8fa89b;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }
    </style>
</head>
<body>

    <div class="login-card">
        <!-- Logo y Título Central -->
        <div class="brand-header">
            <div class="brand-badge">
                <i class="fa-solid fa-graduation-cap"></i> CENTRO EDUCATIVO NONOGASTA
            </div>
            <div class="logo-ring">
                <i class="fa-solid fa-user-shield"></i>
            </div>
            <h1>Gestión de Preceptoría</h1>
            <p>Ingresá tus credenciales para acceder</p>
        </div>

        <!-- Mensaje de error si falla el login -->
        <?php if (!empty($mensaje_error)): ?>
            <div class="alert-error">
                <i class="fa-solid fa-circle-exclamation"></i>
                <span><?= htmlspecialchars($mensaje_error) ?></span>
            </div>
        <?php endif; ?>

        <!-- Formulario -->
        <form action="login.php" method="POST">
            <div class="form-group">
                <label for="email">Correo Institucional</label>
                <div class="input-box">
                    <input type="email" id="email" name="email" placeholder="ejemplo@oicen.com" required autocomplete="email">
                    <i class="fa-regular fa-envelope input-icon"></i>
                </div>
            </div>

            <div class="form-group">
                <div style="overflow: hidden; margin-bottom: 8px;">
                    <label for="password" style="float: left; margin-bottom: 0;">Contraseña</label>
                    <a href="#" class="forgot-link">¿La olvidaste?</a>
                </div>
                <div class="input-box">
                    <input type="password" id="password" name="password" placeholder="••••••••" required>
                    <i class="fa-solid fa-lock input-icon"></i>
                    <i class="fa-regular fa-eye toggle-password" onclick="togglePasswordVisibility()"></i>
                </div>
            </div>

            <button type="submit" class="btn-submit">
                <span>Ingresar al Sistema</span>
                <i class="fa-solid fa-arrow-right"></i>
            </button>
        </form>
<!-- Formulario (Tu código actual) -->
        <form action="login.php" method="POST">
            <!-- ... tus inputs y el botón .btn-submit actual ... -->
        </form>

        
    
        <div class="card-footer">
            <i class="fa-solid fa-shield-halved"></i> Conexión cifrada • Módulo de Inscripción v1.0
        </div>
    </div>

    <script>
    function togglePasswordVisibility() {
        const passwordInput = document.getElementById('password');
        const toggleIcon = document.querySelector('.toggle-password');
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            toggleIcon.classList.replace('fa-eye', 'fa-eye-slash');
        } else {
            passwordInput.type = 'password';
            toggleIcon.classList.replace('fa-eye-slash', 'fa-eye');
        }
    }
    </script>

</body>
</html>