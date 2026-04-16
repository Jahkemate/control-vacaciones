<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Solicitud Aprobada</title>
    <style>
        .logo {
            width: 450px;
            margin-top: 0%;
        }

        .center {
            text-align: center;
        }

        .box {
            text-align: left;
            border-radius: 15px;
        }
    </style>
</head>

<body style="font-family: Arial;">
    {{-- @php
        $logo = public_path('images/hvdlogotipo.png');
    @endphp --}}

    <div
        style="max-width:600px; margin:auto; border:1px solid #ddd; padding:20px; border-radius:10px; background: #EEFCEF ">

        <div class="center">
            <img src="{{ $message->embed(public_path('images/hvdlogotipo.png')) }}" class="logo">
            <p>Sirviendo sin fines de lucro desde el 3 de febrero de 1924</p>

            <div class="title" style="text-align: center;">
                <h2>SOLICITUD DE VACACIONES</h2>
                <h3>Solicitud Aprobada</h3>
            </div>
        </div>

        <div class="box"
            style=" background:#ffffff;
                    padding:16px;
                    border-radius:12px;
                    border:1px solid #e5e5e5;
                    margin-top:20px;
                    line-height:1.5;">
            <p>
                Estado actual de la Solicitud:
                <strong style="color:darkgreen">{{ $request->status->getLabel() }}</strong>
            </p>
            <p>Nombre del Empleado Solicitante: <strong>{{ $request->employee->full_name }}</strong></p>
        </div>

        <div style="margin-top:20px; text-align:center;">
            <a href="{{ $url }}"
                style="background: #095741; color:white; padding:12px 20px; text-decoration:none; border-radius:6px; display:inline-block;">
                Ver detalles de la solicitud
            </a>
            <a href="{{ $print }}"
                style="background: #C6F5C9; color:black; padding:12px 20px; text-decoration:none; border-radius:6px; border-color:darkgreen; border-size: 10px; display:inline-block; margin:12px 20px">
                Imprimir solicitud
            </a>
        </div>

    </div>

</body>

</html>
