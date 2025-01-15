{
  const ROOT = "http://localhost/TDWProject/public";
  function correctPath(imageUrl) {
    let path = imageUrl;
    let trimmedPath = imageUrl.includes("public/")
      ? path.split("public/")[1]
      : path;
    return `${ROOT}/${trimmedPath}`;
  }
  const createPartnerCard = (partner) => `
    <div class="bg-white rounded-lg shadow-lg overflow-hidden transform transition-transform hover:-translate-y-1">
        <div class="relative">
            <img src="${correctPath(partner.logo)}" alt="${
    partner.nom
  }" class="w-full h-48 object-cover">
            <button 
                class="favorite-btn absolute top-3 right-3 p-2 rounded-full bg-white/80 backdrop-blur-sm hover:bg-white/100 hover:text-red-500 transition-colors"
                data-id="${partner.id}"
                data-favorite="${partner.isFavorite}">
                <svg class="w-6 h-6 ${
                  partner.isFavorite ? "text-red-500" : "text-gray-400"
                } transition-colors" 
                     fill="currentColor" 
                     viewBox="0 0 24 24">
                    <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                </svg>
            </button>
        </div>
        <div class="p-4">
            <h3 class="text-xl font-semibold mb-2">${partner.nom}</h3>
            <p class="text-gray-600">${partner.ville}</p>
        </div>
    </div>
`;

  const loadPartenaires = async () => {
    try {
      const response = await fetch(
        "http://localhost/TDWProject/public/Membre/getAllPartenaires"
      );
      const data = await response.json();

      if (data.status === "success") {
        const grid = document.getElementById("partners-grid");
        grid.innerHTML = data.data
          .map((partner) => createPartnerCard(partner))
          .join("");
        attachEventListeners();
      }
    } catch (error) {
      console.error("Error loading partners:", error);
    }
  };

  const toggleFavorite = async (partnerId, currentStatus) => {
    try {
      const formData = new FormData();
      formData.append("partenaire_id", partnerId);
      formData.append("is_favorite", !currentStatus);
      const response = await fetch(
        "http://localhost/TDWProject/public/Membre/toggleFavorite",
        {
          method: "POST",
          body: formData,
        }
      );
      const res = await response.json();

      if (res.status === "success") {
        alert(res.message);
        loadPartenaires();
      } else {
        alert(res.message);
      }
    } catch (error) {
      console.error("Error toggling favorite:", error);
      return null;
    }
  };

  const attachEventListeners = () => {
    document.querySelectorAll(".favorite-btn").forEach((btn) => {
      btn.addEventListener("click", async function () {
        const partnerId = this.dataset.id;
        const isFavorite = this.dataset.favorite === "true";
        const heartIcon = this.querySelector("svg");

        const result = await toggleFavorite(partnerId, isFavorite);
        if (result?.success) {
          this.dataset.favorite = (!isFavorite).toString();
          heartIcon.classList.toggle("text-red-500");
          heartIcon.classList.toggle("text-gray-400");
        }
      });
    });
  };

  loadPartenaires();
}
