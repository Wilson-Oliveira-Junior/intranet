import React, { useEffect, useState } from 'react';
import { Link } from 'react-router-dom';
import Layout from '../components/Layout'; // Importando o layout

const CronogramaUsuarios = () => {
  const [usuarios, setUsuarios] = useState([]);

  useEffect(() => {
    // Aqui você faz a chamada para sua API que retorna os usuários.
    // Substitua a URL '/api/usuarios' pela URL real da sua API em Laravel.
    fetch('/api/usuarios')
      .then((response) => response.json())
      .then((data) => setUsuarios(data))
      .catch((error) => console.error('Erro ao buscar usuários:', error));
  }, []);

  return (
    <Layout>
      <div className="col">
        <div className="card shadow">
          {/* Cabeçalho da Tabela */}
          <div className="card-header border-0">
            <h3 className="mb-0" style={{ float: 'left' }}>Cronograma Usuários</h3>
          </div>
          
          {/* Tabela de Usuários */}
          <div className="table-responsive">
            <table className="table align-items-center">
              <thead className="thead-light">
                <tr>
                  <th scope="col">#</th>
                  <th scope="col">Nome</th>
                  <th scope="col">E-mail</th>
                  <th scope="col">Setor</th>
                  <th scope="col">Ação</th>
                </tr>
              </thead>
              <tbody>
                {usuarios.map((registro) => (
                  <tr key={registro.id_usuario}>
                    <td>{registro.id_usuario}</td>
                    <td>{registro.nome_usuario}</td>
                    <td>{registro.email_usuario}</td>
                    <td>{registro.nome_setor_usuario}</td>
                    <td>
                      <Link to={`/cronograma/${registro.id_usuario}`} className={`btn btn-warning ${registro.classes}`}>
                        Cronograma
                      </Link>
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </Layout>
  );
};

export default CronogramaUsuarios;
