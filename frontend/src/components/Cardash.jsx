import React from 'react';
import '../css/components/Cardash.css';

const Cardash = ({ title, value, year, icon }) => {
  return (
    <div className="card dash-card w-48 h-32 m-2">
      <div className="card-body">
        <div className="d-flex justify-content-between align-items-center">
          <div>
            <h5 className="card-title">{title}</h5>
            <h2 className="card-value">{value}</h2>
            <small className="card-year">{year}</small>
          </div>
          <div className="card-icon">
            <i className={`fas ${icon}`}></i>
          </div>
        </div>
      </div>
    </div>
  );
};

export default Cardash;
