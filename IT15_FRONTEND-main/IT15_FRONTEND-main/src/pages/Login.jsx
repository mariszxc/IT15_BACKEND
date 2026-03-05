import React, { useState } from "react";
import { useNavigate } from "react-router-dom";
import "../App.css";
import eduManzanoLogo from "../assets/Edu manzanoPORTAL.png";

function Login() {
  const [showLogin, setShowLogin] = useState(false);
  const [showPassword, setShowPassword] = useState(false);
  const [email, setEmail] = useState("");
  const [password, setPassword] = useState("");
  const [errorMessage, setErrorMessage] = useState("");
  const [isSubmitting, setIsSubmitting] = useState(false);
  const navigate = useNavigate();
  const apiBase = (import.meta.env.VITE_API_URL || "").replace(/\/+$/, "");

  const handleSubmit = async (event) => {
    event.preventDefault();

    setErrorMessage("");

    if (!email.trim() || !password) {
      setErrorMessage("Please enter your email and password.");
      return;
    }

    try {
      setIsSubmitting(true);

      const response = await fetch(`${apiBase}/api/login`, {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          Accept: "application/json",
        },
        body: JSON.stringify({
          email: email.trim(),
          password,
        }),
      });

      const data = await response.json();

      if (!response.ok) {
        const message = data?.message || "Login failed. Please try again.";
        setErrorMessage(message);
        return;
      }

      if (data?.token) {
        localStorage.setItem("auth_token", data.token);
      }

      if (data?.user) {
        localStorage.setItem("auth_user", JSON.stringify(data.user));
      }

      navigate("/dashboard");
    } catch {
      setErrorMessage("Unable to reach the server. Please try again.");
    } finally {
      setIsSubmitting(false);
    }
  };

  return (
    <div className="app">
      <div
        className={`welcome-screen ${showLogin ? "blur" : ""} animated`}
      >
        <div className="blob-aux" aria-hidden="true"></div>
        <img
          className="welcome-logo"
          src={eduManzanoLogo}
          alt="EDU Manzano logo"
        />
        <h1>WELCOME TO</h1>
        <h2>STUDENT PORTAL</h2>
        {!showLogin && <p>Login to access your account</p>}

        <button className="welcome-login-btn" onClick={() => setShowLogin(true)}>
          Login
        </button>
      </div>

      {showLogin && (
        <>
          <div className="overlay" onClick={() => setShowLogin(false)}></div>

          <div className="login-modal">
            <h2>Login</h2>
            <p className="sub-text">Enter your account details</p>

            <form onSubmit={handleSubmit}>
              <div className="input-group">
                <input
                  type="email"
                  placeholder="Email"
                  value={email}
                  onChange={(event) => setEmail(event.target.value)}
                />
              </div>

              <div className="input-group password-group">
                <input
                  type={showPassword ? "text" : "password"}
                  placeholder="Password"
                  value={password}
                  onChange={(event) => setPassword(event.target.value)}
                />
                <button
                  type="button"
                  className="password-toggle"
                  onClick={() => setShowPassword((prev) => !prev)}
                  aria-label={showPassword ? "Hide password" : "Show password"}
                >
                  {showPassword ? "Hide" : "Show"}
                </button>
              </div>

              {errorMessage && <p className="sub-text">{errorMessage}</p>}

              <div className="forgot">
                <a href="#">Forgot Password?</a>
              </div>

              <button type="submit" className="login-btn" disabled={isSubmitting}>
                {isSubmitting ? "Logging in..." : "Login"}
              </button>

              <p className="register">
                Don&apos;t have an account yet? <a href="#">Register</a>
              </p>
            </form>

            <button className="close-btn" onClick={() => setShowLogin(false)}>
              ✕
            </button>
          </div>
        </>
      )}
    </div>
  );
}

export default Login;
