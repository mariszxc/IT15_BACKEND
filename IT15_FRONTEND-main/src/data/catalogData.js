import catalog from "./mockCatalog.json";

export const programs = catalog.programs;
export const subjects = catalog.subjects;

export function getProgramByCode(programCode) {
  return programs.find((program) => program.code === programCode);
}

export function getSubjectByCode(subjectCode) {
  return subjects.find((subject) => subject.code === subjectCode);
}

export function getDashboardSummary() {
  const activePrograms = programs.filter((program) => program.status === "active").length;
  const inactivePrograms = programs.length - activePrograms;
  const subjectsWithPrerequisites = subjects.filter(
    (subject) => subject.prerequisites.length > 0
  ).length;

  const subjectsPerOffering = subjects.reduce(
    (result, subject) => {
      result[subject.offeringMode] += 1;
      return result;
    },
    { semester: 0, term: 0, both: 0 }
  );

  const recentPrograms = [...programs]
    .sort((a, b) => new Date(b.createdAt) - new Date(a.createdAt))
    .slice(0, 3);
  const recentSubjects = [...subjects]
    .sort((a, b) => new Date(b.createdAt) - new Date(a.createdAt))
    .slice(0, 3);

  return {
    totalPrograms: programs.length,
    totalSubjects: subjects.length,
    activePrograms,
    inactivePrograms,
    subjectsWithPrerequisites,
    subjectsPerOffering,
    recentPrograms,
    recentSubjects,
  };
}

export function getSubjectsForProgramYear(program, yearLevel) {
  const yearSubjects = program?.yearLevels?.[yearLevel] || [];

  return yearSubjects.map((subjectCode) => {
    const subject = getSubjectByCode(subjectCode);
    if (subject) {
      return subject;
    }

    return {
      code: subjectCode,
      title: "Mock Subject",
      units: 3,
      semesterTerm: "TBD",
      offeringMode: "semester",
      description: "Mock subject placeholder.",
      prerequisites: [],
      corequisites: [],
      programCode: program.code,
    };
  });
}