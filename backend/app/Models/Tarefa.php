<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tarefa extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
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
    ];

    /**
     * Relacionamento com o modelo Cronograma.
     */
    public function cronograma()
    {
        return $this->belongsTo(Cronograma::class);
    }
}
