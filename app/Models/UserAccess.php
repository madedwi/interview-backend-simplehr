<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAccess extends Model
{
    use HasFactory;

    const ACTIVITY_LOGIN = 'login';

    const ACTIVITY_LOGOUT = 'logout';

    protected $fillable = ['user_id', 'activity'];
}
