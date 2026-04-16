@extends('welcome')
@section('title', 'Detalle Pago')

@section('content')

<div class="d-flex justify-content-between mb-4">
    <h4>Pago {{ $pago->periodo }}</h4>
        <a href="{{ route('pagos.index', [
        'contrato_id' => $pago->contrato_id,
        'estado' => request('estado', 'activos')
    ]) }}" class="btn btn-outline-secondary">
        Volver
    </a>
</div>

{{-- 🔥 ABONOS --}}
<div class="card mt-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <strong>Abonos</strong>

       @if($pago->estado !== 'pagado' && $pago->contrato && !$pago->contrato->trashed())
            <a href="{{ route('abonos.create', $pago->id) }}" class="btn btn-sm btn-primary">
                <i class="bi bi-plus"></i> Nuevo Abono
            </a>
        @endif
    </div>

    <div class="card-body p-0">

        <table class="table mb-0">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Monto</th>
                    <th>Método</th>
                    <th>Acciones</th>
                </tr>
            </thead>

            <tbody>
                @forelse($pago->abonos as $abono)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($abono->fecha_abono)->format('d/m/Y h:i A') }}</td>
                    <td>L {{ number_format($abono->monto,2) }}</td>
                    <td>{{ ucfirst($abono->metodo) }}</td>
                    <td>
                        <a href="{{ route('abonos.show', $abono) }}" class="btn btn-sm btn-outline-info">
                            <i class="bi bi-eye"></i> Ver
                        </a>
                        <a href="{{ route('recibos.ver.abono', $abono->id) }}" class="btn btn-sm btn-success">
                            📄
                        </a>
                    </td>
                </tr>
                
                @empty
                <tr>
                    <td colspan="3" class="text-center text-muted">
                        No hay abonos
                    </td>
                </tr>
                
                @endforelse
                
            </tbody>

        </table>

    </div>
</div>

@endsection