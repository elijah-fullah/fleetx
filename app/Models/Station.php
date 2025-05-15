<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Station extends Model
{
    protected $table = 'station';
    use HasFactory;

    protected $fillable = [
        'id',
        'stationName',
        'address',
        'email',
        'phone',
        'longitude',
        'latitude',
        'district',
        'chiefdom',
        'dealer_id',
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
