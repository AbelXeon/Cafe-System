<!-- SECTION 2: My Orders (Interactive Real-Time 5-Stage Tracker) -->
<div id="section-orders" class="page-section hidden flex-1 overflow-y-auto p-4 sm:p-8 lg:p-12 custom-scroll bg-[#14131a]/40 w-full" x-data="ordersApp()" x-init="init()">
    <div class="max-w-4xl mx-auto pb-16">
        
        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6 sm:mb-8 pb-4 border-b border-[#2a2731]">
            <div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight flex items-center gap-3">
                    <span>My Orders</span>
                    <span x-show="activeOrders.length > 0" 
                          class="text-xs bg-[#b08d57]/20 border border-[#b08d57]/40 text-[#b08d57] px-2.5 py-0.5 rounded-full font-bold inline-flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-[#b08d57] animate-pulse"></span>
                        <span x-text="activeOrders.length + ' Active'"></span>
                    </span>
                </h1>
                <p class="text-stone-400 text-xs sm:text-sm mt-1">Live updates on food prep, kitchen status, and driver delivery</p>
            </div>

            <div class="flex items-center gap-2">
                <button @click="refreshOrders()" :disabled="refreshing" 
                        class="px-3.5 py-2 rounded-xl bg-[#14131a] hover:bg-[#1e1c25] border border-[#2a2731] hover:border-[#b08d57]/40 text-stone-300 text-xs font-semibold flex items-center gap-2 transition disabled:opacity-50">
                    <i data-lucide="refresh-cw" class="w-3.5 h-3.5" :class="refreshing ? 'animate-spin text-[#b08d57]' : ''"></i>
                    <span>Refresh Status</span>
                </button>
            </div>
        </div>

        <!-- ACTIVE ORDERS (LIVE TRACKER CARDS) -->
        <div class="space-y-8 mb-12">
            <template x-for="order in activeOrders" :key="order.id">
                <div class="bg-[#14131a] border border-[#b08d57]/50 rounded-3xl overflow-hidden shadow-2xl transition duration-300 relative">
                    
                    <!-- Glowing Header Banner -->
                    <div class="p-5 sm:p-6 bg-gradient-to-r from-[#1e1c25] via-[#17161f] to-[#14131a] border-b border-[#2a2731] flex flex-wrap items-center justify-between gap-4">
                        <div class="flex items-center gap-3.5">
                            <div class="w-12 h-12 rounded-2xl bg-[#b08d57]/20 border border-[#b08d57]/40 flex items-center justify-center text-[#b08d57] shadow-lg shadow-[#b08d57]/10">
                                <i data-lucide="flame" class="w-6 h-6 animate-pulse"></i>
                            </div>
                            <div>
                                <div class="flex items-center gap-2.5">
                                    <h3 class="text-lg sm:text-xl font-black text-white" x-text="'Order #' + order.id"></h3>
                                    <span class="text-[11px] font-extrabold uppercase px-2.5 py-0.5 rounded-full bg-[#b08d57] text-[#0f0e13]" x-text="getStatusBadge(order.status)"></span>
                                </div>
                                <p class="text-xs text-stone-400 mt-0.5" x-text="order.created_at + ' (' + order.time_ago + ')'"></p>
                            </div>
                        </div>

                        <div class="text-right">
                            <span class="text-xs text-stone-500 font-semibold uppercase tracking-wider block">Total Paid</span>
                            <span class="text-xl sm:text-2xl font-black text-[#b08d57]" x-text="'$' + order.total_amount.toFixed(2)"></span>
                        </div>
                    </div>

                    <!-- 5-STAGE LIVE TIMELINE STEPPER -->
                    <div class="p-6 sm:p-8 bg-[#0f0e13]/80 border-b border-[#2a2731]">
                        <div class="relative">
                            
                            <!-- Desktop Connecting Line Bar -->
                            <div class="hidden md:block absolute top-1/2 left-8 right-8 -translate-y-5 h-1.5 bg-[#1e1c25] rounded-full z-0">
                                <div class="h-full bg-gradient-to-r from-[#b08d57] to-[#e4cb9d] rounded-full transition-all duration-700 ease-out"
                                     :style="`width: ${Math.max(0, (order.status_step - 1) * 25)}%`"></div>
                            </div>

                            <!-- Stepper Grid -->
                            <div class="grid grid-cols-1 md:grid-cols-5 gap-6 md:gap-2 relative z-10">
                                
                                <!-- STEP 1: Ordered -->
                                <div class="flex md:flex-col items-center md:text-center gap-4 md:gap-3 group">
                                    <div :class="order.status_step >= 1 
                                            ? 'bg-[#b08d57] text-[#0f0e13] ring-4 ring-[#b08d57]/20 shadow-lg shadow-[#b08d57]/30' 
                                            : 'bg-[#1e1c25] text-stone-500 border border-[#2a2731]'"
                                         class="w-12 h-12 rounded-2xl flex items-center justify-center shrink-0 transition duration-300">
                                        <i data-lucide="receipt-text" class="w-5 h-5"></i>
                                    </div>
                                    <div class="min-w-0">
                                        <h4 class="text-sm font-bold" :class="order.status_step >= 1 ? 'text-white' : 'text-stone-500'">Order Placed</h4>
                                        <p class="text-[11px] text-stone-400 mt-0.5">Received by CraveDash</p>
                                    </div>
                                </div>

                                <!-- STEP 2: In Kitchen -->
                                <div class="flex md:flex-col items-center md:text-center gap-4 md:gap-3 group">
                                    <div :class="order.status_step >= 2 
                                            ? 'bg-[#b08d57] text-[#0f0e13] ring-4 ring-[#b08d57]/20 shadow-lg shadow-[#b08d57]/30' 
                                            : 'bg-[#1e1c25] text-stone-500 border border-[#2a2731]'"
                                         class="w-12 h-12 rounded-2xl flex items-center justify-center shrink-0 transition duration-300">
                                        <i data-lucide="chef-hat" class="w-5 h-5" :class="order.status_step === 2 ? 'animate-bounce' : ''"></i>
                                    </div>
                                    <div class="min-w-0">
                                        <h4 class="text-sm font-bold" :class="order.status_step >= 2 ? 'text-white' : 'text-stone-500'">In the Kitchen</h4>
                                        <p class="text-[11px] text-stone-400 mt-0.5">Chef is preparing your meal</p>
                                    </div>
                                </div>

                                <!-- STEP 3: Ready for Pickup -->
                                <div class="flex md:flex-col items-center md:text-center gap-4 md:gap-3 group">
                                    <div :class="order.status_step >= 3 
                                            ? 'bg-[#b08d57] text-[#0f0e13] ring-4 ring-[#b08d57]/20 shadow-lg shadow-[#b08d57]/30' 
                                            : 'bg-[#1e1c25] text-stone-500 border border-[#2a2731]'"
                                         class="w-12 h-12 rounded-2xl flex items-center justify-center shrink-0 transition duration-300">
                                        <i data-lucide="package-check" class="w-5 h-5"></i>
                                    </div>
                                    <div class="min-w-0">
                                        <h4 class="text-sm font-bold" :class="order.status_step >= 3 ? 'text-white' : 'text-stone-500'">Meal Prepared</h4>
                                        <p class="text-[11px] text-stone-400 mt-0.5">Packed & ready for courier</p>
                                    </div>
                                </div>

                                <!-- STEP 4: Out for Delivery -->
                                <div class="flex md:flex-col items-center md:text-center gap-4 md:gap-3 group">
                                    <div :class="order.status_step >= 4 
                                            ? 'bg-[#b08d57] text-[#0f0e13] ring-4 ring-[#b08d57]/20 shadow-lg shadow-[#b08d57]/30 pulse-gold' 
                                            : 'bg-[#1e1c25] text-stone-500 border border-[#2a2731]'"
                                         class="w-12 h-12 rounded-2xl flex items-center justify-center shrink-0 transition duration-300">
                                        <i data-lucide="bike" class="w-5 h-5"></i>
                                    </div>
                                    <div class="min-w-0">
                                        <h4 class="text-sm font-bold" :class="order.status_step >= 4 ? 'text-white' : 'text-stone-500'">Out for Delivery</h4>
                                        <p class="text-[11px] text-stone-400 mt-0.5">Driver is on the way</p>
                                    </div>
                                </div>

                                <!-- STEP 5: Delivered -->
                                <div class="flex md:flex-col items-center md:text-center gap-4 md:gap-3 group">
                                    <div :class="order.status_step >= 5 
                                            ? 'bg-emerald-500 text-black ring-4 ring-emerald-500/20 shadow-lg shadow-emerald-500/30' 
                                            : 'bg-[#1e1c25] text-stone-500 border border-[#2a2731]'"
                                         class="w-12 h-12 rounded-2xl flex items-center justify-center shrink-0 transition duration-300">
                                        <i data-lucide="smile" class="w-5 h-5"></i>
                                    </div>
                                    <div class="min-w-0">
                                        <h4 class="text-sm font-bold" :class="order.status_step >= 5 ? 'text-emerald-400' : 'text-stone-500'">Delivered</h4>
                                        <p class="text-[11px] text-stone-400 mt-0.5">Bon Appétit!</p>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    <!-- ORDER DETAILS ACCORDION / ITEMS BREAKDOWN -->
                    <div class="p-5 sm:p-6 bg-[#14131a]">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            
                            <!-- Delivery Destination -->
                            <div class="bg-[#0f0e13] border border-[#2a2731] rounded-2xl p-4">
                                <div class="flex items-center gap-2 mb-2">
                                    <i data-lucide="map-pin" class="w-4 h-4 text-[#b08d57]"></i>
                                    <span class="text-xs font-bold uppercase tracking-wider text-stone-400">Delivery Address</span>
                                </div>
                                <p class="text-sm text-white font-medium" x-text="order.delivery_address || 'Dine-In / Counter Pickup'"></p>
                                <div x-show="order.latitude && order.longitude" class="mt-2.5 pt-2 border-t border-[#1e1c25] flex items-center justify-between">
                                    <span class="text-[11px] font-mono text-stone-500" x-text="Number(order.latitude).toFixed(4) + ', ' + Number(order.longitude).toFixed(4)"></span>
                                    <a :href="`https://www.google.com/maps?q=${order.latitude},${order.longitude}`" target="_blank"
                                       class="text-[#b08d57] hover:underline text-xs font-bold inline-flex items-center gap-1">
                                        <span>View Location</span>
                                        <i data-lucide="external-link" class="w-3 h-3"></i>
                                    </a>
                                </div>
                            </div>

                            <!-- Ordered Items list -->
                            <div class="bg-[#0f0e13] border border-[#2a2731] rounded-2xl p-4">
                                <div class="flex items-center justify-between mb-2.5 pb-2 border-b border-[#1e1c25]">
                                    <span class="text-xs font-bold uppercase tracking-wider text-stone-400">Items in this Order</span>
                                    <span class="text-[11px] text-stone-500 font-semibold" x-text="order.items.length + ' items'"></span>
                                </div>

                                <div class="space-y-2.5 max-h-44 overflow-y-auto pr-1 custom-scroll">
                                    <template x-for="item in order.items" :key="item.id">
                                        <div class="flex items-start justify-between text-xs py-1 border-b border-[#1e1c25]/40 last:border-0">
                                            <div class="min-w-0 pr-2">
                                                <span class="text-white font-bold block" x-text="item.quantity + 'x ' + item.name"></span>
                                                <span x-show="item.special_note" class="text-[#b08d57] text-[10px] block mt-0.5" x-text="item.special_note"></span>
                                            </div>
                                            <span class="text-stone-300 font-bold whitespace-nowrap" x-text="'$' + item.subtotal.toFixed(2)"></span>
                                        </div>
                                    </template>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>
            </template>
        </div>

        <!-- EMPTY ACTIVE STATE -->
        <div x-show="activeOrders.length === 0" class="py-12 px-6 text-center bg-[#14131a] border border-[#2a2731] rounded-2xl shadow-xl mb-12">
            <div class="w-16 h-16 rounded-2xl bg-[#b08d57]/10 border border-[#b08d57]/20 flex items-center justify-center text-[#b08d57] mx-auto mb-4">
                <i data-lucide="utensils-crossed" class="w-8 h-8"></i>
            </div>
            <h3 class="text-lg font-bold text-white mb-1">No Active Orders In Progress</h3>
            <p class="text-stone-400 text-xs sm:text-sm max-w-sm mx-auto mb-5">You have no pending or delivering orders right now. Explore the menu and satisfy your craving!</p>
            <button @click="showSection('menu')" class="px-5 py-2.5 rounded-xl bg-[#b08d57] hover:bg-[#c9a36b] text-[#0f0e13] font-bold text-xs inline-flex items-center gap-2 shadow-lg shadow-[#b08d57]/10 transition">
                <i data-lucide="layout-grid" class="w-4 h-4"></i>
                <span>Browse Menu Now</span>
            </button>
        </div>

        <!-- COMPLETED / PAST ORDERS HISTORY -->
        <div x-show="completedOrders.length > 0">
            <h3 class="text-sm font-bold uppercase tracking-wider text-stone-400 mb-4 flex items-center gap-2">
                <i data-lucide="history" class="w-4 h-4 text-[#b08d57]"></i>
                <span>Order History</span>
            </h3>

            <div class="space-y-3">
                <template x-for="past in completedOrders" :key="past.id">
                    <div class="bg-[#14131a] border border-[#2a2731] hover:border-[#b08d57]/40 rounded-2xl p-4 sm:p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4 transition">
                        <div class="flex items-start gap-3.5 min-w-0">
                            <div class="w-10 h-10 rounded-xl bg-[#1e1c25] border border-[#2a2731] flex items-center justify-center text-emerald-400 shrink-0">
                                <i data-lucide="check-circle-2" class="w-5 h-5"></i>
                            </div>
                            <div class="min-w-0">
                                <div class="flex items-center gap-2">
                                    <h4 class="text-sm font-bold text-white" x-text="'Order #' + past.id"></h4>
                                    <span class="text-[10px] font-bold uppercase bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 px-2 py-0.5 rounded-md" x-text="past.status"></span>
                                </div>
                                <p class="text-stone-400 text-xs mt-1 truncate" x-text="past.items.map(i => i.quantity + 'x ' + i.name).join(', ')"></p>
                                <p class="text-stone-500 text-[11px] mt-0.5" x-text="past.created_at"></p>
                            </div>
                        </div>

                        <div class="flex sm:flex-col items-center sm:items-end justify-between sm:justify-center border-t sm:border-t-0 border-[#1e1c25] pt-3 sm:pt-0">
                            <span class="text-stone-300 font-extrabold text-sm" x-text="'$' + past.total_amount.toFixed(2)"></span>
                            <span class="text-[11px] text-stone-500 font-medium" x-text="past.delivery_address ? 'Delivered' : 'Dine-In'"></span>
                        </div>
                    </div>
                </template>
            </div>
        </div>

    </div>
</div>