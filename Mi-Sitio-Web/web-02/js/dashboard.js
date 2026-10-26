let pasoActual = 1;
        const totalPasos = 4;

        function cambiarPaso(direccion) {
            // Validar requeridos antes de avanzar (Opcional, versión simplificada)
            if(direccion === 1) {
                // Aquí podrías agregar validaciones de campos required por paso si lo deseas
            }

            // Ocultar paso actual
            document.getElementById(`step-${pasoActual}`).classList.remove('active');
            document.getElementById(`nav-${pasoActual}`).classList.remove('active');

            // Actualizar contador
            pasoActual += direccion;

            // Asegurar que estemos dentro de los límites
            if(pasoActual < 1) pasoActual = 1;
            if(pasoActual > totalPasos) pasoActual = totalPasos;

            // Mostrar nuevo paso
            document.getElementById(`step-${pasoActual}`).classList.add('active');
            document.getElementById(`nav-${pasoActual}`).classList.add('active');

            // Lógica visual de botones
            document.getElementById('btnPrev').style.visibility = (pasoActual === 1) ? 'hidden' : 'visible';
            
            if (pasoActual === totalPasos) {
                document.getElementById('btnNext').style.display = 'none';
                document.getElementById('btnSubmit').style.display = 'flex';
            } else {
                document.getElementById('btnNext').style.display = 'flex';
                document.getElementById('btnSubmit').style.display = 'none';
            }
        }