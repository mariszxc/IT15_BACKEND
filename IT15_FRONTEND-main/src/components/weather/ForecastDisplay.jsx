import React from "react";
import { motion } from "framer-motion";

const MotionDiv = motion.div;

function ForecastDisplay({ data }) {
  if (!data?.list?.length) {
    return <p className="text-secondary mb-0">No forecast data available.</p>;
  }

  const daily = [];
  const seenDates = new Set();

  for (const item of data.list) {
    const date = item.dt_txt?.split(" ")[0];

    if (!date || seenDates.has(date)) {
      continue;
    }

    seenDates.add(date);
    daily.push(item);

    if (daily.length === 5) {
      break;
    }
  }

  return (
    <div className="forecast-grid">
      {daily.map((item, index) => (
        <MotionDiv
          key={item.dt}
          className="forecast-card forecast-card-animated"
          initial={{ opacity: 0, y: 8 }}
          animate={{ opacity: 1, y: 0 }}
          transition={{ duration: 0.28, delay: index * 0.08 }}
        >
          <div className="small fw-semibold">{item.dt_txt.split(" ")[0]}</div>
          <img
            className="weather-icon"
            src={`https://openweathermap.org/img/wn/${item.weather?.[0]?.icon}@2x.png`}
            alt={item.weather?.[0]?.description || "Weather icon"}
          />
          <div className="small">{Math.round(item.main?.temp ?? 0)}°</div>
          <div className="small text-secondary text-capitalize">{item.weather?.[0]?.description}</div>
        </MotionDiv>
      ))}
    </div>
  );
}

export default ForecastDisplay;
