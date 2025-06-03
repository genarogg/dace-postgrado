<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Constancia de Estudios</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 40px;
        }
        .header {
            text-align: center;
            margin-bottom: 10px;
        }
        .logo {
            max-width: 200px;
            margin-bottom: 0px;
        }
        .title {
            font-size: 20px;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 0px;
            text-align: center;
        }
        .content {
            text-align: justify;
            margin-bottom: 0px;
        }
        .student-info {
            margin-bottom: 0px;
        }
        .subjects {
            margin: 0px 0;
        }
        .signature {
            margin-top: 30px;
            text-align: center;
        }
        .signature-line {
            width: 200px;
            border-top: 1px solid #000;
            margin: 10px auto;
        }
        .footer {
            margin-top: 0px;
            text-align: center;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ asset('images/logo.png') }}" alt="Logo Institucional" class="logo">
        <h2>Universidad Nacional Experimental Romulo Gallegos</h2>        
        <h3>Dirección de Postgrado</h3>
    </div>

    <div class="title">
        Constancia de Estudios
    </div>

    <div class="content">
        <p>
            Quien suscribe, Director de Postgrado de la Universidad Nacional Experimental Romulo Gallegos, hace constar por medio de la presente que:
        </p>

        <div class="student-info">
            <p><strong>Ciudadano(a):</strong> {{ $estudiante->nombre }} {{ $estudiante->apellido }}</p>
            <p><strong>Cédula de Identidad:</strong> {{ $estudiante->cedula }}</p>
            @if($inscripcion)
                <p><strong>Programa:</strong> {{ $inscripcion->carrera->nombre }}</p>
            @endif
        </div>

        @if($inscripcion && $inscripcion->materias->count() > 0)
            <p>Se encuentra cursando las siguientes unidades curriculares durante el período actual:</p>
            <div class="subjects">
                <ul>
                    @foreach($inscripcion->materias as $materia)
                        <li>{{ $materia->materia->nombre }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <p>
            Constancia que se expide a solicitud de la parte interesada en la ciudad de Barquisimeto, 
            a los {{ now()->format('d') }} días del mes de {{ now()->locale('es')->monthName }} 
            de {{ now()->format('Y') }}.
        </p>
    </div>

    <div class="signature">
        <div class="signature-line"></div>
        <p>Director de Postgrado</p>
        <p>Universidad Nacional Experimental Romulo Gallegos</p>
    </div>

    <div class="footer">
        <p>Dirección de Postgrado</p>
    </div>
</body>
</html>