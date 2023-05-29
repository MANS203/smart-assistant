<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'room_num',
        'detils',
        'patient_department',
    ];
    public function user()
    {
        return $this->hasOne(User::class);
    }
}

