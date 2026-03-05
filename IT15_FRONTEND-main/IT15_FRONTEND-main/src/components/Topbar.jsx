import React, { useEffect, useState } from "react";

export function TopbarUtilities() {
  const [weatherStatus, setWeatherStatus] = useState("idle");
  const [temperature, setTemperature] = useState(null);
  const [isChatOpen, setIsChatOpen] = useState(false);
  const [chatInput, setChatInput] = useState("");
  const [chatMessages, setChatMessages] = useState([
    {
      id: 1,
      role: "bot",
      text: "Hi! I’m your assistant. Ask me about dashboard features.",
    },
  ]);
  const formattedDate = new Date().toLocaleDateString("en-US", {
    month: "long",
    day: "numeric",
    year: "numeric",
  });
  const weekday = new Date().toLocaleDateString("en-US", {
    weekday: "long",
  });

  useEffect(() => {
    const controller = new AbortController();

    async function loadTopbarWeather() {
      setWeatherStatus("loading");
      try {
        const response = await fetch(
          "https://api.open-meteo.com/v1/forecast?latitude=7.4478&longitude=125.8078&current_weather=true&timezone=Asia%2FManila",
          { signal: controller.signal }
        );
        const data = await response.json();
        const current = data.current_weather;
        setTemperature(Math.round(current.temperature));
        setWeatherStatus("done");
      } catch (error) {
        if (error.name !== "AbortError") {
          setWeatherStatus("error");
        }
      }
    }

    loadTopbarWeather();
    return () => controller.abort();
  }, []);

  const weatherValue =
    weatherStatus === "done" && temperature !== null
      ? `${temperature}°C`
      : weatherStatus === "error"
      ? "Unavailable"
      : "Loading...";

  function getBotReply(message) {
    const query = message.toLowerCase();

    if (query.includes("report") || query.includes("export")) {
      return "You can export data from Reports, Students, and Enrollment using each page's Export button.";
    }

    if (query.includes("weather") || query.includes("date")) {
      return "Topbar weather is set to Tagum City and updates from the weather API.";
    }

    if (query.includes("settings") || query.includes("save")) {
      return "Go to Settings to update preferences and click Save Changes at the bottom.";
    }

    if (query.includes("hello") || query.includes("hi")) {
      return "Hello! How can I help with your dashboard today?";
    }

    return "Thanks for your message. I can help with reports, exports, settings, and dashboard navigation.";
  }

  function handleChatSubmit(event) {
    event.preventDefault();
    const value = chatInput.trim();
    if (!value) {
      return;
    }

    const userMessage = {
      id: Date.now(),
      role: "user",
      text: value,
    };

    const botMessage = {
      id: Date.now() + 1,
      role: "bot",
      text: getBotReply(value),
    };

    setChatMessages((prev) => [...prev, userMessage, botMessage]);
    setChatInput("");
  }

  return (
    <div className="topbar-utilities">
      <div className="topbar-actions">
        <span className="topbar-date" aria-label="Current date">
          <span className="topbar-status-icon" aria-hidden="true">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.8" strokeLinecap="round" strokeLinejoin="round">
              <rect x="3" y="4" width="18" height="18" rx="2" />
              <path d="M8 2v4" />
              <path d="M16 2v4" />
              <path d="M3 10h18" />
            </svg>
          </span>
          <span className="topbar-status-content">
            <span className="topbar-date-label">{weekday}</span>
            <span className="topbar-date-value">{formattedDate}</span>
          </span>
        </span>
        <span className="topbar-weather" aria-live="polite">
          <span className="topbar-status-icon" aria-hidden="true">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.8" strokeLinecap="round" strokeLinejoin="round">
              <path d="M6 16a4 4 0 1 1 1.2-7.8A5 5 0 0 1 17 10h.5a3.5 3.5 0 1 1 0 7H6z" />
            </svg>
          </span>
          <span className="topbar-status-content">
            <span className="topbar-weather-location">Tagum City</span>
            <span className="topbar-weather-value">{weatherValue}</span>
          </span>
        </span>
      </div>
      <div className="topbar-chatbot">
        <button
          className="topbar-chatbot-btn"
          type="button"
          aria-label={isChatOpen ? "Close chatbot" : "Open chatbot"}
          title="Chatbot"
          onClick={() => setIsChatOpen((prev) => !prev)}
        >
          <svg viewBox="0 0 24 24" aria-hidden="true" fill="none" stroke="currentColor" strokeWidth="1.8" strokeLinecap="round" strokeLinejoin="round">
            <path d="M21 15a4 4 0 0 1-4 4H9l-4 3v-3a4 4 0 0 1-4-4V7a4 4 0 0 1 4-4h12a4 4 0 0 1 4 4z" />
            <path d="M8 10h8" />
            <path d="M8 14h5" />
          </svg>
        </button>

        {isChatOpen ? (
          <div className="topbar-chatbot-panel" role="dialog" aria-label="Chatbot interface">
            <div className="topbar-chatbot-header">
              <span>Chatbot</span>
              <button
                type="button"
                className="topbar-chatbot-close"
                aria-label="Close chatbot"
                onClick={() => setIsChatOpen(false)}
              >
                ×
              </button>
            </div>
            <div className="topbar-chatbot-messages">
              {chatMessages.map((message) => (
                <div
                  key={message.id}
                  className={`topbar-chatbot-message ${message.role === "user" ? "user" : "bot"}`}
                >
                  {message.text}
                </div>
              ))}
            </div>
            <form className="topbar-chatbot-form" onSubmit={handleChatSubmit}>
              <input
                type="text"
                value={chatInput}
                onChange={(event) => setChatInput(event.target.value)}
                placeholder="Type a message..."
                aria-label="Type your message"
              />
              <button type="submit">Send</button>
            </form>
          </div>
        ) : null}
      </div>
    </div>
  );
}

function Topbar({ title }) {
  return (
    <header className="topbar">
      <div>
        <h1>{title}</h1>
      </div>
      <TopbarUtilities />
    </header>
  );
}

export default Topbar;
