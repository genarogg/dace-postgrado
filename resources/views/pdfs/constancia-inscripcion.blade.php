<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Constancia de Inscripción</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 40px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .logo {
            max-width: 200px;
            margin-bottom: 20px;
        }
        .titulo {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 20px;
            text-transform: uppercase;
        }
        .contenido {
            margin-bottom: 30px;
        }
        .tabla-materias {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        .tabla-materias th, .tabla-materias td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .tabla-materias th {
            background-color: #f4f4f4;
        }
        .firma {
            margin-top: 50px;
            text-align: center;
        }
        .linea-firma {
            width: 200px;
            border-top: 1px solid #000;
            margin: 10px auto;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ public_path('images/logo.svg') }}" alt="Logo Institucional" class="logo">
        <div class="titulo">Constancia de Inscripción</div>
    </div>

    <div class="contenido">
        <p>Por medio de la presente se hace constar que el(la) ciudadano(a) <strong>{{ $inscripcion->estudiante->nombre_completo }}</strong>, 
        titular de la Cédula de Identidad N° <strong>{{ $inscripcion->estudiante->cedula }}</strong>, 
        se encuentra inscrito(a) como estudiante regular en el programa de postgrado 
        <strong>{{ $inscripcion->carrera->nombre }}</strong> en la sede de <strong>{{ $inscripcion->sede->nombre }}</strong>, 
        correspondiente al período académico <strong>{{ $inscripcion->periodo->nombre }}</strong>.</p>

        <h3>Materias Inscritas:</h3>
        <table class="tabla-materias">
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Asignatura</th>
                    <th>Unidades de Crédito</th>
                </tr>
            </thead>
            <tbody>
                @foreach($dataInscripcion->materias as $materia)
                <tr>
                    <td>{{ $materia->materia->codigo }}</td>
                    <td>{{ $materia->materia->nombre }}</td>
                    <td>{{ $materia->materia->creditos }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <p>Constancia que se expide a petición de la parte interesada en la ciudad de Caracas, 
        a los {{ now()->format('d') }} días del mes de {{ now()->locale('es')->monthName }} de {{ now()->format('Y') }}.</p>
    </div>

    <div class="firma">
        <div class="linea-firma"></div>
        <p>Director(a) de Postgrado<br>Universidad Nacional Experimental Romulo Gallegos</p>
    </div>
</body>
</html>