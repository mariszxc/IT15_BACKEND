import React, { useMemo, useState } from "react";
import FilterBar from "../components/FilterBar";
import ProgramList from "../components/ProgramList";
import ProgramDetails from "../components/ProgramDetails";
import { programs } from "../data/catalogData";

function ProgramOfferings() {
  const [search, setSearch] = useState("");
  const [statusFilter, setStatusFilter] = useState("all");
  const [typeFilter, setTypeFilter] = useState("all");
  const [selectedCode, setSelectedCode] = useState(programs[0]?.code || "");
  const [showProgramModal, setShowProgramModal] = useState(false);

  const filteredPrograms = useMemo(() => {
    return programs.filter((program) => {
      const matchesSearch =
        program.code.toLowerCase().includes(search.toLowerCase()) ||
        program.fullName.toLowerCase().includes(search.toLowerCase());
      const matchesStatus = statusFilter === "all" || program.status === statusFilter;
      const matchesType = typeFilter === "all" || program.type === typeFilter;

      return matchesSearch && matchesStatus && matchesType;
    });
  }, [search, statusFilter, typeFilter]);

  const selectedProgram =
    filteredPrograms.find((program) => program.code === selectedCode) || filteredPrograms[0] || null;

  return (
    <div className="page-shell">
      <FilterBar
        title="Program Filters"
        actions={
          <button className="primary-btn" type="button" onClick={() => setShowProgramModal(true)}>
            Add / Edit Program
          </button>
        }
      >
        <label className="filter-field">
          Search by code or name
          <input
            value={search}
            onChange={(event) => setSearch(event.target.value)}
            placeholder="BSIT / Computer Science"
          />
        </label>

        <label className="filter-field">
          Filter by status
          <select value={statusFilter} onChange={(event) => setStatusFilter(event.target.value)}>
            <option value="all">All</option>
            <option value="active">Active</option>
            <option value="phased out">Phased Out</option>
            <option value="under review">Under Review</option>
          </select>
        </label>

        <label className="filter-field">
          Filter by type
          <select value={typeFilter} onChange={(event) => setTypeFilter(event.target.value)}>
            <option value="all">All</option>
            <option value="Bachelor's Degree">Bachelor&apos;s Degree</option>
            <option value="Diploma">Diploma</option>
          </select>
        </label>
      </FilterBar>

      <ProgramList
        programs={filteredPrograms}
        selectedCode={selectedProgram?.code}
        onSelect={(program) => setSelectedCode(program.code)}
      />

      <ProgramDetails program={selectedProgram} />

      {showProgramModal ? (
        <div className="modal-backdrop" role="presentation" onClick={() => setShowProgramModal(false)}>
          <section className="modal-card" role="dialog" onClick={(event) => event.stopPropagation()}>
            <div className="table-header">
              <div>
                <p className="chart-title">Program Form</p>
              </div>
            </div>
            <div className="filter-grid">
              <label className="filter-field">
                Program Code
                <input placeholder="BSIT" />
              </label>
              <label className="filter-field">
                Program Name
                <input placeholder="Bachelor of Science in Information Technology" />
              </label>
              <label className="filter-field">
                Program Type
                <input placeholder="Bachelor's Degree" />
              </label>
              <label className="filter-field">
                Status
                <input placeholder="Active" />
              </label>
            </div>
            <div className="modal-actions">
              <button className="ghost-btn" type="button" onClick={() => setShowProgramModal(false)}>
                Cancel
              </button>
              <button className="primary-btn" type="button" onClick={() => setShowProgramModal(false)}>
                Save
              </button>
            </div>
          </section>
        </div>
      ) : null}
    </div>
  );
}

export default ProgramOfferings;