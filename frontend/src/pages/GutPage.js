import React, { useEffect, useState } from 'react';
import { FaSyncAlt, FaCalendarAlt } from 'react-icons/fa';
import Layout from '../components/Layout';
import '../css/Gut.css';
import axios from 'axios';

const GutPage = () => {
  const [team, setTeam] = useState('Desenvolvimento');
  const [members, setMembers] = useState([]);
  const [selectedMember, setSelectedMember] = useState('');
  const [tasks, setTasks] = useState([]);

  const handleTeamChange = async (e) => {
    const selectedTeam = e.target.value;
    setTeam(selectedTeam);
    setSelectedMember(''); 
    await fetchTeamMembers(selectedTeam);
    handleUpdateList(); 
  };

  const fetchTeamMembers = async (team) => {
    
    const response = await fetch(`http://127.0.0.1:8000/members?team=${team}`);
    const data = await response.json();
    setMembers(data.membros);
  };

  const handleMemberChange = (e) => {
    setSelectedMember(e.target.value);
  };

  const handleUpdateList = async () => {
    try {
      const response = await fetch('http://127.0.0.1:8000/GUT');
      if (!response.ok) {
        throw new Error(`Erro na rede: ${response.statusText}`);
      }
      const data = await response.json();
      setTasks(data.tarefas);
    } catch (error) {
      console.error('Erro ao atualizar a lista:', error);
    }
  };

  useEffect(() => {
    handleUpdateList();
    fetchTeamMembers(team); 
  }, [team]);

  const filteredTasks = selectedMember
    ? tasks.filter(task => task.responsavel === selectedMember)
    : tasks;

  return (
    <Layout>
      <div className="min-h-screen bg-gray-100">
        <header className="header_gut">
          <h1 className="header-title">GUT</h1>
        </header>
        <div className="container">
          <div className="filter-section mb-4">
            <select 
              className="filter-select" 
              value={team} 
              onChange={handleTeamChange}
            >
              <option value="Desenvolvimento">Desenvolvimento</option>
              
            </select>
            <select 
              className="filter-select" 
              value={selectedMember} 
              onChange={handleMemberChange}
            >
              <option value="">Todos os Membros</option>
              {members.map(member => (
                <option key={member.id} value={member.nome}>{member.nome}</option>
              ))}
            </select>
            <button 
              className="update-button"
              onClick={handleUpdateList} 
            >
              Atualizar Listagem
            </button>
          </div>
          <table className="table min-w-full">
            <thead>
              <tr className="table-header">
                <th className="py-3 px-6 text-left">Tarefa</th>
                <th className="py-3 px-6 text-center">G</th>
                <th className="py-3 px-6 text-center">U</th>
                <th className="py-3 px-6 text-center">T</th>
                <th className="py-3 px-6 text-center">P</th>
                <th className="py-3 px-6 text-center">Data Tarefa</th>
                <th className="py-3 px-6 text-center">Data Desejada</th>
                <th className="py-3 px-6 text-center">Status</th>
                <th className="py-3 px-6 text-center">Responsável</th>
              </tr>
            </thead>
            <tbody className="table-body">
              {filteredTasks.map((item, index) => (
                <tr key={index} className="table-row">
                  <td className="table-cell">{index + 1} - {item.tarefa}</td>
                  <td className="table-cell text-center">
                    <select className="select-input" defaultValue={item.g}>
                      {[...Array(6).keys()].map(i => <option key={i} value={i}>{i}</option>)}
                    </select>
                  </td>
                  <td className="table-cell text-center">
                    <select className="select-input" defaultValue={item.u}>
                      {[...Array(6).keys()].map(i => <option key={i} value={i}>{i}</option>)}
                    </select>
                  </td>
                  <td className="table-cell text-center">
                    <select className="select-input" defaultValue={item.t}>
                      {[...Array(6).keys()].map(i => <option key={i} value={i}>{i}</option>)}
                    </select>
                  </td>
                  <td className="table-cell text-center">{item.p}</td>
                  <td className="table-cell text-center">{item.dataTarefa}</td>
                  <td className="table-cell text-center">
                    <div className="flex items-center justify-center">
                      <input 
                        type="text" 
                        className="date-input" 
                        value={item.dataDesejada} 
                        readOnly 
                      />
                      <FaSyncAlt className="icon" />
                      <FaCalendarAlt className="icon" />
                    </div>
                  </td>
                  <td className="table-cell text-center">{item.status}</td>
                  <td className="table-cell text-center">{item.responsavel}</td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      </div>
    </Layout>
  );
};

export default GutPage;
