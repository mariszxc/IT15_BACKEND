import React, { useState } from "react";
import { Link, useNavigate } from "react-router-dom";
import { useAuth } from "../../context/AuthContext";

function Login() {
  const navigate = useNavigate();
  const { login, register } = useAuth();

  const [mode, setMode] = useState("login");
  const [form, setForm] = useState({ name: "", email: "", password: "" });
  const [errors, setErrors] = useState({});
  const [apiError, setApiError] = useState("");
  const [submitting, setSubmitting] = useState(false);

  const validate = () => {
    const nextErrors = {};

    if (mode === "register" && form.name.trim().length < 2) {
      nextErrors.name = "Name must be at least 2 characters.";
    }

    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(form.email)) {
      nextErrors.email = "Enter a valid email address.";
    }

    if (form.password.length < 8) {
      nextErrors.password = "Password must be at least 8 characters.";
    }

    setErrors(nextErrors);
    return Object.keys(nextErrors).length === 0;
  };

  const handleChange = (event) => {
    const { name, value } = event.target;
    setForm((prev) => ({ ...prev, [name]: value }));
  };

  const handleSubmit = async (event) => {
    event.preventDefault();
    setApiError("");

    if (!validate()) {
      return;
    }

    setSubmitting(true);

    try {
      if (mode === "register") {
        await register(form);
      }

      await login({ email: form.email, password: form.password });
      navigate("/", { replace: true });
    } catch (error) {
      setApiError(error.response?.data?.message || error.response?.data?.errors?.email?.[0] || "Authentication failed.");
    } finally {
      setSubmitting(false);
    }
  };

  return (
    <div className="login-wrapper d-flex align-items-center justify-content-center bg-light px-3">
      <div className="card shadow-sm border-0" style={{ maxWidth: 420, width: "100%" }}>
        <div className="card-body p-4">
          <h1 className="h4 mb-3 text-center">{mode === "login" ? "Sign In" : "Create Account"}</h1>

          <form onSubmit={handleSubmit} noValidate>
            {mode === "register" && (
              <div className="mb-3">
                <label htmlFor="name" className="form-label">
                  Name
                </label>
                <input
                  id="name"
                  name="name"
                  type="text"
                  className={`form-control ${errors.name ? "is-invalid" : ""}`}
                  value={form.name}
                  onChange={handleChange}
                />
                {errors.name && <div className="invalid-feedback">{errors.name}</div>}
              </div>
            )}

            <div className="mb-3">
              <label htmlFor="email" className="form-label">
                Email
              </label>
              <input
                id="email"
                name="email"
                type="email"
                className={`form-control ${errors.email ? "is-invalid" : ""}`}
                value={form.email}
                onChange={handleChange}
              />
              {errors.email && <div className="invalid-feedback">{errors.email}</div>}
            </div>

            <div className="mb-3">
              <label htmlFor="password" className="form-label">
                Password
              </label>
              <input
                id="password"
                name="password"
                type="password"
                className={`form-control ${errors.password ? "is-invalid" : ""}`}
                value={form.password}
                onChange={handleChange}
              />
              {errors.password && <div className="invalid-feedback">{errors.password}</div>}
            </div>

            {apiError && (
              <div className="alert alert-danger py-2" role="alert">
                {apiError}
              </div>
            )}

            <button type="submit" className="btn btn-primary w-100" disabled={submitting}>
              {submitting ? "Please wait..." : mode === "login" ? "Login" : "Register & Login"}
            </button>
          </form>

          <p className="text-center text-secondary mt-3 mb-0 small">
            {mode === "login" ? "No account yet?" : "Already have an account?"}{" "}
            <Link
              to="#"
              onClick={(event) => {
                event.preventDefault();
                setErrors({});
                setApiError("");
                setMode((prev) => (prev === "login" ? "register" : "login"));
              }}
            >
              {mode === "login" ? "Register" : "Login"}
            </Link>
          </p>
        </div>
      </div>
    </div>
  );
}

export default Login;
