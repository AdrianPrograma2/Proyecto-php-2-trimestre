<?php


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
     * Mensaje
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

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Factura de Mantenimiento - Nosecaen S.L.',
        );
    }

    /**
     * mandar email
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