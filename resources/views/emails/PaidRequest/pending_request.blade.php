<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Solicitud enviada por Empleado</title>
</head>

<body style="font-family: Arial; margin:0; padding:0;">

    <div
        style="max-width:600px; margin:auto; border:1px solid #ddd; padding:20px; border-radius:10px; background:#EEFCEF;">

        <!-- HEADER -->
        <div style="text-align:center;">

            <div
                style="background:#095741; border-radius:10px; padding:20px; display:inline-block; width:100%; box-sizing:border-box;">

                <img src="{{ $message->embed(public_path('images/hvdlogotipo.png')) }}"
                    style="background-color:whitesmoke; border-radius:10px; padding:10px; max-width:250px; width:100%; height:auto; display:block; margin:0 auto;">

                <p style="font-style:italic; margin:15px 0 0 0; color:#ffffff;">
                    <strong>"Sirviendo sin fines de lucro desde el 3 de febrero de 1924"</strong>
                </p>

            </div>

            <div style="text-align:center; margin-top:20px;">
                <h2 style="margin:0;">SOLICITUD PAGADA</h2>
                <h3 style="margin:5px 0;"> Solicitud Pendiente </h3>
            </div>

        </div>

        <!-- BOX -->
        <div
            style="background:#ffffff; padding:16px; border-radius:12px; border:1px solid #e5e5e5; margin-top:20px; line-height:1.5; text-align:left;">

            <p>
                Estado actual de la Solicitud:
                <strong style="color:#FF812C">{{ $paid_request->status->getLabel() }}</strong>
            </p>

            <p>
                Nombre del Empleado Solicitante:
                <strong>{{ $paid_request->employee->full_name }}</strong>
            </p>
        </div>

        <!-- BOTONES -->
        <div style="margin-top:20px; text-align:center;">

            <p style="font-style:italic; color:#D1003F;">
                "Esta notificación puede verla también dentro de la aplicación."
            </p>

            <a href="{{ $url }}"
                style="background:#095741; color:white; padding:12px 20px; text-decoration:none; border-radius:6px; border:1px solid black; display:inline-block;">
                Ver detalles de la solicitud
            </a>

            <a href="{{ $print }}"
                style="background:#C6F5C9; color:black; padding:12px 20px; text-decoration:none; border-radius:6px; border:1px solid darkgreen; display:inline-block; margin:12px 10px;">
                Imprimir solicitud
            </a>

        </div>

    </div>

</body>

</html>
