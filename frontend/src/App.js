// src/App.js
import React from 'react';
import { BrowserRouter as Router, Route, Routes, useLocation } from 'react-router-dom'; 
import { Provider } from 'react-redux'; // Importando o Provider
import store from './store'; // Importando o store
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
import { logout } from './actions/authActions'; 

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

const AuthCheck = () => {
  const dispatch = useDispatch();
  const navigate = useNavigate();

  React.useEffect(() => {
    authVerify(dispatch, logout, navigate);
  }, [navigate, dispatch]);

  return null; 
};

function App() {
  return (
    <AuthProvider>
      <Provider store={store}> 
        <Router>
          <AuthCheck /> 
          <AppContent />
        </Router>
      </Provider>
    </AuthProvider>
  );
}

export default App;
