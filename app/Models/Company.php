<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'amocrm_id',
        'name',
        'phone',
        'web',
        'address',
    ];

    const ATTRIBUTES_FOR_COMPARISON = [
        'name' => 'Имя (Компания)',
        'phone' => 'Телефон (Компания)',
        'web' => 'Web (Компания)',
        'address' => 'Адрес (Компания)',
        'email' => 'Email (Компания)',
    ];


}
