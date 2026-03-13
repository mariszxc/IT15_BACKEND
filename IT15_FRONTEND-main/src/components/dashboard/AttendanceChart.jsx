import React from "react";
import { motion } from "framer-motion";
import { CartesianGrid, Line, LineChart, ResponsiveContainer, Tooltip, XAxis, YAxis } from "recharts";

const MotionArticle = motion.article;

function AttendanceChart({ data }) {
  const formatted = data.map((item) => ({
    day: item.school_day,
    rate: Number(item.rate),
  }));

  return (
    <MotionArticle
      className="chart-card"
      initial={{ opacity: 0, y: 18 }}
      animate={{ opacity: 1, y: 0 }}
      transition={{ duration: 0.45, delay: 0.16, ease: "easeOut" }}
      whileHover={{ y: -4 }}
    >
      <div className="chart-header">
        <p className="chart-title">Attendance Patterns Over School Days</p>
      </div>
      <div className="chart-body">
        <div className="chart-canvas">
          <ResponsiveContainer width="100%" height="100%">
            <LineChart data={formatted} margin={{ top: 8, right: 8, left: -10, bottom: 0 }}>
              <CartesianGrid strokeDasharray="3 3" vertical={false} stroke="rgba(45,42,99,0.12)" />
              <XAxis dataKey="day" tickLine={false} axisLine={false} />
              <YAxis domain={[70, 100]} unit="%" tickLine={false} axisLine={false} width={38} />
              <Tooltip />
              <Line
                type="monotone"
                dataKey="rate"
                stroke="#6a4bc4"
                strokeWidth={2.8}
                dot={{ r: 2.5, stroke: "#fff", strokeWidth: 1.4 }}
                activeDot={{ r: 5 }}
                isAnimationActive
                animationDuration={1500}
                animationBegin={260}
              />
            </LineChart>
          </ResponsiveContainer>
        </div>
      </div>
    </MotionArticle>
  );
}

export default AttendanceChart;
