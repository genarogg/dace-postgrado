<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Etiqueta de Expediente</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            line-height: 1.6;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .expediente-info {
            margin-bottom: 15px;
        }
        .documentos-list {
            margin-top: 20px;
        }
        .documento-item {
            margin: 5px 0;
        }
        .estado {
            font-weight: bold;
            text-transform: uppercase;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>Etiqueta de Expediente</h2>
    </div>

    <div class="expediente-info">
        <p><strong>Codigo:</strong> {{ $expediente->codigo }}</p>
        <p><strong>Estudiante:</strong> {{ $expediente->estudiante->cedula }}</p>
        <p><strong>Estado:</strong> <span class="estado">{{ $expediente->estado }}</span></p>
        <p><strong>Fecha de Creación:</strong> {{ $expediente->created_at->format('d/m/Y') }}</p>
        @if($expediente->observaciones)
        <p><strong>Observaciones:</strong> {{ $expediente->observaciones }}</p>
        @endif
    </div>

    <div class="documentos-list">
        <h3>Documentos Entregados:</h3>
        @foreach($expediente->documentos as $documento)
        <div class="documento-item">
            - {{ $documento->nombre }}
        </div>
        @endforeach
    </div>
</body>
</html>