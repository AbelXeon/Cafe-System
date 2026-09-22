<!-- VIEW 2: Menu Stock & Availability Data Table (86 List) -->
<main x-show="currentView === 'stock'" class="flex-1 flex flex-col min-h-0 bg-[#14131a]/40 w-full overflow-hidden">
    
    <!-- Header Bar -->
    <div class="bg-[#0f0e13]/98 backdrop-blur-xl border-b border-[#2a2731] px-4 sm:px-6 lg:px-8 py-4 shrink-0 flex flex-wrap items-center justify-between gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-black text-white tracking-tight flex items-center gap-3">
                <span>Kitchen Stock & 86'd Items</span>
                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-bold"
                      :class="outOfStockCount > 0 ? 'bg-rose-500/15 border border-rose-500/30 text-rose-400' : 'bg-emerald-500/15 border border-emerald-500/30 text-emerald-400'">
                    <span class="w-2 h-2 rounded-full" :class="outOfStockCount > 0 ? 'bg-rose-500 animate-ping' : 'bg-emerald-500'"></span>
                    <span x-text="outOfStockCount + ' Items Out of Stock'"></span>
                </span>
            </h1>
            <p class="text-stone-500 text-xs mt-0.5">Toggle products or add-ons unavailable when you run out. Customers will immediately be prevented from ordering them.</p>
        </div>

        <!-- Fast Search & Category Filter Controls -->
        <div class="flex items-center gap-3 w-full sm:w-auto">
            <div class="relative flex-1 sm:w-64">
                <i data-lucide="search" class="w-4 h-4 text-stone-500 absolute left-3 top-1/2 -translate-y-1/2"></i>
                <input type="text" 
                       x-model="stockSearch" 
                       placeholder="Search dish, meat, fries..." 
                       class="w-full bg-[#14131a] border border-[#2a2731] rounded-xl pl-9 pr-4 py-2 text-xs text-white placeholder-stone-500 cd-input focus:outline-none">
            </div>

            <select x-model="stockFilter" class="bg-[#14131a] border border-[#2a2731] rounded-xl px-3 py-2 text-xs text-white cd-input focus:outline-none cursor-pointer">
                <option value="all">All Items</option>
                <option value="products">Dishes / Meals</option>
                <option value="extras">Extras & Add-ons</option>
                <option value="out_of_stock">Only 86'd (Out of Stock)</option>
            </select>
        </div>
    </div>

    <!-- Stock Data Table Area -->
    <div class="flex-1 overflow-y-auto custom-scroll p-4 sm:p-6 lg:p-8">
        <div class="bg-[#14131a] border border-[#2a2731] rounded-2xl overflow-hidden shadow-2xl">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-[#0f0e13]/90 border-b border-[#2a2731] text-[11px] uppercase tracking-wider text-stone-400 font-bold">
                            <th class="py-3.5 px-4 sm:px-6">Item</th>
                            <th class="py-3.5 px-4">Type</th>
                            <th class="py-3.5 px-4">Category</th>
                            <th class="py-3.5 px-4">Price</th>
                            <th class="py-3.5 px-4 text-center">Status</th>
                            <th class="py-3.5 px-4 sm:px-6 text-right">Quick Stock Toggle</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#1e1c25] text-xs">
                        <template x-for="item in filteredStockItems" :key="item.type + '-' + item.id">
                            <tr class="hover:bg-[#181622]/60 transition-colors"
                                :class="!item.is_available ? 'bg-rose-500/[0.03]' : ''">
                                
                                <!-- Item Name & Thumbnail -->
                                <td class="py-3.5 px-4 sm:px-6">
                                    <div class="flex items-center gap-3">
                                        <template x-if="item.image">
                                            <img :src="item.image" :alt="item.name" class="w-10 h-10 rounded-xl object-cover border border-[#2a2731] shrink-0">
                                        </template>
                                        <template x-if="!item.image">
                                            <div class="w-10 h-10 rounded-xl bg-[#1e1c25] border border-[#2a2731] flex items-center justify-center text-stone-500 shrink-0">
                                                <i :data-lucide="item.type === 'Product' ? 'utensils' : 'plus'" class="w-4 h-4"></i>
                                            </div>
                                        </template>
                                        <div class="min-w-0">
                                            <p class="font-bold text-white truncate" x-text="item.name"></p>
                                            <p class="text-[10px] text-stone-500 truncate" x-text="item.description || (item.type === 'Product' ? 'Meal Dish' : 'Add-on Modifier')"></p>
                                        </div>
                                    </div>
                                </td>

                                <!-- Type -->
                                <td class="py-3.5 px-4">
                                    <span class="px-2 py-0.5 rounded-md text-[10px] font-bold"
                                          :class="item.type === 'Product' ? 'bg-[#b08d57]/20 text-[#b08d57] border border-[#b08d57]/30' : 'bg-purple-500/20 text-purple-300 border border-purple-500/30'"
                                          x-text="item.type"></span>
                                </td>

                                <!-- Category -->
                                <td class="py-3.5 px-4 text-stone-300" x-text="item.category_name"></td>

                                <!-- Price -->
                                <td class="py-3.5 px-4 font-bold text-[#b08d57]" x-text="'ETB ' + Number(item.price).toFixed(2)"></td>

                                <!-- Status Pill -->
                                <td class="py-3.5 px-4 text-center">
                                    <template x-if="item.is_available">
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-500/15 text-emerald-400 border border-emerald-500/30">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                                            Available
                                        </span>
                                    </template>
                                    <template x-if="!item.is_available">
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-rose-500/15 text-rose-400 border border-rose-500/30 animate-pulse">
                                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                            86 / Out of Stock
                                        </span>
                                    </template>
                                </td>

                                <!-- Availability Toggle Button -->
                                <td class="py-3.5 px-4 sm:px-6 text-right">
                                    <button @click="toggleItemAvailability(item)"
                                            :disabled="stockTogglingId === (item.type + '-' + item.id)"
                                            :class="item.is_available 
                                                ? 'bg-rose-500/10 hover:bg-rose-500 text-rose-400 hover:text-white border border-rose-500/30' 
                                                : 'bg-emerald-500/10 hover:bg-emerald-500 text-emerald-400 hover:text-black border border-emerald-500/30'"
                                            class="px-3.5 py-2 rounded-xl text-xs font-bold transition inline-flex items-center gap-2 cursor-pointer disabled:opacity-50">
                                        <i :data-lucide="item.is_available ? 'slash' : 'check'" class="w-3.5 h-3.5"></i>
                                        <span x-text="item.is_available ? 'Mark as Out (86)' : 'Make Available'"></span>
                                    </button>
                                </td>
                            </tr>
                        </template>

                        <tr x-show="filteredStockItems.length === 0">
                            <td colspan="6" class="py-12 text-center text-stone-500">
                                <i data-lucide="search-x" class="w-8 h-8 mx-auto mb-2 opacity-60"></i>
                                <p class="font-bold text-stone-400 text-sm">No items found matching filter.</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>