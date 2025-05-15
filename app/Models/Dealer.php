<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Dealer extends Model
{
    protected $table = 'dealer';
    use HasFactory;

    protected $fillable = [
        'id',
        'first_name',
        'middle_name',
        'last_name',
        'licence_no',
        'licence_exp',
        'omc_id',
        'email',
        'phone',
        'device',
        'is_online',
        'last_login',
        'userName',
        'userId',
        'userRole',
        'editedUserName',
        'editedUserId',
        'editedUserRole',
        'editedDevice',
        'editedTime',
        'status',
    ];

    public $timestamps = true;
}
