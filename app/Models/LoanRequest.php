<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LoanRequest extends Model
{
    use HasFactory, SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'company_id',
        'loan_id',
        'amount',
        'interest_rate',
        'duration',
        'bank_name',
        'account_number',
        'ioweyou',
        'status'
    ];

    // get loan request status name
    public static function getLoanRequestStatusName($status){
        $statuses = [
            1 => 'Pending',
            2 => 'Approved',
            3 => 'Rejected',
            4 => 'Disbursed',
            5 => 'Recovering',
            6 => 'Completed'
        ];
        return $statuses[$status];
    }
}
