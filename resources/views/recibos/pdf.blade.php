<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Recibo</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #000;
        }

        .container {
            padding: 20px;
        }

        .header {
            border: 1px solid #444;
            padding: 15px;
        }

        .header-table {
            width: 100%;
        }

        .header-table td {
            vertical-align: middle;
        }

        .logo {
            width: 60px;
        }

        .titulo {
            text-align: center;
            font-size: 18px;
            font-weight: bold;
        }

        .subheader {
            border-top: 1px solid #999;
            margin-top: 10px;
            padding-top: 8px;
            font-size: 11px;
        }

        .box {
            border: 1px solid #444;
            border-radius: 8px;
            padding: 12px;
            margin-top: 15px;
        }

        .box-title {
            font-weight: bold;
            margin-bottom: 8px;
        }

        .info p {
            margin: 4px 0;
        }

        .tabla {
            width: 100%;
            border-collapse: collapse;
        }

        .tabla th {
            background: #eaeaea;
            border: 1px solid #999;
            padding: 6px;
        }

        .tabla td {
            border: 1px solid #ddd;
            padding: 6px;
        }

        .firma {
            margin-top: 20px;
        }

        .firma-linea {
            margin-top: 30px;
            width: 200px;
            border-top: 1px solid #000;
            text-align: center;
            float: right;
        }

        .footer {
            text-align: center;
            font-size: 10px;
            color: gray;
            margin-top: 20px;
        }
    </style>
</head>

<body>

<div class="container">

    <!-- HEADER -->
    <div class="header">

        <table class="header-table">
            <tr>
                <td style="width:80px;">
                    <img src="{{ public_path('uploads/logo/logo.jpg') }}" class="logo">
                </td>

                <td class="titulo">
                    RECIBO DE PAGO
                </td>
            </tr>
        </table>

        <div class="subheader">
            Número: {{ $recibo->numero }}
            &nbsp;&nbsp;&nbsp;&nbsp;
            Fecha de emisión: {{ \Carbon\Carbon::parse($recibo->fecha_emision)->format('Y-m-d') }}
            &nbsp;&nbsp;&nbsp;&nbsp;
            Tipo: {{ $recibo->tipo }}
        </div>

    </div>

    <!-- INFO -->
    <div class="box">
        <div class="box-title">Información del recibo</div>

        <div class="info">
            <p><strong>Recibido de:</strong> {{ $recibo->recibido_de }}</p>
            <p><strong>Concepto:</strong> {{ $recibo->concepto }}</p>
            <p><strong>Monto recibido:</strong> L. {{ number_format($recibo->monto_recibido,2) }}</p>
            <p><strong>Periodo:</strong> {{ $pago->periodo ?? '-' }}</p>
        </div>
    </div>

    <!-- DETALLE -->
    <div class="box">
        <div class="box-title">Detalle</div>

        <table class="tabla">
            <thead>
                <tr>
                    <th>Campo</th>
                    <th>Valor</th>
                </tr>
            </thead>

            <tbody>

                @if($pago)
                <tr>
                    <td>ID Pago</td>
                    <td>{{ $pago->id }}</td>
                </tr>
                <tr>
                    <td>Estado del pago</td>
                    <td>{{ $pago->estado }}</td>
                </tr>
                <tr>
                    <td>Periodo</td>
                    <td>{{ $pago->periodo }}</td>
                </tr>
                @endif

                @if(isset($abono))
                <tr>
                    <td>ID Abono</td>
                    <td>{{ $abono->id }}</td>
                </tr>
                <tr>
                    <td>Método</td>
                    <td>{{ $abono->metodo }}</td>
                </tr>
                <tr>
                    <td>Referencia</td>
                    <td>{{ $abono->referencia_pago ?? '-' }}</td>
                </tr>
                @endif

            </tbody>
        </table>
    </div>

    <!-- FIRMA -->
    <div class="box">
        <div class="box-title">Firma</div>

        <p>Este recibo deja constancia del monto recibido según el detalle anterior.</p>

        @if($recibo->firma_base64)
            <img src="{{ $recibo->firma_base64 }}" width="150">
        @endif

        <div class="firma-linea">Firma</div>
        <div style="clear: both;"></div>
    </div>

    <!-- FOOTER -->
    <div class="footer">
        Documento generado automáticamente por el sistema de alquileres.
    </div>

</div>

</body>
</html>