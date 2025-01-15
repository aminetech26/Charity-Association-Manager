{
  const ROOT = "http://localhost/TDWProject/public";

  const formatDate = (dateStr) => {
    const date = new Date(dateStr);
    return date.toLocaleDateString("fr-FR", {
      day: "numeric",
      month: "long",
      year: "numeric",
    });
  };

  const truncateText = (text, maxLength = 100) => {
    if (text.length <= maxLength) return text;
    return text.substr(0, maxLength) + "...";
  };

  const createEventCard = (event) => `
        <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300 h-[300px] flex flex-col">
            <div class="p-4 flex flex-col h-full">
                <div class="mb-3">
                    <h3 class="text-xl font-bold text-gray-800 mb-2 line-clamp-1 overflow-hidden">${
                      event.titre
                    }</h3>
                    <div class="flex items-center text-sm text-gray-600">
                        <svg class="w-4 h-4 mr-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span class="text-xs">Du ${formatDate(
                          event.date_debut
                        )} au ${formatDate(event.date_fin)}</span>
                    </div>
                </div>
                
                <div class="flex-1">
                    <div class="mb-3">
                        <p class="text-sm text-gray-600 leading-relaxed overflow-hidden line-clamp-2">${
                          event.description
                        }</p>
                        <button 
                            class="text-blue-600 hover:text-blue-800 text-xs mt-1 voir-plus-btn inline-block"
                            data-description="${encodeURIComponent(
                              event.description
                            )}"
                        >
                            Voir plus
                        </button>
                    </div>
                    
                    <div class="flex items-center text-gray-600">
                        <svg class="w-4 h-4 mr-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <span class="text-sm truncate">${event.lieu}</span>
                    </div>
                </div>

                <div class="mt-4 pt-4 border-t">
                    <button 
                        class="volunteer-btn w-full bg-primary hover:bg-secondary text-white font-semibold py-3 px-6 rounded-lg transition-colors duration-200 flex items-center justify-center"
                        data-event-id="${event.id}"
                    >
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        Devenir bénévole
                    </button>
                </div>
            </div>
        </div>
    `;

  const createEmptyMessage = () => `
        <div class="col-span-full p-8 text-center">
            <div class="mb-4">
                <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <h3 class="text-xl font-medium text-gray-900 mb-2">Aucun événement disponible</h3>
            <p class="text-gray-500">Il n'y a pas d'événements disponibles pour le moment. Revenez plus tard !</p>
        </div>
    `;

  const loadEvents = async () => {
    try {
      const response = await fetch(`${ROOT}/Membre/getEvenementsDisponibles`);
      if (!response.ok) throw new Error("Erreur réseau");

      const data = await response.json();
      const grid = document.getElementById("events-grid");

      if (data.status === "success") {
        if (!data.data || data.data.length === 0) {
          grid.innerHTML = createEmptyMessage();
          return;
        }

        const eventCards = data.data
          .map((event) => createEventCard(event))
          .join("");
        grid.innerHTML = eventCards;
        attachEventListeners();
      } else {
        throw new Error(
          data.message || "Erreur lors du chargement des événements"
        );
      }
    } catch (error) {
      console.error("Error:", error);
      document.getElementById("events-grid").innerHTML = createEmptyMessage(
        "Une erreur s'est produite lors du chargement des événements. Veuillez réessayer plus tard."
      );
    }
  };

  const volunteerForEvent = async (eventId) => {
    try {
      const formData = new FormData();
      formData.append("evenement_id", eventId);

      const response = await fetch(`${ROOT}/Membre/volunteerForEvent`, {
        method: "POST",
        body: formData,
      });

      const data = await response.json();

      if (data.status === "success") {
        alert(data.message);
        loadEvents();
      } else {
        alert(data.message);
      }
    } catch (error) {
      console.error("Error volunteering for event:", error);
    }
  };

  const showDescriptionModal = (description) => {
    const modal = document.createElement("div");
    modal.className =
      "fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50";
    modal.innerHTML = `
            <div class="bg-white rounded-lg p-6 max-w-2xl w-full max-h-[80vh] overflow-y-auto">
                <div class="flex justify-between items-start mb-4">
                    <h3 class="text-xl font-bold text-gray-900">Description complète</h3>
                    <button class="text-gray-400 hover:text-gray-600" id="closeModal">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <p class="text-gray-600 leading-relaxed">${description}</p>
            </div>
        `;

    document.body.appendChild(modal);

    const closeModal = () => modal.remove();
    modal.querySelector("#closeModal").addEventListener("click", closeModal);
    modal.addEventListener("click", (e) => {
      if (e.target === modal) closeModal();
    });
  };

  const attachEventListeners = () => {
    // Existing volunteer button listeners
    document.querySelectorAll(".volunteer-btn").forEach((btn) => {
      btn.addEventListener("click", async function () {
        const eventId = this.dataset.eventId;
        await volunteerForEvent(eventId);
      });
    });

    // New "voir plus" button listeners
    document.querySelectorAll(".voir-plus-btn").forEach((btn) => {
      btn.addEventListener("click", function () {
        const description = decodeURIComponent(this.dataset.description);
        showDescriptionModal(description);
      });
    });
  };

  loadEvents();
}
