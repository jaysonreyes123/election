<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Field extends Model
{
    use HasFactory;

    public function tab_()
    {
        return $this->hasOne(Tab::class, 'id', 'tabid');
    }
}
