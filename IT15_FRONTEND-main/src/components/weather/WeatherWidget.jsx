import React, { useEffect, useState } from "react";
import { motion } from "framer-motion";
import LoadingSpinner from "../common/LoadingSpinner";
import ForecastDisplay from "./ForecastDisplay";
import { fetchCurrentWeather, fetchForecast } from "../../services/weatherApi";

const sanitizeCity = (city) => city.replace(/[^a-zA-Z\s-]/g, "").trim();
const MotionArticle = motion.article;

function WeatherWidget() {
  const [city, setCity] = useState("Tagum City");
  const [current, setCurrent] = useState(null);
  const [forecast, setForecast] = useState(null);
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState("");

  useEffect(() => {
    loadWeather({ city: "Tagum City" });
  }, []);

  const loadWeather = async (params) => {
    setLoading(true);
    setError("");

    try {
      const [currentResponse, forecastResponse] = await Promise.all([
        fetchCurrentWeather(params),
        fetchForecast(params),
      ]);

      setCurrent(currentResponse.data);
      setForecast(forecastResponse.data);
    } catch (err) {
      const status = err.response?.status;

      if (status === 429) {
        setError("Weather API rate limit reached. Please try again shortly.");
      } else if (status === 503) {
        setError(err.response?.data?.message || "Weather service is unavailable.");
      } else {
        setError(err.response?.data?.message || "Unable to fetch weather data.");
      }
    } finally {
      setLoading(false);
    }
  };

  const onSearch = async (event) => {
    event.preventDefault();
    const cleanedCity = sanitizeCity(city);

    if (!cleanedCity) {
      setError("Please enter a valid city name.");
      return;
    }

    setCity(cleanedCity);
    await loadWeather({ city: cleanedCity });
  };

  const useGeolocation = () => {
    if (!navigator.geolocation) {
      setError("Geolocation is not supported by this browser.");
      return;
    }

    navigator.geolocation.getCurrentPosition(
      async (position) => {
        const { latitude, longitude } = position.coords;
        await loadWeather({ lat: latitude, lon: longitude });
      },
      () => {
        setError("Location access denied. Please search by city instead.");
      }
    );
  };

  return (
    <MotionArticle
      className="table-card"
      initial={{ opacity: 0, y: 18 }}
      animate={{ opacity: 1, y: 0 }}
      transition={{ duration: 0.45, delay: 0.24, ease: "easeOut" }}
      whileHover={{ y: -4 }}
    >
      <div className="table-header">
        <div>
          <p className="chart-title">Current Weather & Forecast</p>
        </div>
      </div>

      <form className="weather-search" onSubmit={onSearch}>
        <input
          type="text"
          value={city}
          onChange={(event) => setCity(event.target.value)}
          placeholder="Search city"
          maxLength={80}
          className="transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-violet-300"
        />
        <button className="ghost-btn small" type="submit">
          Search
        </button>
        <button className="ghost-btn small" type="button" onClick={useGeolocation}>
          Use GPS
        </button>
      </form>

      {loading && <LoadingSpinner text="Fetching weather..." />}

      {!loading && error && (
        <p className="weather-note error" role="alert">
          {error}
        </p>
      )}

      {!loading && !error && current && (
        <div className="weather-current weather-current-animated">
          <div className="weather-current-head">
            <img
              src={`https://openweathermap.org/img/wn/${current.weather?.[0]?.icon}@2x.png`}
              alt={current.weather?.[0]?.description || "Weather icon"}
              className="weather-icon-large"
            />
            <div>
              <strong>{current.name}</strong>
              <span className="weather-meta-text text-capitalize">{current.weather?.[0]?.description}</span>
            </div>
          </div>
          <div className="weather-kpis">
            <span>Temp: {Math.round(current.main?.temp ?? 0)}°C</span>
            <span>Humidity: {current.main?.humidity ?? 0}%</span>
            <span>Wind: {current.wind?.speed ?? 0} m/s</span>
          </div>
        </div>
      )}

      {!loading && !error && <ForecastDisplay data={forecast} />}
    </MotionArticle>
  );
}

export default WeatherWidget;
