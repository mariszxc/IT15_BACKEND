import React from "react";
import { Link } from "react-router-dom";
import "../styles/dashboard.css";

function NotFound() {
  return (
    <div className="page-shell">
      <section className="info-card">
        <h2>Page not found</h2>
        <p>The page you are looking for is not available.</p>
        <Link className="ghost-btn" to="/">
          Back to Login
        </Link>
      </section>
    </div>
  );
}

export default NotFound;
