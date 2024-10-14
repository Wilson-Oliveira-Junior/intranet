import React, { createContext, useState, useEffect, useContext } from 'react';
import axios from 'axios';

// Criação do contexto
export const AuthContext = createContext();

// Hook para usar o contexto
export const useAuth = () => {
  return useContext(AuthContext);
};

export const AuthProvider = ({ children }) => {
  const [user, setUser] = useState(null);
  const [loading, setLoading] = useState(true); // Estado de carregamento

  // Configuração da URL base do axios
  axios.defaults.baseURL = 'http://localhost:8000'; // Altere para a URL do seu backend

  // Função de login
  const login = async (email, password) => {
    setLoading(true);
    try {
      const response = await axios.post('/api/login', { email, password });
      if (response.data) {
        setUser(response.data.user); // Armazena o usuário no estado
        localStorage.setItem('user', JSON.stringify(response.data.user)); // Armazena no localStorage
      }
    } catch (error) {
      console.error('Erro ao fazer login', error);
    } finally {
      setLoading(false);
    }
  };

  // Função de logout
  const logout = async () => {
    setLoading(true);
    try {
      await axios.post('/api/logout');
      setUser(null); // Limpa o estado do usuário
      localStorage.removeItem('user'); // Remove o usuário do localStorage
    } catch (error) {
      console.error('Erro ao fazer logout', error);
    } finally {
      setLoading(false);
    }
  };

  // Carregar o usuário do localStorage ao iniciar o aplicativo
  useEffect(() => {
    const storedUser = localStorage.getItem('user');
    if (storedUser) {
      setUser(JSON.parse(storedUser));
    }
    setLoading(false); // Carregamento concluído
  }, []);

  return (
    <AuthContext.Provider value={{ user, login, logout, loading }}>
      {children}
    </AuthContext.Provider>
  );
};
