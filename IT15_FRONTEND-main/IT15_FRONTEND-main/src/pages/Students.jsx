import React from "react";
import { downloadCsv } from "../utils/exportCsv";

const studentSnapshot = [
  {
    label: "Freshmen Applicants",
    value: "1,240",
    note: "Awaiting document validation",
  },
  {
    label: "Transfers",
    value: "320",
    note: "Includes ladderized programs",
  },
  {
    label: "Returning Students",
    value: "3,260",
    note: "Auto-enrolled for next term",
  },
];

function Students() {
  const handleExportStudents = () => {
    downloadCsv("students-snapshot.csv", studentSnapshot);
  };

  return (
    <div className="page-shell">
      <section className="info-card">
        <div className="table-header">
          <div>
            <h2>Student Directory</h2>
          </div>
          <button className="ghost-btn small" type="button" onClick={handleExportStudents}>
            Export
          </button>
        </div>
        <div className="info-grid">
          {studentSnapshot.map((item) => (
            <div key={item.label} className="info-item">
              <p>{item.label}</p>
              <h3>{item.value}</h3>
              <span>{item.note}</span>
            </div>
          ))}
        </div>
      </section>
    </div>
  );
}

export default Students;
