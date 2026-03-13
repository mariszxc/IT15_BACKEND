import React, { useMemo, useState } from "react";
import FilterBar from "../components/FilterBar";
import SubjectList from "../components/SubjectList";
import SubjectDetails from "../components/SubjectDetails";
import { subjects } from "../data/catalogData";

function SubjectOfferings() {
  const [search, setSearch] = useState("");
  const [semesterFilter, setSemesterFilter] = useState("all");
  const [unitsFilter, setUnitsFilter] = useState("all");
  const [prerequisiteFilter, setPrerequisiteFilter] = useState("all");
  const [programFilter, setProgramFilter] = useState("all");
  const [selectedCode, setSelectedCode] = useState(subjects[0]?.code || "");
  const [showSubjectModal, setShowSubjectModal] = useState(false);

  const programOptions = useMemo(() => {
    return [...new Set(subjects.map((subject) => subject.programCode))].sort();
  }, []);

  const filteredSubjects = useMemo(() => {
    return subjects.filter((subject) => {
      const hasPrerequisite = subject.prerequisites.length > 0;

      const matchesSearch =
        subject.code.toLowerCase().includes(search.toLowerCase()) ||
        subject.title.toLowerCase().includes(search.toLowerCase());
      const matchesSemester =
        semesterFilter === "all" || subject.offeringMode === semesterFilter;
      const matchesUnits = unitsFilter === "all" || String(subject.units) === unitsFilter;
      const matchesPrerequisite =
        prerequisiteFilter === "all" ||
        (prerequisiteFilter === "with" && hasPrerequisite) ||
        (prerequisiteFilter === "without" && !hasPrerequisite);
      const matchesProgram =
        programFilter === "all" || subject.programCode === programFilter;

      return (
        matchesSearch &&
        matchesSemester &&
        matchesUnits &&
        matchesPrerequisite &&
        matchesProgram
      );
    });
  }, [search, semesterFilter, unitsFilter, prerequisiteFilter, programFilter]);

  const selectedSubject =
    filteredSubjects.find((subject) => subject.code === selectedCode) || filteredSubjects[0] || null;

  return (
    <div className="page-shell">
      <FilterBar
        title="Subject Filters"
        actions={
          <button className="ghost-btn" type="button" onClick={() => setShowSubjectModal(true)}>
            Open Subject
          </button>
        }
      >
        <label className="filter-field">
          Search by code or title
          <input
            value={search}
            onChange={(event) => setSearch(event.target.value)}
            placeholder="IT101 / Data Structures"
          />
        </label>

        <label className="filter-field">
          Filter by semester/term
          <select value={semesterFilter} onChange={(event) => setSemesterFilter(event.target.value)}>
            <option value="all">All</option>
            <option value="semester">Per Semester</option>
            <option value="term">Per Term</option>
            <option value="both">Both</option>
          </select>
        </label>

        <label className="filter-field">
          Filter by units
          <select value={unitsFilter} onChange={(event) => setUnitsFilter(event.target.value)}>
            <option value="all">All</option>
            <option value="3">3</option>
            <option value="4">4</option>
          </select>
        </label>

        <label className="filter-field">
          Filter pre-requisites
          <select value={prerequisiteFilter} onChange={(event) => setPrerequisiteFilter(event.target.value)}>
            <option value="all">All</option>
            <option value="with">With Pre-requisites</option>
            <option value="without">Without Pre-requisites</option>
          </select>
        </label>

        <label className="filter-field">
          Filter by program
          <select value={programFilter} onChange={(event) => setProgramFilter(event.target.value)}>
            <option value="all">All</option>
            {programOptions.map((programCode) => (
              <option key={programCode} value={programCode}>
                {programCode}
              </option>
            ))}
          </select>
        </label>
      </FilterBar>

      <SubjectList
        subjects={filteredSubjects}
        selectedCode={selectedSubject?.code}
        onSelect={(subject) => setSelectedCode(subject.code)}
      />

      <SubjectDetails subject={selectedSubject} />

      {showSubjectModal && selectedSubject ? (
        <div className="modal-backdrop" role="presentation" onClick={() => setShowSubjectModal(false)}>
          <section className="modal-card" role="dialog" onClick={(event) => event.stopPropagation()}>
            <SubjectDetails subject={selectedSubject} />
            <div className="modal-actions">
              <button className="ghost-btn small" type="button" onClick={() => setShowSubjectModal(false)}>
                Close
              </button>
            </div>
          </section>
        </div>
      ) : null}
    </div>
  );
}

export default SubjectOfferings;