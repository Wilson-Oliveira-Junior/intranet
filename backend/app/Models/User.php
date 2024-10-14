<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail; // Ative se for usar verificação de e-mail
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable // Pode implementar MustVerifyEmail se necessário
{
    use HasFactory, Notifiable;

    /**
     * Os atributos que podem ser preenchidos em massa.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'time_id', // Renomeei para time_id para seguir a convenção
    ];

    /**
     * Os atributos que devem ser ocultados durante a serialização.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Os atributos que devem ser convertidos.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // Relacionamento com Time
    public function time()
    {
        return $this->belongsTo(Time::class);
    }

    // Relacionamento com Tarefa
    public function tarefas()
    {
        return $this->hasMany(Tarefa::class);
    }
}
