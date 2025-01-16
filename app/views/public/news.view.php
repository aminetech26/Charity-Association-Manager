<?php
class News_view
{
    use View;

    public function showNewsDetails($article) {
        $imageUrl = $article->thumbnail_url;
        $finalImageUrl = $imageUrl ? (strpos($imageUrl, "../public/") !== false 
            ? ROOT . str_replace("../public/", "public/", $imageUrl)
            : ROOT . "public/thumbnails/" . basename($imageUrl)) 
            : '';
        ?>
        <div class="max-w-4xl mx-auto px-4 py-8">
            <article class="bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="relative h-96">
                    <img 
                        src="<?= $finalImageUrl ?>" 
                        alt="<?= htmlspecialchars($article->titre) ?>"
                        class="w-full h-full object-cover"
                    >
                    <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/60 to-transparent p-6">
                        <h1 class="text-3xl font-bold text-white mb-2">
                            <?= htmlspecialchars($article->titre) ?>
                        </h1>
                        <div class="text-white/80">
                            <time datetime="<?= date('Y-m-d', strtotime($article->date_publication)) ?>">
                                <?= date('d M Y', strtotime($article->date_publication)) ?>
                            </time>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    <div class="prose max-w-none">
                        <?= nl2br(htmlspecialchars($article->contenu)) ?>
                    </div>
                </div>
            </article>
        </div>
        <?php
    }

    public function showNewsList($articles, $currentPage, $totalPages) {
        ?>
        <div class="max-w-7xl mx-auto px-4 py-8">
            <h2 class="text-3xl font-bold text-gray-900 mb-8">Actualités</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php foreach ($articles as $article): 
                    // Process image path
                    $imageUrl = $article->thumbnail_url;
                    $finalImageUrl = $imageUrl ? (strpos($imageUrl, "../public/") !== false 
                        ? ROOT . str_replace("../public/", "public/", $imageUrl)
                        : ROOT . "public/thumbnails/" . basename($imageUrl)) 
                        : ''
                ?>
                    <article class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300">
                        <a href="<?= ROOT ?>public/Home/article/<?= $article->id ?>" class="block">
                            <img 
                                src="<?= $finalImageUrl ?>" 
                                alt="<?= htmlspecialchars($article->titre) ?>"
                                class="w-full h-48 object-cover"
                            >
                            <div class="p-6">
                                <h3 class="text-xl font-semibold text-gray-900 mb-2">
                                    <?= htmlspecialchars($article->titre) ?>
                                </h3>
                                <div class="text-gray-500 text-sm mb-4">
                                    <time datetime="<?= date('Y-m-d', strtotime($article->date_publication)) ?>">
                                        <?= date('d M Y', strtotime($article->date_publication)) ?>
                                    </time>
                                </div>
                                <p class="text-gray-600 line-clamp-3">
                                    <?= htmlspecialchars(substr($article->contenu, 0, 150)) ?>...
                                </p>
                            </div>
                        </a>
                    </article>
                <?php endforeach; ?>
            </div>

            <!-- Pagination -->
            <?php if ($totalPages > 1): ?>
                <div class="mt-8 flex justify-center">
                    <nav class="inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                        <?php if ($currentPage > 1): ?>
                            <a href="<?= ROOT ?>public/Home/news?page=<?= $currentPage - 1 ?>" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                <span class="sr-only">Précédent</span>
                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                            </a>
                        <?php endif; ?>

                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                            <a href="<?= ROOT ?>public/Home/news?page=<?= $i ?>" 
                               class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium <?= $i === $currentPage ? 'text-primary bg-primary/10' : 'text-gray-700 hover:bg-gray-50' ?>">
                                <?= $i ?>
                            </a>
                        <?php endfor; ?>

                        <?php if ($currentPage < $totalPages): ?>
                            <a href="<?= ROOT ?>public/Home/news?page=<?= $currentPage + 1 ?>" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                <span class="sr-only">Suivant</span>
                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                </svg>
                            </a>
                        <?php endif; ?>
                    </nav>
                </div>
            <?php endif; ?>
        </div>
        <?php
    }
}
