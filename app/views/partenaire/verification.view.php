<div class="container mx-auto p-4">
    <div class="text-sm font-medium text-center text-gray-500 border-b border-gray-200">
        <ul class="flex flex-wrap -mb-px" id="verificationTabs">
            <li class="me-2">
                <a href="#qrcode" class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 tab-link active" aria-current="page">QR Code Verification</a>
            </li>
            <li class="me-2">
                <a href="#id" class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 tab-link">ID Verification</a>
            </li>
        </ul>
    </div>

    <div id="qrcode" class="tab-pane">
        <div class="max-w-md mx-auto mt-8 p-6 bg-white rounded-lg shadow-md">
            <h2 class="text-xl font-bold mb-4 text-center">Vérification avec code QR</h2>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Membre ID Unique</label>
                    <input type="text" id="qrMemberId" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <button id="generateQR" class="w-full bg-primary text-white px-4 py-2 rounded-md hover:bg-blue-700 transition-colors">
                    Obtenir code QR
                </button>
                <div id="qrResult" class="hidden mt-4">
                    <div id="qrImage" class="mb-4 text-center">


                    </div>
                    <div id="qrVerificationResult" class="text-center p-4 rounded-lg">

                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="id" class="tab-pane hidden">
        <div class="max-w-md mx-auto mt-8 p-6 bg-white rounded-lg shadow-md">
            <h2 class="text-xl font-bold mb-4 text-center">Vérification direct avec identifiant unique</h2>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Membre ID Unique</label>
                    <input type="text" id="directMemberId" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <button id="verifyId" class="w-full bg-primary text-white px-4 py-2 rounded-md hover:bg-blue-700 transition-colors">
                    Vérifier l'éligibilité
                </button>
                <div id="idVerificationResult" class="hidden text-center p-4 rounded-lg">
                </div>
            </div>
        </div>
    </div>
</div>
<script src="<?= ROOT ?>public/assets/js/verification_content.js"></script>