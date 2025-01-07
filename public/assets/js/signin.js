document.addEventListener("DOMContentLoaded", function () {
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
      const response = await simulateApiCall(formData);

      if (response.success) {
        const redirectUrl =
          formData.get("login_type") === "member"
            ? `${ROOT}public/Membre/dashboard`
            : `${ROOT}public/Partenaire/dashboard`;
        window.location.href = redirectUrl;
      } else {
        showError(
          response.message || "Une erreur est survenue lors de la connexion."
        );
      }
    } catch (error) {
      showError("Une erreur est survenue lors de la connexion.");
    }
  });

  async function simulateApiCall(formData) {
    return new Promise((resolve) => {
      setTimeout(() => {
        resolve({
          success: true,
          message: "Connexion réussie",
        });
      }, 1000);
    });
  }

  function showError(message) {
    errorAlertContent.textContent = message;
    errorAlert.classList.remove("hidden");

    setTimeout(() => {
      errorAlert.classList.add("hidden");
    }, 5000);
  }
});