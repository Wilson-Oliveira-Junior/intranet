import React from 'react';
import '@fortawesome/fontawesome-free/css/all.min.css';
import '../css/components/sidebar.css'; 
import { Link } from 'react-router-dom'; // Use Link para navegação interna
import { useAuth } from '../context/AuthContext'; // Certifique-se que o contexto está implementado

const Sidebar = () => {
  const { user } = useAuth();
  return (
    <div className="sidebar">
      <img
        alt="Lógica Digital Logo"
        height="50"
        src="/img/logo.png"
        width="120"
      />
      {user && user.is_admin && (
        <Link to="/admin">
          <i className="fas fa-user-shield" aria-label="Admin"></i>
          Admin
        </Link>
      )}
      <Link to="/">
        <i className="fas fa-tv" aria-label="Dashboard"></i>
        Dashboard
      </Link>
      <Link to="/cronograma">
        <i className="fas fa-calendar-alt" aria-label="Cronograma Equipes"></i>
        Cronograma Equipes
      </Link>
      <Link to="/tarefas">
        <i className="fas fa-tasks" aria-label="Tarefas"></i>
        Tarefas
      </Link>
      <Link to="/GUT">
        <i className="fas fa-sort-amount-up" aria-label="GUT - Priorização"></i>
        GUT - Priorização
      </Link>
      <a href="#">
        <i className="fas fa-list-alt" aria-label="Pautas"></i>
        Pautas
      </a>
      <a href="#">
        <i className="fas fa-user" aria-label="Meu espaço"></i>
        Meu espaço
        <i className="fas fa-chevron-right" style={{ marginLeft: 'auto' }}></i>
      </a>
      <a href="#">
        <i className="fas fa-clipboard" aria-label="Relatórios"></i>
        Relatórios
        <i className="fas fa-chevron-right" style={{ marginLeft: 'auto' }}></i>
      </a>
      <a href="#">
        <i className="fas fa-tasks" aria-label="Gestão"></i>
        Gestão
        <i className="fas fa-chevron-right" style={{ marginLeft: 'auto' }}></i>
      </a>
      <div className="section-title">REDES SOCIAIS</div>
      <a href="https://www.facebook.com/agencialogicadigital/">
        <i className="fab fa-facebook" aria-label="Facebook"></i>
        Facebook
      </a>
      <a href="https://www.instagram.com/agencialogicadigital/">
        <i className="fab fa-instagram" aria-label="Instagram"></i>
        Instagram
      </a>
      <a href="https://www.youtube.com/channel/UCibYLnhb7tT6febvhlZtMXg">
        <i className="fab fa-youtube" aria-label="YouTube"></i>
        YouTube
      </a>
      <a href="https://www.linkedin.com/company/l-gica-digital/mycompany/">
        <i className="fab fa-linkedin" aria-label="LinkedIn"></i>
        LinkedIn
      </a>
      <a href="https://br.pinterest.com/logicadigital">
        <i className="fab fa-pinterest" aria-label="Pinterest"></i>
        Pinterest
      </a>
    </div>
  );
};

export default Sidebar;
