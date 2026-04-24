<?php

/**
 * Mailable FacturaCuotaMail
 * 
 * Envía la factura en PDF al cliente cuando se crea una cuota.
 * Punto 1.8 del PDF - Enviar factura por correo automáticamente.
 * 
 * @author Tu Nombre
 * @version 1.0
 * @since 2026-01-24
 * @category Mail
 */

namespace App\Mail;

use App\Models\Cuota;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class FacturaCuotaMail extends Mailable
{
    use Queueable, SerializesModels;

    public $cuota;
    public $pdf;

    /**
     * Create a new message instance.
     */
    public function __construct(Cuota $cuota)
    {
        $this->cuota = $cuota;
        
        // Aseguramos que el cliente esté cargado
        if (!$cuota->relationLoaded('cliente')) {
            $cuota->load('cliente');
        }
        
        // Generar el PDF solo si tenemos cliente
        if ($cuota->cliente) {
            $pdf = Pdf::loadView('cuotas.factura', compact('cuota'));
            $this->pdf = $pdf;
        }
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Factura de Mantenimiento - Nosecaen S.L.',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.factura-cuota',
            with: [
                'cuota' => $this->cuota,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        // Solo adjuntar si el PDF fue generado
        if ($this->pdf) {
            return [
                \Illuminate\Mail\Mailables\Attachment::fromData(
                    fn () => $this->pdf->output(),
                    'factura_'.$this->cuota->id.'.pdf'
                )->withMime('application/pdf'),
            ];
        }
        
        return [];
    }
}