// src/App.js
import React from 'react';
import '@fortawesome/fontawesome-free/css/all.min.css';
import { BrowserRouter as Router, Route, Routes, useLocation } from 'react-router-dom'; // Mover o Router para envolver a aplicação
import { AuthProvider } from './context/AuthContext'; // Importando o AuthProvider
import Sidebar from './components/Sidebar';
import HomePage from './pages/HomePage';
import UserPage from './pages/UserPage';
import EmailVerification from './pages/EmailVerification'; 
import ResendVerification from './pages/ResendVerification';
import Login from './pages/Login'; 
import Register from './pages/Register'; 
import Cronograma from './pages/Cronograma';
import CronogramaUsuarios from './pages/CronogramaUsuarios';
import Tarefas from './pages/Tarefas';
import GutPage from './pages/GutPage'; 

const AppContent = () => {
  const location = useLocation(); // Obtenha a rota atual

  // Rotas onde o Sidebar não deve aparecer
  const noSidebarRoutes = ['/login', '/register'];

  return (
    <>
      {/* Exibe o sidebar apenas se a rota atual NÃO estiver em noSidebarRoutes */}
      {!noSidebarRoutes.includes(location.pathname) && <Sidebar />}

      <Routes>
        <Route path="/" element={<HomePage />} />
        <Route path="/users" element={<UserPage />} />
        <Route path="/email-verification" element={<EmailVerification />} />
        <Route path="/resend-verification" element={<ResendVerification />} />
        <Route path="/login" element={<Login />} />
        <Route path="/register" element={<Register />} />
        <Route path="/cronograma" element={<Cronograma />} />
        <Route path="/cronograma-usuarios" element={<CronogramaUsuarios />} /> 
        <Route path="/tarefas" element={<Tarefas />} />
        <Route path="/GUT" element={<GutPage />} />
      </Routes>
    </>
  );
};

const App = () => {
  return (
    <AuthProvider>
      <Router>
        <AppContent />
      </Router>
    </AuthProvider>
  );
};

export default App;
