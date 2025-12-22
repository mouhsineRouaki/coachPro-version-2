const form = document.getElementById("sportifProfileForm");

document.getElementById("skipBtn").onclick = () => {
  alert("Profil sportif ignoré");
  window.location.href = "index.html";
};

form.addEventListener("submit", e => {
  e.preventDefault();

  const data = {
    niveau: form.niveau.value,
    image: form.url_image.value,
    bio: form.bio.value
  };

  console.log("DATA SPORTIF :", data);

  // AJAX → PHP plus tard
  alert("Profil sportif enregistré");
});
