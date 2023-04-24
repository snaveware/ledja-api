<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Notifications\Notifiable;
use Illuminate\Notifications\Notification;


class Transaction extends Model
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'user_id',
        'wallet_id',
        'type',
        'amount',
        'trx_ref',
        'payment_mode',
        'trx_otp',
        'mobile_no',
        'trx_status'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function wallet(): BelongsTo
    {
        return $this->belongsTo(Wallet::class);
    }

    public function routeNotificationForVonage(Notification $notification): string
    {
        if($this->mobile_no == null)
        {
            return '254729472867';
        }
        return $this->mobile_no;

    }

    public function routeNotificationForMail(Notification $notification): array|string
    {
        $user = \Auth::user();
        // Return email address only...
        return $user->email;
    }


}
