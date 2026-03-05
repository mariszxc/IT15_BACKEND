import React from "react";
import SubjectCard from "./SubjectCard";

function SubjectList({ subjects, selectedCode, onSelect }) {
  return (
    <section className="table-card">
      <div className="table-header">
        <div>
          <p className="chart-title">Subject Lists</p>
        </div>
      </div>

      <div className="program-grid">
        {subjects.map((subject) => (
          <SubjectCard
            key={subject.code}
            subject={subject}
            selected={selectedCode === subject.code}
            onClick={onSelect}
          />
        ))}
      </div>

      {subjects.length === 0 ? (
        <p className="empty-state">No subjects match the selected filters.</p>
      ) : null}
    </section>
  );
}

export default SubjectList;