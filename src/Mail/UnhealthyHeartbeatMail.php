<?php

namespace MortenScheel\Heartbeat\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class UnhealthyHeartbeatMail extends Mailable
{
    use Queueable, SerializesModels;

    /** @var \Illuminate\Support\Collection */
    protected $heartbeats;

    /**
     * Create a new message instance.
     * @return void
     */
    public function __construct(Collection $heartbeats)
    {
        $this->heartbeats = $heartbeats;
    }

    /**
     * Build the message.
     * @return $this
     */
    public function build()
    {
        return $this->markdown('heartbeat::unhealthy_heartbeat_mail')
            ->with('heartbeats', $this->heartbeats)
            ->subject('Unhealthy heartbeat warning');
    }
}
