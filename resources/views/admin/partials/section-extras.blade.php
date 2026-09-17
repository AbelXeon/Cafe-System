<section id="section-extras" class="page-section hidden">
    <div class="mb-6 sm:mb-8">
        <h2 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">Extras</h2>
        <p class="text-stone-500 text-xs sm:text-sm mt-1">Add-ons and side options</p>
    </div>

    <!-- Edit Extra Animated Drawer (Top of Section) -->
    <div id="extra-edit-container" class="edit-drawer-closed">
        <div class="bg-[#14131a] border-2 border-[#b08d57]/60 rounded-2xl p-5 sm:p-6 shadow-2xl shadow-[#b08d57]/10 relative overflow-hidden">
            <div class="absolute top-0 right-0 left-0 h-1 bg-gradient-to-r from-transparent via-[#b08d57] to-transparent"></div>
            
            <div class="flex items-center justify-between pb-4 mb-4 border-b border-[#2a2731]">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-[#b08d57]/20 border border-[#b08d57]/40 flex items-center justify-center text-[#b08d57]">
                        <i data-lucide="sparkles" class="w-4 h-4"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-white">Edit Extra Item</h3>
                        <p class="text-xs text-stone-400">Update addon name, pricing and availability</p>
                    </div>
                </div>
                <button type="button" id="cancel-edit-extra-btn-x" class="p-1.5 rounded-lg text-stone-400 hover:text-white hover:bg-[#1e1c25] transition" title="Cancel">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>

            <form id="extra-edit-form" class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-5">
                <input type="hidden" name="_method" value="PUT">
                <input type="hidden" id="edit-extra-id" name="extra_id" value="">

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-stone-400 mb-2">Name</label>
                    <input type="text" id="edit-extra-name" name="name" required class="cd-input w-full rounded-xl px-3.5 py-2.5 text-sm text-white focus:outline-none transition">
                </div>
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-stone-400 mb-2">Price (ETB)</label>
                    <input type="number" step="0.01" id="edit-extra-price" name="price" placeholder="0.00" required class="cd-input w-full rounded-xl px-3.5 py-2.5 text-sm text-white focus:outline-none transition">
                </div>

                <div class="sm:col-span-2 flex items-center gap-2.5 pt-1">
                    <input type="hidden" name="is_available" value="0">
                    <input type="checkbox" id="edit-extra-available" name="is_available" value="1" class="w-4 h-4 rounded border-[#2a2731] bg-[#0f0e13] text-[#b08d57] focus:ring-[#b08d57] focus:ring-offset-[#0f0e13]">
                    <label for="edit-extra-available" class="text-xs text-stone-300 font-semibold cursor-pointer">Available for Orders</label>
                </div>

                <div class="sm:col-span-2 flex flex-col sm:flex-row sm:items-center justify-between gap-3 pt-2">
                    <p class="text-rose-400 text-xs sm:text-sm form-error" data-form="extra-edit"></p>
                    <div class="flex items-center gap-2 justify-end">
                        <button type="button" id="cancel-edit-extra-btn" class="border border-[#2a2731] hover:bg-[#1e1c25] text-stone-300 hover:text-white font-semibold rounded-xl px-4 py-2.5 text-sm transition flex items-center justify-center gap-1.5">
                            <i data-lucide="x" class="w-4 h-4"></i><span>Cancel</span>
                        </button>
                        <button type="submit" class="bg-[#b08d57] hover:bg-[#c9a36b] text-[#0f0e13] font-bold rounded-xl px-5 py-2.5 text-sm transition flex items-center justify-center gap-2 shadow-lg shadow-[#b08d57]/20">
                            <i data-lucide="check" class="w-4 h-4"></i><span>Save Changes</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Create Extra Form -->
    <form id="extra-form" class="bg-[#14131a] border border-[#2a2731] rounded-2xl p-5 sm:p-6 grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-5 mb-8 shadow-lg">
        <div>
            <label class="block text-xs font-semibold uppercase tracking-wider text-stone-400 mb-2">Name</label>
            <input type="text" name="name" required class="cd-input w-full rounded-xl px-3.5 py-2.5 text-sm text-white focus:outline-none transition">
        </div>
        <div>
            <label class="block text-xs font-semibold uppercase tracking-wider text-stone-400 mb-2">Price (ETB)</label>
            <input type="number" step="0.01" name="price" placeholder="0.00" required class="cd-input w-full rounded-xl px-3.5 py-2.5 text-sm text-white focus:outline-none transition">
        </div>
        <div class="sm:col-span-2 flex flex-col sm:flex-row sm:items-center justify-between gap-3 pt-1">
            <p class="text-rose-400 text-xs sm:text-sm form-error" data-form="extra"></p>
            <button type="submit" class="bg-[#b08d57] hover:bg-[#c9a36b] text-[#0f0e13] font-bold rounded-xl px-5 py-2.5 text-sm transition flex items-center justify-center gap-2">
                <i data-lucide="plus" class="w-4 h-4"></i><span>Create Extra</span>
            </button>
        </div>
    </form>

    <div class="bg-[#14131a] border border-[#2a2731] rounded-2xl overflow-hidden shadow-lg">
        <div class="p-4 sm:p-5 border-b border-[#2a2731] flex flex-col md:flex-row md:items-center justify-between gap-4 bg-[#0f0e13]/40">
            <div class="relative flex-1 max-w-md">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-stone-500">
                    <i data-lucide="search" class="w-4 h-4 search-icon"></i>
                    <div class="w-4 h-4 border-2 border-[#b08d57] border-t-transparent rounded-full animate-spin hidden buffer-spinner"></div>
                </div>
                <input type="text" id="extras-search-input" placeholder="Search extras by name or price..." class="cd-input w-full rounded-xl pl-10 pr-9 py-2.5 text-sm text-white focus:outline-none transition">
                <button type="button" class="clear-search-btn absolute inset-y-0 right-0 pr-3 flex items-center text-stone-500 hover:text-stone-300 hidden">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>

            <div class="flex flex-wrap items-center gap-3">
                <div class="relative custom-select-wrapper min-w-[140px]">
                    <select id="extras-filter-status" class="custom-native-select opacity-0 absolute pointer-events-none h-0 w-0">
                        <option value="all">All Status</option>
                        <option value="available">Available</option>
                        <option value="unavailable">Unavailable</option>
                    </select>
                    <button type="button" class="custom-select-trigger cd-input w-full rounded-xl px-3 py-2 text-xs text-left flex items-center justify-between transition group hover:border-[#b08d57]/70">
                        <span class="custom-select-label text-stone-300 font-semibold truncate" data-placeholder="All Status">All Status</span>
                        <i data-lucide="chevron-down" class="w-3.5 h-3.5 text-stone-400 group-hover:text-stone-200 transition-transform duration-300 chevron-icon shrink-0 ml-2"></i>
                    </button>
                    <div class="custom-select-menu hidden absolute right-0 left-0 top-[calc(100%+6px)] bg-[#14131a]/95 backdrop-blur-xl border border-[#2a2731] rounded-xl p-1.5 shadow-2xl z-50 max-h-52 overflow-y-auto custom-scroll space-y-1">
                        <div class="custom-option px-2.5 py-1.5 rounded-lg text-xs text-stone-300 hover:text-white hover:bg-[#1e1c25] cursor-pointer flex items-center justify-between transition" data-value="all" data-text="All Status">
                            <span>All Status</span>
                            <i data-lucide="check" class="w-3.5 h-3.5 text-[#b08d57] hidden check-icon"></i>
                        </div>
                        <div class="custom-option px-2.5 py-1.5 rounded-lg text-xs text-stone-300 hover:text-white hover:bg-[#1e1c25] cursor-pointer flex items-center justify-between transition" data-value="available" data-text="Available">
                            <span>Available</span>
                            <i data-lucide="check" class="w-3.5 h-3.5 text-[#b08d57] hidden check-icon"></i>
                        </div>
                        <div class="custom-option px-2.5 py-1.5 rounded-lg text-xs text-stone-300 hover:text-white hover:bg-[#1e1c25] cursor-pointer flex items-center justify-between transition" data-value="unavailable" data-text="Unavailable">
                            <span>Unavailable</span>
                            <i data-lucide="check" class="w-3.5 h-3.5 text-[#b08d57] hidden check-icon"></i>
                        </div>
                    </div>
                </div>

                <span id="extras-count-badge" class="px-3 py-1.5 rounded-xl bg-[#1e1c25] border border-[#2a2731] text-xs font-semibold text-stone-300 whitespace-nowrap">
                    Showing {{ $extras->count() }} of {{ $extras->count() }}
                </span>
            </div>
        </div>

        <div class="overflow-x-auto custom-scroll">
            <table class="w-full text-sm text-left" id="extras-table">
                <thead>
                    <tr class="text-stone-400 border-b border-[#2a2731] bg-[#0f0e13]/50">
                        <th class="py-3 px-4 sm:px-5 font-semibold">Name</th>
                        <th class="px-4 sm:px-5 font-semibold">Price</th>
                        <th class="px-4 sm:px-5 font-semibold">Available</th>
                        <th class="px-4 sm:px-5 font-semibold text-right">Action</th>
                    </tr>
                </thead>
                <tbody id="extras-table-body">
                    @foreach ($extras as $e)
                        <tr class="data-row border-b border-[#2a2731]/60 hover:bg-[#1e1c25]/40 transition group/row"
                            id="extra-row-{{ $e->id }}"
                            data-id="{{ $e->id }}"
                            data-name="{{ strtolower($e->name) }}"
                            data-price="{{ $e->price }}"
                            data-status="{{ $e->is_available ? 'available' : 'unavailable' }}">
                            <td class="py-3 px-4 sm:px-5 text-white font-medium whitespace-nowrap extra-name-cell">{{ $e->name }}</td>
                            <td class="px-4 sm:px-5 text-[#b08d57] font-bold whitespace-nowrap extra-price-cell">{{ number_format($e->price, 2) }} ETB</td>
                            <td class="px-4 sm:px-5 whitespace-nowrap extra-status-cell">
                                @if ($e->is_available)
                                    <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-emerald-400 bg-emerald-500/10 border border-emerald-500/20 px-2.5 py-1 rounded-full"><i data-lucide="check" class="w-3 h-3"></i>Yes</span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-rose-400 bg-rose-500/10 border border-rose-500/20 px-2.5 py-1 rounded-full"><i data-lucide="x" class="w-3 h-3"></i>No</span>
                                @endif
                            </td>
                            <td class="px-4 sm:px-5 text-right whitespace-nowrap">
                                <button type="button" 
                                    class="edit-extra-btn inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold text-stone-300 bg-[#1e1c25] hover:bg-[#b08d57] hover:text-[#0f0e13] border border-[#2a2731] hover:border-[#b08d57] transition-all duration-200 active:scale-95"
                                    data-id="{{ $e->id }}"
                                    data-name="{{ $e->name }}"
                                    data-price="{{ $e->price }}"
                                    data-is-available="{{ $e->is_available ? '1' : '0' }}">
                                    <i data-lucide="square-pen" class="w-3.5 h-3.5"></i>
                                    <span>Edit</span>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div id="extras-empty-state" class="hidden p-8 sm:p-12 text-center flex-col items-center justify-center space-y-3">
            <div class="w-12 h-12 rounded-2xl bg-[#1e1c25] flex items-center justify-center text-stone-500 mx-auto">
                <i data-lucide="sparkles" class="w-6 h-6"></i>
            </div>
            <p class="text-white font-bold text-sm">No extras found</p>
            <p class="text-stone-500 text-xs max-w-sm mx-auto">No extras matched your search query or filter selection.</p>
        </div>
    </div>
</section>