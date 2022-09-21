<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * Class Customer
 * @package App\Models
 * @property $id
 * @property $name
 * @property $since
 * @property $revenue
 */
class Customer extends Authenticatable
{
    use SoftDeletes, HasFactory, Notifiable, HasApiTokens;

    protected $table = 'customers';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'since', 'revenue', 'email', 'password'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];
    protected $dates = [
        'created_at', 'updated_at', 'deleted_at', 'since'
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
