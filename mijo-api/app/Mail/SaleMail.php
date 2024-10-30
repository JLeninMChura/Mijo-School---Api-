<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SaleMail extends Mailable
{
    use Queueable, SerializesModels;

   /**
     * Create a new message instance.
     *
     * @return void
     */
    public $sale;
    public function __construct($sale)
    {
        $this->sale = $sale;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $sale = $this->sale;
        return $this->view('email.sale',compact("sale"));
    }
}
