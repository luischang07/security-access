<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

use App\Domain\Pedido;
use App\Domain\User\UserEntity;

class OrderReadyForPickupMail extends Mailable
{
  use Queueable, SerializesModels;

  public Pedido $pedido;
  public UserEntity $user;

  /**
   * Create a new message instance.
   */
  public function __construct(Pedido $pedido, UserEntity $user)
  {
    $this->pedido = $pedido;
    $this->user = $user;
  }

  /**
   * Get the message envelope.
   */
  public function envelope(): Envelope
  {
    return new Envelope(
      subject: __('emails.order_ready.subject'),
    );
  }

  /**
   * Get the message content definition.
   */
  public function content(): Content
  {
    return new Content(
      view: 'emails.order-ready',
    );
  }

  /**
   * Get the attachments for the message.
   *
   * @return array<int, \Illuminate\Mail\Mailables\Attachment>
   */
  public function attachments(): array
  {
    return [];
  }
}
