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
import axios from 'axios'; 

const App = () => {
  useEffect(() => {
    const configureAxios = () => {
      axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
      const csrfMetaTag = document.querySelector('meta[name="csrf-token"]');
      const token = csrfMetaTag ? csrfMetaTag.getAttribute('content') : '';
      axios.defaults.headers.common['X-CSRF-TOKEN'] = token;
    };

    configureAxios();
  }, []);

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

  
  const noSidebarRoutes = ['/login', '/register'];

  return (
    <>
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

export default App;
