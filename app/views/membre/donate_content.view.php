<div id="faire-don" class="tab-pane p-4">
    <h2 class="text-2xl font-bold mb-6 text-gray-900 text-center">Faire un Don</h2>
    <form id="donForm" class="max-w-2xl mx-auto bg-white rounded-lg shadow-md p-6">
        <div class="mb-4">
            <label for="montant" class="block text-sm font-medium text-gray-700">Montant du Don (DZD)</label>
            <input type="number" id="montant" name="montant" required min="1"
                   class="mt-1 block w-full p-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
        </div>

        <div class="mb-4">
            <label for="receipt" class="block text-sm font-medium text-gray-700">Joindre le Reçu de Paiement</label>
            <div class="flex justify-center items-center w-full">
                <label for="receipt" class="flex flex-col justify-center items-center w-full h-32 bg-gray-50 rounded-lg border-2 border-gray-300 border-dashed cursor-pointer hover:bg-gray-100 transition-colors group">
                    <div class="flex flex-col justify-center items-center p-4">
                        <svg class="w-8 h-8 mb-2 text-gray-400 group-hover:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                        </svg>
                        <p class="text-sm text-gray-500 text-center"><span class="font-semibold">Cliquez</span> ou glissez</p>
                        <p class="text-xs text-gray-500">Fichier PDF ou Image (MAX. 10MB)</p>
                    </div>
                    <input style="opacity: 0; position: absolute; width: 100%; height: 100%; top: 0; left: 0; cursor: pointer;" 
                           name="recu_paiement" id="receipt" type="file" class="hidden" required accept=".jpg, .jpeg, .png" onchange="validateFile(this)">
                    <span class="text-sm text-red-500 hidden">Un fichier est requis</span>
                </label>
            </div>
        </div>

        <div class="mb-6">
            <label class="flex items-center">
                <input type="checkbox" id="keep_trace" name="est_tracable" value="1" class="form-checkbox h-4 w-4 text-blue-600 transition duration-150 ease-in-out">
                <span class="ml-2 text-sm text-gray-700">Garder une trace de ce don</span>
            </label>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="bg-blue-600 text-white px-5 py-2.5 rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-300">
                Envoyer le Don
            </button>
        </div>
    </form>
</div>
<script src="<?= ROOT ?>public/assets/js/donate_content.js"></script>
