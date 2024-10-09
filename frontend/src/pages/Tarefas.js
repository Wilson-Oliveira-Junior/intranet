import React, { useState, useEffect } from 'react';
import Layout from '../components/Layout';
import CardTarefa from '../components/CardTarefa';
import ModalTarefa from '../components/ModalTarefa'; 
import '../css/Tarefas.css';

const Tarefas = () => {
    const [tarefas, setTarefas] = useState([]);
    const [abaAtiva, setAbaAtiva] = useState('abertas');
    const [tarefaSelecionada, setTarefaSelecionada] = useState(null);

    useEffect(() => {
        setTarefas([
            {
                id: 31383,
                titulo: '[Imparpec] migração para servidor novo do projeto www.pecaempilhadeira.com.br',
                site: 'imparpec.com.br',
                projeto: 'Migração de Servidor',
                dataCriada: '03/10/2024',
                status: 'Abertas',
                detalhes: 'Isso é um teste que não está aparecendo',
                criadoPor: 'Usuário Teste',
                tipo: 'Tipo de Tarefa',
                tags: 'Tag Exemplo',
                esforcoTotal: '02:00',
                totalTrabalhado: '01:00',
                descricao: 'Isso é a descrição da tarefa que você verá no modal.',
            },
            {
                id: 31384,
                titulo: '[Imparpec] migração para servidor novo do projeto www.pecaempilhadeira.com.br',
                site: 'imparpec.com.br',
                projeto: 'Migração de Servidor',
                dataCriada: '03/10/2024',
                status: 'Entregues',
                detalhes: 'Detalhes da tarefa...',
                criadoPor: 'Usuário Teste 2',
                tipo: 'Tipo de Tarefa 2',
                tags: 'Nenhuma',
                esforcoTotal: '01:00',
                totalTrabalhado: '00:30',
                descricao: 'Essa tarefa já foi entregue e você pode reabri-la.',
            },
        ]);
    }, []);

    const reabrirTarefa = (tarefaId) => {
        console.log("Reabrindo tarefa com ID:", tarefaId);
        setTarefas(prevTarefas =>
            prevTarefas.map(tarefa =>
                tarefa.id === tarefaId ? { ...tarefa, status: 'Abertas' } : tarefa
            )
        );
    };

    return (
        <Layout>
            <div className="container-fluid">
                <nav className="navbar navbar-expand-lg navbar-light bg-light">
                    <div className="nav-item">Para mim</div>
                    <div className="nav-item">Que Criei</div>
                    <div className="nav-item">Que eu sigo</div>
                    <div className="nav-item">Backlog</div>
                </nav>
                <div className="content">
                    <div className="buttons">
                        <button
                            className={`abertas-btn ${abaAtiva === 'abertas' ? 'active' : ''}`}
                            onClick={() => setAbaAtiva('abertas')}
                        >
                            Abertas
                        </button>
                        <button
                            className={`entregues-btn ${abaAtiva === 'entregues' ? 'active' : ''}`}
                            onClick={() => setAbaAtiva('entregues')}
                        >
                            Entregues
                        </button>
                    </div>

                    <h2 className="mt-3">{abaAtiva === 'abertas' ? 'Tarefas Abertas' : 'Tarefas Entregues'}</h2>
                    <div className="row">
                        {tarefas.map(tarefa => (
                            <div key={tarefa.id} className="col-md-12 mb-3">
                                <CardTarefa
                                    tarefa={tarefa}
                                    onClick={() => {
                                        console.log("Card clicado:", tarefa);
                                        setTarefaSelecionada(tarefa);
                                    }}
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
                />
            )}
        </Layout>
    );
};

export default Tarefas;
