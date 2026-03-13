import React from "react";

const offeringLabels = {
  semester: "Per Semester",
  term: "Per Term",
  both: "Both",
};

function SubjectCard({ subject, selected, onClick }) {
  return (
    <button
      className={`subject-card ${selected ? "selected" : ""}`}
      type="button"
      onClick={() => onClick(subject)}
    >
      <div className="program-card-header">
        <h3>{subject.code}</h3>
        <span className={`offering-badge ${subject.offeringMode}`}>
          {offeringLabels[subject.offeringMode]}
        </span>
      </div>
      <p className="program-name">{subject.title}</p>
      <div className="program-meta">
        <span>{subject.units} units</span>
        <span>{subject.semesterTerm}</span>
        <span>{subject.programCode}</span>
      </div>
    </button>
  );
}

export default SubjectCard;