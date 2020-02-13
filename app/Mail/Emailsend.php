<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Emailsend extends Mailable
{
    use Queueable, SerializesModels;
    public $order;
    public $pdf;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data, $pdf, $inv_name)
    {
        $this->order = $data;
        $this->pdf = $pdf;
        $this->inv_name = $inv_name;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Order Placed')
                ->view('invoice')
                ->attachData($this->pdf->output(), $this->inv_name.".pdf");
    }
}
