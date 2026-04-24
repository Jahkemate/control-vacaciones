@php
    $record = $this->getRecord();
    $logs = $record?->logs()->with('user')->latest()->get() ?? collect();
@endphp
<div style="overflow-x-auto">
    <table style="w-full text-sm border rounded-xl overflow-hidden; width:100%">
        <thead style="bg-gray-100 dark:bg-gray-800">
            <tr>
                <th style="padding:6px; border:1px solid ;text-align:center;">Usuario</th>
                <th style="padding:6px; border:1px solid ;text-align:center;">Nombre</th>
                <th style="padding:6px; border:1px solid ;text-align:center;">Estado</th>
                <th style="padding:6px; border:1px solid ;text-align:center;">Comentario</th>
                <th style="padding:6px; border:1px solid ;text-align:center;">Fecha</th>
            </tr>
        </thead>
        <tbody>

            @forelse ($logs as $log)
                <tr style="border-t">
                    <td style="padding:6px; border:1px solid; text-align:center">
                        {{ $log->user?->name ?? '—' }}
                    </td>

                    <td style="padding:6px; border:1px solid; text-align:center">
                        {{ $log->user?->employee->full_name ?? '—' }}
                    </td>

                    <td style="padding:6px; border:1px solid; text-align:center">
                        {{ $log->status?->getLabel() ?? $log->status }}
                    </td>

                    <td style="padding:6px; border:1px solid; text-align:center">
                        {{ $log->comment }}
                    </td>

                    <td style="padding:6px; border:1px solid; text-align:center">
                        {{ $log->created_at?->format('d/m/Y H:i') }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="padding:6px;border:1px solid #d9d9d9; font-size:20px; text-align:center;">
                        No hay historial aún
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
