<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Storage extends Model
{
    use HasFactory;

    public const
        STORAGE_SERGIEV_POSAD = 1,
        STORAGE_NIZHNIY_NOVGOROD = 2,
        STORAGE_ORDER = 3;
}
