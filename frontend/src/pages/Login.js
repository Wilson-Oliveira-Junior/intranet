import React, { useState } from 'react';
import '../css/Login.css'; // Certifique-se de que o caminho esteja correto
import axios from 'axios';
import { useNavigate } from 'react-router-dom';

const Login = () => {
    const [email, setEmail] = useState('');
    const [password, setPassword] = useState('');
    const [error, setError] = useState('');
    const navigate = useNavigate();

    const handleSubmit = async (e) => {
        e.preventDefault();
        try {
            const response = await axios.post('http://localhost:8000/api/login', { email, password });
            navigate('/dashboard'); // Redireciona após o login bem-sucedido
        } catch (err) {
            setError('Credenciais inválidas');
        }
    };

    return (
        <div className="login-container">
            <img src="/img/logo.png" alt="Logo" className="logo" />
            <h2>Bem-Vindo!</h2>
            <p>Seja Bem-vindo(a) à Intranet da Lógica Digital, um novo mundo apresentado a você!</p>

            <form onSubmit={handleSubmit}>
                {error && <div className="alert alert-danger">{error}</div>}
                
                <div className="form-group">
                    <input
                        id="email"
                        type="email"
                        placeholder="Email"
                        value={email}
                        onChange={(e) => setEmail(e.target.value)}
                        required
                    />
                </div>

                <div className="form-group">
                    <input
                        id="password"
                        type="password"
                        placeholder="Senha"
                        value={password}
                        onChange={(e) => setPassword(e.target.value)}
                        required
                    />
                </div>

                <div className="custom-control custom-checkbox">
                    <input className="custom-control-input" id="customCheckLogin" type="checkbox" />
                    <label className="custom-control-label" htmlFor="customCheckLogin">
                        <span className="text-muted">Lembre-me</span>
                    </label>
                </div>

                <div className="text-center">
                    <button type="submit">Entrar</button>
                </div>
            </form>

            <div className="row mt-3">
                <div className="col-6">
                    <a href="/forgot-password"><small>Esqueceu a senha?</small></a>
                </div>
            </div>

            <div className="footer">
                <p>© 2024 Todos os direitos reservados</p>
            </div>
        </div>
    );
};

export default Login;
