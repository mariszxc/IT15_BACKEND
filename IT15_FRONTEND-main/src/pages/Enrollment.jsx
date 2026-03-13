import React from "react";
import { downloadCsv } from "../utils/exportCsv";

const enrollmentPipeline = [
  { label: "Submitted", count: "1,482", note: "Awaiting initial review" },
  { label: "Validated", count: "1,204", note: "Credentials verified" },
  { label: "For Approval", count: "436", note: "Registrar queue" },
  { label: "Approved", count: "982", note: "Ready for enrollment" },
];

function Enrollment() {
  const handleExportEnrollment = () => {
    downloadCsv("enrollment-pipeline.csv", enrollmentPipeline);
  };

  return (
    <div className="page-shell">
      <section className="info-card">
        <div className="table-header">
          <div>
            <h2>Enrollment Status</h2>
          </div>
          <button className="ghost-btn small" type="button" onClick={handleExportEnrollment}>
            Export
          </button>
        </div>
        <div className="pipeline">
          {enrollmentPipeline.map((stage) => (
            <div key={stage.label} className="pipeline-card">
              <h3>{stage.count}</h3>
              <p>{stage.label}</p>
              <span>{stage.note}</span>
            </div>
          ))}
        </div>
      </section>
    </div>
  );
}

export default Enrollment;
