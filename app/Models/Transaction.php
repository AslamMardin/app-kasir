<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'invoice_number',
        'shift_id',
        'user_id',
        'member_id',
        'subtotal',
        'discount_total',
        'tax_total',
        'grand_total',
        'payment_method',
        'payment_amount',
        'change_amount',
        'status',
        'voided_by',
        'void_reason',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function shift()
    {
        return $this->belongsTo(Shift::class);
    }

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function items()
    {
        return $this->hasMany(TransactionItem::class);
    }

    public function voidedBy()
    {
        return $this->belongsTo(User::class, 'voided_by');
    }
}
