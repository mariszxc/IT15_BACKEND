import React from "react";
import { Outlet, useLocation } from "react-router-dom";
import Sidebar from "../components/Sidebar";
import Topbar from "../components/Topbar";
import "../styles/dashboard.css";

const pageTitles = {
  "/dashboard": "Dashboard",
  "/dashboard/students": "Students",
  "/dashboard/enrollment": "Enrollment",
  "/dashboard/reports": "Reports",
  "/dashboard/settings": "Settings",
  "/dashboard/programs": "Program Offerings",
  "/dashboard/subjects": "Subject Offerings",
};

function DashboardLayout() {
  const location = useLocation();
  const title = pageTitles[location.pathname];
  const isDashboardHome = location.pathname === "/dashboard";

  return (
    <div className="dashboard">
      <Sidebar />
      <div className="dashboard-main">
        {!isDashboardHome && <Topbar title={title} />}
        <div className="dashboard-content">
          <Outlet />
        </div>
      </div>
    </div>
  );
}

export default DashboardLayout;
