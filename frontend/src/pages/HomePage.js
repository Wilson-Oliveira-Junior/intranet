import React, { useEffect, useState } from 'react';
import '../css/HomePage.css';
import Cardash from '../components/Cardash';
import Layout from '../components/Layout';

const Home = () => {
  const [data, setData] = useState({});

  useEffect(() => {
    const fetchData = async () => {
      try {
        const response = await fetch('http://127.0.0.1:8000/dashboard-data');
        if (!response.ok) {
          throw new Error(`HTTP error! status: ${response.status}`);
        }
        const result = await response.json();
        setData(result);
      } catch (error) {
        console.error('Error fetching data:', error);
      }
    };
    fetchData();
  }, []);

  return (
    <Layout>
      <div className="row">
        <div className="col-md-4">
          <Cardash title="ENTREGUES" value={data.quadro1} year={new Date().getFullYear()} icon="fa-check" />
        </div>
        <div className="col-md-4">
          <Cardash title="TAREFAS" value={data.quadro2} year={new Date().getFullYear()} icon="fa-tasks" />
        </div>
        <div className="col-md-4">
          <Cardash title="USUÁRIOS" value={data.quadro3} year={new Date().getFullYear()} icon="fa-users" />
        </div>
        <div className="col-md-4">
          <Cardash title="CLIENTES" value={data.quadro4} year={new Date().getFullYear()} icon="fa-child" />
        </div>
      </div>

      <div className="row">

        <div className="col-md-4">
          <div className="card">
            <h2>Minhas Pautas (0)</h2>
            <p>Sem registros.</p>
          </div>
        </div>
        
        <div className="col-md-4">
          <div className="card">
            <h2>Sugestões (0)</h2>
            <p>Sem registros.</p>
          </div>
        </div>
        <div className="col-md-4">
          <div className="card">
            <h2>Aniversariante do Mês</h2>
            <div className="profile">
              <img
                alt="Profile picture of Pedro Pavan"
                height="50"
                src="https://oaidalleapiprodscus.blob.core.windows.net/private/org-RcpoXHkzChYnDbFAyeQ8tamr/user-ehrvabJ3DufsCu8YJ7PqY5gl/img-M52z0QnaG6sJcPhw7H6PLtKs.png"
                width="50"
              />
              <p>Pedro Pavan</p>
            </div>
          </div>
        </div>
        <div className="row">
        <div className="col-md-6">
          <div className="card">
            <h2>Wiki Lógica Digital</h2>
            <p>
              O wiki da lógica digital foi desenvolvido com o intuito de expor as etapas de
              desenvolvimento de cada área, padrões a serem seguidos em determinados prosseguimentos,
              etapas e treinamento para quem é novo na lógica, e também temos algumas dúvidas frequentes
              dos clientes ou da própria equipe.
            </p>
            <h3>Wiki - Departamentos da Lógica Digital</h3>
            <ul>
              <li>Atendimento</li>
              <li>Comercial</li>
              <li>Desenvolvimento</li>
              <li>Criação</li>
              <li>Marketing</li>
              <li>RH</li>
            </ul>
            <button>Acessar Wiki</button>
          </div>
        </div>

        <div className="col-md-6">
          <div className="card">
            <h2>Ramais</h2>
            <div className="contact">
              <div><i className="fas fa-phone-alt" style={{ color: '#00c851' }}></i> Marcelo Abib - 7210</div>
              <div><i className="fas fa-phone-alt" style={{ color: '#33b5e5' }}></i> Rodrigo Camillo - 7211</div>
              <div><i className="fas fa-phone-alt" style={{ color: '#ff4444' }}></i> Letícia dos Santos Couto - 7201</div>
              <div><i className="fas fa-phone-alt" style={{ color: '#ff4444' }}></i> José Carvalho - 7202</div>
              <div><i className="fas fa-phone-alt" style={{ color: '#aa66cc' }}></i> Andre Magalhães - 7209</div>
            </div>
          </div>
        </div>
      </div>
      </div>
    </Layout>
  );
};

export default Home;
