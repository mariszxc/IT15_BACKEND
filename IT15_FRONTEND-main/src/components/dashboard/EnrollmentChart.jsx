import React from "react";
import { motion } from "framer-motion";
import { Bar, BarChart, CartesianGrid, ResponsiveContainer, Tooltip, XAxis, YAxis } from "recharts";

const MotionArticle = motion.article;

function EnrollmentChart({ data }) {
  const formatted = data.map((item) => ({
    month: item.month,
    total: Number(item.total),
  }));

  return (
    <MotionArticle
      className="chart-card"
      initial={{ opacity: 0, y: 18 }}
      animate={{ opacity: 1, y: 0 }}
      transition={{ duration: 0.45, ease: "easeOut" }}
      whileHover={{ y: -4 }}
    >
      <div className="chart-header">
        <p className="chart-title">Monthly Enrollment Trends</p>
      </div>
      <div className="chart-body">
        <div className="chart-canvas">
          <ResponsiveContainer width="100%" height="100%">
            <BarChart data={formatted} margin={{ top: 8, right: 8, left: -10, bottom: 0 }}>
              <CartesianGrid strokeDasharray="3 3" vertical={false} stroke="rgba(45,42,99,0.12)" />
              <XAxis dataKey="month" tickLine={false} axisLine={false} />
              <YAxis allowDecimals={false} tickLine={false} axisLine={false} width={28} />
              <Tooltip />
              <Bar
                dataKey="total"
                fill="#6a4bc4"
                radius={[8, 8, 2, 2]}
                isAnimationActive
                animationDuration={1200}
                animationBegin={150}
              />
            </BarChart>
          </ResponsiveContainer>
        </div>
      </div>
    </MotionArticle>
  );
}

export default EnrollmentChart;
