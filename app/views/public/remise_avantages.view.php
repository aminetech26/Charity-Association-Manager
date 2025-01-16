<?php
class RemiseAvantages_view {
    use View;

    public function showOffersSection() {
        ?>
        <div class="min-h-screen py-12 bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="mb-8">
                    <div class="flex flex-col md:flex-row gap-4 items-center justify-between">
                        <div class="relative w-full md:w-96">
                            <input type="text" 
                                   id="offerSearch" 
                                   class="w-full px-4 py-2 pl-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-white focus:ring-primary-500 focus:border-primary-500" 
                                   placeholder="Rechercher une offre...">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <svg class="w-4 h-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                        </div>
                        
                        <div class="w-full md:w-64">
                            <select id="sortValue" class="w-full px-4 py-2 text-sm text-gray-900 border border-gray-300 rounded-lg bg-white focus:ring-primary-500 focus:border-primary-500">
                                <option value="value_desc">Prix (Plus élevé)</option>
                                <option value="value_asc">Prix (Plus bas)</option>
                                <option value="date_desc">Plus récent</option>
                                <option value="date_asc">Plus ancien</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">Offres Spéciales</h2>
                    <div id="specialOffers" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    </div>
                </div>

                <div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">Toutes les Offres</h2>
                    <div id="regularOffers" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    </div>
                </div>

                <div class="mt-8 flex justify-center">
                    <nav class="flex items-center space-x-2" id="pagination">
                    </nav>
                </div>
            </div>
        </div>
        <?php
    }
}
