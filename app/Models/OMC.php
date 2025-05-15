<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OMC extends Model
{
    protected $table = 'omc';
    use HasFactory;

    protected $fillable = [
        'id',
        'omcName',
        'address',
        'email',
        'phone',
        'licence_no',
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
