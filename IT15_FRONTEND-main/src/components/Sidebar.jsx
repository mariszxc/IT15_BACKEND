import React, { useState } from "react";
import { NavLink, useNavigate } from "react-router-dom";
import eduManzanoLogo from "../assets/EDUMANZANOSYSTEM.png";

const navItems = [
  { label: "Dashboard", path: "/dashboard", icon: "dashboard" },
  { label: "Programs", path: "/dashboard/programs", icon: "programs" },
  { label: "Subjects", path: "/dashboard/subjects", icon: "subjects" },
  { label: "Students", path: "/dashboard/students", icon: "students" },
  { label: "Enrollment", path: "/dashboard/enrollment", icon: "enrollment" },
  { label: "Reports", path: "/dashboard/reports", icon: "reports" },
  { label: "Settings", path: "/dashboard/settings", icon: "settings" },
];

function SidebarIcon({ icon }) {
  if (icon === "dashboard") {
    return (
      <svg viewBox="0 0 24 24" className="sidebar-icon" aria-hidden="true" fill="none" stroke="currentColor" strokeWidth="1.8" strokeLinecap="round" strokeLinejoin="round">
        <rect x="3" y="3" width="8" height="8" rx="1.8" />
        <rect x="13" y="3" width="8" height="5" rx="1.8" />
        <rect x="13" y="10" width="8" height="11" rx="1.8" />
        <rect x="3" y="13" width="8" height="8" rx="1.8" />
      </svg>
    );
  }

  if (icon === "programs") {
    return (
      <svg viewBox="0 0 24 24" className="sidebar-icon" aria-hidden="true" fill="none" stroke="currentColor" strokeWidth="1.8" strokeLinecap="round" strokeLinejoin="round">
        <rect x="3" y="6" width="18" height="13" rx="2" />
        <path d="M9 6V5a3 3 0 0 1 6 0v1" />
      </svg>
    );
  }

  if (icon === "subjects") {
    return (
      <svg viewBox="0 0 24 24" className="sidebar-icon" aria-hidden="true" fill="none" stroke="currentColor" strokeWidth="1.8" strokeLinecap="round" strokeLinejoin="round">
        <path d="M4 5.5A2.5 2.5 0 0 1 6.5 3H20v17H6.5A2.5 2.5 0 0 0 4 22z" />
        <path d="M8 7h8" />
        <path d="M8 11h8" />
      </svg>
    );
  }

  if (icon === "students") {
    return (
      <svg viewBox="0 0 24 24" className="sidebar-icon" aria-hidden="true" fill="none" stroke="currentColor" strokeWidth="1.8" strokeLinecap="round" strokeLinejoin="round">
        <circle cx="9" cy="8" r="3" />
        <circle cx="17" cy="9" r="2.5" />
        <path d="M3.5 19a5.5 5.5 0 0 1 11 0" />
        <path d="M14 19a4 4 0 0 1 7 0" />
      </svg>
    );
  }

  if (icon === "enrollment") {
    return (
      <svg viewBox="0 0 24 24" className="sidebar-icon" aria-hidden="true" fill="none" stroke="currentColor" strokeWidth="1.8" strokeLinecap="round" strokeLinejoin="round">
        <rect x="5" y="3" width="14" height="18" rx="2" />
        <path d="M9 3.5h6" />
        <path d="M8 11h8" />
        <path d="M8 15h5" />
      </svg>
    );
  }

  if (icon === "reports") {
    return (
      <svg viewBox="0 0 24 24" className="sidebar-icon" aria-hidden="true" fill="none" stroke="currentColor" strokeWidth="1.8" strokeLinecap="round" strokeLinejoin="round">
        <path d="M4 20V10" />
        <path d="M10 20V4" />
        <path d="M16 20v-7" />
        <path d="M22 20v-11" />
      </svg>
    );
  }

  if (icon === "settings") {
    return (
      <svg viewBox="0 0 24 24" className="sidebar-icon" aria-hidden="true" fill="none" stroke="currentColor" strokeWidth="1.8" strokeLinecap="round" strokeLinejoin="round">
        <circle cx="12" cy="12" r="3" />
        <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09a1.65 1.65 0 0 0-1-1.51 1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09a1.65 1.65 0 0 0 1.51-1 1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33h.01A1.65 1.65 0 0 0 10 3.09V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51h.01a1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82v.01a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z" />
      </svg>
    );
  }

  return (
    <svg viewBox="0 0 24 24" className="sidebar-icon" aria-hidden="true" fill="none" stroke="currentColor" strokeWidth="1.8" strokeLinecap="round" strokeLinejoin="round">
      <circle cx="12" cy="12" r="3" />
      <path d="M12 2v3" />
      <path d="M12 19v3" />
      <path d="M4.93 4.93l2.12 2.12" />
      <path d="M16.95 16.95l2.12 2.12" />
      <path d="M2 12h3" />
      <path d="M19 12h3" />
      <path d="M4.93 19.07l2.12-2.12" />
      <path d="M16.95 7.05l2.12-2.12" />
    </svg>
  );
}

