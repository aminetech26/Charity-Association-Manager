const ROOT = "http://localhost/TDWProject/";
let currentPage = 1;
let isLoading = false;

function escapeHtml(unsafe) {
  return unsafe
    .replace(/&/g, "&amp;")
    .replace(/</ / g, "&lt;")
    .replace(/>/g, "&gt;")
    .replace(/"/g, "&quot;")
    .replace(/'/g, "&#039;");
}

function createArticleElement(article) {
  const div = document.createElement("div");
  div.className =
    "bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300";
  div.innerHTML = `
        <a href="${ROOT}news/article/${article.id}" class="block">
            <img 
                src="${article.thumbnail_url || ""}" 
                alt="${escapeHtml(article.titre)}"
                class="w-full h-48 object-cover"
            >
            <div class="p-6">
                <h3 class="text-xl font-semibold text-gray-900 mb-2">${escapeHtml(
                  article.titre
                )}</h3>
                <div class="text-gray-500 text-sm mb-4">
                    <time datetime="${
                      new Date(article.date_publication)
                        .toISOString()
                        .split("T")[0]
                    }">
                        ${new Date(
                          article.date_publication
                        ).toLocaleDateString()}
                    </time>
                </div>
                <p class="text-gray-600 line-clamp-3">${escapeHtml(
                  article.contenu.substring(0, 150)
                )}...</p>
            </div>
        </a>
    `;
  return div;
}

async function loadMoreNews() {
  if (isLoading) return;

  const container = document.querySelector(".news-grid");
  if (!container) {
    console.error("News grid container not found");
    return;
  }

  try {
    isLoading = true;
    const response = await fetch(
      `${ROOT}/public/Home/fetchMoreNews?page=${currentPage + 1}`
    );
    const data = await response.json();

    if (data.articles && data.articles.length > 0) {
      data.articles.forEach((article) => {
        container.appendChild(createArticleElement(article));
      });
      currentPage++;
    }
  } catch (error) {
    console.error("Error loading more news:", error);
  } finally {
    isLoading = false;
  }
}

document.addEventListener("DOMContentLoaded", () => {
  window.addEventListener("scroll", () => {
    if (
      window.innerHeight + window.scrollY >=
      document.body.offsetHeight - 1000
    ) {
      loadMoreNews();
    }
  });
});
