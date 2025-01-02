<?php
class Home_view
{
    use View;

    public function show_diaporama() {
        $carouselData = [
            [
                'src' => ROOT . 'public/assets/images/logo.png',
                'alt' => 'Slide 1',
            ],
            [
                'src' => ROOT . 'public/assets/images/SUPER_ADMIN.png',
                'alt' => 'Slide 2',
            ],
            [
                'src' => ROOT . 'public/assets/images/logo.png',
                'alt' => 'Slide 3',
            ],
            [
                'src' => ROOT . 'public/assets/images/SUPER_ADMIN.png',
                'alt' => 'Slide 4',
            ],
            [
                'src' => ROOT . 'public/assets/images/logo.png',
                'alt' => 'Slide 5',
            ],
        ];
        ?>
        <div id="default-carousel" class="relative w-full" data-carousel="slide">
        <div class="relative h-52 md:h-[500px] overflow-hidden rounded-lg">
                <?php foreach ($carouselData as $index => $item): ?>
                    <!-- Item <?= $index + 1 ?> -->
                    <div class="hidden duration-3000 ease-in-out" data-carousel-item>
                    <img src="<?= $item['src'] ?>" class="absolute block w-full -translate-x-1/2 -translate-y-1/2 top-1/2 left-1/2 object-fit h-full" alt="<?= $item['alt'] ?>">                    </div>
                <?php endforeach; ?>
            </div>
            <div class="absolute z-30 flex -translate-x-1/2 bottom-5 left-1/2 space-x-3 rtl:space-x-reverse">
                <?php foreach ($carouselData as $index => $item): ?>
                    <button type="button" class="w-3 h-3 rounded-full bg-white" aria-current="<?= $index === 0 ? 'true' : 'false' ?>" aria-label="Slide <?= $index + 1 ?>" data-carousel-slide-to="<?= $index ?>"></button>
                <?php endforeach; ?>
            </div>
            <button type="button" class="absolute top-0 start-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none" data-carousel-prev>
                <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-primary/30 dark:bg-gray-800/30 group-hover:bg-white/50 dark:group-hover:bg-gray-800/60 group-focus:ring-4 group-focus:ring-white dark:group-focus:ring-gray-800/70 group-focus:outline-none">
                    <svg class="w-4 h-4 text-primary dark:text-gray-800 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 1 1 5l4 4"/>
                    </svg>
                    <span class="sr-only">Previous</span>
                </span>
            </button>
            <button type="button" class="absolute top-0 end-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none" data-carousel-next>
                <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-primary/30 dark:bg-gray-800/30 group-hover:bg-white/50 dark:group-hover:bg-gray-800/60 group-focus:ring-4 group-focus:ring-white dark:group-focus:ring-gray-800/70 group-focus:outline-none">
                    <svg class="w-4 h-4 text-primary dark:text-gray-800 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                    </svg>
                    <span class="sr-only">Next</span>
                </span>
            </button>
        </div>
        <?php
    }
    
