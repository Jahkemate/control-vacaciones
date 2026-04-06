<!DOCTYPE html>
<html>

<head>
    <title>Solicitud de Vacaciones</title>
    <style>
        @page {
            size: letter;
            margin-left: 2.54cm;
            margin-right: 2.54cm;
            margin-top: 1.27cm;
            margin-bottom: 1.27cm;
        }

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            font-size: 11pt;
        }

        .center {
            text-align: center;
        }

        .line {
            margin-bottom: 8px;
        }

        .firma {
            font-size: 10pt;
            margin-top: 40px;
            display: flex;
            justify-content: space-between;
        }

        .address_number {
            text-align: left;
            font-size: 12pt;
        }

        .firma div {
            text-align: left;
            width: 40%;
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
        }

        .proceso {
            margin-top: 40px;
            text-align: right;
            font-size: 10pt;
            color: cornflowerblue
        }

        .title h2 {
            font-size: 12pt;
            margin: 5px 0;
        }

        .logo {
            width: 250px;
        }

        .logo_fundahrse {
            text-align: left;
            width: 50px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #e6e6e6;
            font-size: 10pt;
        }

        td {
            border: 1px solid #666;
            padding: 6px;
        }

        td:first-child {
            width: 40%;
        }

        p {
            font-family: 'Times New Roman', Times, serif;
            font-size: 10pt;
            font-style: italic;

        }
    </style>
</head>

<body onload="window.print()">
    <div class="center">
        <img src="{{ url('images/LOGO HVD.jpeg') }}" class="logo">
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
                <td>{{ $data['employee_id'] }}</td>
            </tr>
            <tr>
                <td><strong>Puesto:</strong></td>
                <td>{{ $data['role.name'] }}</td>
            </tr>
            <tr>
                <td><strong>Departamento:</strong></td>
                <td>{{ $data['department'] }}</td>
            </tr>
            <tr>
                <td><strong>Fecha de ingreso:</strong></td>
                <td>{{ $data['hiring_date'] }}</td>
            </tr>
        </table>
    </div>
    <br>

    <div class="line">
        Solicito mis vacaciones correspondientes al año:
        ---
        las cuales deseo tomar a partir del
        <u>{{ $data['start_date'] }}</u>,
        regresando a mis labores el
        <u>{{ $data['end_date'] }}.</u>
    </div>

    <br>

    <table border="1">
        <tr>
            <td><strong>Vacaciones pendientes a la fecha:</strong></td>
            <td> </td>
        </tr>
        <tr>
            <td><strong>( - ) Días solicitados:</strong></td>
            <td>{{ $data['total_business_days'] }}</td>
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

    <div class="firma">
        <div>
            ____________________________<br>
            Firma del empleado <br>
            <span class="address_number"> No. Empleado: {{ $data['address_number'] }}</span>
        </div>

        <div>
            ____________________________<br>
            Vo. Bo. Jefe inmediato
        </div>
    </div>

    <div class="hr"></div>
    <div class="informacion_rrhh">
        <div class="center">
            <strong>USO EXCLUSIVO DE RECURSOS HUMANOS</strong>
        </div>

        <br>

        <div class="anotaciones">
            <span>Anotaciones relevantes:</span>____________________________________________________ <br>
            <br>
            _____________________________________________________________________ <br>
        </div>

        <br>
        <br>
        <br>

        <div class="firma-rrhh">
            ____________________________<br>
            <strong>Autorización Recursos Humanos</strong>
        </div>
        <br>
        <br>

        <div class="informacion">
            <span>Original: RRHH</span> <br>
            <span>CC: Empleado</span>
        </div>
    </div>

    <div class="logo_fundahrse">
        <img src="{{ url('images/FUNDAHRSE.jpeg') }}" class="logo_fundahrse">
    </div>

    <div class="proceso">
        <strong>Proceso de Recursos Humanos</strong>
    </div>
</body>

</html>
