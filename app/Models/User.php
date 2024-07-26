<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'company_id',
        'first_name',
        'middle_name',
        'last_name',
        'email',
        'phone_number',
        'address',
        'role',
        'password',
        'authorized_credit_limit',
        'ine',
        'proof_of_address'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // get user role name
    public static function getRoleName($role)
    {
        $userTypes = [
            1=>'Super Admin',
            2=>'Company Admin',
            3=>'Payment Collector',
            4=>'Employee'
        ];
        return $userTypes[$role];
    }

    // get access name
    // public static function getAccessName($role)
    // {
    //     $accessName =  [
    //         1=>'super_admin',
    //         2=>'company_admin',
    //         3=>'payment_collector',
    //         4=>'employee'
    //     ];

    //     return $accessName[$role];
    // }
}
