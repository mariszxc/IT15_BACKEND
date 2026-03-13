import React from "react";

function formatDependency(values) {
  if (!values || values.length === 0) {
    return "none";
  }

  return values.join(", ");
}

function SubjectDetails({ subject }) {
  if (!subject) {
    return (
      <section className="table-card">
        <p className="empty-state">Select a subject to view details.</p>
      </section>
    );
  }

  return (
    <section className="table-card">
      <div className="table-header">
        <div>
          <p className="chart-title">Subject Details</p>
          <span className="chart-subtitle">
            {subject.code} - {subject.title}
          </span>
        </div>
      </div>

      <div className="details-grid">
        <div className="info-item">
          <strong>Units</strong>
          <span>{subject.units}</span>
        </div>
        <div className="info-item">
          <strong>Semester/Term Offered</strong>
          <span>{subject.semesterTerm}</span>
        </div>
        <div className="info-item">
          <strong>Program Assignment</strong>
          <span>{subject.programCode}</span>
        </div>
        <div className="info-item">
          <strong>Pre-requisites</strong>
          <span>{formatDependency(subject.prerequisites)}</span>
        </div>
        <div className="info-item">
          <strong>Co-requisites</strong>
          <span>{formatDependency(subject.corequisites)}</span>
        </div>
      </div>

      <div className="details-description">
        <strong>Description</strong>
        <p>{subject.description}</p>
      </div>
    </section>
  );
}

export default SubjectDetails;