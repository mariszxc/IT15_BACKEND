import React, { createContext, useContext, useEffect, useMemo, useState } from "react";
import { loginRequest, logoutRequest, meRequest, registerRequest } from "../services/api";

const AuthContext = createContext(null);

const sanitizeTextInput = (value) => value.replace(/[<>]/g, "").trim();

export function AuthProvider({ children }) {
  const [user, setUser] = useState(() => {
    const stored = localStorage.getItem("auth_user");
    return stored ? JSON.parse(stored) : null;
  });
  const [token, setToken] = useState(() => localStorage.getItem("auth_token"));
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    async function hydrateSession() {
      if (!token) {
        setLoading(false);
        return;
      }

      try {
        const response = await meRequest();
        setUser(response.data.user);
      } catch {
        setUser(null);
        setToken(null);
        localStorage.removeItem("auth_token");
        localStorage.removeItem("auth_user");
      } finally {
        setLoading(false);
      }
    }

    hydrateSession();
  }, [token]);

  const login = async ({ email, password }) => {
    const payload = {
      email: sanitizeTextInput(email).toLowerCase(),
      password,
    };

    const response = await loginRequest(payload);
    const authToken = response.data.token;
    const authUser = response.data.user;

    setToken(authToken);
    setUser(authUser);
    localStorage.setItem("auth_token", authToken);
    localStorage.setItem("auth_user", JSON.stringify(authUser));

    return response;
  };

  const register = async ({ name, email, password }) => {
    const payload = {
      name: sanitizeTextInput(name),
      email: sanitizeTextInput(email).toLowerCase(),
      password,
    };

    const response = await registerRequest(payload);
    return response;
  };

  const logout = async () => {
    try {
      await logoutRequest();
    } catch {
      // no-op: local cleanup still proceeds
    } finally {
      setToken(null);
      setUser(null);
      localStorage.removeItem("auth_token");
      localStorage.removeItem("auth_user");
    }
  };

  const value = useMemo(
    () => ({
      user,
      token,
      loading,
      login,
      register,
      logout,
    }),
    [user, token, loading]
  );

  return <AuthContext.Provider value={value}>{children}</AuthContext.Provider>;
}

export function useAuth() {
  const context = useContext(AuthContext);

  if (!context) {
    throw new Error("useAuth must be used within AuthProvider");
  }

  return context;
}
