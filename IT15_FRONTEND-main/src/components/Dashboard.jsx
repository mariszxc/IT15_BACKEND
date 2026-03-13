import React, { useMemo, useState, useEffect } from "react";
import { dashboardMetricsRequest } from "../services/api";
import eduManzanoSystemLogo from "../assets/EDUMANZANOSYSTEM.png";
import { TopbarUtilities } from "./Topbar";
import EnrollmentChart from "./dashboard/EnrollmentChart";
import CourseDistributionChart from "./dashboard/CourseDistributionChart";
import AttendanceChart from "./dashboard/AttendanceChart";
import WeatherWidget from "./weather/WeatherWidget";

const FALLBACK_METRICS = {
  totals: {
    students: 248,
    courses: 6,
    enrollments: 312,
  },
  enrollmentTrends: [
    { month: "2025-09", total: 36 },
    { month: "2025-10", total: 44 },
    { month: "2025-11", total: 51 },
    { month: "2025-12", total: 39 },
    { month: "2026-01", total: 66 },
    { month: "2026-02", total: 76 },
  ],
  courseDistribution: [
    { name: "Computer Science", total: 72 },
    { name: "Information Technology", total: 96 },
    { name: "Nursing", total: 57 },
    { name: "Engineering", total: 52 },
    { name: "Education", total: 42 },
    { name: "Tourism", total: 18 },
  ],
  attendancePatterns: [
    { school_day: "Mon", rate: 90 },
    { school_day: "Tue", rate: 60 },
    { school_day: "Wed", rate: 99 },
    { school_day: "Thu", rate: 60 },
    { school_day: "Fri", rate: 50 },
  ],
};

function Dashboard() {
  const [metrics, setMetrics] = useState(null);
  const [metricsStatus, setMetricsStatus] = useState("loading");
  const [metricsError, setMetricsError] = useState("");

  useEffect(() => {
    async function loadDashboardMetrics() {
      setMetricsStatus("loading");
      setMetricsError("");

      try {
        const response = await dashboardMetricsRequest();
        setMetrics(response.data);
        setMetricsStatus("done");
      } catch (error) {
        setMetrics(FALLBACK_METRICS);
        setMetricsStatus("fallback");
        setMetricsError(error.response?.data?.message || "Unable to load dashboard metrics.");
      }
    }

    loadDashboardMetrics();
  }, []);

  const activeMetrics = metrics || FALLBACK_METRICS;

  const overviewCards = [
    {
      title: "Total Students",
      value: activeMetrics.totals?.students ?? "-",
    },
    {
      title: "Total Courses",
      value: activeMetrics.totals?.courses ?? "-",
    },
    {
      title: "Total Enrollments",
      value: activeMetrics.totals?.enrollments ?? "-",
    },
    {
      title: "Attendance Data Points",
      value: activeMetrics.attendancePatterns?.length ?? "-",
    },
  ];

  const offeringsData = useMemo(
    () =>
      (activeMetrics.enrollmentTrends || []).map((item) => ({
        month: item.month,
        total: Number(item.total),
      })),
    [activeMetrics]
  );

  const statusData = useMemo(
    () =>
      (activeMetrics.courseDistribution || []).map((item) => ({
        name: item.name,
        total: Number(item.total),
      })),
    [activeMetrics]
  );

  const attendanceData = useMemo(
    () =>
      (activeMetrics.attendancePatterns || []).map((item) => ({
        school_day: item.school_day,
        rate: Number(item.rate),
      })),
    [activeMetrics]
  );

  return (
    <div className="dashboard-grid">
      <section className="dashboard-hero" aria-label="Portal overview banner">
        <img
          src={eduManzanoSystemLogo}
          alt="Edu Manzano portal logo"
          className="dashboard-hero-logo"
        />
        <div className="dashboard-hero-text">
          <p className="dashboard-hero-label">Official Portal</p>
          <h2>Edu Manzano Enrollment Dashboard</h2>
        </div>
        <div className="dashboard-hero-tools">
          <TopbarUtilities />
        </div>
      </section>

      <section className="overview-grid">
        {overviewCards.map((card) => (
          <article key={card.title} className="stat-card">
            <p className="stat-title">{card.title}</p>
            <h3>{card.value}</h3>
            <p className="stat-hint">{card.hint}</p>
          </article>
        ))}
      </section>

      <section className="chart-grid">
        <EnrollmentChart data={offeringsData} />
        <CourseDistributionChart data={statusData} />
        <AttendanceChart data={attendanceData} />
      </section>

      {metricsStatus === "loading" && <div className="dashboard-feedback">Loading dashboard data...</div>}
      {metricsStatus === "error" && <div className="dashboard-feedback error">{metricsError}</div>}
      {metricsStatus === "fallback" && (
        <div className="dashboard-feedback">
          Live dashboard data is currently unavailable ({metricsError}). Showing demo chart data for now.
        </div>
      )}

      <section className="list-two-col">
        <WeatherWidget />

        <article className="table-card">
          <div className="table-header">
            <div>
              <p className="chart-title">Course Enrollment Summary</p>
            </div>
          </div>
          <ul className="recent-list">
            {statusData.map((course) => (
              <li key={course.name}>
                <strong>{course.name}</strong>
                <span>{course.total} students enrolled</span>
              </li>
            ))}
          </ul>
        </article>
      </section>
    </div>
  );
}

export default Dashboard;