    function showNewsSection() {
        $newsData = [
            [
                'title' => 'Les grandes banques obtiennent un répit face à la règle Volcker de l\'ère de crise',
                'author' => 'Don Howard',
                'readTime' => '99,7 %',
                'image' => ROOT . 'public/assets/images/logo.png',
                'featured' => true
            ],
            [
                'title' => 'Comment un contrat avec le Pentagone est devenu une crise identitaire pour Google',
                'author' => 'Lauren Gregory',
                'readTime' => '98,7 %',
                'image' => ROOT . 'public/assets/images/logo.png',
            ],
            [
                'title' => 'Les comédies sur la maternité s\'accrochent à la réalité brute de la nouvelle maternité',
                'author' => 'Charlie Bell',
                'readTime' => '99,7 %',
                'image' => ROOT . 'public/assets/images/logo.png',
            ],
            [
                'title' => 'Pourquoi vous devriez arrêter d\'être si dur avec vous-même',
                'author' => 'Craig Estrada',
                'readTime' => '99,7 %',
                'image' => ROOT . 'public/assets/images/logo.png',
            ],
            [
                'title' => 'Pusha-T, un conférencier à la recherche d\'une cible, en trouve une',
                'author' => 'Henry Larson',
                'readTime' => '99,1 %',
                'image' => ROOT . 'public/assets/images/logo.png',
            ]
        ];
        ?>
        <div class="bg-background-light p-6 max-w-6xl mx-auto">
            <h2 class="text-2xl font-semibold text-primary mb-8">News</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php foreach ($newsData as $index => $item): ?>
                    <div class="<?= $index === 0 ? 'md:col-span-2' : '' ?> relative group">
                        <div class="relative h-80 overflow-hidden rounded-lg shadow-lg">
                            <img src="<?= $item['image'] ?>" alt="<?= $item['title'] ?>" class="w-full h-full object-cover">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent">
                                <div class="absolute bottom-6 left-6 right-6 text-white">
                                    <h3 class="<?= $index === 0 ? 'text-2xl' : 'text-lg' ?> font-bold mb-3"><?= $item['title'] ?></h3>
                                    <div class="flex justify-between items-center">
                                        <span class="<?= $index === 0 ? 'text-sm' : 'text-xs' ?>"><?= $item['author'] ?></span>
                                        <span class="<?= $index === 0 ? 'text-sm' : 'text-xs' ?>"><?= $item['readTime'] ?></span>
                                    </div>
                                    <button class="mt-4 bg-secondary hover:bg-secondary-hover text-white px-4 py-2 rounded-full flex items-center">
                                        <span>Lire la suite</span>
                                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php
    }


    function showMemberBenefits() {
    ?>
    <div class="bg-gradient-to-br from-background-light to-white p-8 max-w-6xl mx-auto rounded-lg shadow-md">
        <h2 class="text-2xl font-bold text-primary mb-8 border-b pb-2">Avantages offerts aux membres</h2>
        <div class="relative overflow-x-auto rounded-lg shadow-sm">
            <table class="w-full text-sm text-left">
                <thead class="text-xs uppercase bg-secondary text-white">
                    <tr>
                        <th scope="col" class="px-6 py-4 font-semibold">Partenaire</th>
                        <th scope="col" class="px-6 py-4 font-semibold">Type d'offre</th>
                        <th scope="col" class="px-6 py-4 font-semibold">Valeur</th>
                        <th scope="col" class="px-6 py-4 font-semibold">Description</th>
                    </tr>
                </thead>
                <tbody id="benefitsTableBody">
                </tbody>
            </table>
        </div>
        <nav class="flex flex-col items-start justify-between p-4 space-y-3 md:flex-row md:items-center md:space-y-0" aria-label="Table navigation">
            <span class="text-sm font-normal text-text-secondary">
                Affichage <span class="font-semibold text-text-primary" id="startIndex">0</span>-<span class="font-semibold text-text-primary" id="endIndex">0</span> sur <span class="font-semibold text-text-primary" id="totalItems">0</span>
            </span>
            <ul class="inline-flex items-stretch -space-x-px" id="pagination">
            </ul>
        </nav>
    </div>
    <?php
}

public function showPartnersLogosSection() {
    ?>
    <div class="bg-background-light p-8 max-w-6xl mx-auto">
        <h2 class="text-2xl font-semibold text-primary mb-8">Nos Partenaires</h2>
        <div
            x-data="{}"
            x-init="$nextTick(() => {
                let ul = $refs.logos;
                ul.insertAdjacentHTML('afterend', ul.outerHTML);
                ul.nextSibling.setAttribute('aria-hidden', 'true');
            })"
            class="w-full inline-flex flex-nowrap overflow-hidden [mask-image:_linear-gradient(to_right,transparent_0,_black_128px,_black_calc(100%-128px),transparent_100%)]"
        >
            <ul x-ref="logos" class="flex items-center justify-center md:justify-start [&_li]:mx-8 [&_img]:max-w-none animate-infinite-scroll">
            </ul>
        </div>
    </div>
    <?php
}

}
