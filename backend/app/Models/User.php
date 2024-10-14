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
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'team', // Adicione o campo de equipe aqui
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // Defina relacionamentos, se necessário
    // Exemplo:
    // public function tasks()
    // {
    //     return $this->hasMany(Task::class);
    // }
}
