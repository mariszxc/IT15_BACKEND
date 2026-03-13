import React, { useState } from "react";

function Settings() {
  const [savedAt, setSavedAt] = useState("");

  const handleSaveSettings = () => {
    setSavedAt(
      new Date().toLocaleTimeString("en-US", {
        hour: "numeric",
        minute: "2-digit",
      })
    );
  };

  return (
    <div className="page-shell">
      <section className="info-card">
 
        {savedAt && <span className="chart-subtitle settings-saved">Saved at {savedAt}</span>}
        <div className="settings-grid">
          <div className="settings-item">
            <p>Academic year</p>
            <h3>2025 - 2026</h3>
            <span>Default intake schedule</span>
          </div>
          <div className="settings-item">
            <p>Notifications</p>
            <h3>Enabled</h3>
            <span>Email and SMS alerts</span>
          </div>
          <div className="settings-item">
            <p>API status</p>
            <h3>Ready</h3>
            <span>Awaiting Laravel endpoint</span>
          </div>
        </div>
        <div className="settings-actions">
          <button className="primary-btn" type="button" onClick={handleSaveSettings}>
            Save
          </button>
        </div>
      </section>
    </div>
  );
}

export default Settings;
