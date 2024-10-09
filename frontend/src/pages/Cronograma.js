import React, { useEffect, useState } from 'react';
import Layout from '../components/Layout';
import '../css/cronograma.css';
import axios from 'axios';
import Modal from 'react-modal';

Modal.setAppElement('#root');

const Cronograma = () => {
  const [usuario, setUsuario] = useState({ name: '', sobrenome: '', id: null });
  const [cronogramas, setCronogramas] = useState([]);
  const [equipes] = useState(['Atendimento', 'Criação', 'Desenvolvimento', 'Marketing', 'Comercial', 'Administrativo']);
  const [selectedEquipe, setSelectedEquipe] = useState('Atendimento');
  const [modalIsOpen, setModalIsOpen] = useState(false);
  const [secondModalIsOpen, setSecondModalIsOpen] = useState(false);
  const [selectedTask, setSelectedTask] = useState(null);
  const [indicatorPosition, setIndicatorPosition] = useState(0);

  const [files, setFiles] = useState([]);
  const [reminderDate, setReminderDate] = useState('');
  const [priority, setPriority] = useState('');
  const [followerId, setFollowerId] = useState(null);
  const [users, setUsers] = useState([]);

  useEffect(() => {
    const fetchUserData = async () => {
      const userData = { name: 'Wilson', sobrenome: 'Silva', id: 1 };
      setUsuario(userData);
    };

    const fetchCronogramas = async () => {
      try {
        const response = await axios.get(`http://127.0.0.1:8000/cronograma?equipe=${selectedEquipe}`);
        setCronogramas(response.data || []);
      } catch (error) {
        console.error('Erro ao buscar cronogramas:', error);
        setCronogramas([]);
      }
    };

    const fetchUsers = async () => {
      try {
        const response = await axios.get('http://127.0.0.1:8000/users');
        setUsers(response.data || []);
      } catch (error) {
        console.error('Erro ao buscar usuários:', error);
      }
    };

    fetchUserData();
    fetchCronogramas();
    fetchUsers();
  }, [selectedEquipe]);

  const handleTabChange = (equipe, index) => {
    setSelectedEquipe(equipe);
    setIndicatorPosition(index * 130);
  };

  const renderCalendar = () => {
    const today = new Date();
    const currentYear = today.getFullYear();
    const currentMonth = today.getMonth();
    const daysInMonth = new Date(currentYear, currentMonth + 1, 0).getDate();
    const firstDay = new Date(currentYear, currentMonth, 1).getDay();

    let weeks = [];
    let days = [];

    for (let i = 0; i < firstDay; i++) {
      days.push(<div key={`empty-${i}`} className="calendar-day empty" />);
    }

    for (let day = 1; day <= daysInMonth; day++) {
      const tasksForDay = cronogramas.filter(task => new Date(task.data).getDate() === day);
      days.push(
        <div key={day} className="calendar-day">
          <div className="day-number">{day}</div>
          {tasksForDay.map((task, index) => (
            <div
              key={index}
              className={`task ${task.categoria}`}
              onClick={() => openModal(task)}
            >
              {task.cliente}
            </div>
          ))}
        </div>
      );

      if (days.length === 7 || day === daysInMonth) {
        weeks.push(
          <div key={weeks.length} className="calendar-week">
            {days}
          </div>
        );
        days = [];
      }
    }

    return weeks;
  };

  const openModal = (task) => {
    setSelectedTask(task);
    setModalIsOpen(true);
  };

  const closeModal = () => {
    setModalIsOpen(false);
    setSelectedTask(null);
  };

  const openSecondModal = (modalType) => {
    setSecondModalIsOpen(modalType);
  };

  const closeSecondModal = () => {
    setSecondModalIsOpen(false);
  };

  const handleFileChange = (e) => {
    setFiles(Array.from(e.target.files));
  };

  const handleReminderDateChange = (e) => {
    setReminderDate(e.target.value);
  };

  const handlePriorityChange = (e) => {
    setPriority(e.target.value);
  };

  const handleFollowerChange = (e) => {
    setFollowerId(e.target.value);
  };

  const saveTaskDetails = () => {
    console.log('Tarefa salva com os seguintes detalhes:', {
      reminderDate,
      priority,
      followerId,
      files,
    });
    closeModal();
  };

  return (
    <Layout>
      <div className="calendar-container">
        <h1>Calendário de Tarefas</h1>
        <div className="tab-container">
          {equipes.map((equipe, index) => (
            <div key={index} className="tab">
              <input
                type="radio"
                name="tab"
                id={`tab${index}`}
                className="tab_input"
                checked={selectedEquipe === equipe}
                onChange={() => handleTabChange(equipe, index)}
              />
              <label className="tab_label" htmlFor={`tab${index}`}>
                {equipe}
              </label>
            </div>
          ))}
          <div className="indicator" style={{ left: `${indicatorPosition + 2}px` }} />
        </div>

        <div className="header-container">
          <button className="add-task-button" onClick={() => openModal(null)}>Adicionar Tarefa</button>
          <div className="legenda-container">
            <div className="legenda-item">
              <span className="legenda-task normal" /> Normal
            </div>
            <div className="legenda-item">
              <span className="legenda-task atencao" /> Atenção
            </div>
            <div className="legenda-item">
              <span className="legenda-task urgente" /> Urgente
            </div>
          </div>
        </div>

        <div>{renderCalendar()}</div>

        <Modal
          isOpen={modalIsOpen}
          onRequestClose={closeModal}
          ariaHideApp={false}
          ariaLabel="Modal de Tarefa"
          className="ReactModal__Content"
          overlayClassName="ReactModal__Overlay"
        >
          <div style={{ padding: '20px' }}>
            {selectedTask ? (
              <h2>{selectedTask.cliente}</h2>
            ) : (
              <h2>Adicionar Tarefa</h2>
            )}

            <input
              type="text"
              placeholder="Título da Tarefa (obrigatório)"
              className="modal-input"
              required
            />

            <select className="modal-input">
              <option value="">Selecione um usuário ou equipe</option>
            </select>

            <select className="modal-input" required>
              <option value="">Selecione o tipo</option>
            </select>

            <input
              type="text"
              placeholder="Nome do Cliente/Empresa"
              className="modal-input"
            />

            <textarea
              placeholder="Descrição da tarefa"
              className="modal-input"
            />

            <div className="modal-button-container">
              <button className="modal-button" onClick={() => openSecondModal('anexar')}>Anexar</button>
              <button className="modal-button" onClick={() => openSecondModal('data')}>Selecionar Data</button>
              <button className="modal-button" onClick={() => openSecondModal('seguidor')}>Adicionar Seguidor</button>
            </div>

            <button onClick={saveTaskDetails} className="modal-button">Salvar</button>
            <button onClick={closeModal} className="modal-button">Fechar</button>
          </div>
        </Modal>

        <Modal
          isOpen={secondModalIsOpen !== false}
          onRequestClose={closeSecondModal}
          className="ReactModal__Content"
          overlayClassName="ReactModal__Overlay"
        >
          <div style={{ padding: '20px' }}>
            {secondModalIsOpen === 'anexar' && (
              <>
                <h2>Anexar Arquivo</h2>
                <input
                  type="file"
                  multiple
                  onChange={handleFileChange}
                />
                <button onClick={closeSecondModal} className="modal-button">Fechar</button>
              </>
            )}

            {secondModalIsOpen === 'data' && (
              <>
                <h2>Selecionar Data</h2>
                <input
                  type="date"
                  className="modal-input"
                  onChange={handleReminderDateChange}
                />
                <select onChange={handlePriorityChange} className="modal-input">
                  <option value="">Selecione a prioridade</option>
                  <option value="alta">Alta</option>
                  <option value="media">Média</option>
                  <option value="baixa">Baixa</option>
                </select>
                <button onClick={closeSecondModal} className="modal-button">Fechar</button>
              </>
            )}

            {secondModalIsOpen === 'seguidor' && (
              <>
                <h2>Adicionar Seguidor</h2>
                <select onChange={handleFollowerChange} className="modal-input">
                  <option value="">Selecione um seguidor</option>
                  {users.map(user => (
                    <option key={user.id} value={user.id}>{user.name}</option>
                  ))}
                </select>
                <button onClick={closeSecondModal} className="modal-button">Fechar</button>
              </>
            )}
          </div>
        </Modal>
      </div>
    </Layout>
  );
};

export default Cronograma;
