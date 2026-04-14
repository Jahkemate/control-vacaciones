<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Solicitud de Vacaciones</title>
    <style>
        @page {
            size: letter;
            margin: 1.5cm;
        }

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            font-size: 11pt;
        }

        .content {
            max-width: 800px;
            margin: 0 auto;
        }

        .center {
            text-align: center;
        }

        .line {
            margin-bottom: 8px;
        }

        .VoBo {
            text-align: left;
            font-size: 10pt;
        }

        .address_number {
            text-align: left;
            font-size: 12pt;
        }

        .firma div {
            text-align: left;
            width: 30%;
            font-size: 10pt;
            margin-top: 40px;
            justify-content: space-between;
        }

        .firma-rrhh {
            font-size: 10pt;
        }

        .hr {
            margin: 20px 0;
            border-top: 1px solid #000;
        }

        .informacion {
            font-size: 10pt;
            margin-bottom: 35px;
        }

        .proceso {
            font-size: 10pt;
            color: cornflowerblue
        }

        .title h2 {
            font-size: 12pt;
            margin: 5px 0;
        }

        .logo {
            width: 250px;
            margin-top: 0%;
        }

        .logo_fundahrse {
            width: 60px;
        }

        .section {
            margin-top: 15px;
        }

        .spacer {
            margin-top: 30px;
            margin-bottom: 30px;
        }

        header {
            top: -50px;
            left: 0;
            right: 0;
            height: 50px;
            text-align: center;
            font-size: 14px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10pt;
        }

        td {
            border: 1px solid #666;
            padding: 4px;
        }

        td:first-child {
            width: 40%;
        }

        p {
            font-family: 'Times New Roman', Times, serif;
            margin-top: 15px;
            font-size: 10pt;
            font-style: italic;
        }
    </style>
</head>

<body>
    <div class="content">
        <div class="center">
            <img src="{{ public_path('images/HVD LOGOTIPO.jpeg') }}" class="logo">
            <p>Sirviendo sin fines de lucro desde el 3 de febrero de 1924</p>

            <div class="title" style="text-align: center;">
                <h2>SOLICITUD DE VACACIONES</h2>
            </div>
        </div>

        <br>

        <div class="table">
            <table border="1">
                <tr>
                    <td><strong>Nombre del empleado:</strong></td>
                    <td>{{ $employee->first_name }} {{ $employee->last_name }}</td>
                </tr>
                <tr>
                    <td><strong>Puesto:</strong></td>
                    <td>{{ $employee->user->role_label ?? '' }} de {{ $employee->department->name ?? '' }}</td>
                </tr>
                <tr>
                    <td><strong>Departamento:</strong></td>
                    <td>{{ $employee->department->name ?? '' }}</td>
                </tr>
                <tr>
                    <td><strong>Fecha de ingreso:</strong></td>
                    <td>{{ $hiring_date }}
                    </td>
                </tr>
            </table>
        </div>


        <div class="spacer">
            Solicito mis vacaciones correspondientes al año:
            __________________,
            las cuales deseo tomar a partir del
            <u>{{ $start_date }}</u>,
            regresando a mis labores el
            <u>{{ $end_date }}</u>.
        </div>



        <table border="1">
            <tr>
                <td><strong>Vacaciones pendientes a la fecha:</strong></td>
                <td> </td>
            </tr>
            <tr>
                <td><strong>( - ) Días solicitados:</strong></td>
                <td>{{  $vacationRequest->total_business_days ?? ''  }}</td>
            </tr>
            <tr>
                <td><strong>Pendientes de Gozar:</strong></td>
                <td> </td>
            </tr>
        </table>
        <br>

        <div class="line">
            <strong>Lugar y Fecha:</strong> <u>La Ceiba, Atlántida, Honduras,
                {{ now()->locale('es')->isoFormat('dddd D [de] MMMM [de] YYYY') }}</u>
        </div>

        <div class="section">
            <table style="width:100%; margin-top:40px;">
                <tr>
                    <td style="border:none; width:50%; text-align:left; vertical-align:top;">
                        ____________________________<br>
                        Firma del empleado <br>
                        <span class="address_number">
                            No. Empleado: {{ $employee->address_number ?? '' }}
                        </span>
                    </td>

                    <td style="border:none; width:50%; text-align:left; vertical-align:top;">
                        ____________________________<br>
                        Vo. Bo. Jefe inmediato
                    </td>
                </tr>
            </table>
        </div>
        <div class="hr"></div>
        <div class="informacion_rrhh">
            <div class="center">
                <strong>USO EXCLUSIVO DE RECURSOS HUMANOS</strong>
            </div>

            <br>

            <div class="anotaciones">
                <span>Anotaciones relevantes:</span>_________________________________________________________ <br>
                <br>
                _____________________________________________________________________ <br>
            </div>

            <div class="spacer" style="margin-bottom: 0px"></div>

            <div class="firma-rrhh">
                ____________________________<br>
                <strong>Autorización Recursos Humanos</strong>
            </div>
            <br>
            <div class="informacion">
                <span>Original: RRHH</span> <br>
                <span>CC: Empleado</span>
            </div>
        </div>
        <table style="width:100%; ">
            <tr>
                <td style="border:none; text-align:left;">
                    <img src="{{ public_path('images/FUNDAHRSE1.jpeg') }}" class="logo_fundahrse">
                </td>

                <td style="border:none; text-align:right;">
                    <strong class="proceso">Proceso de Recursos Humanos</strong>
                </td>
            </tr>
        </table>
    </div>
    <script>
        window.onload = function() {
            window.print();
        };
    </script>
</body>

</html>
