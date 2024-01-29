<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    protected $fillable = [
        'amocrm_id',
        'phone',
        'position',
        'name',
        'email',
        'company_id',
        'responsible_user_id'
    ];

    const ATTRIBUTES_FOR_COMPARISON = [
        'name' => 'Имя (Контакт)',
        'phone' => 'Телефон (Контакт)',
        'position' => 'Должность (Контакт)',
        'email' => 'Почта (Контакт)',
        'responsible_user_id' => 'ID Ответственного'
    ];
}
