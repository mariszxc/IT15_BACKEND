import React, { useEffect, useState } from "react";
import { dashboardMetricsRequest } from "../../services/api";
import LoadingSpinner from "../common/LoadingSpinner";
import EnrollmentChart from "./EnrollmentChart";
import CourseDistributionChart from "./CourseDistributionChart";
import AttendanceChart from "./AttendanceChart";
import WeatherWidget from "../weather/WeatherWidget";

function Dashboard() {
  const [metrics, setMetrics] = useState(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState("");

  useEffect(() => {
    async function loadMetrics() {
      setLoading(true);
      setError("");

      try {
        const response = await dashboardMetricsRequest();
        setMetrics(response.data);
      } catch (err) {
        setError(err.response?.data?.message || "Failed to load dashboard metrics.");
      } finally {
        setLoading(false);
      }
    }

    loadMetrics();
  }, []);

  return (
    <main className="container py-4">
      <div className="row g-3 mb-3">
        <div className="col-12 col-md-4">
          <div className="card border-0 shadow-sm">
            <div className="card-body">
              <div className="small text-secondary">Students</div>
              <div className="h3 mb-0">{metrics?.totals?.students ?? "-"}</div>
            </div>
          </div>
        </div>
        <div className="col-12 col-md-4">
          <div className="card border-0 shadow-sm">
            <div className="card-body">
              <div className="small text-secondary">Courses</div>
              <div className="h3 mb-0">{metrics?.totals?.courses ?? "-"}</div>
            </div>
          </div>
        </div>
        <div className="col-12 col-md-4">
          <div className="card border-0 shadow-sm">
            <div className="card-body">
              <div className="small text-secondary">Enrollments</div>
              <div className="h3 mb-0">{metrics?.totals?.enrollments ?? "-"}</div>
            </div>
          </div>
        </div>
      </div>

      {loading && <LoadingSpinner skeleton />}

      {!loading && error && (
        <div className="alert alert-danger" role="alert">
          {error}
        </div>
      )}

      {!loading && !error && metrics && (
        <div className="row g-3">
          <div className="col-12 col-xl-6">
            <EnrollmentChart data={metrics.enrollmentTrends || []} />
          </div>
          <div className="col-12 col-xl-6">
            <CourseDistributionChart data={metrics.courseDistribution || []} />
          </div>
          <div className="col-12 col-xl-8">
            <AttendanceChart data={metrics.attendancePatterns || []} />
          </div>
          <div className="col-12 col-xl-4">
            <WeatherWidget />
          </div>
        </div>
      )}
    </main>
  );
}

export default Dashboard;
