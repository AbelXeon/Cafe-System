<!-- SECTION 4: Delivery Addresses -->
<div id="section-address" class="page-section hidden flex-1 overflow-y-auto p-4 sm:p-8 lg:p-12 custom-scroll bg-[#14131a]/40 w-full" x-data="addressApp()" x-init="init()">
    <div class="max-w-3xl mx-auto pb-16">
        <div class="mb-6 sm:mb-8">
            <h1 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">Delivery Addresses</h1>
            <p class="text-stone-500 text-xs sm:text-sm mt-1">Manage saved locations for quick, accurate delivery</p>
        </div>

        <!-- New Address Form -->
        <div class="mb-8 sm:mb-10 pb-6 sm:pb-8 border-b border-[#2a2731]">
            <h3 class="text-base font-bold text-white mb-4 flex items-center gap-2">
                <i data-lucide="plus-circle" class="w-4 h-4 text-[#b08d57]"></i>
                <span>Add New Address</span>
            </h3>

            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-stone-400 mb-2">Address Label <span class="text-[#b08d57]">*</span></label>
                    <input type="text" x-model="form.name" placeholder="e.g. Home, Office, Dormitory"
                        class="cd-input w-full bg-[#0f0e13] border border-[#2a2731] rounded-xl px-4 py-2.5 text-sm text-white placeholder-stone-600 focus:outline-none transition">
                </div>
                
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label class="block text-xs font-semibold uppercase tracking-wider text-stone-400">Street Address / Landmark</label>
                        <span class="text-[11px] text-stone-500" x-text="resolvingAddress ? 'Looking up exact street address...' : 'Auto-filled on map pin drag / click'"></span>
                    </div>
                    <input type="text" x-model="form.address" placeholder="Street name, apartment, building no."
                        class="cd-input w-full bg-[#0f0e13] border border-[#2a2731] rounded-xl px-4 py-2.5 text-sm text-white placeholder-stone-600 focus:outline-none transition">
                </div>

                <!-- Action Buttons for Location -->
                <div class="pt-1 flex flex-wrap gap-2.5 items-center">
                    <button @click="useCurrentLocation()" :disabled="capturing"
                        type="button"
                        class="inline-flex items-center gap-2 text-xs font-bold text-[#b08d57] hover:text-[#c9a36b] bg-[#b08d57]/10 hover:bg-[#b08d57]/20 border border-[#b08d57]/30 px-3.5 py-2.5 rounded-xl disabled:opacity-50 transition">
                        <i data-lucide="crosshair" class="w-4 h-4"></i>
                        <span x-show="!capturing">Detect Current GPS Location</span>
                        <span x-show="capturing" x-text="gpsStatusText || 'Locking GPS sat signal...'"></span>
                    </button>

                    <button @click="openManualPicker()"
                        type="button"
                        class="inline-flex items-center gap-2 text-xs font-bold text-stone-200 hover:text-white bg-[#1e1c25] hover:bg-[#2a2731] border border-[#2a2731] hover:border-[#b08d57]/50 px-3.5 py-2.5 rounded-xl transition">
                        <i data-lucide="map" class="w-4 h-4 text-[#b08d57]"></i>
                        <span>Choose / Drag on Map</span>
                    </button>

                    <span x-show="accuracy" class="text-[11px] font-mono text-emerald-400 bg-emerald-500/10 border border-emerald-500/20 px-2.5 py-1 rounded-lg" x-text="'Precision: ±' + Math.round(accuracy) + 'm'"></span>
                </div>

                <!-- Live Draggable Location Map Preview -->
                <div x-show="form.latitude !== null && form.longitude !== null" x-cloak class="mt-4 rounded-2xl overflow-hidden border border-[#b08d57]/40 bg-[#0f0e13] shadow-xl">
                    <div class="px-4 py-2.5 bg-[#14131a] border-b border-[#2a2731] flex flex-wrap items-center justify-between gap-2">
                        <div class="flex items-center gap-2 text-xs font-semibold text-white">
                            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                            <span>Delivery Pin Location</span>
                            <span class="text-[11px] text-stone-400 font-normal">(Drag pin or click map to adjust exact spot)</span>
                        </div>
                        <span class="text-[11px] font-mono text-[#b08d57]" x-text="Number(form.latitude).toFixed(6) + ', ' + Number(form.longitude).toFixed(6)"></span>
                    </div>
                    <div id="detect-preview-map" class="w-full h-56 sm:h-72 z-10"></div>
                </div>

                <!-- Form Error -->
                <p x-show="error" x-text="error" class="text-rose-400 text-xs bg-rose-500/10 border border-rose-500/20 p-2.5 rounded-xl"></p>

                <div class="pt-2">
                    <button @click="save()" :disabled="saving"
                        class="bg-[#b08d57] hover:bg-[#c9a36b] disabled:opacity-50 text-[#0f0e13] text-sm font-bold rounded-xl px-6 py-2.5 transition flex items-center gap-2 shadow-lg shadow-[#b08d57]/10">
                        <i data-lucide="save" class="w-4 h-4"></i>
                        <span x-show="!saving">Save Address</span>
                        <span x-show="saving">Saving...</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Saved Addresses List -->
        <div>
            <h3 class="text-sm font-bold uppercase tracking-wider text-stone-400 mb-4">Saved Locations</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <template x-for="addr in $store.addresses.list" :key="addr.id">
                    <div class="bg-[#14131a] border border-[#2a2731] rounded-2xl overflow-hidden flex flex-col justify-between shadow-lg hover:border-[#b08d57]/50 transition">
                        
                        <!-- Saved Location Map Banner Preview -->
                        <div x-show="addr.latitude && addr.longitude" 
                             x-init="$nextTick(() => initSavedMiniMap($el, addr.latitude, addr.longitude))"
                             class="w-full h-32 bg-[#0f0e13] border-b border-[#2a2731] relative z-0">
                        </div>

                        <div class="p-5 flex-1 flex flex-col justify-between">
                            <div>
                                <div class="flex items-center justify-between">
                                    <h4 class="text-white font-bold text-base" x-text="addr.name"></h4>
                                    <span class="text-[10px] bg-[#1e1c25] text-stone-400 uppercase font-bold px-2 py-0.5 rounded">Saved</span>
                                </div>
                                <p class="text-stone-400 text-xs sm:text-sm mt-1.5 line-clamp-2" x-text="addr.address || 'GPS Coordinate Location'"></p>
                                
                                <div x-show="addr.latitude" class="flex items-center justify-between mt-3 pt-2 border-t border-[#2a2731]/60 text-[11px]">
                                    <span class="text-stone-500 font-mono">
                                        GPS: <span x-text="Number(addr.latitude).toFixed(5) + ', ' + Number(addr.longitude).toFixed(5)"></span>
                                    </span>
                                    <a :href="`https://www.google.com/maps?q=${addr.latitude},${addr.longitude}`" target="_blank" class="text-[#b08d57] hover:underline flex items-center gap-1 font-semibold">
                                        <span>Open Map</span>
                                        <i data-lucide="external-link" class="w-3 h-3"></i>
                                    </a>
                                </div>
                            </div>

                            <div class="mt-4 pt-3 border-t border-[#2a2731]/80 flex justify-end">
                                <button @click="remove(addr.id)" class="text-rose-400 hover:text-rose-300 text-xs font-semibold transition flex items-center gap-1">
                                    <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                    <span>Delete</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </template>

                <div x-show="$store.addresses.list.length === 0" class="col-span-full py-12 text-center bg-[#14131a]/40 border border-[#2a2731]/50 rounded-2xl">
                    <div class="w-12 h-12 rounded-full bg-[#14131a] border border-[#2a2731] flex items-center justify-center mx-auto text-stone-500 mb-3">
                        <i data-lucide="map-pin-off" class="w-6 h-6"></i>
                    </div>
                    <p class="text-stone-400 font-medium text-sm">No saved locations found.</p>
                    <p class="text-stone-600 text-xs mt-1">Use the form above to add your home or current GPS location.</p>
                </div>
            </div>
        </div>
    </div>
</div>