<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tarefa extends Model
{
    use HasFactory;

    /**
     * Os atributos que podem ser preenchidos em massa.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'titulo',
        'descricao',
        'status', // Exemplo: pendente, em andamento, concluída
        'data_inicio',
        'data_fim',
        'cronograma_id', // Para associar a tarefa a um cronograma
        'user_id', // Adicionado para associar a tarefa a um usuário
    ];

    /**
     * Relacionamento com o modelo Cronograma.
     */
    public function cronograma()
    {
        return $this->belongsTo(Cronograma::class);
    }

    // Relacionamento com User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
