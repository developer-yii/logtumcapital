<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LoanInstallment extends Model
{
    use HasFactory, SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'loan_id',
        'loan_collection_id',
        'installment_date',
        'capital',
        'interest',
        'payment',
        'balance',
        'status'
    ];

    // get loan installment status name
    public static function getLoanInstallmentStatusName($status){
        $statuses = [0 => 'Pending', 1 => 'Paid'];
        return $statuses[$status];
    }
}
