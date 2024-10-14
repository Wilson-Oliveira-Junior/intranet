import React from 'react';
import '@fortawesome/fontawesome-free/css/all.min.css';
import '../css/components/sidebar.css'; 

const Sidebar = () => {
  return (
    <div className="sidebar">
      <img
        alt="Lógica Digital Logo"
        height="50"
        src="/img/logo.png"
        width="120"
      />
      <a href="http://localhost:3000">
        <i className="fas fa-tv"></i>
        Dashboard
      </a>
      <a href="http://localhost:3000/cronograma">
        <i className="fas fa-calendar-alt"></i>
        Cronograma Equipes
      </a>
      <a href="http://localhost:3000/tarefas">
        <i className="fas fa-tasks"></i>
        Tarefas
      </a>
      <a href="http://localhost:3000/GUT">
        <i className="fas fa-sort-amount-up"></i>
        GUT - Priorização
      </a>
      <a href="#">
        <i className="fas fa-list-alt"></i>
        Pautas
      </a>
      <a href="#">
        <i className="fas fa-user"></i>
        Meu espaço
        <i className="fas fa-chevron-right" style={{ marginLeft: 'auto' }}></i>
      </a>
      <a href="#">
        <i className="fas fa-clipboard"></i>
        Relatórios
        <i className="fas fa-chevron-right" style={{ marginLeft: 'auto' }}></i>
      </a>
      <a href="#">
        <i className="fas fa-tasks"></i>
        Gestão
        <i className="fas fa-chevron-right" style={{ marginLeft: 'auto' }}></i>
      </a>
      <div className="section-title">REDES SOCIAIS</div>
      <a href="https://www.facebook.com/agencialogicadigital/">
        <i className="fab fa-facebook"></i>
        Facebook
      </a>
      <a href="https://www.instagram.com/agencialogicadigital/">
        <i className="fab fa-instagram"></i>
        Instagram
      </a>
      <a href="https://www.youtube.com/channel/UCibYLnhb7tT6febvhlZtMXg">
        <i className="fab fa-youtube"></i>
        Youtube
      </a>
      <a href="https://www.linkedin.com/company/l-gica-digital/mycompany/">
        <i className="fab fa-linkedin"></i>
        Linkedin
      </a>
      <a href="https://br.pinterest.com/logicadigital">
        <i className="fab fa-pinterest"></i>
        Pinterest
      </a>
    </div>
  );
};

export default Sidebar;
