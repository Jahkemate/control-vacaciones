<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Solicitud de Compensación</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 15px;
            color: #2c3e50;
        }

        .header {
            text-align: center;
            margin-bottom: 25px;
        }

        .header h2 {
            margin: 0;
            color: #1f4e79;
        }

        .section {
            margin-bottom: 20px;
            border: 1px solid #dcdcdc;
            border-radius: 6px;
            overflow: hidden;
            font-size: 15px;
        }

        .section-title {
            background-color: #095741;
            color: white;
            padding: 8px 12px;
            font-weight: bold;
            font-size: 15px;
        }

        .section-content {
            padding: 12px;
        }

        .row {
            display: table;
            width: 100%;
            margin-bottom: 6px;
        }

        .label {
            display: table-cell;
            width: 40%;
            font-weight: bold;
            color: #34495e;
        }

        .value {
            display: table-cell;
            width: 60%;
            color: #000;
        }

        .grid-2 {
            display: table;
            width: 100%;
        }

        .box {
            display: table-cell;
            width: 50%;
            border: 1px solid #eee;
            padding: 10px;
        }

        .box-title {
            font-weight: bold;
            margin-bottom: 4px;
            color: #1f4e79;
        }

        .empty {
            color: #7f8c8d;
            font-style: italic;
        }

        .footer {
            position: fixed;
            bottom: 10px;
            text-align: center;
            font-size: 10px;
            color: #999;
        }
    </style>
</head>

<body>

    {{-- HEADER --}}
    <div class="header">
        <h2>SOLICITUD DE COMPENSACIÓN</h2>
    </div>

    {{-- INFORMACIÓN --}}
    <div class="section">
        <div class="section-title">Información de la Solicitud</div>

        <div class="section-content">

            <div class="row">
                <div class="label">Nombre del Empleado:</div>
                <div class="value">
                    {{ $record->employee?->first_name }} {{ $record->employee?->last_name }}
                </div>
            </div>

            <div class="row">
                <div class="label">Departamento al que pertenece:</div>
                <div class="value">
                    {{ $record->employee?->department->name }}
                </div>
            </div>

            <div class="row">
                <div class="label">Cargo:</div>
                <div class="value">
                    {{ $record->employee?->user?->role_label }} 
                </div>
            </div>

            <div class="row">
                <div class="label">Fecha de Creación:</div>
                <div class="value">
                    {{ $record->date_creation ?? 'No disponible' }}
                </div>
            </div>

            <div class="row">
                <div class="label">Estado:</div>
                <div class="value">
                    {{ $record->status->getLabel() }}
                </div>
            </div>

            <div class="row">
                <div class="label">Fecha de Aprobación:</div>
                <div class="value">
                    {{ $record->approval_date ?? 'No disponible' }}
                </div>
            </div>

            <div class="row">
                <div class="label">Días a Compensar:</div>
                <div class="value">
                    {{ $record->days_to_compensate ?? '0' }}
                </div>
            </div>

            <div class="row">
                <div class="label">Total de Días Usados:</div>
                <div class="value">
                    {{ $record->total_days ?? '0' }}
                </div>
            </div>

        </div>
    </div>

    {{-- FECHAS --}}
    <div class="section">
        <div class="section-title">Fechas de Compensación</div>

        <div class="section-content">
            <div class="grid-2">

                <div class="box">
                    <div class="box-title">Fecha de Inicio</div>
                    <div>
                        {{ $record->start_date ?? 'No disponible' }}
                    </div>
                </div>

                <div class="box">
                    <div class="box-title">Fecha Final</div>
                    <div>
                        {{ $record->end_date ?? 'No disponible' }}
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- BALANCE --}}
    <div class="section">
        <div class="section-title">Información del Balance</div>

        <div class="section-content">

            <div class="row">
                <div class="label">Acumulado a Compensar:</div>
                <div class="value">
                    {{ $record->accrued_compensation ?? '0' }}
                </div>
            </div>

            <div class="row">
                <div class="label">Usado:</div>
                <div class="value">
                    {{ $record->used ?? '0' }}
                </div>
            </div>

            <div class="row">
                <div class="label">Dias de Compensación:</div>
                <div class="value">
                    {{ $record->total_compensation ?? '0' }}
                </div>
            </div>

        </div>
    </div>

    {{-- MOTIVO --}}
    <div class="section">
        <div class="section-title">Motivo de la Solicitud</div>

        <div class="section-content">
            <div class="{{ empty($record->comment) ? 'empty' : '' }}">
                {{ $record->comment ?? 'Sin comentario o motivo' }}
            </div>
        </div>
    </div>

    <div class="footer">
        Documento generado por el Empleado
    </div>
</body>

</html>
