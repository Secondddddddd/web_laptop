<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ShipperActivatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $shipper;

    public function __construct($shipper)
    {
        $this->shipper = $shipper;
    }

    public function build()
    {
        return $this->subject('Thông báo kích hoạt tài khoản')
            ->view('emails.shipper_activated')
            ->with([
                'shipperName' => $this->shipper->name,
            ]);
    }
}
