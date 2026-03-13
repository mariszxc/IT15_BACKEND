import React, { useEffect, useState } from "react";
import axios from "axios";
import { downloadCsv } from "../utils/exportCsv";


const studentSnapshot = [
  {
    label: "Freshmen Applicants",
    value: "1,240",
    note: "Awaiting document validation",
  },
  {
    label: "Transfers",
    value: "320",
    note: "Includes ladderized programs",
  },
  {
    label: "Returning Students",
    value: "3,260",
    note: "Auto-enrolled for next term",
  },
];

function Students() {
  const [studentForm, setStudentForm] = useState({
    firstName: "",
    lastName: "",
    email: "",
  });
  const [formStatus, setFormStatus] = useState({ type: "", message: "" });
  const [isSaving, setIsSaving] = useState(false);
  const [students, setStudents] = useState([]);
  const [studentsStatus, setStudentsStatus] = useState({ loading: true, error: "" });
  const [selectedStudent, setSelectedStudent] = useState(null);
  const [profileStatus, setProfileStatus] = useState({ loading: false, error: "" });
  const [showAddForm, setShowAddForm] = useState(false);

  const loadStudents = async () => {
    setStudentsStatus({ loading: true, error: "" });

    try {
      const response = await axios.get("/api/students");
      setStudents(response.data?.students || []);
      setStudentsStatus({ loading: false, error: "" });
    } catch (error) {
      setStudentsStatus({
        loading: false,
        error: error.response?.data?.message || "Unable to load students.",
      });
    }
  };

  useEffect(() => {
    loadStudents();
  }, []);

  const handleExportStudents = () => {
    downloadCsv("students-snapshot.csv", studentSnapshot);
  };

  const capitalizeFirstLetter = (value) => {
    const trimmed = value.trimStart();

    if (!trimmed) {
      return value;
    }

    const firstChar = trimmed.charAt(0).toUpperCase();
    const rest = trimmed.slice(1).toLowerCase();
    const leadingSpaces = value.slice(0, value.length - trimmed.length);

    return `${leadingSpaces}${firstChar}${rest}`;
  };

  const handleFormChange = (event) => {
    const { name, value } = event.target;
    const nextValue =
      name === "firstName" || name === "lastName"
        ? capitalizeFirstLetter(value)
        : value;
    setStudentForm((prev) => ({ ...prev, [name]: nextValue }));
  };

  const handleFormSubmit = async (event) => {
    event.preventDefault();

    setFormStatus({ type: "", message: "" });
    setIsSaving(true);

    try {
      await axios.post("/api/students", {
        first_name: studentForm.firstName,
        last_name: studentForm.lastName,
        email: studentForm.email,
      });

      handleFormCancel();
      setFormStatus({ type: "success", message: "Student saved successfully." });
      await loadStudents();
    } catch (error) {
      const apiMessage =
        error.response?.data?.message ||
        error.response?.data?.errors?.email?.[0] ||
        "Unable to save student. Please try again.";
      setFormStatus({ type: "error", message: apiMessage });
    } finally {
      setIsSaving(false);
    }
  };

  const handleFormCancel = () => {
    setStudentForm({
      firstName: "",
      lastName: "",
      email: "",
    });
  };

  const handleViewProfile = async (student) => {
    setProfileStatus({ loading: true, error: "" });

    try {
      const response = await axios.get(`/api/students/${student.id}`);
      setSelectedStudent(response.data?.student || student);
      setProfileStatus({ loading: false, error: "" });
    } catch (error) {
      setSelectedStudent(student);
      setProfileStatus({
        loading: false,
        error: error.response?.data?.message || "Unable to load student profile.",
      });
    }
  };

  const handleCloseProfile = () => {
    setSelectedStudent(null);
    setProfileStatus({ loading: false, error: "" });
  };

  return (
    <div className="page-shell">
      <section className="info-card">
        <div className="table-header">
          <div>
            <h2>Student Directory</h2>
          </div>
          <button
            className="primary-btn"
            type="button"
            onClick={() => setShowAddForm((prev) => !prev)}
          >
            {showAddForm ? "Close" : "Add Student"}
          </button>
        </div>
        <div className="info-grid">
          {studentSnapshot.map((item) => (
            <div key={item.label} className="info-item">
              <p>{item.label}</p>
              <h3>{item.value}</h3>
              <span>{item.note}</span>
            </div>
          ))}
        </div>
      </section>

      <section className="info-card">
        <div className="table-header">
          <div>
            <h2>Recent Students</h2>
          </div>
        </div>

        {studentsStatus.loading && <p className="dashboard-feedback">Loading students...</p>}
        {studentsStatus.error && <p className="dashboard-feedback error">{studentsStatus.error}</p>}

        {!studentsStatus.loading && !studentsStatus.error && (
          <ul className="recent-list student-list">
            {students.length === 0 && <li className="empty-state">No students added yet.</li>}
            {students.map((student) => (
              <li key={student.id} className="student-row">
                <div className="student-row-main">
                  <div>
                    <strong>
                      {student.first_name} {student.last_name}
                    </strong>
                  </div>
                  <button
                    type="button"
                    className="ghost-btn small"
                    onClick={() => handleViewProfile(student)}
                  >
                    View Profile
                  </button>
                </div>
              </li>
            ))}
          </ul>
        )}
      </section>

      {selectedStudent && (
        <div className="modal-backdrop" role="dialog" aria-modal="true">
          <div className="modal-card">
            <div className="table-header">
              <div>
                <h2>Student Profile</h2>
              </div>
              <button type="button" className="ghost-btn small" onClick={handleCloseProfile}>
                Close
              </button>
            </div>

            {profileStatus.error && (
              <div className="dashboard-feedback error">{profileStatus.error}</div>
            )}

            {profileStatus.loading ? (
              <div className="dashboard-feedback">Loading profile...</div>
            ) : (
              <div className="details-grid">
                <div className="info-item">
                  <p>Student Number</p>
                  <strong>{selectedStudent.student_number || "-"}</strong>
                </div>
                <div className="info-item">
                  <p>First Name</p>
                  <strong>{selectedStudent.first_name}</strong>
                </div>
                <div className="info-item">
                  <p>Last Name</p>
                  <strong>{selectedStudent.last_name}</strong>
                </div>
                <div className="info-item">
                  <p>Email</p>
                  <strong>{selectedStudent.email}</strong>
                </div>
              </div>
            )}
          </div>
        </div>
      )}

      {showAddForm && (
        <div className="modal-backdrop" role="dialog" aria-modal="true">
          <div className="modal-card">
            <div className="table-header">
              <div>
                <h2>Add Student Information</h2>
              </div>
            </div>

            <form className="student-form" onSubmit={handleFormSubmit}>
              <div className="student-form-grid">
                <label className="student-form-field">
                  Student Number
                  <input
                    type="text"
                    name="studentNumber"
                    value=""
                    placeholder="Enter your student number"
                    disabled
                    readOnly
                  />
                </label>

                <label className="student-form-field">
                  First Name
                  <input
                    type="text"
                    name="firstName"
                    value={studentForm.firstName}
                    onChange={handleFormChange}
                    placeholder="Maris"
                  />
                </label>

                <label className="student-form-field">
                  Last Name
                  <input
                    type="text"
                    name="lastName"
                    value={studentForm.lastName}
                    onChange={handleFormChange}
                    placeholder="Bautista"
                  />
                </label>

                <label className="student-form-field">
                  Email
                  <input
                    type="email"
                    name="email"
                    value={studentForm.email}
                    onChange={handleFormChange}
                    placeholder="student@example.com"
                  />
                </label>
              </div>

              <div className="student-form-actions">
                <button type="submit" className="action-btn save-btn" disabled={isSaving}>
                  {isSaving ? "Saving..." : "Save"}
                </button>
                <button
                  type="button"
                  className="action-btn cancel-btn"
                  onClick={() => {
                    handleFormCancel();
                    setShowAddForm(false);
                  }}
                >
                  Cancel
                </button>
              </div>

              {formStatus.message && (
                <div className={`dashboard-feedback ${formStatus.type === "error" ? "error" : ""}`}>
                  {formStatus.message}
                </div>
              )}
            </form>
          </div>
        </div>
      )}
    </div>
  );
}

export default Students;
