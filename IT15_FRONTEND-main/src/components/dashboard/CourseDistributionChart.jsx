import React from "react";
import { motion } from "framer-motion";
import { Cell, Pie, PieChart, ResponsiveContainer, Tooltip } from "recharts";

const MotionArticle = motion.article;

const COLORS = ["#0d6efd", "#6f42c1", "#20c997", "#fd7e14", "#dc3545", "#198754"];

function CourseDistributionChart({ data }) {
  const formatted = data.map((item) => ({
    name: item.name,
    value: Number(item.total),
  }));

  return (
    <MotionArticle
      className="chart-card"
      initial={{ opacity: 0, y: 18 }}
      animate={{ opacity: 1, y: 0 }}
      transition={{ duration: 0.45, delay: 0.08, ease: "easeOut" }}
      whileHover={{ y: -4 }}
    >
      <div className="chart-header">
        <p className="chart-title">Student Distribution Across Courses</p>
      </div>
      <div className="chart-body">
        <div className="chart-canvas">
          <ResponsiveContainer width="100%" height="100%">
            <PieChart>
              <Pie
                data={formatted}
                dataKey="value"
                nameKey="name"
                cx="50%"
                cy="50%"
                outerRadius={95}
                label
                isAnimationActive
                animationDuration={1300}
                animationBegin={200}
              >
                {formatted.map((_, index) => (
                  <Cell key={index} fill={COLORS[index % COLORS.length]} />
                ))}
              </Pie>
              <Tooltip />
            </PieChart>
          </ResponsiveContainer>
        </div>
      </div>
    </MotionArticle>
  );
}

export default CourseDistributionChart;
