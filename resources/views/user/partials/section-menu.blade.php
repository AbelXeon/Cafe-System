<!-- SECTION 1: Menu Catalog -->
<div id="section-menu" class="page-section flex flex-1 min-h-0 bg-[#14131a]/40 w-full">
    <main class="flex-1 overflow-y-auto custom-scroll pb-24 lg:pb-8 relative" x-data="menuApp()" x-init="init()">
        <!-- STICKY TOP: Browse Menu & Category Filters Banner -->
        <div class="sticky top-0 z-20 bg-[#0f0e13]/98 backdrop-blur-xl border-b border-[#2a2731] px-4 sm:px-6 lg:px-8 py-4 sm:py-5 shadow-2xl shadow-black/60">
            <div class="space-y-3 sm:space-y-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">Browse Menu</h1>
                    </div>
                    <div class="flex items-center gap-2">
                        <h2 class="hidden sm:inline-block text-xs uppercase font-bold text-[#b08d57] tracking-wider" x-text="activeCategory"></h2>
                        <span class="text-xs bg-[#1e1c25] text-stone-400 px-2.5 py-0.5 rounded-full font-medium" x-text="visibleProducts.length + ' items'"></span>
                    </div>
                </div>

                <!-- Horizontally scrollable category list -->
                <div class="flex items-center gap-2 overflow-x-auto custom-scroll pb-1 sm:flex-wrap">
                    <template x-for="cat in categories" :key="cat">
                        <button
                            @click="activeCategory = cat"
                            :class="activeCategory === cat
                                ? 'bg-[#b08d57] text-[#0f0e13] font-bold shadow-md shadow-[#b08d57]/10'
                                : 'bg-[#14131a] text-stone-400 hover:text-white border border-[#2a2731]'"
                            class="px-3.5 sm:px-4 py-2 rounded-xl text-xs sm:text-sm whitespace-nowrap transition cursor-pointer shrink-0"
                            x-text="cat">
                        </button>
                    </template>
                </div>
            </div>
        </div>

        <!-- Products Content Area -->
        <div class="p-4 sm:p-6 lg:p-8">
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-4 gap-4 sm:gap-6">
                <template x-for="product in visibleProducts" :key="product.id">
                    <div class="bg-[#14131a] border border-[#2a2731] rounded-2xl overflow-hidden hover:border-[#b08d57]/60 transition duration-200 flex flex-col justify-between group cursor-pointer shadow-lg"
                         @click="openModal(product)">

                        <div class="relative w-full h-40 sm:h-44 bg-[#0f0e13] overflow-hidden">
                            <img :src="product.image" :alt="product.name" loading="lazy" decoding="async" class="w-full h-full object-cover group-hover:scale-105 transition duration-300 ease-out">
                            <div class="absolute top-3 right-3 bg-[#0f0e13]/85 backdrop-blur-md px-2.5 py-1 rounded-lg border border-[#2a2731]">
                                <span class="text-[#b08d57] font-extrabold text-sm" x-text="'$' + product.price.toFixed(2)"></span>
                            </div>
                        </div>

                        <div class="p-4 sm:p-5 flex flex-col flex-1 justify-between">
                            <div>
                                <div class="flex items-center justify-between gap-2">
                                    <h3 class="text-white font-bold text-sm sm:text-base leading-snug group-hover:text-[#b08d57] transition" x-text="product.name"></h3>
                                    <span class="text-[10px] bg-[#1e1c25] text-[#b08d57] px-2 py-0.5 rounded-md font-semibold shrink-0" x-text="product.category"></span>
                                </div>
                                <p class="text-stone-500 text-xs mt-1.5 line-clamp-2 leading-relaxed" x-text="product.description"></p>
                            </div>

                            <button
                                @click.stop="openModal(product)"
                                class="w-full mt-4 bg-[#1e1c25] hover:bg-[#b08d57] hover:text-[#0f0e13] text-stone-200 text-xs font-bold rounded-xl py-2.5 transition flex items-center justify-center gap-2">
                                <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                                <span>Add to Cart</span>
                            </button>
                        </div>
                    </div>
                </template>

                <div x-show="visibleProducts.length === 0" class="col-span-full py-16 text-center">
                    <div class="w-12 h-12 rounded-full bg-[#14131a] border border-[#2a2731] flex items-center justify-center mx-auto text-stone-500 mb-3">
                        <i data-lucide="package-open" class="w-6 h-6"></i>
                    </div>
                    <p class="text-stone-500 font-medium text-sm">No food items found in this category yet.</p>
                </div>
            </div>
        </div>

        <!-- Product Details & Extras Modal -->
        <div x-show="modalProduct"
             x-cloak
             class="fixed inset-0 bg-black/80 backdrop-blur-sm flex items-center justify-center z-50 p-4 overflow-y-auto"
             @click.self="closeModal()">
            <div class="bg-[#14131a] border border-[#2a2731] rounded-2xl w-full max-w-lg overflow-hidden shadow-2xl my-auto">
                <template x-if="modalProduct">
                    <div>
                        <div class="relative w-full h-48 sm:h-52 bg-[#0f0e13]">
                            <img :src="modalProduct.image" :alt="modalProduct.name" class="w-full h-full object-cover">
                            <button @click="closeModal()" class="absolute top-4 right-4 w-8 h-8 rounded-full bg-[#0f0e13]/80 text-stone-400 hover:text-white flex items-center justify-center border border-[#2a2731] transition">
                                <i data-lucide="x" class="w-4 h-4"></i>
                            </button>
                        </div>

                        <div class="p-5 sm:p-6 max-h-[70vh] overflow-y-auto custom-scroll">
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <h3 class="text-lg sm:text-xl font-bold text-white tracking-tight" x-text="modalProduct.name"></h3>
                                    <span class="text-xs text-stone-500 font-medium" x-text="modalProduct.category"></span>
                                </div>
                                <span class="text-[#b08d57] font-black text-base sm:text-lg" x-text="'$' + modalProduct.price.toFixed(2)"></span>
                            </div>
                            <p class="text-stone-400 text-xs sm:text-sm mt-2 leading-relaxed" x-text="modalProduct.description"></p>

                            <!-- Extras / Add-ons Section -->
                            <div class="mt-6 border-t border-[#2a2731] pt-4" x-show="extras.length > 0">
                                <div class="flex items-center justify-between mb-3">
                                    <div class="flex items-center gap-2">
                                        <i data-lucide="sparkles" class="w-4 h-4 text-[#b08d57]"></i>
                                        <span class="text-xs uppercase font-bold text-white tracking-wider">Select Extras & Add-ons</span>
                                    </div>
                                    <span class="text-[11px] text-stone-500">Optional</span>
                                </div>

                                <div class="space-y-2.5">
                                    <template x-for="extra in extras" :key="extra.id">
                                        <div class="flex items-center justify-between p-3 rounded-xl bg-[#0f0e13] border border-[#2a2731] hover:border-[#b08d57]/40 transition">
                                            <div class="min-w-0 pr-2">
                                                <p class="text-xs sm:text-sm font-semibold text-white truncate" x-text="extra.name"></p>
                                                <p class="text-[11px] text-[#b08d57] font-bold mt-0.5" x-text="'+ $' + extra.price.toFixed(2) + ' each'"></p>
                                            </div>

                                            <div class="flex items-center gap-2 bg-[#14131a] border border-[#2a2731] rounded-lg p-1 shrink-0">
                                                <button @click="decrementExtra(extra.id)"
                                                    class="w-6 h-6 rounded bg-[#1e1c25] hover:bg-[#2a2731] text-white font-bold flex items-center justify-center text-xs transition active:scale-95">-</button>
                                                
                                                <span class="text-white font-bold w-5 text-center text-xs" x-text="getExtraQty(extra.id)"></span>
                                                
                                                <button @click="incrementExtra(extra.id)"
                                                    class="w-6 h-6 rounded bg-[#1e1c25] hover:bg-[#b08d57] hover:text-[#0f0e13] text-white font-bold flex items-center justify-center text-xs transition active:scale-95">+</button>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>

                            <!-- Item Quantity Stepper -->
                            <div class="mt-5 flex items-center justify-between border-t border-[#2a2731] pt-4">
                                <span class="text-xs uppercase font-semibold text-stone-400 tracking-wider">Order Quantity</span>
                                <div class="flex items-center gap-3 bg-[#0f0e13] border border-[#2a2731] rounded-xl p-1">
                                    <button @click="modalQty = Math.max(1, modalQty - 1)"
                                        class="w-8 h-8 rounded-lg bg-[#1e1c25] hover:bg-[#2a2731] text-white font-bold flex items-center justify-center transition">-</button>
                                    <span class="text-white font-bold w-8 text-center text-sm" x-text="modalQty"></span>
                                    <button @click="modalQty++"
                                        class="w-8 h-8 rounded-lg bg-[#1e1c25] hover:bg-[#2a2731] text-white font-bold flex items-center justify-center transition">+</button>
                                </div>
                            </div>

                            <!-- Special Instructions -->
                            <div class="mt-4">
                                <label class="block text-xs uppercase font-semibold text-stone-400 tracking-wider mb-2">Special Instructions</label>
                                <textarea x-model="modalNote" placeholder="e.g. No onions, sauce on the side..." rows="2"
                                    class="cd-input w-full bg-[#0f0e13] border border-[#2a2731] rounded-xl px-3.5 py-2.5 text-sm text-white placeholder:text-stone-600 focus:outline-none transition"></textarea>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex gap-3 mt-6">
                                <button @click="closeModal()"
                                    class="w-1/3 bg-[#1e1c25] hover:bg-[#2a2731] text-white text-sm font-semibold rounded-xl py-3 transition">Cancel</button>
                                <button @click="addToCartFromModal()"
                                    class="w-2/3 bg-[#b08d57] hover:bg-[#c9a36b] text-[#0f0e13] text-sm font-bold rounded-xl py-3 transition flex items-center justify-center gap-2 shadow-lg shadow-[#b08d57]/10">
                                    <span>Add to Cart</span>
                                    <span class="font-normal opacity-50">|</span>
                                    <span x-text="'$' + modalTotalPrice.toFixed(2)"></span>
                                </button>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        <!-- Confirmation Modal -->
        <div x-show="showConfirm"
             x-cloak
             class="fixed inset-0 bg-black/80 backdrop-blur-sm flex items-center justify-center z-50 p-4 overflow-y-auto"
             @click.self="showConfirm = false">
            <div class="bg-[#14131a] border border-[#2a2731] rounded-2xl w-full max-w-lg p-5 sm:p-8 shadow-2xl my-auto">
                <div class="flex items-center justify-between pb-4 border-b border-[#2a2731]">
                    <h3 class="text-lg sm:text-xl font-bold text-white tracking-tight">Confirm Order</h3>
                    <button @click="showConfirm = false" class="text-stone-500 hover:text-white transition">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>

                <div class="space-y-3 max-h-40 sm:max-h-48 overflow-y-auto my-4 pr-1 custom-scroll">
                    <template x-for="item in $store.cart.items" :key="item.id + (item.note || '') + (item.extrasText || '')">
                        <div class="flex justify-between items-start text-sm py-1.5 border-b border-[#1e1c25]/60 last:border-0">
                            <div class="min-w-0 pr-4">
                                <span class="text-white font-medium block truncate text-xs sm:text-sm" x-text="item.qty + 'x ' + item.name"></span>
                                <span x-show="item.extrasText" class="text-[#b08d57] text-[11px] block mt-0.5" x-text="item.extrasText"></span>
                                <span x-show="item.note" class="text-stone-500 text-[11px] truncate block" x-text="'Note: ' + item.note"></span>
                            </div>
                            <span class="text-stone-300 font-bold whitespace-nowrap text-xs sm:text-sm" x-text="'$' + (item.price * item.qty).toFixed(2)"></span>
                        </div>
                    </template>
                </div>

                <div class="border-t border-[#2a2731] pt-3 flex justify-between items-center mb-5">
                    <span class="text-stone-400 font-semibold text-sm">Total Due</span>
                    <span class="text-[#b08d57] font-black text-lg sm:text-xl" x-text="'$' + $store.cart.total.toFixed(2)"></span>
                </div>

                <div>
                    <label class="block text-xs uppercase font-semibold text-stone-400 tracking-wider mb-2">Delivery Destination</label>
                    <div class="space-y-2 max-h-36 overflow-y-auto pr-1 custom-scroll mb-2">
                        <template x-for="addr in $store.addresses.list" :key="addr.id">
                            <label class="flex items-center gap-3 bg-[#0f0e13] border border-[#2a2731] rounded-xl p-3 cursor-pointer text-sm hover:border-[#b08d57]/60 transition">
                                <input type="radio" name="addr" :value="addr.id" x-model.number="$store.addresses.selectedId" class="text-[#b08d57] focus:ring-0">
                                <div class="min-w-0">
                                    <span class="text-white font-bold block truncate text-xs sm:text-sm" x-text="addr.name"></span>
                                    <span class="text-stone-500 text-[11px] truncate block" x-text="addr.address || 'No street text'"></span>
                                </div>
                            </label>
                        </template>
                        <p x-show="$store.addresses.list.length === 0" class="text-stone-500 text-xs p-2 bg-[#0f0e13] rounded-lg">
                            No saved addresses. Add one under "My Addresses" tab or continue for Dine-In.
                        </p>
                    </div>
                </div>

                <p x-show="orderError" x-text="orderError" class="text-rose-400 text-xs mt-3 bg-rose-500/10 border border-rose-500/20 p-2.5 rounded-xl"></p>

                <div class="flex gap-3 mt-6">
                    <button @click="showConfirm = false" :disabled="placingOrder"
                        class="w-1/3 bg-[#1e1c25] hover:bg-[#2a2731] text-white text-sm font-semibold rounded-xl py-3 transition">Back</button>
                    <button @click="placeOrder()" :disabled="placingOrder"
                        class="w-2/3 bg-[#b08d57] hover:bg-[#c9a36b] disabled:opacity-50 text-[#0f0e13] text-sm font-bold rounded-xl py-3 transition flex items-center justify-center gap-2">
                        <span x-show="!placingOrder">Place Order Now</span>
                        <span x-show="placingOrder">Submitting...</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Floating Mobile Cart Bar -->
        <div x-show="$store.cart.count > 0"
             x-cloak
             class="fixed bottom-4 left-4 right-4 lg:hidden z-30">
            <button
                @click="mobileCartOpen = true; $nextTick(() => lucide.createIcons())"
                class="w-full bg-[#b08d57] hover:bg-[#c9a36b] text-[#0f0e13] font-extrabold rounded-2xl p-4 shadow-2xl flex items-center justify-between transition active:scale-[0.98]">
                <div class="flex items-center gap-2.5">
                    <div class="w-7 h-7 rounded-lg bg-[#0f0e13]/20 flex items-center justify-center font-black text-xs" x-text="$store.cart.count"></div>
                    <span class="text-sm">View Cart</span>
                </div>
                <div class="flex items-center gap-2 text-sm">
                    <span x-text="'$' + $store.cart.total.toFixed(2)"></span>
                    <i data-lucide="arrow-right" class="w-4 h-4"></i>
                </div>
            </button>
        </div>

    </main>

    <!-- Cart Backdrop for Mobile Drawer -->
    <div x-show="mobileCartOpen"
         x-cloak
         x-transition:enter="transition-opacity ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="mobileCartOpen = false"
         class="fixed inset-0 bg-black/80 backdrop-blur-sm z-40 lg:hidden">
    </div>

    <!-- Cart Sidebar -->
    <aside
        :class="mobileCartOpen ? 'translate-x-0' : 'translate-x-full lg:translate-x-0'"
        class="fixed lg:static top-0 right-0 bottom-0 w-80 sm:w-96 lg:w-80 xl:w-96 bg-[#0f0e13] border-l border-[#1e1c25] flex flex-col shrink-0 justify-between z-50 lg:z-10 transition-transform duration-300 ease-in-out">

        <div class="p-5 sm:p-6 border-b border-[#1e1c25] flex items-center justify-between">
            <div class="flex items-center gap-2">
                <i data-lucide="shopping-bag" class="w-5 h-5 text-[#b08d57]"></i>
                <h2 class="text-base font-bold text-white">Order Summary</h2>
            </div>
            <div class="flex items-center gap-2">
                <span class="text-xs bg-[#1e1c25] text-stone-400 px-2.5 py-1 rounded-full font-bold" x-text="$store.cart.count + ' items'"></span>
                <button @click="mobileCartOpen = false" class="lg:hidden p-1.5 rounded-xl text-stone-400 hover:text-white hover:bg-[#1e1c25] transition" aria-label="Close Cart">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
        </div>

        <div class="flex-1 overflow-y-auto p-4 space-y-3 custom-scroll">
            <template x-for="item in $store.cart.items" :key="item.id + (item.note || '') + (item.extrasText || '')">
                <div class="bg-[#14131a] border border-[#2a2731] rounded-xl p-3 flex gap-3 items-center">
                    <img :src="item.image" loading="lazy" decoding="async" class="w-14 h-14 object-cover rounded-lg bg-[#0f0e13] shrink-0">
                    <div class="flex-1 min-w-0">
                        <div class="flex items-start justify-between gap-1">
                            <p class="text-white text-xs font-bold truncate" x-text="item.name"></p>
                            <button @click="$store.cart.remove(item)" class="text-stone-500 hover:text-rose-400 transition text-xs shrink-0">
                                <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                            </button>
                        </div>
                        <p x-show="item.extrasText" class="text-[#b08d57] text-[11px] truncate mt-0.5" x-text="item.extrasText"></p>
                        <p x-show="item.note" class="text-stone-500 text-[11px] truncate mt-0.5" x-text="'Note: ' + item.note"></p>

                        <div class="flex items-center justify-between mt-2.5">
                            <div class="flex items-center gap-1.5 bg-[#0f0e13] border border-[#2a2731] rounded-lg px-1.5 py-0.5">
                                <button @click="$store.cart.decrement(item)" class="text-stone-400 hover:text-white text-xs px-1">-</button>
                                <span class="text-white text-xs font-bold px-1" x-text="item.qty"></span>
                                <button @click="$store.cart.increment(item)" class="text-stone-400 hover:text-white text-xs px-1">+</button>
                            </div>
                            <span class="text-[#b08d57] text-xs font-bold" x-text="'$' + (item.price * item.qty).toFixed(2)"></span>
                        </div>
                    </div>
                </div>
            </template>

            <div x-show="$store.cart.items.length === 0" class="h-64 flex flex-col items-center justify-center text-center p-4">
                <div class="w-12 h-12 rounded-full bg-[#14131a] border border-[#2a2731] flex items-center justify-center text-stone-600 mb-3">
                    <i data-lucide="shopping-basket" class="w-6 h-6"></i>
                </div>
                <p class="text-stone-400 text-sm font-medium">Your basket is empty</p>
                <p class="text-stone-600 text-xs mt-1">Select meals from the menu to start ordering</p>
            </div>
        </div>

        <div class="border-t border-[#1e1c25] p-4 sm:p-5 bg-[#0f0e13] space-y-3">
            <div class="space-y-1.5 text-xs">
                <div class="flex justify-between text-stone-400">
                    <span>Items Total</span>
                    <span class="text-stone-200 font-medium" x-text="'$' + $store.cart.total.toFixed(2)"></span>
                </div>
                <div class="flex justify-between text-stone-400">
                    <span>Tax &amp; Fees</span>
                    <span class="text-stone-200 font-medium">$0.00</span>
                </div>
                <div class="flex justify-between text-sm pt-2 border-t border-[#1e1c25] font-bold">
                    <span class="text-white">Estimated Total</span>
                    <span class="text-[#b08d57] text-base" x-text="'$' + $store.cart.total.toFixed(2)"></span>
                </div>
            </div>

            <button
                @click="mobileCartOpen = false; document.dispatchEvent(new CustomEvent('open-confirm'))"
                :disabled="$store.cart.items.length === 0"
                class="w-full bg-[#b08d57] hover:bg-[#c9a36b] disabled:opacity-30 disabled:cursor-not-allowed text-[#0f0e13] font-bold rounded-xl py-3 transition flex items-center justify-center gap-2">
                <span>Checkout Now</span>
                <i data-lucide="arrow-right" class="w-4 h-4"></i>
            </button>
        </div>
    </aside>
</div>