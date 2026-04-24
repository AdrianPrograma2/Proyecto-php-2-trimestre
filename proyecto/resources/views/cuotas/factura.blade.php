<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Factura {{ $cuota->id }}</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            font-size: 12px; 
            color: #333; 
            margin: 0;
            padding: 20px;
        }
        .header { 
            text-align: center; 
            margin-bottom: 30px; 
            border-bottom: 3px solid #0056b3; 
            padding-bottom: 15px; 
        }
        .header h1 { 
            margin: 0; 
            color: #0056b3; 
            font-size: 24px;
        }
        .info-container { 
            display: flex; 
            justify-content: space-between; 
            margin-bottom: 30px; 
        }
        .box { 
            width: 48%; 
            border: 1px solid #ddd; 
            padding: 15px; 
            border-radius: 5px; 
            background-color: #f9f9f9;
        }
        .box h3 { 
            margin-top: 0; 
            background: #0056b3; 
            color: white;
            padding: 8px; 
            border-radius: 3px;
            font-size: 14px;
        }
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 30px; 
        }
        table th, table td { 
            border: 1px solid #ddd; 
            padding: 10px; 
            text-align: left; 
        }
        table th { 
            background-color: #0056b3; 
            color: white; 
            font-weight: bold;
        }
        .total { 
            text-align: right; 
            margin-top: 30px; 
            font-size: 18px; 
            font-weight: bold; 
            color: #0056b3;
        }
        .footer { 
            margin-top: 50px; 
            text-align: center; 
            font-size: 10px; 
            color: #777; 
            border-top: 1px solid #ddd; 
            padding-top: 15px; 
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>NOSECAEN S.L.</h1>
        <p>Mantenimiento de Ascensores</p>
    </div>

    <div class="info-container">
        <div class="box">
            <h3>Datos del Cliente</h3>
            <p><strong>Nombre:</strong> {{ $cuota->cliente->nombre }}</p>
            <p><strong>CIF:</strong> {{ $cuota->cliente->cif }}</p>
            <p><strong>Teléfono:</strong> {{ $cuota->cliente->telefono ?? 'N/A' }}</p>
            <p><strong>Correo:</strong> {{ $cuota->cliente->correo ?? 'N/A' }}</p>
        </div>
        <div class="box">
            <h3>Datos de la Factura</h3>
            <p><strong>Nº Factura:</strong> {{ $cuota->id }}-{{ now()->format('Y') }}</p>
            <p><strong>Fecha Emisión:</strong> {{ $cuota->fecha_emision->format('d/m/Y') }}</p>
            <p><strong>Concepto:</strong> {{ $cuota->concepto }}</p>
            <p><strong>Estado:</strong> 
                @if($cuota->pagada === 'S')
                    <span style="color: green; font-weight: bold;">✓ PAGADA</span>
                @else
                    <span style="color: red; font-weight: bold;">⏳ PENDIENTE</span>
                @endif
            </p>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Descripción</th>
                <th>Fecha</th>
                <th>Importe</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $cuota->concepto }}</td>
                <td>{{ $cuota->fecha_emision->format('d/m/Y') }}</td>
                <td style="text-align: right; font-weight: bold;">
                    {{ number_format($cuota->importe, 2) }} {{ $cuota->cliente->moneda ?? 'EUR' }}
                </td>
            </tr>
        </tbody>
    </table>

    <div class="total">
        TOTAL: {{ number_format($cuota->importe, 2) }} {{ $cuota->cliente->moneda ?? 'EUR' }}
    </div>

    @if($cuota->notas)
        <div style="margin-top: 30px; border: 1px solid #ddd; padding: 15px; background-color: #f9f9f9;">
            <h3 style="margin-top: 0; color: #0056b3;">Notas:</h3>
            <p>{{ $cuota->notas }}</p>
        </div>
    @endif

    <div class="footer">
        <p>Gracias por confiar en Nosecaen S.L.</p>
        <p>Este documento es una factura generada electrónicamente.</p>
    </div>

</body>
</html>