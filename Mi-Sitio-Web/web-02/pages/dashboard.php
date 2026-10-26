<?php
session_start();

// 1. Verificar si el preceptor ha iniciado sesión
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../login.php");
    exit();
}

// Conexión a la base de datos
try {
    $pdo = new PDO("mysql:host=localhost;dbname=veintidosrazonesparaaprobar_bd", "root", "", [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
    ]);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}

$error_mensaje = "";

// 2. Procesar el formulario si se envió por POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $pdo->beginTransaction();

        // Guardar Alumno (Incluyendo nombre y apellido obligatorios)
       $stmtAlumno = $pdo->prepare("
    INSERT INTO alumnos (
        nombre,
        apellido,
        dni,
        fecha_nacimiento,
        lugar_nacimiento,
        escuela_origen,
        observaciones_salud
    ) VALUES (?, ?, ?, ?, ?, ?, ?)
");

$stmtAlumno->execute([
    $_POST['alumno_nombre'] ?? '',
    $_POST['alumno_apellido'] ?? '',
    trim($_POST['alumno_dni'] ?? ''),
    $_POST['alumno_fecha_nac'] ?: null,
    $_POST['alumno_lugar_nac'] ?? null,
    $_POST['alumno_escuela'] ?? null,
    $_POST['alumno_salud'] ?? null
]);

$alumno_id = $pdo->lastInsertId();

        // Guardar Madre / Tutora
        $madre_id = null;
        if (!empty($_POST['madre_nombre'])) {
            $stmtMadre = $pdo->prepare("INSERT INTO tutores (tipo_tutor, apellido_nombre, dni, cuil, fecha_nacimiento, lugar_nacimiento, telefono, email, domicilio) VALUES ('madre', ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmtMadre->execute([
                $_POST['madre_nombre'],
                $_POST['madre_dni'],
                $_POST['madre_cuil'] ?? null,
                $_POST['madre_fecha_nac'] ?? null,
                $_POST['madre_lugar_nac'] ?? null,
                $_POST['madre_telefono'],
                $_POST['madre_email'] ?? null,
                $_POST['madre_domicilio']
            ]);
            $madre_id = $pdo->lastInsertId();
        }

      // Guardar Padre / Tutor
        $padre_id = null;
        $padre_domicilio = isset($_POST['mismo_domicilio']) ? ($_POST['madre_domicilio'] ?? '') : ($_POST['padre_domicilio'] ?? '');
        
        // Verificamos que al menos hayan puesto el nombre o el teléfono del padre
        if (!empty($_POST['padre_nombre']) || !empty($_POST['padre_telefono'])) {
            $stmtPadre = $pdo->prepare("INSERT INTO tutores (tipo_tutor, apellido_nombre, dni, cuil, fecha_nacimiento, lugar_nacimiento, telefono, email, domicilio, percibe_asignacion, estudios_cursados) VALUES ('padre', ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmtPadre->execute([
                !empty($_POST['padre_nombre']) ? $_POST['padre_nombre'] : null,
                !empty($_POST['padre_dni']) ? $_POST['padre_dni'] : null,
                !empty($_POST['padre_cuil']) ? $_POST['padre_cuil'] : null,
                !empty($_POST['padre_fecha_nac']) ? $_POST['padre_fecha_nac'] : null,
                !empty($_POST['padre_lugar_nac']) ? $_POST['padre_lugar_nac'] : null,
                !empty($_POST['padre_telefono']) ? $_POST['padre_telefono'] : null,
                !empty($_POST['padre_email']) ? $_POST['padre_email'] : null,
                !empty($padre_domicilio) ? $padre_domicilio : null,
                !empty($_POST['padre_asignacion']) ? $_POST['padre_asignacion'] : null,
                !empty($_POST['padre_estudios']) ? $_POST['padre_estudios'] : null
            ]);
            $padre_id = $pdo->lastInsertId();
        }

        // Registrar Inscripción final
        $stmtInscripcion = $pdo->prepare("INSERT INTO inscripciones (alumno_id, madre_id, padre_id, responsable_firma, correo_firma, autoriza_imagen, usuario_preceptor_id) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmtInscripcion->execute([
            $alumno_id,
            $madre_id,
            $padre_id,
            $_POST['responsable_firma'],
            $_POST['correo_firma'] ?? null,
            $_POST['autoriza_imagen'],
            $_SESSION['usuario_id']
        ]);

        $pdo->commit();

        // Redirigir para evitar reenvíos de formulario al recargar
        header("Location: exito.php?exito=1");
        exit();

    } catch (Exception $e) {
        $pdo->rollBack();
        $error_mensaje = "Error al procesar la inscripción: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CEN - Ficha de Inscripción</title>
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../css/dashboard.css">
</head>
<body>

    <div class="inscripcion-card">
        <!-- ---------------- BARRA LATERAL (PASOS) ---------------- -->
        <div class="sidebar">
            <h3>Pasos</h3>
            <ul class="step-list">
                <li class="step-item active" id="nav-1">
                    <div class="step-number">1</div>
                    <div>
                        Alumno/a
                        <span class="obligatorios">0 de 3 obligatorios</span>
                    </div>
                </li>
                <li class="step-item" id="nav-2">
                    <div class="step-number">2</div>
                    <div>
                        Madre / Tutora
                        <span class="obligatorios">0 de 4 obligatorios</span>
                    </div>
                </li>
                <li class="step-item" id="nav-3">
                    <div class="step-number">3</div>
                    <div>
                        Padre / Tutor
                        <span class="obligatorios">0 de 4 obligatorios</span>
                    </div>
                </li>
                <li class="step-item" id="nav-4">
                    <div class="step-number">4</div>
                    <div>
                        Acta de convivencia
                        <span class="obligatorios">0 de 2 obligatorios</span>
                    </div>
                </li>
            </ul>
            
            <a href="../index.php" class="btn-volver">
                <i class="fa-solid fa-arrow-left"></i> Volver al Portal
            </a>
        </div>

        <!-- ---------------- ÁREA CENTRAL DEL FORMULARIO ---------------- -->
        <div class="form-area">
            <div class="brand-header">
                <div class="brand-badge">
                    <i class="fa-solid fa-graduation-cap"></i> CEN - Ficha de Inscripción
                </div>
                <h1>Formulario de inscripción del estudiante</h1>
                <p>Completá un paso a la vez. Los campos con * son obligatorios. Podés volver a cualquier paso; lo que cargaste no se pierde.</p>
                
                <?php if (!empty($error_mensaje)): ?>
                    <div style="background-color: #f8d7da; color: #721c24; padding: 10px; margin-top: 10px; border-radius: 5px;">
                        <?php echo htmlspecialchars($error_mensaje); ?>
                    </div>
                <?php endif; ?>

                <?php if (isset($_GET['exito']) && $_GET['exito'] == 1): ?>
                    <div style="background-color: #d4edda; color: #155724; padding: 10px; margin-top: 10px; border-radius: 5px;">
                        ¡Inscripción guardada correctamente con éxito!
                    </div>
                <?php endif; ?>
            </div>

            <form action="" method="POST" id="formInscripcion">
                
                <!-- ================= PASO 1: ALUMNO/A ================= -->
                <div class="step-content active" id="step-1">
                    <div class="section-subtitle">Paso 1 de 4 - Datos del Alumno/a</div>
                    
                    <h4 style="color: #6a8375; margin-bottom: 15px; font-size: 13px;">DATOS PERSONALES</h4>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Nombre/s *</label>
                            <div class="input-box"><input type="text" name="alumno_nombre" required></div>
                        </div>
                        <div class="form-group">
                            <label>Apellido/s *</label>
                            <div class="input-box"><input type="text" name="alumno_apellido" required></div>
                        </div>
                    </div>


                    <div class="form-group">
    <label>DNI del alumno/a *</label>
    <div class="input-box">
        <input
            type="text"
            name="alumno_dni"
            placeholder="Ingresá el DNI sin puntos"
            required
        >
    </div>
</div>

                    <h4 style="color: #6a8375; margin-top: 15px; margin-bottom: 15px; font-size: 13px;">NACIMIENTO</h4>
                    <div class="form-row-3">
                        <div class="form-group">
                            <label>Fecha de nacimiento *</label>
                            <div class="input-box"><input type="date" name="alumno_fecha_nac"></div>
                        </div>
                        <div class="form-group">
                            <label>Edad</label>
                            <div class="input-box"><input type="text" placeholder="Se calcula sola" disabled></div>
                        </div>
                        <div class="form-group">
                            <label>Lugar de nacimiento</label>
                            <div class="input-box"><input type="text" name="alumno_lugar_nac" placeholder="Ej: Chilecito, La Rioja"></div>
                        </div>
                    </div>

                    <h4 style="color: #6a8375; margin-top: 25px; margin-bottom: 15px; font-size: 13px;">ESCOLARIDAD Y SALUD</h4>
                    <div class="form-group" style="margin-bottom: 20px;">
                        <label>Escuela de origen</label>
                        <div class="input-box"><input type="text" name="alumno_escuela" placeholder="Ej: Escuela N° 123"></div>
                    </div>
                    <div class="form-group">
                        <label>Observaciones de salud / necesidades</label>
                        <div class="input-box">
                            <textarea name="alumno_salud" placeholder="Alergias, medicación, apoyos que necesita en el aula..."></textarea>
                        </div>
                        <p style="font-size: 11px; color: #8fa89b; margin-top: 5px;">Esta información es confidencial y solo la ve el equipo directivo.</p>
                    </div>
                </div>

                <!-- ================= PASO 2: MADRE / TUTORA ================= -->
                <div class="step-content" id="step-2">
                    <div class="section-subtitle">Paso 2 de 4 - Datos de la madre / tutora</div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label>Apellido y nombres *</label>
                            <div class="input-box"><input type="text" name="madre_nombre" placeholder="Ej: Gomez, Maria Laura" required></div>
                        </div>
                        <div class="form-group">
                            <label>DNI *</label>
                            <div class="input-box"><input type="number" name="madre_dni" placeholder="Sin puntos" required></div>
                        </div>
                    </div>

                    <div class="form-row-3">
                        <div class="form-group">
                            <label>CUIL</label>
                            <div class="input-box"><input type="text" name="madre_cuil" placeholder="27-12345678-9"></div>
                        </div>
                        <div class="form-group">
                            <label>Fecha de nacimiento</label>
                            <div class="input-box"><input type="date" name="madre_fecha_nac"></div>
                        </div>
                        <div class="form-group">
                            <label>Lugar de nacimiento</label>
                            <div class="input-box"><input type="text" name="madre_lugar_nac"></div>
                        </div>
                    </div>

                    <h4 style="color: #6a8375; margin-top: 15px; margin-bottom: 15px; font-size: 13px;">CONTACTO</h4>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Teléfono *</label>
                            <div class="input-box"><input type="text" name="madre_telefono" placeholder="Ej: 3825 401234" required></div>
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <div class="input-box"><input type="email" name="madre_email" placeholder="nombre@correo.com"></div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Domicilio *</label>
                        <div class="input-box"><input type="text" name="madre_domicilio" placeholder="Calle, número, barrio" required></div>
                    </div>
                </div>

                <!-- ================= PASO 3: PADRE / TUTOR ================= -->
                <div class="step-content" id="step-3">
                    <div class="section-subtitle">Paso 3 de 4 - Datos del padre / tutor</div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label>Apellido y nombres</label>
                            <div class="input-box"><input type="text" name="padre_nombre" placeholder="Ej: Perez, Juan Carlos"></div>
                        </div>
                        <div class="form-group">
                            <label>DNI</label>
                            <div class="input-box"><input type="number" name="padre_dni" placeholder="Sin puntos"></div>
                        </div>
                    </div>

                    <div class="form-row-3">
                        <div class="form-group">
                            <label>CUIL</label>
                            <div class="input-box"><input type="text" name="padre_cuil" placeholder="20-12345678-9"></div>
                        </div>
                        <div class="form-group">
                            <label>Fecha de nacimiento</label>
                            <div class="input-box"><input type="date" name="padre_fecha_nac"></div>
                        </div>
                        <div class="form-group">
                            <label>Lugar de nacimiento</label>
                            <div class="input-box"><input type="text" name="padre_lugar_nac"></div>
                        </div>
                    </div>

                    <h4 style="color: #6a8375; margin-top: 15px; margin-bottom: 15px; font-size: 13px;">CONTACTO</h4>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Teléfono</label>
                            <div class="input-box"><input type="text" name="padre_telefono" placeholder="Ej: 3825 401234"></div>
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <div class="input-box"><input type="email" name="padre_email" placeholder="nombre@correo.com"></div>
                        </div>
                    </div>

                    <div class="checkbox-group">
                        <input type="checkbox" id="mismo_domicilio" name="mismo_domicilio">
                        <label for="mismo_domicilio" style="text-transform: none; margin:0; cursor:pointer;">Vive en el mismo domicilio que la madre/tutora</label>
                    </div>

                    <div class="form-group" style="margin-bottom: 20px;">
                        <label>Domicilio</label>
                        <div class="input-box"><input type="text" name="padre_domicilio" placeholder="Calle, número, barrio"></div>
                    </div>

                    <h4 style="color: #6a8375; margin-bottom: 15px; font-size: 13px;">SITUACIÓN</h4>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Percibe asignación</label>
                            <div class="input-box">
                                <select name="padre_asignacion">
                                    <option value="">Seleccionar...</option>
                                    <option value="si">Sí</option>
                                    <option value="no">No</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Estudios cursados</label>
                            <div class="input-box">
                                <select name="padre_estudios">
                                    <option value="">Seleccionar...</option>
                                    <option value="primario">Primario</option>
                                    <option value="secundario">Secundario</option>
                                    <option value="terciario">Terciario / Universitario</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ================= PASO 4: ACTA DE CONVIVENCIA ================= -->
                <div class="step-content" id="step-4">
                    <div class="section-subtitle">Paso 4 de 4 - Acta de aceptación del Régimen de Convivencia</div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label>Responsable que firma *</label>
                            <div class="input-box">
                                <select name="responsable_firma" required>
                                    <option value="">Seleccionar...</option>
                                    <option value="madre">Madre</option>
                                    <option value="padre">Padre</option>
                                    <option value="tutor">Tutor/a Legal</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Correo de contacto</label>
                            <div class="input-box"><input type="email" name="correo_firma" placeholder="nombre@correo.com"></div>
                        </div>
                    </div>

                    <div class="acta-text">
                        Mediante la presente, el/la abajo firmante, en su carácter de tutor/a del alumno/a, conociendo los términos del <strong>Régimen de Convivencia Escolar</strong>, acepta en todos sus términos lo establecido en el mismo y se compromete a colaborar para su estricto cumplimiento.<br><br>
                        Asimismo, en este mismo acto, <strong>autoriza / no autoriza</strong> a esta institución la utilización, exhibición y/o reproducción de fotos, imágenes y audio del alumno/a para publicaciones educativas y audiovisuales.
                    </div>

                    <div class="form-group">
                        <label>Autorización de uso de imagen *</label>
                        <div class="radio-options">
                            <div class="radio-option">
                                <label><input type="radio" name="autoriza_imagen" value="si" required> Autorizo</label>
                                <span>Puede aparecer en fotos y videos institucionales.</span>
                            </div>
                            <div class="radio-option">
                                <label><input type="radio" name="autoriza_imagen" value="no" required> No autorizo</label>
                                <span>No se publicarán imágenes del alumno/a.</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ================= BOTONES DE NAVEGACIÓN ================= -->
                <div class="form-actions">
                    <button type="button" class="btn-action btn-prev" id="btnPrev" onclick="cambiarPaso(-1)" style="visibility: hidden;">
                        <i class="fa-solid fa-arrow-left"></i> Anterior
                    </button>
                    <button type="button" class="btn-action btn-next" id="btnNext" onclick="cambiarPaso(1)">
                        Siguiente <i class="fa-solid fa-arrow-right"></i>
                    </button>
                    <button type="submit" class="btn-action btn-next" id="btnSubmit" style="display: none; background: linear-gradient(135deg, #1b3b2b 0%, #2e5942 100%);">
                        Enviar Inscripción <i class="fa-solid fa-paper-plane"></i>
                    </button>
                </div>

            </form>
        </div>
    </div>

    <!-- Script de navegación de pasos -->
    <script src="../js/dashboard.js"></script>
</body>
</html>