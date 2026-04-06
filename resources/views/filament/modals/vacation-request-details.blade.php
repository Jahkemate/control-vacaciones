<div class="space-y-4">

    <div>
        <h2 class="text-lg font-bold">Información del Empleado</h2>
        <p><strong>Nombre:</strong> {{ $user->name }}</p>
    </div>

    <div>
        <h2 class="text-lg font-bold">Detalles de la Solicitud</h2>
        <p><strong>Fecha Inicio:</strong> {{ $request->start_date }}</p>
        <p><strong>Fecha Fin:</strong> {{ $request->end_date }}</p>
        <p><strong>Días:</strong> {{ $request->total_business_days }}</p>
        <p><strong>Motivo:</strong> {{ $request->comment }}</p>
    </div>

    <div>
        <h2 class="text-lg font-bold">Estado</h2>
        <p>
            <span
                class="
                px-2 py-1 rounded text-white
                {{ $request->status === \App\States\RequestStatus::Approved ? 'bg-green-500' : 'bg-red-500' }}
            ">
                {{ $request->status->getLabel() }}
            </span>
            @if ($request->status === \App\States\RequestStatus::Rejected && $request->observation)
                <div>
                    <h2 class="text-lg font-bold text-red-600">Motivo de Rechazo</h2>
                    <p class="bg-red-50 border border-red-200 p-3 rounded">
                        {{ $request->comment }}
                    </p>
                </div>
            @endif
        </p>
    </div>

</div>
