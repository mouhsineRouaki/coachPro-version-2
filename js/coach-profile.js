let sports = [];
let experiences = [];
let certificats = [];

const sportsList = document.getElementById("sportsList");
const sportInput = document.getElementById("sportInput");

document.getElementById("addSportBtn").onclick = () => {
  const value = sportInput.value.trim();
  if (value && !sports.includes(value)) {
    sports.push(value);
    renderSports();
    sportInput.value = "";
  }
};

function renderSports() {
  sportsList.innerHTML = sports.map(s =>
    `<span>${s} <i onclick="removeSport('${s}')" class="fas fa-times"></i></span>`
  ).join("");
}

function removeSport(s) {
  sports = sports.filter(x => x !== s);
  renderSports();
}

document.getElementById("coachProfileForm").addEventListener("submit", e => {
  e.preventDefault();

  const data = {
    biographie: e.target.biographie.value,
    niveau: e.target.niveau.value,
    sports,
    experiences,
    certificats
  };

  console.log("DATA COACH :", data);

});

document.getElementById("skipBtn").onclick = () => {
  window.location.href = "index.html";
};
