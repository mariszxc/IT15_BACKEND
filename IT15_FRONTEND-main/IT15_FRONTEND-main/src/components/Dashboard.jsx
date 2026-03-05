import React, { useMemo, useState } from "react";
import {
  Bar,
  BarChart,
  CartesianGrid,
  Cell,
  Legend,
  Pie,
  PieChart,
  ResponsiveContainer,
  Sector,
  Tooltip,
  XAxis,
  YAxis,
} from "recharts";
import { getDashboardSummary } from "../data/catalogData";
import eduManzanoSystemLogo from "../assets/EDUMANZANOSYSTEM.png";
import { TopbarUtilities } from "./Topbar";

const BAR_COLORS = ["#3a2e80", "#5e45af", "#8572d1"];
const PIE_COLORS = ["#3a2e80", "#c6bdf0"];

function renderPieActiveShape(props) {
  const {
    cx,
    cy,
    innerRadius,
    outerRadius,
    startAngle,
    endAngle,
    fill,
  } = props;

  return (
    <g>
      <Sector
        cx={cx}
        cy={cy}
        innerRadius={innerRadius}
        outerRadius={outerRadius + 8}
        startAngle={startAngle}
        endAngle={endAngle}
        fill={fill}
      />
    </g>
  );
}

function DashboardTooltip({ active, payload, label }) {
  if (!active || !payload || payload.length === 0) {
    return null;
  }

  return (
    <div className="chart-tooltip-card">
      {label ? <p className="chart-tooltip-label">{label}</p> : null}
      {payload.map((entry) => (
        <div key={entry.dataKey || entry.name} className="chart-tooltip-row">
          <span className="chart-tooltip-dot" style={{ backgroundColor: entry.color }}></span>
          <span>{entry.name || "Value"}</span>
          <strong>{entry.value}</strong>
        </div>
      ))}
    </div>
  );
}

