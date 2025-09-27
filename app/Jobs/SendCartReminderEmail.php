<?php

namespace App\Jobs;

use App\Mail\CartReminderMail;
use App\Models\User;
use App\Models\CartItem;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendCartReminderEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $userId;

    public function __construct($userId)
    {
        $this->userId = $userId;
    }

    public function handle()
    {
        $user = User::find($this->userId);
        if (!$user) return;

        $cartItems = CartItem::with('book')->where('user_id', $user->id)->get();
        if ($cartItems->isEmpty()) return; // Não enviar se carrinho vazio

        Mail::to($user->email)->send(new CartReminderMail($user, $cartItems));
    }
}
