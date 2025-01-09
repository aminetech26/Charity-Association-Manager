const ROOT = "http://localhost/TDWProject/";

const loginForm = document.getElementById("login-form");
const loginTypeInput = document.getElementById("login_type");
const loginTypeButtons = document.querySelectorAll(".login-type-btn");
const errorAlert = document.getElementById("error-alert");
const errorAlertContent = errorAlert.querySelector("div");

loginTypeButtons.forEach((button) => {
  button.addEventListener("click", function () {
    loginTypeButtons.forEach((btn) => {
      if (btn === this) {
        btn.classList.remove(
          "bg-white",
          "text-text-primary",
          "outline",
          "outline-1",
          "outline-text-secondary"
        );
        btn.classList.add("bg-primary", "text-white");
      } else {
        btn.classList.remove("bg-primary", "text-white");
        btn.classList.add(
          "bg-white",
          "text-text-primary",
          "outline",
          "outline-1",
          "outline-text-secondary"
        );
      }
    });

    loginTypeInput.value = this.dataset.type;
  });
});

loginForm.addEventListener("submit", async function (e) {
  e.preventDefault();

  const formData = new FormData(this);

  try {
    const response =
      formData.get("login_type") === "membre"
        ? await fetch(`${ROOT}public/Membre/signin`, {
            method: "POST",
            body: formData,
          })
        : await fetch(`${ROOT}public/Partenaire/signin`, {
            method: "POST",
            body: formData,
          });

    const data = await response.json();

    if (data.status === "success") {
      const redirectUrl =
        loginTypeInput.value == "membre"
          ? "public/Membre/dashboard"
          : "public/Partenaire/dashboard";
      window.location.href = ROOT + redirectUrl;
    } else {
      alert(data.message || "Une erreur s'est produite");
    }
  } catch (error) {
    console.error("Error submitting form:", error);
    alert("Une erreur s'est produite lors de la soumission du formulaire.");
  }
});
