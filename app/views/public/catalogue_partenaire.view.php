<?php
class CataloguePartenaire_view
{
    use View;


    function showCataloguePartenaires() {
        ?>
        <div class="bg-gradient-to-br from-background-light to-white p-8">
            <h2 class="text-2xl font-bold text-primary mb-8 border-b pb-2">Catalogue des partenaires de l'association</h2>
            
            <div class="flex space-x-4 mb-8" id="categoryTabs">
            </div>
    
            <div class="mb-8">
                <label for="citySearch" class="block text-sm font-medium text-gray-700">Rechercher par ville :</label>
                <input type="text" id="citySearch" placeholder="Entrez le nom d'une ville" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary">
            </div>
    
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="partnersContainer">
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

}