<!-- Main: Orders Dispatch Queue View -->
<main x-show="!showChat" class="flex-1 flex flex-col min-h-0 bg-[#14131a]/40 w-full overflow-hidden">

    <!-- Header Bar -->
    <div class="bg-[#0f0e13]/98 backdrop-blur-xl border-b border-[#2a2731] px-4 sm:px-6 lg:px-8 py-4 shrink-0 flex flex-wrap items-center justify-between gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-black text-white tracking-tight flex items-center gap-3">
                <span>Delivery Dispatch</span>
                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-bold bg-[#b08d57]/15 border border-[#b08d57]/30 text-[#b08d57]">
                    <span class="w-2 h-2 rounded-full bg-[#b08d57] animate-pulse"></span>Auto-refreshing (6s)
                </span>
            </h1>
            <p class="text-stone-500 text-xs mt-0.5">Accept incoming orders, navigate to drop-offs, and mark deliveries complete</p>
        </div>

        <div class="flex items-center gap-2 overflow-x-auto custom-scroll">
            <button @click="activeTab = 'all'"
                :class="activeTab === 'all' ? 'bg-[#b08d57] text-[#0f0e13] font-bold shadow-md shadow-[#b08d57]/10' : 'bg-[#14131a] text-stone-400 border border-[#2a2731]'"
                class="px-3.5 py-1.5 rounded-xl text-xs font-semibold transition shrink-0 cursor-pointer">All (<span x-text="orders.length"></span>)</button>
            <button @click="activeTab = 'ready'"
                :class="activeTab === 'ready' ? 'bg-amber-500 text-black font-bold' : 'bg-[#14131a] text-amber-400/80 border border-[#2a2731]'"
                class="px-3.5 py-1.5 rounded-xl text-xs font-semibold transition shrink-0 flex items-center gap-1.5 cursor-pointer">
                <span class="w-2 h-2 rounded-full bg-amber-400"></span>Incoming (<span x-text="counts.ready"></span>)
            </button>
            <button @click="activeTab = 'out_for_delivery'"
                :class="activeTab === 'out_for_delivery' ? 'bg-sky-500 text-black font-bold' : 'bg-[#14131a] text-sky-400 border border-[#2a2731]'"
                class="px-3.5 py-1.5 rounded-xl text-xs font-semibold transition shrink-0 cursor-pointer">Active (<span x-text="counts.out_for_delivery"></span>)</button>
            <button @click="activeTab = 'delivered'"
                :class="activeTab === 'delivered' ? 'bg-emerald-500 text-black font-bold' : 'bg-[#14131a] text-emerald-400 border border-[#2a2731]'"
                class="px-3.5 py-1.5 rounded-xl text-xs font-semibold transition shrink-0 cursor-pointer">Done (<span x-text="counts.delivered"></span>)</button>
        </div>
    </div>

    <!-- Offline Banner Alert -->
    <div x-show="!online" x-cloak
         class="bg-amber-500/10 border-b border-amber-500/30 px-4 sm:px-6 lg:px-8 py-2.5 flex items-center justify-between gap-3 shrink-0">
        <p class="text-xs text-amber-400 font-semibold flex items-center gap-2">
            <i data-lucide="alert-triangle" class="w-4 h-4"></i>
            You are offline — go online to start receiving incoming ready orders.
        </p>
        <button @click="toggleOnline()" class="text-xs font-bold text-amber-300 hover:text-amber-200 underline cursor-pointer">Go Online</button>
    </div>

    <!-- Active Delivery Banner -->
    <div x-show="hasActiveDelivery" x-cloak
         class="bg-sky-500/10 border-b border-sky-500/30 px-4 sm:px-6 lg:px-8 py-2.5 flex items-center justify-between gap-3 shrink-0">
        <p class="text-xs text-sky-400 font-semibold flex items-center gap-2">
            <i data-lucide="info" class="w-4 h-4"></i>
            You have an active delivery in progress. Complete it before accepting another order.
        </p>
        <button @click="activeTab = 'out_for_delivery'" class="text-xs font-bold text-sky-300 hover:text-sky-200 underline cursor-pointer">View Active Delivery</button>
    </div>

    <!-- Orders Grid -->
    <div class="flex-1 overflow-y-auto custom-scroll p-4 sm:p-6 lg:p-8">
        <div class="grid grid-cols-1 md:grid-cols-2 2xl:grid-cols-3 gap-5">

            <template x-for="order in filteredOrders" :key="order.id">
                <div class="bg-[#14131a] border rounded-2xl overflow-hidden flex flex-col shadow-xl transition-all duration-200 min-w-0"
                     :class="{
                        'border-amber-500/60 ring-1 ring-amber-500/30': order.status === 'ready' && !hasActiveDelivery,
                        'border-[#2a2731] opacity-50 grayscale-[40%]': order.status === 'ready' && hasActiveDelivery,
                        'border-sky-500/60 ring-1 ring-sky-500/30': order.status === 'out_for_delivery',
                        'border-emerald-500/60': order.status === 'delivered',
                        'border-[#2a2731] opacity-80': order.status === 'cancelled'
                     }">

                    <!-- Card Header -->
                    <div class="p-4 sm:p-5 border-b border-[#2a2731] bg-[#0f0e13]/60 flex items-start justify-between gap-3 min-w-0">
                        <div class="min-w-0 flex-1">
                            <div class="flex items-center gap-2 flex-wrap">
                                <span class="text-base font-black text-white shrink-0" x-text="'Order #' + order.id"></span>
                                <span class="text-[10px] uppercase font-bold px-2 py-0.5 rounded-md shrink-0"
                                      :class="{
                                        'bg-amber-500/20 text-amber-400 border border-amber-500/40': order.status === 'ready',
                                        'bg-sky-500/20 text-sky-400 border border-sky-500/40': order.status === 'out_for_delivery',
                                        'bg-emerald-500/20 text-emerald-400 border border-emerald-500/40': order.status === 'delivered'
                                      }"
                                      x-text="statusLabel(order.status)"></span>
                            </div>
                            <p class="text-xs text-stone-400 mt-1 flex items-center gap-2 flex-wrap min-w-0">
                                <template x-if="order.status === 'ready'">
                                    <span class="font-bold text-amber-400">Ready for Pickup</span>
                                </template>
                                <template x-if="order.status !== 'ready'">
                                    <span class="font-bold text-white truncate" x-text="order.customer_name"></span>
                                </template>
                                <span>&bull;</span>
                                <span x-text="order.created_at"></span>
                                <span>&bull;</span>
                                <span class="text-[#b08d57]" x-text="order.time_ago"></span>
                            </p>
                        </div>
                        <div class="text-right shrink-0">
                            <span class="text-sm font-black text-[#b08d57]" x-text="'$' + (Number(order.total_amount) || 0).toFixed(2)"></span>
                            <span class="text-[10px] text-stone-500 block uppercase" x-text="order.order_type.replace('_', ' ')"></span>
                        </div>
                    </div>

                    <!-- PRE-ACCEPT VIEW (status === 'ready') -->
                    <template x-if="order.status === 'ready'">
                        <div class="p-4 sm:p-5 border-b border-[#2a2731] bg-[#0f0e13]/40">
                            <div class="flex items-start gap-3">
                                <div class="w-9 h-9 rounded-xl bg-[#1e1c25] border border-[#2a2731] flex items-center justify-center text-[#b08d57] shrink-0">
                                    <i data-lucide="map-pin" class="w-4 h-4"></i>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="text-[10px] uppercase font-bold tracking-wider text-stone-500">Delivery Drop-off Destination</p>
                                    <p class="text-sm text-white leading-snug break-words" x-text="order.address_text || 'Address will be revealed upon acceptance'"></p>
                                    <p x-show="!hasActiveDelivery" class="text-[11px] text-stone-500 mt-1">Accept the order to view customer contact info, live GPS coordinates, and meal instructions.</p>
                                    <p x-show="hasActiveDelivery" class="text-[11px] text-amber-400/90 font-medium mt-1">Complete your active delivery before accepting this order.</p>
                                </div>
                            </div>
                        </div>
                    </template>

                    <!-- POST-ACCEPT VIEW (out_for_delivery / delivered) -->
                    <template x-if="order.status !== 'ready'">
                        <div class="p-4 sm:p-5 space-y-3 border-b border-[#2a2731] bg-[#0f0e13]/40">

                            <!-- Customer Contact Info -->
                            <div class="flex items-start gap-3">
                                <div class="w-9 h-9 rounded-xl bg-[#1e1c25] border border-[#2a2731] flex items-center justify-center text-[#b08d57] shrink-0">
                                    <i data-lucide="user" class="w-4 h-4"></i>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="text-[10px] uppercase font-bold tracking-wider text-stone-500">Customer</p>
                                    <p class="text-sm font-bold text-white truncate" x-text="order.customer_name"></p>
                                    <div class="flex items-center gap-2 mt-1" x-show="order.customer_phone">
                                        <i data-lucide="phone" class="w-3.5 h-3.5 text-stone-500"></i>
                                        <a :href="'tel:' + (order.customer_phone || '')"
                                           class="text-xs font-semibold text-[#b08d57] hover:text-[#c9a36b] transition"
                                           x-text="order.customer_phone"></a>
                                    </div>
                                </div>
                                <a x-show="order.customer_phone" :href="'tel:' + (order.customer_phone || '')"
                                   class="shrink-0 inline-flex items-center gap-1.5 text-xs font-bold bg-[#b08d57] text-[#0f0e13] px-3 py-2 rounded-xl hover:bg-[#c9a36b] transition shadow-md shadow-[#b08d57]/10">
                                    <i data-lucide="phone-call" class="w-3.5 h-3.5"></i><span>Call</span>
                                </a>
                            </div>

                            <!-- Delivery GPS Destination & Turn-by-Turn Navigation -->
                            <div class="flex items-start gap-3">
                                <div class="w-9 h-9 rounded-xl bg-[#1e1c25] border border-[#2a2731] flex items-center justify-center text-[#b08d57] shrink-0">
                                    <i data-lucide="map-pin" class="w-4 h-4"></i>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="text-[10px] uppercase font-bold tracking-wider text-stone-500">Drop-off Point</p>
                                    <p class="text-sm text-white leading-snug break-words" x-text="order.address_text || 'No street text provided'"></p>
                                    <p x-show="order.latitude && order.longitude" class="text-[11px] text-stone-500 font-mono mt-1">
                                        GPS: <span x-text="Number(order.latitude).toFixed(5)"></span>, <span x-text="Number(order.longitude).toFixed(5)"></span>
                                    </p>
                                </div>
                                <button x-show="order.latitude && order.longitude"
                                   @click="navigateTo(order)"
                                   class="shrink-0 inline-flex items-center gap-1.5 text-xs font-bold bg-[#1e1c25] border border-[#2a2731] text-stone-200 px-3 py-2 rounded-xl hover:border-[#b08d57]/60 transition cursor-pointer">
                                    <i data-lucide="navigation" class="w-3.5 h-3.5 text-[#b08d57]"></i><span>Navigate</span>
                                </button>
                            </div>
                        </div>
                    </template>

                    <!-- Special Order Note -->
                    <div x-show="order.status !== 'ready' && order.special_note" class="px-4 py-3 bg-amber-500/10 border-b border-amber-500/30 text-xs min-w-0">
                        <div class="flex items-center gap-1.5 font-extrabold uppercase text-[11px] text-amber-400 tracking-wider mb-1.5">
                            <i data-lucide="alert-triangle" class="w-3.5 h-3.5 shrink-0"></i><span>Special Order Note</span>
                        </div>
                        <div class="bg-[#0f0e13]/90 text-white font-medium p-2.5 rounded-lg border border-amber-500/25 leading-relaxed whitespace-pre-line break-words" x-text="order.special_note"></div>
                    </div>

                    <!-- Items List & Extras -->
                    <div x-show="order.status !== 'ready'" class="p-4 sm:p-5 space-y-3 flex-1 overflow-y-auto max-h-64 custom-scroll min-w-0">
                        <template x-for="item in order.items" :key="item.id">
                            <div class="bg-[#0f0e13] border border-[#2a2731] rounded-xl p-3.5 min-w-0">
                                <div class="flex items-start justify-between gap-3 min-w-0">
                                    <div class="flex items-center gap-2.5 min-w-0 flex-1">
                                        <span class="w-6 h-6 rounded-lg bg-[#b08d57] text-[#0f0e13] font-black text-xs flex items-center justify-center shrink-0" x-text="item.quantity + 'x'"></span>
                                        <span class="text-sm font-bold text-white truncate min-w-0" x-text="item.name"></span>
                                    </div>
                                    <span class="text-xs font-bold text-stone-400 shrink-0" x-text="'$' + (Number(item.subtotal) || 0).toFixed(2)"></span>
                                </div>

                                <!-- Add-on Extras -->
                                <div x-show="hasExtras(item)" class="mt-2 pl-8 flex flex-wrap gap-1.5">
                                    <template x-for="extra in normalizedExtras(item)" :key="extra">
                                        <span class="text-[11px] font-semibold bg-[#1e1c25] text-[#b08d57] border border-[#2a2731] px-2 py-0.5 rounded-md" x-text="extra"></span>
                                    </template>
                                </div>

                                <p x-show="item.special_note" class="text-xs text-amber-400/90 mt-2 pl-8 break-words" x-text="item.special_note"></p>
                            </div>
                        </template>
                    </div>

                    <!-- Actions -->
                    <div class="p-4 sm:p-5 border-t border-[#2a2731] bg-[#0f0e13]/70 flex items-center gap-2">
                        <!-- Ready: Accept / Decline Buttons -->
                        <template x-if="order.status === 'ready'">
                            <div class="w-full flex gap-2">
                                <button @click="declineOrder(order)"
                                        :disabled="hasActiveDelivery"
                                        class="w-1/3 text-stone-300 font-bold text-xs py-3 rounded-xl transition flex items-center justify-center gap-2 border border-[#2a2731] disabled:opacity-40 disabled:cursor-not-allowed cursor-pointer"
                                        :class="hasActiveDelivery ? 'bg-[#18171f] text-stone-600' : 'bg-[#1e1c25] hover:bg-rose-500/15 hover:text-rose-400'">
                                    <i data-lucide="x" class="w-4 h-4"></i><span>Decline</span>
                                </button>
                                <button @click="acceptOrder(order)"
                                        :disabled="actionId === order.id || hasActiveDelivery"
                                        class="flex-1 font-extrabold text-xs py-3 rounded-xl transition flex items-center justify-center gap-2 shadow-lg disabled:opacity-40 disabled:cursor-not-allowed cursor-pointer"
                                        :class="hasActiveDelivery ? 'bg-stone-800 text-stone-500 border border-[#2a2731]' : 'bg-[#b08d57] hover:bg-[#c9a36b] text-[#0f0e13] shadow-[#b08d57]/10'">
                                    <i :data-lucide="hasActiveDelivery ? 'lock' : 'check'" class="w-4 h-4 stroke-[2.5]"></i>
                                    <span x-text="hasActiveDelivery ? 'Delivery in Progress' : 'Accept Order'"></span>
                                </button>
                            </div>
                        </template>

                        <!-- Out for Delivery: Mark Delivered Button -->
                        <template x-if="order.status === 'out_for_delivery'">
                            <button @click="markDelivered(order)"
                                    :disabled="actionId === order.id"
                                    class="w-full bg-emerald-500 hover:bg-emerald-400 text-black font-extrabold text-xs py-3 rounded-xl transition flex items-center justify-center gap-2 shadow-lg shadow-emerald-500/10 disabled:opacity-50 cursor-pointer">
                                <i data-lucide="check-check" class="w-4 h-4 stroke-[3]"></i><span>Mark as Delivered</span>
                            </button>
                        </template>

                        <!-- Completed -->
                        <template x-if="order.status === 'delivered'">
                            <div class="w-full py-2 text-center text-xs text-emerald-400 font-bold flex items-center justify-center gap-2">
                                <i data-lucide="check-circle-2" class="w-4 h-4"></i><span>Delivery Completed</span>
                            </div>
                        </template>
                    </div>
                </div>
            </template>

            <!-- Empty State -->
            <div x-show="filteredOrders.length === 0" class="col-span-full py-24 text-center bg-[#14131a]/40 border border-[#2a2731]/50 rounded-2xl">
                <div class="w-16 h-16 rounded-2xl bg-[#14131a] border border-[#2a2731] flex items-center justify-center mx-auto text-[#b08d57] mb-3">
                    <i data-lucide="package-check" class="w-8 h-8"></i>
                </div>
                <h3 class="text-base font-bold text-white">No Orders in this Queue</h3>
                <p class="text-stone-500 text-xs mt-1" x-text="online ? 'New ready orders from the kitchen will appear here automatically.' : 'Go online to start receiving delivery dispatches.'"></p>
            </div>
        </div>
    </div>
</main>