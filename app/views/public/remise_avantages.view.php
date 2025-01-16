<?php
class RemiseAvantages_view {
    use View;

    public function showOffersSection() {
        ?>
        <div class="min-h-screen py-12 bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="mb-8 bg-white p-4 rounded-lg shadow">
                    <form id="filterForm" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Type d'offre</label>
                            <select name="type_offre" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                                <option value="">Tous</option>
                                <option value="REDUCTION">Réduction</option>
                                <option value="CADEAU">Cadeau</option>
                                <option value="SPECIAL">Offre Spéciale</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Partenaire</label>
                            <select name="partenaire" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                                <option value="">Tous</option>
                                <!-- Will be populated by JS -->
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Trier par</label>
                            <select name="sort" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                                <option value="date_desc">Plus récent</option>
                                <option value="date_asc">Plus ancien</option>
                                <option value="value_desc">Valeur (décroissant)</option>
                                <option value="value_asc">Valeur (croissant)</option>
                            </select>
                        </div>
                        <div class="flex items-end">
                            <button type="submit" class="w-full bg-primary text-white px-4 py-2 rounded-md hover:bg-primary-dark">
                                Filtrer
                            </button>
                        </div>
                    </form>
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
