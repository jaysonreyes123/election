<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPrivilege extends Model
{
    use HasFactory;

    public function tabs_()
    {
        return $this->hasOne(Tab::class, 'id', 'tabid');
    }
}
