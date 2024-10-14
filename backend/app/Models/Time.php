<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Time extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome', // Adicione o campo de nome do time aqui
    ];

    // Relacionamento com User
    public function users()
    {
        return $this->hasMany(User::class);
    }
}
