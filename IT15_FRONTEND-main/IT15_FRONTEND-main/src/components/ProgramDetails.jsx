import React from "react";
import { getSubjectsForProgramYear } from "../data/catalogData";

function ProgramDetails({ program }) {
  if (!program) {
    return (
      <section className="table-card">
        <p className="empty-state">Select a program to view details.</p>
      </section>
    );
  }

  const yearLevels = Object.keys(program.yearLevels || {});

  return (
    <section className="table-card">
      <div className="table-header">
        <div>
          <p className="chart-title">Program Details</p>
          <span className="chart-subtitle">{program.fullName}</span>
        </div>
      </div>

      <div className="details-grid">
        <div className="info-item">
          <strong>Program Code</strong>
          <span>{program.code}</span>
        </div>
        <div className="info-item">
          <strong>Program Type</strong>
          <span>{program.type}</span>
        </div>
        <div className="info-item">
          <strong>Duration</strong>
          <span>{program.duration}</span>
        </div>
        <div className="info-item">
          <strong>Total Units</strong>
          <span>{program.totalUnits}</span>
        </div>
      </div>

      <div className="details-description">
        <strong>Description</strong>
        <p>{program.description}</p>
      </div>

      <div className="year-level-list">
        {yearLevels.map((yearLevel) => {
          const yearSubjects = getSubjectsForProgramYear(program, yearLevel);

          return (
            <article key={yearLevel} className="year-level-card">
              <h4>{yearLevel}</h4>
              <ul>
                {yearSubjects.map((subject) => (
                  <li key={`${yearLevel}-${subject.code}`}>
                    <span>{subject.code}</span>
                    <span>{subject.title}</span>
                    <span>{subject.units} units</span>
                  </li>
                ))}
              </ul>
            </article>
          );
        })}
      </div>
    </section>
  );
}

export default ProgramDetails;