// src/components/Layout.jsx
import React from 'react';
import Sidebar from './Sidebar';
import '../css/Layout.css';

const Layout = ({ children }) => {
  return (
    <div className="layout-container">
      <Sidebar />
      <div className="container">
        <div className="header d-flex justify-content-between align-items-center">
          <h1>DASHBOARD</h1>
          <div className="search-bar">
            <input placeholder="Buscar..." type="text" />
            <i className="fas fa-search"></i>
          </div>
        </div>
        <div className="content">{children}</div>
      </div>
    </div>
  );
};

export default Layout;
