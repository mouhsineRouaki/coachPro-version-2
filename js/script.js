const nameRegex = /^[A-Za-zÀ-ÿ\s]{2,}$/;
const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
const phoneRegex = /^(?:\+212|0)(6|7)\d{8}$/;
const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[A-Za-z\d]{8,}$/;
const signupBtn = document.getElementById("signup-btn");
const signinBtn = document.getElementById("signin-btn");
const mainContainer = document.querySelector(".container");

signupBtn.addEventListener("click", () => {
  mainContainer.classList.toggle("change");
});
signinBtn.addEventListener("click", () => {
  mainContainer.classList.toggle("change");
});

document.querySelector('.signup-form form').addEventListener('submit', function (e) {
    e.preventDefault();

    const inputs = this.querySelectorAll('input, select');

    const nom = inputs[0].value.trim();
    const prenom = inputs[1].value.trim();
    const email = inputs[2].value.trim();
    const phone = inputs[3].value.trim();
    const role = inputs[4].value;
    const password = inputs[5].value;
    const confirmPassword = inputs[6].value;

    if (!nameRegex.test(nom)) {
        alert("Nom invalide");
        return;
    }

    if (!nameRegex.test(prenom)) {
        alert("Prénom invalide");
        return;
    }

    if (!emailRegex.test(email)) {
        alert("Email invalide");
        return;
    }

    if (!phoneRegex.test(phone)) {
        alert("Téléphone invalide");
        return;
    }

    if (role === "") {
        alert("Veuillez choisir un rôle");
        return;
    }

    if (!passwordRegex.test(password)) {
        alert("Mot de passe faible (8 caractères, 1 majuscule, 1 minuscule, 1 chiffre)");
        return;
    }

    if (password !== confirmPassword) {
        alert("Les mots de passe ne correspondent pas");
        return;
    }
    this.submit();
});

document.querySelector('.signin-form form').addEventListener('submit', function (e) {
    e.preventDefault();

    const email = this.querySelector('input[type="email"]').value.trim();
    const password = this.querySelector('input[type="password"]').value;

    if (!emailRegex.test(email)) {
        alert("Email invalide");
        return;
    }

    if (password === "") {
        alert("Mot de passe requis");
        return;
    }
    this.submit();
});