function Sidebar() {
  const navigate = useNavigate();
  const [isMenuOpen, setIsMenuOpen] = useState(false);

  const handleLogout = () => {
    const confirmed = window.confirm("Are you sure you want to log out?");
    if (confirmed) {
      navigate("/");
    }
    setIsMenuOpen(false);
  };

  return (
    <aside className="sidebar">
      <div className="sidebar-topbar">
        <div className="sidebar-brand">
          <img
            src={eduManzanoLogo}
            alt="Edu Manzano logo"
            className="brand-logo"
          />
          <div>
            <p className="brand-title">EDU MANZANO</p>
            <span className="brand-subtitle">ENROLLMENT SYSTEM</span>
          </div>
        </div>
        <button
          className="sidebar-menu-toggle"
          type="button"
          aria-label="Toggle navigation menu"
          aria-expanded={isMenuOpen}
          aria-controls="sidebar-navigation"
          onClick={() => setIsMenuOpen((previous) => !previous)}
        >
          <svg
            viewBox="0 0 24 24"
            className="sidebar-menu-icon"
            aria-hidden="true"
            fill="none"
            stroke="currentColor"
            strokeWidth="1.8"
            strokeLinecap="round"
            strokeLinejoin="round"
          >
            {isMenuOpen ? (
              <>
                <path d="M6 6l12 12" />
                <path d="M18 6L6 18" />
              </>
            ) : (
              <>
                <path d="M4 7h16" />
                <path d="M4 12h16" />
                <path d="M4 17h16" />
              </>
            )}
          </svg>
        </button>
      </div>

      <nav
        id="sidebar-navigation"
        className={`sidebar-nav ${isMenuOpen ? "open" : ""}`}
      >
        {navItems.map((item) => (
          <NavLink
            key={item.path}
            to={item.path}
            className={({ isActive }) =>
              `sidebar-link ${isActive ? "active" : ""}`
            }
            end={item.path === "/dashboard"}
            onClick={() => setIsMenuOpen(false)}
          >
            <SidebarIcon icon={item.icon} />
            {item.label}
          </NavLink>
        ))}

        <button
          className="sidebar-logout-action"
          type="button"
          onClick={handleLogout}
        >
          <svg viewBox="0 0 24 24" className="sidebar-icon" aria-hidden="true" fill="none" stroke="currentColor" strokeWidth="1.8" strokeLinecap="round" strokeLinejoin="round">
            <path d="M14 4h4a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2h-4" />
            <path d="M10 17l5-5-5-5" />
            <path d="M15 12H4" />
          </svg>
          Log out
        </button>

        <div className="sidebar-mobile-meta">
          <p>SY 2025-2026</p>
          <span>MARIS BAUTISTA</span>
        </div>
      </nav>

      <div className="sidebar-footer">
        <p>SY 2025-2026</p>
        <span>MARIS BAUTISTA</span>
      </div>
    </aside>
  );
}

export default Sidebar;
