import React from 'react';
import '@fortawesome/fontawesome-free/css/all.min.css';
import { BrowserRouter as Router, Route, Routes } from 'react-router-dom';
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

const App = () => {
  return (
    <Router>
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
        <Route path="/GUT" element={<GutPage />} /> {/* Certifique-se de que esta rota está definida */}
      </Routes>
    </Router>
  );
};

export default App;
