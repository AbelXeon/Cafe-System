<!-- Main Kitchen Orders Area -->
<main class="flex-1 flex flex-col min-h-0 bg-[#14131a]/40 w-full overflow-hidden">

    <!-- Header Bar -->
    <div class="bg-[#0f0e13]/98 backdrop-blur-xl border-b border-[#2a2731] px-4 sm:px-6 lg:px-8 py-4 shrink-0 flex flex-wrap items-center justify-between gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-black text-white tracking-tight flex items-center gap-3">
                <span>Live Kitchen Feed</span>
                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-bold bg-[#b08d57]/15 border border-[#b08d57]/30 text-[#b08d57]">
                    <span class="w-2 h-2 rounded-full bg-[#b08d57] animate-pulse"></span>
                    Auto-refreshing (6s)
                </span>
            </h1>
            <p class="text-stone-500 text-xs mt-0.5">Manage meal tickets, special requests, and order preparation statuses</p>
        </div>

        <!-- Category Tab Filters -->
        <div class="flex items-center gap-2 overflow-x-auto custom-scroll">
            <button @click="activeTab = 'all'"
                :class="activeTab === 'all' ? 'bg-[#b08d57] text-[#0f0e13] font-bold shadow-md shadow-[#b08d57]/10' : 'bg-[#14131a] text-stone-400 border border-[#2a2731]'"
                class="px-3.5 py-1.5 rounded-xl text-xs font-semibold transition shrink-0 cursor-pointer">
                All (<span x-text="orders.length"></span>)
            </button>
            <button @click="activeTab = 'pending'"
                :class="activeTab === 'pending' ? 'bg-amber-500 text-black font-bold' : 'bg-[#14131a] text-amber-400/80 border border-[#2a2731]'"
                class="px-3.5 py-1.5 rounded-xl text-xs font-semibold transition shrink-0 flex items-center gap-1.5 cursor-pointer">
                <span class="w-2 h-2 rounded-full bg-amber-400"></span>
                Incoming (<span x-text="counts.pending"></span>)
            </button>
            <button @click="activeTab = 'preparing'"
                :class="activeTab === 'preparing' ? 'bg-sky-500 text-black font-bold' : 'bg-[#14131a] text-sky-400 border border-[#2a2731]'"
                class="px-3.5 py-1.5 rounded-xl text-xs font-semibold transition shrink-0 cursor-pointer">
                Preparing (<span x-text="counts.preparing"></span>)
            </button>
            <button @click="activeTab = 'ready'"
                :class="activeTab === 'ready' ? 'bg-emerald-500 text-black font-bold' : 'bg-[#14131a] text-emerald-400 border border-[#2a2731]'"
                class="px-3.5 py-1.5 rounded-xl text-xs font-semibold transition shrink-0 cursor-pointer">
                Ready (<span x-text="counts.ready"></span>)
            </button>
        </div>
    </div>

    <!-- Orders Grid Stream -->
    <div class="flex-1 overflow-y-auto custom-scroll p-4 sm:p-6 lg:p-8">
        <div class="grid grid-cols-1 md:grid-cols-2 2xl:grid-cols-3 gap-5">
            <template x-for="order in filteredOrders" :key="order.id">
                <div class="bg-[#14131a] border rounded-2xl overflow-hidden flex flex-col justify-between shadow-xl transition-all duration-200 min-w-0"
                     :class="{
                        'border-amber-500/60 shadow-amber-500/5 ring-1 ring-amber-500/30': order.status === 'pending',
                        'border-sky-500/60 shadow-sky-500/5 ring-1 ring-sky-500/30': order.status === 'preparing',
                        'border-emerald-500/60 shadow-emerald-500/5': order.status === 'ready',
                        'border-[#2a2731] opacity-70': order.status === 'completed' || order.status === 'cancelled'
                     }">
                    
                    <!-- Order Ticket Header -->
                    <div class="p-4 sm:p-5 border-b border-[#2a2731] bg-[#0f0e13]/60 flex items-start justify-between gap-3 min-w-0">
                        <div class="min-w-0 flex-1">
                            <div class="flex items-center gap-2 flex-wrap">
                                <span class="text-base font-black text-white shrink-0" x-text="'Order #' + order.id"></span>
                                <span class="text-[10px] uppercase font-bold px-2 py-0.5 rounded-md shrink-0"
                                      :class="{
                                        'bg-amber-500/20 text-amber-400 border border-amber-500/40': order.status === 'pending',
                                        'bg-sky-500/20 text-sky-400 border border-sky-500/40': order.status === 'preparing',
                                        'bg-emerald-500/20 text-emerald-400 border border-emerald-500/40': order.status === 'ready',
                                        'bg-stone-800 text-stone-400': order.status === 'completed' || order.status === 'cancelled'
                                      }"
                                      x-text="order.status.replace('_', ' ')">
                                </span>
                            </div>
                            <p class="text-xs text-stone-400 mt-1 flex items-center gap-2 flex-wrap min-w-0">
                                <span class="font-bold text-white truncate" x-text="order.customer_name"></span>
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

                    <!-- Order Image Preview Display -->
                    <template x-if="getOrderImage(order)">
                        <div class="w-full h-40 bg-[#0f0e13] border-b border-[#2a2731] overflow-hidden relative group">
                            <img :src="getOrderImage(order)" 
                                 :alt="'Order #' + order.id" 
                                 class="w-full h-full object-cover object-center transition-transform duration-500 group-hover:scale-105"
                                 loading="lazy">
                            <div class="absolute inset-0 bg-gradient-to-t from-[#14131a] via-transparent to-transparent opacity-60 pointer-events-none"></div>
                        </div>
                    </template>

                    <!-- Special Order-level Note -->
                    <div x-show="order.special_note" class="px-4 py-3 bg-amber-500/10 border-b border-amber-500/30 text-xs min-w-0">
                        <div class="flex items-center gap-1.5 font-extrabold uppercase text-[11px] text-amber-400 tracking-wider mb-1.5">
                            <i data-lucide="alert-triangle" class="w-3.5 h-3.5 text-amber-400 shrink-0"></i>
                            <span>Special Order Note:</span>
                        </div>
                        <div class="bg-[#0f0e13]/90 text-white font-medium p-2.5 rounded-lg border border-amber-500/25 leading-relaxed whitespace-pre-line break-all sm:break-words min-w-0" x-text="order.special_note"></div>
                    </div>

                    <!-- Order Itemized List -->
                    <div class="p-4 sm:p-5 space-y-3 flex-1 overflow-y-auto max-h-80 custom-scroll min-w-0">
                        <template x-for="item in order.items" :key="item.id">
                            <div class="bg-[#0f0e13] border border-[#2a2731] rounded-xl p-3.5 flex flex-col justify-between gap-2.5 min-w-0 w-full overflow-hidden">
                                <div class="flex items-start justify-between gap-3 min-w-0">
                                    <div class="flex items-center gap-2.5 min-w-0 flex-1">
                                        <span class="w-6 h-6 rounded-lg bg-[#b08d57] text-[#0f0e13] font-black text-xs flex items-center justify-center shrink-0" x-text="item.quantity + 'x'"></span>
                                        <span class="text-sm font-bold text-white truncate min-w-0" x-text="item.name"></span>
                                    </div>
                                    <span class="text-xs font-bold text-stone-400 shrink-0" x-text="'$' + (Number(item.subtotal) || 0).toFixed(2)"></span>
                                </div>

                                <!-- Item-specific Special Instructions and Extras -->
                                <div x-data="{ details: getItemDetails(item) }" 
                                     x-show="details.hasContent || item.special_note" 
                                     class="pt-2.5 border-t border-[#1e1c25] space-y-2.5 min-w-0 w-full overflow-hidden">
                                    
                                    <!-- Extras & Add-ons Section -->
                                    <template x-if="details.extras && details.extras.length > 0">
                                        <div class="space-y-1.5 min-w-0 w-full">
                                            <div class="flex items-center gap-1.5 font-black uppercase text-[10px] text-emerald-400 tracking-wider">
                                                <i data-lucide="plus-circle" class="w-3.5 h-3.5 text-emerald-400 shrink-0"></i>
                                                <span>Extras & Add-ons</span>
                                            </div>
                                            <div class="space-y-1 min-w-0 w-full">
                                                <template x-for="(extra, idx) in details.extras" :key="idx">
                                                    <div class="flex items-start gap-2 text-xs font-medium text-white bg-[#191722] border border-[#2a2731] px-2.5 py-1.5 rounded-lg shadow-sm min-w-0 w-full overflow-hidden">
                                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 shrink-0 mt-1.5"></span>
                                                        <span class="leading-snug text-white font-medium break-all sm:break-words flex-1 min-w-0" x-text="extra"></span>
                                                    </div>
                                                </template>
                                            </div>
                                        </div>
                                    </template>

                                    <!-- Special Instructions & Notes Section -->
                                    <template x-if="details.notes && details.notes.length > 0">
                                        <div class="space-y-1.5 min-w-0 w-full">
                                            <div class="flex items-center gap-1.5 font-black uppercase text-[10px] text-amber-400 tracking-wider">
                                                <i data-lucide="file-text" class="w-3.5 h-3.5 text-amber-400 shrink-0"></i>
                                                <span>Special Instructions</span>
                                            </div>
                                            <div class="space-y-1 min-w-0 w-full">
                                                <template x-for="(noteLine, nIdx) in details.notes" :key="nIdx">
                                                    <div class="flex items-start gap-2 text-xs font-medium text-white bg-amber-500/10 border border-amber-500/30 px-2.5 py-1.5 rounded-lg leading-relaxed shadow-sm min-w-0 w-full overflow-hidden">
                                                        <i data-lucide="corner-down-right" class="w-3.5 h-3.5 text-amber-400 shrink-0 mt-0.5"></i>
                                                        <span class="flex-1 min-w-0 text-white font-medium break-all sm:break-words leading-relaxed" x-text="noteLine"></span>
                                                    </div>
                                                </template>
                                            </div>
                                        </div>
                                    </template>

                                    <!-- Raw Text Fallback -->
                                    <template x-if="!details.hasContent && item.special_note">
                                        <div class="bg-amber-500/10 border border-amber-500/30 rounded-lg p-2.5 min-w-0 w-full overflow-hidden">
                                            <div class="flex items-center gap-1.5 font-bold uppercase text-[10px] text-amber-400 tracking-wider mb-1">
                                                <i data-lucide="file-text" class="w-3.5 h-3.5 text-amber-400 shrink-0"></i>
                                                <span>Item Note</span>
                                            </div>
                                            <p class="text-xs font-medium text-white bg-[#0f0e13]/80 p-2 rounded-md border border-amber-500/20 leading-relaxed whitespace-pre-line break-all sm:break-words min-w-0" x-text="item.special_note"></p>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </template>
                    </div>

                    <!-- Action Buttons Section -->
                    <div class="p-4 sm:p-5 border-t border-[#2a2731] bg-[#0f0e13]/70 flex items-center gap-2">
                        
                        <!-- If Pending: Accept / Start Preparing Button -->
                        <template x-if="order.status === 'pending'">
                            <button @click="updateStatus(order, 'preparing')"
                                    :disabled="updatingId === order.id"
                                    class="w-full bg-amber-500 hover:bg-amber-400 text-black font-extrabold text-xs py-3 rounded-xl transition flex items-center justify-center gap-2 shadow-lg shadow-amber-500/10 cursor-pointer disabled:opacity-50">
                                <i data-lucide="flame" class="w-4 h-4"></i>
                                <span>Accept & Start Preparing</span>
                            </button>
                        </template>

                        <!-- If Preparing: Mark as Done / Ready Button -->
                        <template x-if="order.status === 'preparing'">
                            <div class="w-full flex gap-2">
                                <button @click="updateStatus(order, 'ready')"
                                        :disabled="updatingId === order.id"
                                        class="flex-1 bg-emerald-500 hover:bg-emerald-400 text-black font-extrabold text-xs py-3 rounded-xl transition flex items-center justify-center gap-2 shadow-lg shadow-emerald-500/10 cursor-pointer disabled:opacity-50">
                                    <i data-lucide="check" class="w-4 h-4 stroke-[3]"></i>
                                    <span>Mark Food as Done (Ready)</span>
                                </button>
                            </div>
                        </template>

                        <!-- If Ready: Mark Completed / Picked up -->
                        <template x-if="order.status === 'ready'">
                            <button @click="updateStatus(order, 'completed')"
                                    :disabled="updatingId === order.id"
                                    class="w-full bg-[#1e1c25] hover:bg-[#2a2731] text-stone-200 font-bold text-xs py-3 rounded-xl transition flex items-center justify-center gap-2 border border-[#2a2731] cursor-pointer disabled:opacity-50">
                                <i data-lucide="check-check" class="w-4 h-4 text-[#b08d57]"></i>
                                <span>Complete Ticket</span>
                            </button>
                        </template>

                        <!-- If Finished/Cancelled -->
                        <template x-if="order.status === 'completed' || order.status === 'cancelled'">
                            <div class="w-full py-2 text-center text-xs text-stone-500 font-semibold">
                                Ticket Closed
                            </div>
                        </template>
                    </div>
                </div>
            </template>

            <!-- Empty State -->
            <div x-show="filteredOrders.length === 0" class="col-span-full py-24 text-center bg-[#14131a]/40 border border-[#2a2731]/50 rounded-2xl">
                <div class="w-16 h-16 rounded-2xl bg-[#14131a] border border-[#2a2731] flex items-center justify-center mx-auto text-[#b08d57] mb-3">
                    <i data-lucide="utensils-crossed" class="w-8 h-8"></i>
                </div>
                <h3 class="text-base font-bold text-white">No Orders in this Queue</h3>
                <p class="text-stone-500 text-xs mt-1">New incoming orders from customers will appear here automatically.</p>
            </div>
        </div>
    </div>
</main>