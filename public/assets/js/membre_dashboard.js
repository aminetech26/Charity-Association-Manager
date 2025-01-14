const ROOT = "http://localhost/TDWProject/";

document.addEventListener("DOMContentLoaded", function () {
  const userMenuButton = document.getElementById("user-menu-button");
  const userDropdown = document.getElementById("user-dropdown");

  if (userMenuButton && userDropdown) {
    userMenuButton.addEventListener("click", function () {
      userDropdown.classList.toggle("hidden");
    });

    document.addEventListener("click", function (event) {
      if (
        !userMenuButton.contains(event.target) &&
        !userDropdown.contains(event.target)
      ) {
        userDropdown.classList.add("hidden");
      }
    });
  }

  const notificationsButton = document.getElementById(
    "notifications-menu-button"
  );
  const notificationsDropdown = document.getElementById(
    "notifications-dropdown"
  );
  const notificationsBadge = document.getElementById("notification-badge");
  const notificationsList = document.getElementById("notifications-list");

  let unreadCount = 0;

  if (notificationsButton && notificationsDropdown) {
    notificationsButton.addEventListener("click", function () {
      notificationsDropdown.classList.toggle("hidden");
      if (!notificationsDropdown.classList.contains("hidden")) {
        loadNotifications();
      }
    });

    document.addEventListener("click", function (event) {
      if (
        !notificationsButton.contains(event.target) &&
        !notificationsDropdown.contains(event.target)
      ) {
        notificationsDropdown.classList.add("hidden");
      }
    });
  }

  function loadNotifications() {
    fetch(`${ROOT}public/assets/json/notif.json`)
      .then((response) => response.json())
      .then((data) => {
        updateNotifications(data.notifications);
        updateUnreadCount(data.unreadCount);
      })
      .catch((error) => {
        console.error("Error loading notifications:", error);
        notificationsList.innerHTML =
          '<div class="p-4 text-gray-500">Erreur de chargement des notifications.</div>';
      });
  }

  function updateNotifications(notifications) {
    notificationsList.innerHTML = "";

    if (notifications.length === 0) {
      notificationsList.innerHTML =
        '<div class="p-4 text-gray-500">Aucune notification.</div>';
      return;
    }

    notifications.forEach((notification) => {
      const notificationElement = document.createElement("div");
      notificationElement.className = `p-4 ${
        notification.read ? "bg-white" : "bg-blue-50"
      }`;
      notificationElement.innerHTML = `
                <div class="flex items-start">
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-900">${
                          notification.title
                        }</p>
                        <p class="text-sm text-gray-500">${
                          notification.message
                        }</p>
                        <p class="text-xs text-gray-400 mt-1">${
                          notification.date
                        }</p>
                    </div>
                    ${
                      !notification.read
                        ? `
                        <button 
                            class="text-blue-600 hover:text-blue-800 text-sm"
                            onclick="markAsRead(${notification.id})"
                        >
                            Marquer comme lu
                        </button>
                    `
                        : ""
                    }
                </div>
            `;
      notificationsList.appendChild(notificationElement);
    });
  }

  function updateUnreadCount(count) {
    unreadCount = count;
    if (unreadCount > 0) {
      notificationsBadge.textContent = unreadCount > 99 ? "99+" : unreadCount;
      notificationsBadge.classList.remove("hidden");
    } else {
      notificationsBadge.classList.add("hidden");
    }
  }

  function markAsRead(notificationId) {
    fetch(`${ROOT}membre/Membre/markNotificationAsRead`, {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({ notificationId }),
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          loadNotifications();
        }
      })
      .catch((error) =>
        console.error("Error marking notification as read:", error)
      );
  }

  const drawerButton = document.querySelector(
    '[data-drawer-target="logo-sidebar"]'
  );
  const drawer = document.getElementById("logo-sidebar");

  if (drawerButton && drawer) {
    drawerButton.addEventListener("click", function () {
      drawer.classList.toggle("-translate-x-full");
    });
  }

  const menuItems = document.querySelectorAll(".menu-item");

  menuItems.forEach((item) => {
    item.addEventListener("click", function (e) {
      e.preventDefault();
      const target = this.getAttribute("data-target");
      if (target) {
        loadContent(target);
      }
    });
  });

  function loadContent(target) {
    const mainContent = document.querySelector("main");
    if (!mainContent) return;

    mainContent.innerHTML = "";

    document
      .querySelectorAll("script[data-dynamic-script]")
      .forEach((oldScript) => {
        oldScript.remove();
      });

    fetch(`${ROOT}public/Membre/${target}`)
      .then((response) =>
        response.ok ? response.text() : Promise.reject("Response not ok")
      )
      .then((data) => {
        mainContent.innerHTML = data;

        const scripts = mainContent.querySelectorAll("script");
        scripts.forEach((script) => {
          const newScript = document.createElement("script");
          if (script.src) {
            newScript.src = script.src;
          } else {
            newScript.textContent = script.textContent;
          }
          newScript.setAttribute("data-dynamic-script", "true");
          document.body.appendChild(newScript);
        });
      })
      .catch((error) => {
        console.error("Error:", error);
        mainContent.innerHTML = "Failed to load content.";
      });
  }

  loadNotifications();
});
