import React from "react";
import ProgramCard from "./ProgramCard";

function ProgramList({ programs, selectedCode, onSelect }) {
  return (
    <section className="table-card">
      <div className="table-header">
        <div>
          <p className="chart-title">Program Lists</p>
        </div>
      </div>

      <div className="program-grid">
        {programs.map((program) => (
          <ProgramCard
            key={program.code}
            program={program}
            selected={selectedCode === program.code}
            onClick={onSelect}
          />
        ))}
      </div>

      {programs.length === 0 ? (
        <p className="empty-state">No programs match the selected filters.</p>
      ) : null}
    </section>
  );
}

export default ProgramList;