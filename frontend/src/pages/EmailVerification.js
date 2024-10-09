import React from 'react';
import { useLocation, Link } from 'react-router-dom';

const EmailVerification = () => {
    const location = useLocation();
    const resent = location.state && location.state.resent; // Verifica se um novo link foi enviado

    return (
        <div className="container">
            <div className="row justify-content-center">
                <div className="col-md-8">
                    <div className="card">
                        <div className="card-header">Verifique seu endereço de e-mail</div>
                        <div className="card-body">
                            {resent && (
                                <div className="alert alert-success" role="alert">
                                    Um novo link de verificação foi enviado para o seu endereço de e-mail.
                                </div>
                            )}
                            <p>Antes de prosseguir, verifique seu e-mail para um link de verificação.</p>
                            <p>
                                Se você não recebeu o e-mail,{' '}
                                <Link to="/resend-verification">clique aqui para solicitar outro</Link>.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
};

export default EmailVerification;
