<div style="display:flex; flex-direction:column; gap:16px;">

    <div style="background:#0a71d85e; border:1px solid #ddd; padding:12px; border-radius:8px;">
        <h2>Informacion del Empleado</h2>
        <strong>Empleado:</strong> {{ $request->employee->full_name }}
    </div>

    <div style="border:1px solid #ddd; padding:12px; border-radius:8px;">
        <h2>Detalles de la Solicitud</h2>
        <strong>Fecha de Inicio:</strong> {{ $request->start_date }} <br>
        <strong>Fecha de Fin:</strong> {{ $request->end_date }} <br>
        <strong>Días Totales:</strong> {{ $request->total_business_days }}
    </div>

    @if ($request->status === \App\States\RequestStatus::Rejected)

        @php
            $rejectionComment = $request->commentsAdditional()
                ->where('type_comment', 'rejection')
                ->latest()
                ->first();
        @endphp

        @if ($request->comment)
            <div style="background:#088b5938; border-left:5px solid #22c55e; padding:10px; border-radius:6px;">
                <strong>Comentario del empleado</strong>
                <p>{{ $request->comment }}</p>
            </div>
        @endif

        @if ($rejectionComment)
            <div style="background:#8b080838; border-left:5px solid #ef4444; padding:10px; border-radius:6px;">
                <strong>Motivo de rechazo</strong>
                <p>{{ $rejectionComment->additional_comment }}</p>
            </div>
        @endif

    @endif

</div>