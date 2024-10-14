import React, { useState, useEffect } from 'react';
import Layout from '../components/Layout';
import CardTarefa from '../components/CardTarefa';
import ModalTarefa from '../components/ModalTarefa';
import '../css/Tarefas.css';

const Tarefas = () => {
    const [tarefas, setTarefas] = useState([]);
    const [tarefasSeguidas, setTarefasSeguidas] = useState([]); // Estado para tarefas seguidas
    const [abaAtiva, setAbaAtiva] = useState('abertas');
    const [tarefaSelecionada, setTarefaSelecionada] = useState(null);

    useEffect(() => {
        // Fetch de tarefas do backend
        fetch('http://127.0.0.1:8000/api/tarefas')
            .then(response => response.json())
            .then(data => {
                setTarefas(data);
                // Filtra tarefas seguidas (supondo que o usuário é ID 2)
                setTarefasSeguidas(data.filter(tarefa => tarefa.seguidoPor && tarefa.seguidoPor.includes(2)));
            })
            .catch(error => console.error('Erro ao buscar tarefas:', error));
    }, []);

    const atualizarStatus = (tarefaId, novoStatus, tempoTrabalhado = 0) => {
        setTarefas(prevTarefas =>
            prevTarefas.map(tarefa => {
                if (tarefa.id === tarefaId) {
                    const atualizada = { ...tarefa, status: novoStatus, totalTrabalhado: tempoTrabalhado };

                    if (tarefa.seguidoPor.includes(2)) {
                        setTarefasSeguidas(prev => prev.map(t => (t.id === tarefaId ? atualizada : t)));
                    }

                    return atualizada;
                }
                return tarefa;
            })
        );
    };

    const reabrirTarefa = (tarefaId) => {
        setTarefas(prevTarefas =>
            prevTarefas.map(tarefa =>
                tarefa.id === tarefaId ? { ...tarefa, status: 'abertas', totalTrabalhado: 0 } : tarefa
            )
        );
    };

    const handleCardClick = (tarefa) => {
        if (tarefa.status === 'entregues') {
            alert('Reabra a tarefa para visualizar os detalhes.');
        } else {
            setTarefaSelecionada(tarefa);
        }
    };

    return (
        <Layout>
            <div className="container-fluid">
                <nav className="navbar navbar-expand-lg navbar-light bg-light">
                    <div className="nav-item" onClick={() => setAbaAtiva('abertas')}>Para mim</div>
                    <div className="nav-item" onClick={() => setAbaAtiva('criei')}>Que Criei</div>
                    <div className="nav-item" onClick={() => setAbaAtiva('seguidas')}>Que eu sigo</div>
                    <div className="nav-item" onClick={() => setAbaAtiva('backlog')}>Backlog</div>
                </nav>
                <div className="content">
                    <h2 className="mt-3">
                        {abaAtiva === 'abertas' ? 'Tarefas Abertas' : abaAtiva === 'seguidas' ? 'Tarefas Seguidas' : abaAtiva === 'criei' ? 'Tarefas Criadas' : 'Backlog'}
                    </h2>
                    <div className="row">
                        {abaAtiva === 'backlog'
                            ? tarefas.filter(tarefa => tarefa.status === 'backlog').map(tarefa => (
                                <div key={tarefa.id} className="col-md-12 mb-3">
                                    <CardTarefa
                                        tarefa={tarefa}
                                        onClick={() => handleCardClick(tarefa)}
                                        abaAtiva={abaAtiva}
                                        onReabrir={reabrirTarefa}
                                    />
                                </div>
                            ))
                            : abaAtiva === 'seguidas'
                                ? tarefasSeguidas.map(tarefa => (
                                    <div key={tarefa.id} className="col-md-12 mb-3">
                                        <CardTarefa
                                            tarefa={tarefa}
                                            onClick={() => handleCardClick(tarefa)}
                                            abaAtiva={abaAtiva}
                                            onReabrir={reabrirTarefa}
                                        />
                                    </div>
                                ))
                                : abaAtiva === 'criei'
                                    ? tarefas.filter(tarefa => tarefa.criadorId === 2).map(tarefa => (  // Aqui filtra as tarefas que você criou
                                        <div key={tarefa.id} className="col-md-12 mb-3">
                                            <CardTarefa
                                                tarefa={tarefa}
                                                onClick={() => handleCardClick(tarefa)}
                                                abaAtiva={abaAtiva}
                                                onReabrir={reabrirTarefa}
                                            />
                                        </div>
                                    ))
                                    : tarefas
                                        .filter(tarefa => tarefa.status.toLowerCase() === abaAtiva || (tarefa.status.toLowerCase() === 'em andamento' && abaAtiva === 'abertas'))
                                        .map(tarefa => (
                                            <div key={tarefa.id} className="col-md-12 mb-3">
                                                <CardTarefa
                                                    tarefa={tarefa}
                                                    onClick={() => handleCardClick(tarefa)}
                                                    abaAtiva={abaAtiva}
                                                    onReabrir={reabrirTarefa}
                                                />
                                            </div>
                                        ))}
                    </div>
                </div>

            </div>

            {tarefaSelecionada && (
                <ModalTarefa
                    tarefa={tarefaSelecionada}
                    onClose={() => {
                        setTarefaSelecionada(null);
                    }}
                    atualizarStatus={atualizarStatus}
                />
            )}
        </Layout>
    );
};

export default Tarefas;
