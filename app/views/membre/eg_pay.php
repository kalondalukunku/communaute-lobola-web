<?php 
    $title = $title;
    include APP_PATH . 'views/layouts/header.php';
    include APP_PATH . 'templates/alertView.php'; 
?>

    <div class="container mx-auto py-8 px-4">
        <div class="max-w-md mx-auto">
            
            <div class="text-center mb-6 fade-in">
                <div class="inline-block bg-primary/10 p-4 rounded-3xl mb-0">
                    <i class="fas fa-lock text-primary text-2xl"></i>
                </div>
                <h1 class="font-serif text-3xl text-primary font-bold">Paiement Sécurisé</h1>
                <p class="text-gray-500 text-sm mt-2">Complétez votre transaction en toute sécurité</p>
            </div>

            <div class="payment-card rounded-[2.5rem] overflow-hidden fade-in">
                
                <div class="bg-primary p-7 text-white text-center relative overflow-hidden">
                    <div class="relative z-10 text-[#000f0e]">
                        <p class="text-[10px] uppercase tracking-[0.3em] opacity-60 mb-1">Total à payer</p>
                        <div class="text-3xl font-bold">
                            <?= Utils::getMonthsNumber($membre->modalite_engagement) * $membre->montant ?>
                            <span class="text-lg font-bold not-italic opacity-80 ml-0">
                                <?= $membre->devise   ?>
                            </span>
                        </div>
                    </div>
                    <div class="absolute -top-10 -right-10 w-32 h-32 bg-white/5 rounded-full"></div>
                    <div class="absolute -bottom-10 -left-10 w-24 h-24 bg-black/5 rounded-full"></div>
                </div>
                <div class="flex border-b border-[#cfbb30]">
                    <button onclick="switchTab('mobile')" id="btn-mobile" class="method-tab text-primary flex-1 py-5 text-[11px] uppercase font-bold tracking-widest transition-all">
                        <i class="fas fa-mobile-screen-button mr-2 opacity-70"></i> Mobile
                    </button>
                    <button onclick="switchTab('card')" id="btn-card" class="method-tab flex-1 py-5 text-[11px] uppercase font-bold tracking-widest text-gray-400 transition-all">
                        <i class="fas fa-credit-card mr-2 opacity-70"></i> Carte
                    </button>
                </div>

                <div class="p-8">
                    <div id="pane-mobile" class="space-y-6 fade-in">
                        <div class="space-y-5">
                            <div>
                                <label class="text-[10px] text-gray-400 uppercase font-extrabold tracking-widest ml-1 mb-2 block">Numéro de téléphone du payeur</label>
                                
        
                                <div class="input-group flex items-stretch border border-gray-200 rounded-2xl overflow-hidden transition-all focus-within:ring-2 focus-within:ring-primary/10 focus-within:border-primary">
                                    <div class="bg-paper border-r border-gray-200 px-3 flex items-center">
                                        <select class="bg-paper text-sm font-bold text-primary outline-none pr-6 cursor-pointer">
                                            <option value="+243">🇨🇩 +243</option>
                                            <option value="+242">🇨🇬 +242</option>
                                            <option value="+33">🇫🇷 +33</option>
                                            <option value="+1">🇺🇸 +1</option>
                                            <option value="+225">🇮🇨 +225</option>
                                            <option value="+221">🇸🇳 +221</option>
                                        </select>
                                    </div>
                                    <input type="tel" placeholder="812345678" class="flex-1 bg-paper py-4 px-4 text-sm font-bold tracking-wider outline-none placeholder:text-gray-600" style="color: #fff !important;">
                                </div>
                            </div>

                            <div>
                                <label class="text-[10px] text-gray-400 uppercase font-extrabold tracking-widest ml-1 mb-2 block">Sélectionner l'opérateur</label>
                                <div class="grid grid-cols-3 gap-3">
                                    <button class="border-2 border-primary bg-primary/5 rounded-xl py-3 flex flex-col items-center gap-1 transition-all">
                                        <span class="text-[10px] font-bold text-primary">M-PESA</span>
                                    </button>
                                    <button class="border-2 border-gray-100 hover:border-primary/30 rounded-xl py-3 flex flex-col items-center gap-1 transition-all">
                                        <span class="text-[10px] font-bold text-gray-400">AIRTEL</span>
                                    </button>
                                    <button class="border-2 border-gray-100 hover:border-primary/30 rounded-xl py-3 flex flex-col items-center gap-1 transition-all">
                                        <span class="text-[10px] font-bold text-gray-400">ORANGE</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="pane-card" class="hidden space-y-5 fade-in">
                        <div>
                            <label class="text-[10px] text-gray-400 uppercase font-extrabold tracking-widest ml-1 mb-2 block">Détenteur de la carte</label>
                            <input type="text" placeholder="NOM COMPLET" class="w-full placeholder:text-gray-600 border border-gray-200 py-3 px-5 rounded-2xl text-sm font-bold outline-none focus:border-primary focus:ring-2 focus:ring-primary/10 transition-all uppercase placeholder:text-gray-300" style="color: #fff !important;">
                        </div>
                        <div>
                            <label class="text-[10px] text-gray-400 uppercase font-extrabold tracking-widest ml-1 mb-2 block">Numéro de carte</label>
                            <div class="relative">
                                <input type="text" placeholder="0000 0000 0000 0000" class="w-full placeholder:text-gray-600 border border-gray-200 py-3 px-5 rounded-2xl text-sm font-bold outline-none focus:border-primary focus:ring-2 focus:ring-primary/10 transition-all placeholder:text-gray-300" style="color: #fff !important;">
                                <div class="absolute right-4 top-1/2 -translate-y-1/2 flex gap-2">
                                    <i class="fab fa-cc-visa text-gray-300 text-xl"></i>
                                    <i class="fab fa-cc-mastercard text-gray-300 text-xl"></i>
                                </div>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="text-[10px] text-gray-400 uppercase font-extrabold tracking-widest ml-1 mb-2 block">Expiration</label>
                                <input type="text" placeholder="MM / YY" class="w-full placeholder:text-gray-600 border border-gray-200 py-3 px-5 rounded-2xl text-sm font-bold outline-none focus:border-primary focus:ring-2 focus:ring-primary/10 transition-all text-center placeholder:text-gray-300" style="color: #fff !important;">
                            </div>
                            <div>
                                <label class="text-[10px] text-gray-400 uppercase font-extrabold tracking-widest ml-1 mb-2 block">CVC / CVV</label>
                                <input type="password" placeholder="***" class="w-full placeholder:text-gray-600 border border-gray-200 py-3 px-5 rounded-2xl text-sm font-bold outline-none focus:border-primary focus:ring-2 focus:ring-primary/10 transition-all text-center placeholder:text-gray-300" style="color: #fff !important;">
                            </div>
                        </div>
                    </div>

                    <button id="main-pay-btn" onclick="processPayment()" class="w-full mt-10 bg-primary text-[#000f0e] py-5 px-8 rounded-[1.5rem] font-bold text-md tracking-[0.2em] hover:bg-blue-900 transition-all shadow-xl shadow-primary/25 flex items-center justify-center gap-3 active:scale-[0.98]">
                        <span id="btn-text">Payer</span>
                        <i id="btn-icon" class="fas fa-arrow-right text-[10px] opacity-60"></i>
                    </button>
                    
                    <div class="flex items-center justify-center gap-4 mt-8">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/b/b5/PayPal.svg" class="h-4" alt="Paypal">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/5/5e/Visa_Inc._logo.svg" class="h-3" alt="Visa">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/2/2a/Mastercard-logo.svg" class="h-5" alt="Mastercard">
                    </div>
                </div>
            </div>

            <div class="mt-10 text-center">
                <p class="text-[10px] text-gray-400 uppercase tracking-widest flex items-center justify-center gap-2">
                    <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span> Serveur de paiement opérationnel
                </p>
                <button onclick="history.back()" class="mt-6 text-xs text-primary font-bold hover:underline">
                    <i class="fas fa-arrow-left mr-2"></i> Annuler et retourner
                </button>
            </div>
        </div>
    </div>

    <!-- <div class="fixed bottom-6 left-6 right-6 md:left-auto md:right-12 md:w-96 audio-player-mini p-4 rounded-2xl shadow-2xl text-white flex items-center gap-4 border border-primary/30">
        <div class="w-12 h-12 bg-primary rounded-lg flex-shrink-0 flex items-center justify-center text-[#000f0e]">
            <i class="fas fa-music"></i>
        </div>
        <div class="flex-grow min-w-0">
            <h4 class="text-xs font-bold truncate">L'Essence de la Paix Intérieure</h4>
            <div class="flex items-center gap-2 mt-1">
                <span class="text-[10px] text-white/50">12:45 / 45:00</span>
                <div class="flex-grow h-1 bg-white/10 rounded-full overflow-hidden">
                    <div class="h-full bg-primary" style="width: 30%"></div>
                </div>
            </div>
        </div>
        <div class="flex gap-3">
            <button class="hover:text-primary"><i class="fas fa-backward-step"></i></button>
            <button class="w-8 h-8 bg-white text-[#000f0e] rounded-full flex items-center justify-center hover:bg-primary transition"><i class="fas fa-pause"></i></button>
            <button class="hover:text-primary"><i class="fas fa-forward-step"></i></button>
        </div>
    </div> -->
    
    <script src="<?= ASSETS ?>js/modules/switch.js"></script>
    <script src="<?= ASSETS ?>js/app.js"></script>