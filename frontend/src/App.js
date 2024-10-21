import React, { useEffect } from 'react';
import '@fortawesome/fontawesome-free/css/all.min.css';
import { BrowserRouter as Router, Route, Routes, useLocation } from 'react-router-dom'; 
import { AuthProvider } from './context/AuthContext'; 
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
import authVerify from './api/token';
import { useDispatch } from 'react-redux';
import { useNavigate } from 'react-router-dom';
import axios from 'axios'; 

function App() {
  const dispatch = useDispatch();
  const navigate = useNavigate();
  useEffect(() => {
    authVerify(dispatch, logout, navigate);
  }, [navigate, dispatch]);

  return (
    <AuthProvider>
      <Router>
        <AppContent />
      </Router>
    </AuthProvider>
  );
};

const AppContent = () => {
  const location = useLocation(); 

  // Definir rotas que não mostram a sidebar
  const noSidebarRoutes = ['/login', '/register'];

  return (
    <>
      {/* Renderiza a sidebar se a rota não estiver em noSidebarRoutes */}
      {!noSidebarRoutes.includes(location.pathname) && <Sidebar />}

      {/* Definição de rotas do React */}
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

export default App;
