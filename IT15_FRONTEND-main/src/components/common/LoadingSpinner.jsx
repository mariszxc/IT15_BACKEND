import React from "react";

function LoadingSpinner({ text = "Loading...", skeleton = false }) {
  if (skeleton) {
    return (
      <div className="d-flex flex-column gap-2">
        <div className="skeleton" style={{ height: 24 }} />
        <div className="skeleton" style={{ height: 160 }} />
        <div className="skeleton" style={{ height: 60 }} />
      </div>
    );
  }

  return (
    <div className="d-flex align-items-center gap-2 py-3">
      <div className="spinner-border text-primary" role="status" aria-hidden="true" />
      <span>{text}</span>
    </div>
  );
}

export default LoadingSpinner;
