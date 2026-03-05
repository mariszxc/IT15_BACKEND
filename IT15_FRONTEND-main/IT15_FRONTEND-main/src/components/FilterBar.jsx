import React from "react";

function FilterBar({ title, children, actions }) {
  return (
    <section className="table-card">
      <div className="table-header">
        <div>
          <p className="chart-title">{title}</p>
        </div>
        {actions ? <div className="filter-actions">{actions}</div> : null}
      </div>
      <div className="filter-grid">{children}</div>
    </section>
  );
}

export default FilterBar;