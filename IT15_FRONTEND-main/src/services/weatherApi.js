import api from "./api";

export const fetchCurrentWeather = (params) => api.get("/weather/current", { params });
export const fetchForecast = (params) => api.get("/weather/forecast", { params });
