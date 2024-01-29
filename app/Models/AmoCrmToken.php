<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AmoCrmToken extends Model
{
    use HasFactory;

    protected $table = 'amocrm_tokens';

    protected $fillable = ['token'];

}