function Dashboard() {
  const summary = getDashboardSummary();
  const [activePieIndex, setActivePieIndex] = useState(0);
  const [hoveredBarIndex, setHoveredBarIndex] = useState(null);

  const overviewCards = [
    {
      title: "Total Programs",
      value: summary.totalPrograms,
    },
    {
      title: "Total Subjects",
      value: summary.totalSubjects,
    },
    {
      title: "Active / Non-active Programs",
      value: `${summary.activePrograms} / ${summary.inactivePrograms}`,
    },
    {
      title: "Subjects with Pre-requisites",
      value: summary.subjectsWithPrerequisites,
    },
  ];

  const offeringsData = useMemo(
    () => [
      { name: "Semester", subjects: summary.subjectsPerOffering.semester },
      { name: "Term", subjects: summary.subjectsPerOffering.term },
      { name: "Both", subjects: summary.subjectsPerOffering.both },
    ],
    [summary.subjectsPerOffering]
  );

  const statusData = useMemo(
    () => [
      { name: "Active", value: summary.activePrograms },
      { name: "Inactive", value: summary.inactivePrograms },
    ],
    [summary.activePrograms, summary.inactivePrograms]
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
        <article className="chart-card">
          <div className="chart-header">
            <div>
              <p className="chart-title">Subjects per Semester/Term</p>
            </div>
          </div>
          <div className="chart-body">
            <div className="chart-canvas">
              <ResponsiveContainer width="100%" height="100%">
                <BarChart
                  data={offeringsData}
                  margin={{ top: 12, right: 8, left: -10, bottom: 4 }}
                >
                  <defs>
                    <linearGradient id="barGradient1" x1="0" y1="0" x2="0" y2="1">
                      <stop offset="0%" stopColor="#4f3ca3" />
                      <stop offset="100%" stopColor="#2d2a63" />
                    </linearGradient>
                    <linearGradient id="barGradient2" x1="0" y1="0" x2="0" y2="1">
                      <stop offset="0%" stopColor="#745ed0" />
                      <stop offset="100%" stopColor="#4b3a90" />
                    </linearGradient>
                    <linearGradient id="barGradient3" x1="0" y1="0" x2="0" y2="1">
                      <stop offset="0%" stopColor="#9f8eee" />
                      <stop offset="100%" stopColor="#6a4bc4" />
                    </linearGradient>
                  </defs>
                  <CartesianGrid strokeDasharray="3 3" vertical={false} stroke="rgba(45,42,99,0.12)" />
                  <XAxis dataKey="name" tickLine={false} axisLine={false} />
                  <YAxis allowDecimals={false} tickLine={false} axisLine={false} width={28} />
                  <Tooltip content={<DashboardTooltip />} cursor={{ fill: "rgba(106,75,196,0.08)" }} />
                  <Bar
                    dataKey="subjects"
                    name="Subjects"
                    radius={[10, 10, 2, 2]}
                    isAnimationActive
                    animationDuration={1000}
                    animationEasing="ease-in-out"
                  >
                    {offeringsData.map((entry, index) => (
                      <Cell
                        key={entry.name}
                        fill={`url(#barGradient${index + 1})`}
                        fillOpacity={hoveredBarIndex === null || hoveredBarIndex === index ? 1 : 0.78}
                        stroke={hoveredBarIndex === index ? BAR_COLORS[index] : "transparent"}
                        strokeWidth={hoveredBarIndex === index ? 1.2 : 0}
                        onMouseEnter={() => setHoveredBarIndex(index)}
                        onMouseLeave={() => setHoveredBarIndex(null)}
                      />
                    ))}
                  </Bar>
                </BarChart>
              </ResponsiveContainer>
            </div>
          </div>
        </article>

        <article className="chart-card">
          <div className="chart-header">
            <div>
              <p className="chart-title">Program Status</p>
            </div>
          </div>
          <div className="chart-body">
            <div className="chart-canvas">
              <ResponsiveContainer width="100%" height="100%">
                <PieChart>
                  <defs>
                    <linearGradient id="pieGradient1" x1="0" y1="0" x2="1" y2="1">
                      <stop offset="0%" stopColor="#5d48bc" />
                      <stop offset="100%" stopColor="#2d2a63" />
                    </linearGradient>
                    <linearGradient id="pieGradient2" x1="0" y1="0" x2="1" y2="1">
                      <stop offset="0%" stopColor="#ece8fc" />
                      <stop offset="100%" stopColor="#b4a7eb" />
                    </linearGradient>
                  </defs>
                  <Tooltip content={<DashboardTooltip />} />
                  <Legend verticalAlign="bottom" iconType="circle" iconSize={8} />
                  <Pie
                    data={statusData}
                    dataKey="value"
                    nameKey="name"
                    cx="50%"
                    cy="46%"
                    innerRadius={56}
                    outerRadius={82}
                    activeIndex={activePieIndex}
                    activeShape={renderPieActiveShape}
                    onMouseEnter={(_, index) => setActivePieIndex(index)}
                    isAnimationActive
                    animationDuration={1100}
                    animationEasing="ease-in-out"
                  >
                    {statusData.map((entry, index) => (
                      <Cell key={entry.name} fill={`url(#pieGradient${index + 1})`} stroke={PIE_COLORS[index]} />
                    ))}
                  </Pie>
                </PieChart>
              </ResponsiveContainer>
            </div>
          </div>
        </article>
      </section>

      <section className="list-two-col">
        <article className="table-card">
          <div className="table-header">
            <div>
              <p className="chart-title">Recently Added Programs</p>
            </div>
          </div>
          <ul className="recent-list">
            {summary.recentPrograms.map((program) => (
              <li key={program.code}>
                <strong>{program.code}</strong>
                <span>{program.fullName}</span>
              </li>
            ))}
          </ul>
        </article>

        <article className="table-card">
          <div className="table-header">
            <div>
              <p className="chart-title">Recently Added Subjects</p>
            </div>
          </div>
          <ul className="recent-list">
            {summary.recentSubjects.map((subject) => (
              <li key={subject.code}>
                <strong>{subject.code}</strong>
                <span>{subject.title}</span>
              </li>
            ))}
          </ul>
        </article>
      </section>
    </div>
  );
}

export default Dashboard;