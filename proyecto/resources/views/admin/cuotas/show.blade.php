@extends('layouts.app')

@section('title', 'Ver Cuota')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2><i class="fas fa-file-invoice me-2"></i>Detalles de la Cuota</h2>
                <div class="btn-group">
                    <a href="{{ route('admin.cuotas.edit', $cuota->id) }}" class="btn btn-warning">
                        <i class="fas fa-edit me-2"></i>Editar
                    </a>
                    <a href="{{ route('admin.cuotas.factura', $cuota->id) }}" class="btn btn-danger">
                        <i class="fas fa-file-pdf me-2"></i>Descargar PDF
                    </a>
                    <a href="{{ route('admin.cuotas.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Volver
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Información de la Cuota</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Cliente:</strong>
                            <p class="mb-0">{{ $cuota->cliente->nombre }}</p>
                        </div>
                        <div class="col-md-6">
                            <strong>CIF Cliente:</strong>
                            <p class="mb-0">{{ $cuota->cliente->cif }}</p>
                        </div>
                    </div>

                    <hr>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Concepto:</strong>
                            <p class="mb-0">{{ $cuota->concepto }}</p>
                        </div>
                        <div class="col-md-6">
                            <strong>Nº Cuota:</strong>
                            <p class="mb-0">{{ $cuota->id }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <strong>Fecha Emisión:</strong>
                            <p class="mb-0">{{ $cuota->fecha_emision->format('d/m/Y') }}</p>
                        </div>
                        <div class="col-md-4">
                            <strong>Importe:</strong>
                            <p class="mb-0">{{ number_format($cuota->importe, 2) }} €</p>
                        </div>
                        <div class="col-md-4">
                            <strong>Estado:</strong>
                            <p class="mb-0">
                                @if($cuota->pagada === 'S')
                                    <span class="badge bg-success"><i class="fas fa-check"></i> Pagada</span>
                                @else
                                    <span class="badge bg-danger"><i class="fas fa-clock"></i> Pendiente</span>
                                @endif
                            </p>
                        </div>
                    </div>

                    @if($cuota->fecha_pago)
                        <div class="mb-3">
                            <strong>Fecha de Pago:</strong>
                            <p class="mb-0">{{ $cuota->fecha_pago->format('d/m/Y') }}</p>
                        </div>
                    @endif

                    @if($cuota->notas)
                        <div class="mb-3">
                            <strong>Notas:</strong>
                            <p class="mb-0">{{ $cuota->notas }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">Acciones Rápidas</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.cuotas.factura', $cuota->id) }}" class="btn btn-danger">
                            <i class="fas fa-file-pdf me-2"></i>Descargar Factura
                        </a>
                        <form action="{{ route('admin.cuotas.enviar-email', $cuota->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-success w-100">
                                <i class="fas fa-envelope me-2"></i>Enviar por Email
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection