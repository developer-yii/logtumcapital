<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Loan extends Model
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
        'company_admin_id',
        'amount',
        'duration',
        'yearly_interest_rate',
        'loan_interest_rate',
        'weekly_interest_rate',
        'first_installment_date',
        'last_installment_date',
        'status',
        'ioweyou',
    ];

    // get loan status name
    public static function getLoanStatusName($status){
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
