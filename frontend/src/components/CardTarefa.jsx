import React from 'react';
import '../css/components/CardTarefa.css';

const CardTarefa = ({ tarefa, onClick, abaAtiva, onReabrir }) => {
    const calcularDuracao = (dataCriada) => {
        const dataAbertura = new Date(dataCriada.split('/').reverse().join('-'));
        const dataAtual = new Date();
        const diferencaMs = dataAtual - dataAbertura;
        const diferencaDias = Math.floor(diferencaMs / (1000 * 60 * 60 * 24));
        return diferencaDias;
    };

    const tempoDesdeAbertura = calcularDuracao(tarefa.dataCriada);

    return (
        <div className="card-tarefa" onClick={onClick}>
            <h4>{tarefa.descricao}</h4>
            <p>Site: {tarefa.site}</p>
            <p>Data Criada: {tarefa.dataCriada}</p>
            <p>Status: {tarefa.status}</p>
            
            {abaAtiva === 'abertas' ? (
                <p className="tempo">Tempo desde abertura: {tempoDesdeAbertura} dias</p>
            ) : (
                <button className="reabrir-btn" onClick={(e) => { e.stopPropagation(); onReabrir(tarefa.id); }}>
                    Reabrir
                </button>
            )}
        </div>
    );
};

export default CardTarefa;
