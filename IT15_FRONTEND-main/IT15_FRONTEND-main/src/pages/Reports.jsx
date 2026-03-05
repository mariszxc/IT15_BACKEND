import React from "react";
import { downloadCsv } from "../utils/exportCsv";

const reportQueue = [
  {
    title: "Enrollment Forecast",
    owner: "Planning Office",
    status: "Approved",
    updated: "Feb 18, 2026",
  },
  {
    title: "Slot Utilization",
    owner: "Registrar",
    status: "Pending",
    updated: "Feb 17, 2026",
  },
  {
    title: "Payment Compliance",
    owner: "Finance",
    status: "For Review",
    updated: "Feb 16, 2026",
  },
];

function Reports() {
  const handleExportReports = () => {
    downloadCsv("reports-center.csv", reportQueue);
  };

  return (
    <div className="page-shell">
      <section className="table-card">
        <div className="table-header">
          <div>
            <p className="chart-title">Reports Center</p>
          </div>
          <button className="ghost-btn small" type="button" onClick={handleExportReports}>
            Export
          </button>
        </div>
        <div className="table-body">
          <table>
            <thead>
              <tr>
                <th>Report</th>
                <th>Owner</th>
                <th>Status</th>
                <th>Updated</th>
              </tr>
            </thead>
            <tbody>
              {reportQueue.map((report) => {
                const statusClass = report.status.toLowerCase().replace(/\s+/g, "-");
                return (
                  <tr key={report.title}>
                    <td>{report.title}</td>
                    <td>{report.owner}</td>
                    <td>
                      <span className={`status-pill ${statusClass}`}>{report.status}</span>
                    </td>
                    <td>{report.updated}</td>
                  </tr>
                );
              })}
            </tbody>
          </table>
        </div>
      </section>
    </div>
  );
}

export default Reports;
