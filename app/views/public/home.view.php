<?php
class Home_view
{
    use View;

    public function show_diaporama() {
        ?>
        <div id="default-carousel" class="relative w-full" data-carousel="slide">
            <div class="relative h-52 md:h-[500px] overflow-hidden rounded-lg">
                <!-- Content will be dynamically loaded by JavaScript -->
            </div>
            <div class="absolute z-30 flex -translate-x-1/2 bottom-5 left-1/2 space-x-3 rtl:space-x-reverse">
                <!-- Indicators will be dynamically loaded -->
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
        ?>
        <div class="news-section">
            <!-- Le contenu sera dynamiquement rempli par home.js via fetchHomeNews -->
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
                        <!-- Le contenu sera dynamiquement rempli par home.js via fetchMemberBenefits -->
                    </tbody>
                </table>
            </div>
            <nav class="flex flex-col items-start justify-between p-4 space-y-3 md:flex-row md:items-center md:space-y-0" aria-label="Table navigation">
                <span class="text-sm font-normal text-text-secondary">
                    Affichage <span class="font-semibold text-text-primary" id="startIndex">0</span>-<span class="font-semibold text-text-primary" id="endIndex">0</span> sur <span class="font-semibold text-text-primary" id="totalItems">0</span>
                </span>
                <ul class="inline-flex items-stretch -space-x-px" id="pagination">
                    <!-- La pagination sera gérée par home.js -->
                </ul>
            </nav>
        </div>
        <?php
    }

    public function showPartnersLogosSection() {
        ?>
        <div class="bg-background-light p-8 max-w-6xl mx-auto">
            <h2 class="text-2xl font-semibold text-primary mb-8">Nos Partenaires</h2>
            <div x-data="{}" 
                 x-init="$nextTick(() => {
                     let ul = $refs.logos;
                     ul.insertAdjacentHTML('afterend', ul.outerHTML);
                     ul.nextSibling.setAttribute('aria-hidden', 'true');
                 })"
                 class="w-full inline-flex flex-nowrap overflow-hidden [mask-image:_linear-gradient(to_right,transparent_0,_black_128px,_black_calc(100%-128px),transparent_100%)]">
                <ul x-ref="logos" class="flex items-center justify-center md:justify-start [&_li]:mx-8 [&_img]:max-w-none animate-infinite-scroll">
                    <!-- Le contenu sera dynamiquement rempli par home.js via fetchPartnersLogos -->
                </ul>
            </div>
        </div>
        <?php
    }
}
