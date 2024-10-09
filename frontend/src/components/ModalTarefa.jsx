import React, { useState } from 'react';
import { Button, NavItem, NavLink, Nav, TabContent, TabPane, Input } from 'reactstrap';
import '../css/components/modaltarefa.css';

const ModalTarefa = ({ tarefa, onClose }) => {
    const [activeTab, setActiveTab] = useState('comentario');

    return (
        <div className="modal-overlay">
            <div className="modal">
                <div className="modal-header">
                    <div className="header-content">
                        <div className="user-image">
                            <img alt="User profile" className="avatar avatar-sm rounded-circle" src="https://placehold.co/50x50" />
                            <div className="task-title">
                                <h1 className="title">{tarefa.titulo}</h1>
                                <h2 className="subtitle">Criado por: {tarefa.criador}</h2>
                            </div>
                        </div>
                        <div className="buttons">
                            <Button color="primary" onClick={() => console.log("Entregar tarefa", tarefa.id)}>
                                Entregar
                            </Button>
                            <Button color="success" onClick={() => console.log("Começar tarefa", tarefa.id)}>
                                Começar
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
                            <div><span>Total Trabalhado:</span> {tarefa.totalTrabalhado}</div>
                        </div>
                    </div>
                </div>

                <div className="description">
                    <p>{tarefa.descricao}</p>
                </div>

                <div className="comments">
                    <Nav tabs>
                        <NavItem>
                            <NavLink className={activeTab === 'comentario' ? 'active' : ''} onClick={() => setActiveTab('comentario')}>
                                Comentário
                            </NavLink>
                        </NavItem>
                        <NavItem>
                            <NavLink className={activeTab === 'anexo' ? 'active' : ''} onClick={() => setActiveTab('anexo')}>
                                Anexo
                            </NavLink>
                        </NavItem>
                        <NavItem>
                            <NavLink className={activeTab === 'seguidores' ? 'active' : ''} onClick={() => setActiveTab('seguidores')}>
                                Seguidores
                            </NavLink>
                        </NavItem>
                    </Nav>

                    <TabContent activeTab={activeTab} className="mt-3">
                        <TabPane tabId="comentario">
                            <Input type="textarea" placeholder="Escreva o comentário da tarefa..." />
                            <div className="text-right mt-2">
                                <Button color="primary">
                                    <i className="fas fa-paper-plane mr-2"></i>Enviar
                                </Button>
                            </div>
                        </TabPane>
                        <TabPane tabId="anexo">
                            {tarefa.anexos && tarefa.anexos.length > 0 ? (
                                tarefa.anexos.map((anexo, index) => <div key={index}>{anexo}</div>)
                            ) : (
                                <p>Nenhum anexo disponível.</p>
                            )}
                        </TabPane>
                        <TabPane tabId="seguidores">
                            {tarefa.seguidores && tarefa.seguidores.length > 0 ? (
                                tarefa.seguidores.map((seguidor, index) => <div key={index}>{seguidor}</div>)
                            ) : (
                                <p>Nenhum seguidor nesta tarefa.</p>
                            )}
                        </TabPane>
                    </TabContent>
                </div>
            </div>
        </div>
    );
};

export default ModalTarefa;
