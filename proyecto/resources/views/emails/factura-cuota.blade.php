@component('mail::message')
# Factura de Mantenimiento - Nosecaen S.L.

Estimado/a {{ $cuota->cliente->nombre ?? 'Cliente' }},

Adjuntamos la factura correspondiente al concepto: **{{ $cuota->concepto }}**

## Detalles de la factura:

- **Número de factura:** {{ $cuota->id }}-{{ now()->format('Y') }}
- **Fecha de emisión:** {{ $cuota->fecha_emision->format('d/m/Y') }}
- **Importe:** {{ number_format($cuota->importe, 2) }} {{ $cuota->cliente->moneda ?? 'EUR' }}
- **Estado:** {{ $cuota->pagada === 'S' ? 'Pagada' : 'Pendiente de pago' }}

Puede encontrar el PDF de la factura adjunto a este correo.

Si tiene cualquier duda o consulta, no dude en ponerse en contacto con nosotros.

Gracias por confiar en Nosecaen S.L.

Atentamente,<br>
El equipo de Nosecaen S.L.
@endcomponent