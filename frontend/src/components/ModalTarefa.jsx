import React, { useState, useEffect } from 'react';
import { Button, NavItem, NavLink, Nav, TabContent, TabPane, Input, FormGroup, Label, FormText } from 'reactstrap';
import '../css/components/modaltarefa.css';

const ModalTarefa = ({ tarefa, onClose, atualizarStatus }) => {
    const [activeTab, setActiveTab] = useState('comentario');
    const [newComment, setNewComment] = useState('');
    const [newAttachment, setNewAttachment] = useState('');
    const [elapsedTime, setElapsedTime] = useState(tarefa.totalTrabalhado || 0); // Inicialize com o tempo já trabalhado
    const [timer, setTimer] = useState(null);

    const formatTime = (timeInSeconds) => {
        const hours = Math.floor(timeInSeconds / 3600);
        const minutes = Math.floor((timeInSeconds % 3600) / 60);
        const seconds = timeInSeconds % 60;
        return `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
    };

    const handleAddComment = () => {
        if (newComment.trim() === '') {
            alert('O comentário não pode estar vazio.');
            return;
        }

        // Simular a adição de um comentário
        tarefa.comentarios = [...(tarefa.comentarios || []), newComment];
        setNewComment('');
    };

    const handleAddAttachment = (e) => {
        const file = e.target.files[0];

        if (!file) {
            alert('Nenhum arquivo selecionado.');
            return;
        }

        if (file.size > 5 * 1024 * 1024) {
            alert('O arquivo excede o limite de 5MB.');
            return;
        }

        // Simular a adição de um anexo
        alert('Arquivo anexado com sucesso.');
        setNewAttachment('');
    };

    const handleToggleTask = () => {
        if (tarefa.status === 'Abertas') {
            // Iniciar tarefa
            setTimer(setInterval(() => {
                setElapsedTime(prevTime => prevTime + 1);
            }, 1000));
            atualizarStatus(tarefa.id, 'em andamento'); // Atualiza status para "Em Andamento"
        } else if (tarefa.status === 'em andamento') {
            // Pausar tarefa
            clearInterval(timer);
            atualizarStatus(tarefa.id, 'pausada', elapsedTime); // Atualiza status para "Pausada"
            setTimer(null);
        }
    };

    const handleCompleteTask = () => {
        clearInterval(timer);
        atualizarStatus(tarefa.id, 'entregues', elapsedTime); // Atualiza status para "Entregues"
        setTimer(null);
    };

    useEffect(() => {
        return () => clearInterval(timer);
    }, [timer]);

    return (
        <div className="modal-overlay">
            <div className="modal">
                <div className="modal-header">
                    <div className="header-content">
                        <div className="user-image">
                            <img alt="User profile" className="avatar avatar-sm rounded-circle" src="https://placehold.co/50x50" />
                            <div className="task-title">
                                <h1 className="title">{tarefa.titulo}</h1>
                                <h2 className="subtitle">Criado por: {tarefa.criadoPor}</h2>
                            </div>
                        </div>
                        <div className="buttons">
                            <Button color="primary" onClick={handleCompleteTask}>
                                Entregar
                            </Button>
                            <Button color="success" onClick={handleToggleTask}>
                                {tarefa.status === 'Abertas' ? 'Começar' : 'Pausar'}
                            </Button>
                        </div>
                        <button onClick={onClose} className="modal-close">X</button>
                    </div>
                </div>

                <div className="container">
                    <div className="task-info">
                        <div className="left">
                            <div><span>Status:</span> {tarefa.status}</div>
                            <div><span>Tipo:</span> {tarefa.tipo}</div>
                            <div><span>Projeto:</span> {tarefa.projeto}</div>
                            <div><span>Tags:</span> {tarefa.tags || 'Nenhuma'}</div>
                        </div>
                        <div className="right">
                            <div><span>Esforço total:</span> {tarefa.esforcoTotal}</div>
                            <div><span>Total Trabalhado:</span> {formatTime(elapsedTime)}</div>
                        </div>
                    </div>
                </div>

                <div className="description">
                    <p>{tarefa.descricao}</p>
                </div>

                <div className="comments">
                    <Nav tabs>
                        <NavItem>
                            <NavLink
                                className={activeTab === 'comentario' ? 'active' : ''}
                                onClick={() => setActiveTab('comentario')}
                            >
                                Comentário
                            </NavLink>
                        </NavItem>
                        <NavItem>
                            <NavLink
                                className={activeTab === 'anexo' ? 'active' : ''}
                                onClick={() => setActiveTab('anexo')}
                            >
                                Anexo
                            </NavLink>
                        </NavItem>
                        <NavItem>
                            <NavLink
                                className={activeTab === 'seguidores' ? 'active' : ''}
                                onClick={() => setActiveTab('seguidores')}
                            >
                                Seguidores
                            </NavLink>
                        </NavItem>
                    </Nav>

                    <TabContent activeTab={activeTab} className="mt-3">
                        {activeTab === 'comentario' && (
                            <TabPane tabId="comentario">
                                <FormGroup>
                                    <Label for="exampleText">Adicionar um comentário</Label>
                                    <Input
                                        id="exampleText"
                                        name="text"
                                        type="textarea"
                                        value={newComment}
                                        onChange={(e) => setNewComment(e.target.value)}
                                        className="textarea-standard"
                                    />
                                </FormGroup>
                                <div className="text-right mt-2">
                                    <Button color="primary" onClick={handleAddComment}>
                                        <i className="fas fa-paper-plane mr-2"></i>Enviar
                                    </Button>
                                </div>
                                <div className="mt-3">
                                    <h5>Comentários:</h5>
                                    {tarefa.comentarios && tarefa.comentarios.length > 0 ? (
                                        tarefa.comentarios.map((comentario, index) => (
                                            <div key={index}>{comentario}</div>
                                        ))
                                    ) : (
                                        <p>Nenhum comentário disponível.</p>
                                    )}
                                </div>
                            </TabPane>
                        )}
                        {activeTab === 'anexo' && (
                            <TabPane tabId="anexo">
                                <FormGroup>
                                    <Label for="exampleFile">Anexo de Arquivo</Label>
                                    <Input
                                        id="exampleFile"
                                        name="file"
                                        type="file"
                                        onChange={handleAddAttachment}
                                        className="file-standard"
                                    />
                                    <FormText>
                                        Adicione arquivos de até 5MB. Formatos permitidos: .jpg, .png, .pdf.
                                    </FormText>
                                </FormGroup>
                            </TabPane>
                        )}
                        {activeTab === 'seguidores' && (
                            <TabPane tabId="seguidores">
                                <h5>Seguidores:</h5>
                                {tarefa.seguidores && tarefa.seguidores.length > 0 ? (
                                    tarefa.seguidores.map((seguidor, index) => <div key={index}>{seguidor}</div>)
                                ) : (
                                    <p>Nenhum seguidor nesta tarefa.</p>
                                )}
                            </TabPane>
                        )}
                    </TabContent>
                </div>
            </div>
        </div>
    );
};

export default ModalTarefa;
