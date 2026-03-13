import React from "react";

function ProgramCard({ program, selected, onClick }) {
  return (
    <button
      className={`program-card ${selected ? "selected" : ""}`}
      type="button"
      onClick={() => onClick(program)}
    >
      <div className="program-card-header">
        <h3>{program.code}</h3>
        <span className={`status-pill ${program.status.replace(/\s+/g, "-")}`}>
          {program.status}
        </span>
      </div>
      <p className="program-name">{program.fullName}</p>
      <div className="program-meta">
        <span>{program.type}</span>
        <span>{program.duration}</span>
        <span>{program.totalUnits} units</span>
      </div>
    </button>
  );
}

export default ProgramCard;