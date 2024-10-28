import React, { useState, useEffect } from 'react';
import axios from 'axios';
import Cookies from 'js-cookie'; 
import '../css/Register.css';
import { useAuth } from '../context/AuthContext';
import { useNavigate } from 'react-router-dom';

const Register = () => {
    const { login } = useAuth();
    const [name, setName] = useState('');
    const [email, setEmail] = useState('');
    const [password, setPassword] = useState('');
    const [passwordConfirmation, setPasswordConfirmation] = useState('');
    const [errors, setErrors] = useState({});
    const [successMessage, setSuccessMessage] = useState('');
    const [isSubmitting, setIsSubmitting] = useState(false);
    const navigate = useNavigate();

    const fetchCsrfToken = async () => {
        try {
            await axios.get('http://localhost:8000/sanctum/csrf-cookie'); 
        } catch (error) {
            console.error("Erro ao obter o CSRF token:", error);
        }
    };
    
    useEffect(() => {
        fetchCsrfToken();
    }, []);

    const handleSubmit = async (e) => {
        e.preventDefault();
        setErrors({});
        setSuccessMessage('');
        setIsSubmitting(true);
    
        if (password !== passwordConfirmation) {
            setErrors({ passwordConfirmation: ['As senhas não correspondem.'] });
            setIsSubmitting(false);
            return;
        }
    
        const csrfToken = Cookies.get('XSRF-TOKEN'); 
    
        try {
            const response = await axios.post('http://localhost:8000/register', { 
                name,
                email,
                password,
                password_confirmation: passwordConfirmation,
            }, {
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
                withCredentials: true,
            });

            // Mova o console.log aqui
            console.log(response.data);

            if (response.status === 201) {
                await login(email, password);
                setSuccessMessage('Cadastro realizado com sucesso! Redirecionando para a página de login...');
                setTimeout(() => {
                    navigate('/login');
                }, 2000);
            }
        } catch (error) {
            console.error('Erro ao registrar:', error);
            if (error.response) {
                console.error('Erro detalhado:', error.response.data); 
                if (error.response.status === 422) {
                    setErrors(error.response.data.errors);
                } else {
                    setErrors({ global: ['Ocorreu um erro inesperado. Tente novamente mais tarde.'] });
                }
            } else {
                setErrors({ global: ['Não foi possível conectar ao servidor.'] });
            }
        } finally {
            setIsSubmitting(false);
        }
    };
    
    return (
        <div className="register-container">
            <img src="/img/logo.png" alt="Logo" className="logo" />
            <h2>Bem-Vindo!</h2>
            <p>Seja Bem-vindo(a) à Intranet da Lógica Digital, um novo mundo apresentado a você!</p>

            <div className="form-container">
                {successMessage && <div className="alert alert-success">{successMessage}</div>}
                {errors.global && <div className="alert alert-danger">{errors.global[0]}</div>}

                <form onSubmit={handleSubmit}>
                    <div className="form-group">
                        <label htmlFor="name">Nome</label>
                        <input
                            id="name"
                            type="text"
                            className={`form-control ${errors.name ? 'is-invalid' : ''}`}
                            value={name}
                            onChange={(e) => setName(e.target.value)}
                            required
                        />
                        {errors.name && (
                            <span className="invalid-feedback">
                                <strong>{errors.name[0]}</strong>
                            </span>
                        )}
                    </div>

                    <div className="form-group">
                        <label htmlFor="email">E-Mail</label>
                        <input
                            id="email"
                            type="email"
                            className={`form-control ${errors.email ? 'is-invalid' : ''}`}
                            value={email}
                            onChange={(e) => setEmail(e.target.value)}
                            required
                        />
                        {errors.email && (
                            <span className="invalid-feedback">
                                <strong>{errors.email[0]}</strong>
                            </span>
                        )}
                    </div>

                    <div className="form-group">
                        <label htmlFor="password">Senha</label>
                        <input
                            id="password"
                            type="password"
                            className={`form-control ${errors.password ? 'is-invalid' : ''}`}
                            value={password}
                            onChange={(e) => setPassword(e.target.value)}
                            required
                        />
                        {errors.password && (
                            <span className="invalid-feedback">
                                <strong>{errors.password[0]}</strong>
                            </span>
                        )}
                    </div>

                    <div className="form-group">
                        <label htmlFor="password-confirm">Confirmar Senha</label>
                        <input
                            id="password-confirm"
                            type="password"
                            className={`form-control ${errors.passwordConfirmation ? 'is-invalid' : ''}`}
                            value={passwordConfirmation}
                            onChange={(e) => setPasswordConfirmation(e.target.value)}
                            required
                        />
                        {errors.passwordConfirmation && (
                            <span className="invalid-feedback">
                                <strong>{errors.passwordConfirmation[0]}</strong>
                            </span>
                        )}
                    </div>

                    <button type="submit" className="btn btn-primary" disabled={isSubmitting}>
                        {isSubmitting ? 'Registrando...' : 'Registrar'}
                    </button>
                </form>
            </div>
        </div>
    );
};

export default Register;
