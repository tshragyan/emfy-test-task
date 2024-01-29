<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    use HasFactory;

    protected $fillable = [
        'amocrm_id',
        'name',
        'price',
        'company_id',
        'status_id',
        'responsible_user_id',
    ];

    const ATTRIBUTES_FOR_COMPARISON = [
        'name' => 'Имя (Сделка)',
        'price' => 'Цена (Сделка)',
        'status_id' => 'Статус (Сделка)',
        'responsible_user_id' => 'ID Ответственного (Сделка)'
    ];

}
