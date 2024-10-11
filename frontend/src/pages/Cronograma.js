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

  // Novos estados para o título da tarefa e descrição
  const [taskTitle, setTaskTitle] = useState('');
  const [taskDescription, setTaskDescription] = useState('');

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
    setTaskTitle(task ? task.cliente : ''); // Preenche o título com o cliente se a tarefa for selecionada
    setTaskDescription(task ? task.descricao : ''); // Preenche a descrição se a tarefa for selecionada
    setModalIsOpen(true);
  };

  const closeModal = () => {
    setModalIsOpen(false);
    setSelectedTask(null);
    setFiles([]);
    setReminderDate('');
    setPriority('');
    setFollowerId(null);
    setTaskTitle(''); // Limpa o título ao fechar o modal
    setTaskDescription(''); // Limpa a descrição ao fechar o modal
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

  const saveTaskDetails = async () => {
    const newTask = {
      cliente: taskTitle,
      descricao: taskDescription,
      files: files,
      followerId: followerId,
      priority: priority,
      reminderDate: reminderDate,
      categoria: priority || 'normal',
      data: reminderDate || new Date().toISOString().slice(0, 10),
    };

    try {
      const response = await axios.post('http://127.0.0.1:8000/cronograma', newTask);
      setCronogramas(prevCronogramas => [...prevCronogramas, response.data]); // Adiciona a nova tarefa ao estado
      console.log('Tarefa salva com os seguintes detalhes:', newTask);
    } catch (error) {
      console.error('Erro ao salvar a tarefa:', error);
    }

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
              value={taskTitle} // Vincula o estado
              onChange={(e) => setTaskTitle(e.target.value)} // Atualiza o estado
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
              value={taskDescription} // Vincula o estado
              onChange={(e) => setTaskDescription(e.target.value)} // Atualiza o estado
            />

            <input
              type="file"
              onChange={handleFileChange}
              multiple
            />

            <button onClick={() => openSecondModal('data')} className="modal-button">Selecionar Data</button>
            <button onClick={() => openSecondModal('seguidor')} className="modal-button">Adicionar Seguidor</button>
            <button onClick={saveTaskDetails} className="modal-button">Salvar</button>
            <button onClick={closeModal} className="modal-button">Fechar</button>
          </div>
        </Modal>

        <Modal
          isOpen={secondModalIsOpen}
          onRequestClose={closeSecondModal}
          ariaHideApp={false}
          ariaLabel="Modal Secundário"
          className="ReactModal__Content"
          overlayClassName="ReactModal__Overlay"
        >
          <div style={{ padding: '20px' }}>
            {secondModalIsOpen === 'data' && (
              <>
                <h2>Selecionar Data de Lembrete</h2>
                <input
                  type="date"
                  value={reminderDate}
                  onChange={handleReminderDateChange}
                />
                <button onClick={closeSecondModal} className="modal-button">Fechar</button>
              </>
            )}

            {secondModalIsOpen === 'seguidor' && (
              <>
                <h2>Adicionar Seguidor</h2>
                <select onChange={handleFollowerChange}>
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
