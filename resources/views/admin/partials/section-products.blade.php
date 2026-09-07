<section id="section-products" class="page-section hidden">
    <div class="mb-6 sm:mb-8">
        <h2 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">Products</h2>
        <p class="text-stone-500 text-xs sm:text-sm mt-1">Add and manage your menu items</p>
    </div>

    <form id="product-form" class="bg-[#14131a] border border-[#2a2731] rounded-2xl p-5 sm:p-6 grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-5 mb-8 shadow-lg">
        <div>
            <label class="block text-xs font-semibold uppercase tracking-wider text-stone-400 mb-2">Category</label>
            
            <div class="relative custom-select-wrapper" id="product-category-wrapper">
                <select name="category_id" required class="custom-native-select opacity-0 absolute pointer-events-none h-0 w-0">
                    <option value="">Select category</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                    @endforeach
                </select>
                
                <button type="button" class="custom-select-trigger cd-input w-full rounded-xl px-3.5 py-2.5 text-sm text-left flex items-center justify-between transition group hover:border-[#b08d57]/70">
                    <div class="flex items-center gap-2.5 truncate pr-2">
                        <div class="w-2 h-2 rounded-full bg-stone-600 transition trigger-dot"></div>
                        <span class="custom-select-label text-stone-500 font-medium truncate" data-placeholder="Select category">Select category</span>
                    </div>
                    <div class="w-6 h-6 rounded-lg bg-[#1e1c25] group-hover:bg-[#2a2731] flex items-center justify-center text-stone-400 group-hover:text-stone-200 shrink-0 transition">
                        <i data-lucide="chevron-down" class="w-3.5 h-3.5 transition-transform duration-300 chevron-icon"></i>
                    </div>
                </button>

                <div class="custom-select-menu hidden absolute left-0 right-0 top-[calc(100%+6px)] bg-[#14131a]/95 backdrop-blur-xl border border-[#2a2731] rounded-xl p-1.5 shadow-2xl z-50 max-h-56 overflow-y-auto custom-scroll space-y-1">
                    @foreach ($categories as $cat)
                        <div class="custom-option px-3 py-2.5 rounded-lg text-sm text-stone-300 hover:text-white hover:bg-[#1e1c25] cursor-pointer flex items-center justify-between transition group/opt" data-value="{{ $cat->id }}" data-text="{{ $cat->name }}">
                            <div class="flex items-center gap-2.5">
                                <div class="w-2 h-2 rounded-full bg-stone-600 group-hover/opt:bg-[#b08d57] transition dot-indicator"></div>
                                <span class="font-medium">{{ $cat->name }}</span>
                            </div>
                            <i data-lucide="check" class="w-4 h-4 text-[#b08d57] hidden check-icon"></i>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <div>
            <label class="block text-xs font-semibold uppercase tracking-wider text-stone-400 mb-2">Name</label>
            <input type="text" name="name" required class="cd-input w-full rounded-xl px-3.5 py-2.5 text-sm text-white focus:outline-none transition">
        </div>
        <div>
            <label class="block text-xs font-semibold uppercase tracking-wider text-stone-400 mb-2">Price (ETB)</label>
            <input type="number" step="0.01" name="price" placeholder="0.00" required class="cd-input w-full rounded-xl px-3.5 py-2.5 text-sm text-white focus:outline-none transition">
        </div>
        
        <div>
            <label class="block text-xs font-semibold uppercase tracking-wider text-stone-400 mb-2">Product Image</label>
            <input type="file" id="product-image-input" name="image" accept="image/*" required class="hidden">
            
            <div id="image-dropzone" class="relative group cursor-pointer border-2 border-dashed border-[#2a2731] hover:border-[#b08d57]/70 bg-[#0f0e13]/80 hover:bg-[#14131a] rounded-xl p-3 sm:p-4 transition flex flex-col items-center justify-center min-h-[105px]">
                <div id="image-placeholder" class="flex flex-col items-center justify-center text-center py-1.5 space-y-1.5 pointer-events-none">
                    <div class="w-9 h-9 rounded-xl bg-[#1e1c25] group-hover:bg-[#b08d57]/20 flex items-center justify-center text-stone-400 group-hover:text-[#b08d57] transition">
                        <i data-lucide="image-plus" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-stone-300 group-hover:text-white transition">
                            <span class="text-[#b08d57]">Click to upload</span> or drag image
                        </p>
                        <p class="text-[10px] text-stone-500">PNG, JPG, WEBP up to 4MB</p>
                    </div>
                </div>

                <div id="image-preview-container" class="hidden w-full relative flex items-center gap-3">
                    <div class="relative w-16 h-16 rounded-xl overflow-hidden bg-[#0f0e13] border border-[#2a2731] shrink-0 shadow-md">
                        <img id="image-preview-img" src="" alt="Preview" class="w-full h-full object-cover">
                    </div>
                    <div class="flex-1 min-w-0 pr-1">
                        <p id="image-preview-name" class="text-xs font-bold text-white truncate"></p>
                        <span class="inline-flex items-center gap-1 text-[10px] text-emerald-400 bg-emerald-500/10 border border-emerald-500/20 px-2 py-0.5 rounded-full font-semibold mt-1">
                            <i data-lucide="check-circle-2" class="w-3 h-3"></i> Ready to upload
                        </span>
                        <p class="text-[11px] text-stone-500 mt-1">Click to replace</p>
                    </div>
                    <button type="button" id="remove-image-btn" class="p-2 rounded-xl bg-rose-500/10 border border-rose-500/20 text-rose-400 hover:bg-rose-500/20 hover:text-rose-300 transition shrink-0" title="Remove image">
                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                    </button>
                </div>
            </div>
        </div>

        <div class="sm:col-span-2">
            <label class="block text-xs font-semibold uppercase tracking-wider text-stone-400 mb-2">Description</label>
            <textarea name="description" rows="2" class="cd-input w-full rounded-xl px-3.5 py-2.5 text-sm text-white focus:outline-none transition"></textarea>
        </div>
        <div class="sm:col-span-2 flex flex-col sm:flex-row sm:items-center justify-between gap-3 pt-1">
            <p class="text-rose-400 text-xs sm:text-sm form-error" data-form="product"></p>
            <button type="submit" class="bg-[#b08d57] hover:bg-[#c9a36b] text-[#0f0e13] font-bold rounded-xl px-5 py-2.5 text-sm transition flex items-center justify-center gap-2">
                <i data-lucide="plus" class="w-4 h-4"></i><span>Create Product</span>
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
                <input type="text" id="products-search-input" placeholder="Search products by name, category, or price..." class="cd-input w-full rounded-xl pl-10 pr-9 py-2.5 text-sm text-white focus:outline-none transition">
                <button type="button" class="clear-search-btn absolute inset-y-0 right-0 pr-3 flex items-center text-stone-500 hover:text-stone-300 hidden">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>

            <div class="flex flex-wrap items-center gap-3">
                <div class="relative custom-select-wrapper min-w-[150px]">
                    <select id="products-filter-category" class="custom-native-select opacity-0 absolute pointer-events-none h-0 w-0">
                        <option value="all">All Categories</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->name }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                    <button type="button" class="custom-select-trigger cd-input w-full rounded-xl px-3 py-2 text-xs text-left flex items-center justify-between transition group hover:border-[#b08d57]/70">
                        <span class="custom-select-label text-stone-300 font-semibold truncate" data-placeholder="All Categories">All Categories</span>
                        <i data-lucide="chevron-down" class="w-3.5 h-3.5 text-stone-400 group-hover:text-stone-200 transition-transform duration-300 chevron-icon shrink-0 ml-2"></i>
                    </button>
                    <div class="custom-select-menu hidden absolute right-0 left-0 top-[calc(100%+6px)] bg-[#14131a]/95 backdrop-blur-xl border border-[#2a2731] rounded-xl p-1.5 shadow-2xl z-50 max-h-52 overflow-y-auto custom-scroll space-y-1">
                        <div class="custom-option px-2.5 py-1.5 rounded-lg text-xs text-stone-300 hover:text-white hover:bg-[#1e1c25] cursor-pointer flex items-center justify-between transition" data-value="all" data-text="All Categories">
                            <span>All Categories</span>
                            <i data-lucide="check" class="w-3.5 h-3.5 text-[#b08d57] hidden check-icon"></i>
                        </div>
                        @foreach ($categories as $cat)
                            <div class="custom-option px-2.5 py-1.5 rounded-lg text-xs text-stone-300 hover:text-white hover:bg-[#1e1c25] cursor-pointer flex items-center justify-between transition" data-value="{{ $cat->name }}" data-text="{{ $cat->name }}">
                                <span>{{ $cat->name }}</span>
                                <i data-lucide="check" class="w-3.5 h-3.5 text-[#b08d57] hidden check-icon"></i>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="relative custom-select-wrapper min-w-[130px]">
                    <select id="products-filter-status" class="custom-native-select opacity-0 absolute pointer-events-none h-0 w-0">
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

                <span id="products-count-badge" class="px-3 py-1.5 rounded-xl bg-[#1e1c25] border border-[#2a2731] text-xs font-semibold text-stone-300 whitespace-nowrap">
                    Showing {{ $products->count() }} of {{ $products->count() }}
                </span>
            </div>
        </div>

        <div class="overflow-x-auto custom-scroll">
            <table class="w-full text-sm text-left" id="products-table">
                <thead>
                    <tr class="text-stone-400 border-b border-[#2a2731] bg-[#0f0e13]/50">
                        <th class="py-3 px-4 sm:px-5 font-semibold">Image</th>
                        <th class="px-4 sm:px-5 font-semibold">Name</th>
                        <th class="px-4 sm:px-5 font-semibold">Category</th>
                        <th class="px-4 sm:px-5 font-semibold">Price</th>
                        <th class="px-4 sm:px-5 font-semibold">Available</th>
                    </tr>
                </thead>
                <tbody id="products-table-body">
                    @foreach ($products as $p)
                        <tr class="data-row border-b border-[#2a2731]/60 hover:bg-[#1e1c25]/40 transition" 
                            data-name="{{ strtolower($p->name) }}" 
                            data-category="{{ strtolower($p->category->name) }}" 
                            data-price="{{ $p->price }}" 
                            data-status="{{ $p->is_available ? 'available' : 'unavailable' }}">
                            <td class="py-3 px-4 sm:px-5"><img src="{{ asset('storage/' . $p->image) }}" class="w-10 h-10 object-cover rounded-lg bg-[#0f0e13]"></td>
                            <td class="px-4 sm:px-5 text-white font-medium whitespace-nowrap product-name-cell">{{ $p->name }}</td>
                            <td class="px-4 sm:px-5 text-stone-400 whitespace-nowrap product-cat-cell">{{ $p->category->name }}</td>
                            <td class="px-4 sm:px-5 text-[#b08d57] font-bold whitespace-nowrap">{{ number_format($p->price, 2) }} ETB</td>
                            <td class="px-4 sm:px-5 whitespace-nowrap">
                                @if ($p->is_available)
                                    <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-emerald-400 bg-emerald-500/10 border border-emerald-500/20 px-2.5 py-1 rounded-full"><i data-lucide="check" class="w-3 h-3"></i>Yes</span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-rose-400 bg-rose-500/10 border border-rose-500/20 px-2.5 py-1 rounded-full"><i data-lucide="x" class="w-3 h-3"></i>No</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div id="products-empty-state" class="hidden p-8 sm:p-12 text-center flex-col items-center justify-center space-y-3">
            <div class="w-12 h-12 rounded-2xl bg-[#1e1c25] flex items-center justify-center text-stone-500 mx-auto">
                <i data-lucide="package-search" class="w-6 h-6"></i>
            </div>
            <p class="text-white font-bold text-sm">No products found</p>
            <p class="text-stone-500 text-xs max-w-sm mx-auto">No products matched your search query or filter selection.</p>
        </div>
    </div>
</section>