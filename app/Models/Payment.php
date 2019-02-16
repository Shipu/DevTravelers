<?php

namespace App\Models;

class Payment extends BaseModel
{
    public $timestamps = false;

    protected $fillable = [
        'event_id',
        'sender_id',
        'sender_type',
        'receiver_id',
        'receiver_type',
        'amount',
        'purpose',
        'channel',
        'status',
        'paid_at',
        'remarks',
        'transaction_id'
    ];

}
