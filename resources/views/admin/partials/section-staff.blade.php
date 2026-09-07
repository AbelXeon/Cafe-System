<section id="section-staff" class="page-section hidden">
    <div class="mb-6 sm:mb-8">
        <h2 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">Staff &amp; Delivery</h2>
        <p class="text-stone-500 text-xs sm:text-sm mt-1">Manage your team members and drivers</p>
    </div>

    <form id="staff-form" class="bg-[#14131a] border border-[#2a2731] rounded-2xl p-5 sm:p-6 grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-5 mb-8 shadow-lg">
        <div>
            <label class="block text-xs font-semibold uppercase tracking-wider text-stone-400 mb-2">Role</label>
            <div class="relative custom-select-wrapper" id="staff-role-wrapper">
                <select name="role_id" required class="custom-native-select opacity-0 absolute pointer-events-none h-0 w-0">
                    <option value="">Select role</option>
                    @foreach ($roles as $r)
                        <option value="{{ $r->id }}">{{ ucfirst($r->name) }}</option>
                    @endforeach
                </select>
                
                <button type="button" class="custom-select-trigger cd-input w-full rounded-xl px-3.5 py-2.5 text-sm text-left flex items-center justify-between transition group hover:border-[#b08d57]/70">
                    <div class="flex items-center gap-2.5 truncate pr-2">
                        <div class="w-2 h-2 rounded-full bg-stone-600 transition trigger-dot"></div>
                        <span class="custom-select-label text-stone-500 font-medium truncate" data-placeholder="Select role">Select role</span>
                    </div>
                    <div class="w-6 h-6 rounded-lg bg-[#1e1c25] group-hover:bg-[#2a2731] flex items-center justify-center text-stone-400 group-hover:text-stone-200 shrink-0 transition">
                        <i data-lucide="chevron-down" class="w-3.5 h-3.5 transition-transform duration-300 chevron-icon"></i>
                    </div>
                </button>

                <div class="custom-select-menu hidden absolute left-0 right-0 top-[calc(100%+6px)] bg-[#14131a]/95 backdrop-blur-xl border border-[#2a2731] rounded-xl p-1.5 shadow-2xl z-50 max-h-56 overflow-y-auto custom-scroll space-y-1">
                    @foreach ($roles as $r)
                        <div class="custom-option px-3 py-2.5 rounded-lg text-sm text-stone-300 hover:text-white hover:bg-[#1e1c25] cursor-pointer flex items-center justify-between transition group/opt" data-value="{{ $r->id }}" data-text="{{ ucfirst($r->name) }}">
                            <div class="flex items-center gap-2.5">
                                <div class="w-2 h-2 rounded-full bg-stone-600 group-hover/opt:bg-[#b08d57] transition dot-indicator"></div>
                                <span class="font-medium">{{ ucfirst($r->name) }}</span>
                            </div>
                            <i data-lucide="check" class="w-4 h-4 text-[#b08d57] hidden check-icon"></i>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <div>
            <label class="block text-xs font-semibold uppercase tracking-wider text-stone-400 mb-2">Full name</label>
            <input type="text" name="fullname" required class="cd-input w-full rounded-xl px-3.5 py-2.5 text-sm text-white focus:outline-none transition">
        </div>
        <div>
            <label class="block text-xs font-semibold uppercase tracking-wider text-stone-400 mb-2">Username</label>
            <input type="text" name="username" required class="cd-input w-full rounded-xl px-3.5 py-2.5 text-sm text-white focus:outline-none transition">
        </div>
        <div>
            <label class="block text-xs font-semibold uppercase tracking-wider text-stone-400 mb-2">Email</label>
            <input type="email" name="email" class="cd-input w-full rounded-xl px-3.5 py-2.5 text-sm text-white focus:outline-none transition">
        </div>
        <div>
            <label class="block text-xs font-semibold uppercase tracking-wider text-stone-400 mb-2">Phone</label>
            <input type="text" name="phone" class="cd-input w-full rounded-xl px-3.5 py-2.5 text-sm text-white focus:outline-none transition">
        </div>
        <div>
            <label class="block text-xs font-semibold uppercase tracking-wider text-stone-400 mb-2">Password</label>
            <input type="password" name="password" required class="cd-input w-full rounded-xl px-3.5 py-2.5 text-sm text-white focus:outline-none transition">
        </div>
        <div class="sm:col-span-2 flex flex-col sm:flex-row sm:items-center justify-between gap-3 pt-1">
            <p class="text-rose-400 text-xs sm:text-sm form-error" data-form="staff"></p>
            <button type="submit" class="bg-[#b08d57] hover:bg-[#c9a36b] text-[#0f0e13] font-bold rounded-xl px-5 py-2.5 text-sm transition flex items-center justify-center gap-2">
                <i data-lucide="user-plus" class="w-4 h-4"></i><span>Create Staff</span>
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
                <input type="text" id="staff-search-input" placeholder="Search staff by name, username, or phone..." class="cd-input w-full rounded-xl pl-10 pr-9 py-2.5 text-sm text-white focus:outline-none transition">
                <button type="button" class="clear-search-btn absolute inset-y-0 right-0 pr-3 flex items-center text-stone-500 hover:text-stone-300 hidden">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>

            <div class="flex flex-wrap items-center gap-3">
                <div class="relative custom-select-wrapper min-w-[150px]">
                    <select id="staff-filter-role" class="custom-native-select opacity-0 absolute pointer-events-none h-0 w-0">
                        <option value="all">All Roles</option>
                        @foreach ($roles as $r)
                            <option value="{{ strtolower($r->name) }}">{{ ucfirst($r->name) }}</option>
                        @endforeach
                    </select>
                    <button type="button" class="custom-select-trigger cd-input w-full rounded-xl px-3 py-2 text-xs text-left flex items-center justify-between transition group hover:border-[#b08d57]/70">
                        <span class="custom-select-label text-stone-300 font-semibold truncate" data-placeholder="All Roles">All Roles</span>
                        <i data-lucide="chevron-down" class="w-3.5 h-3.5 text-stone-400 group-hover:text-stone-200 transition-transform duration-300 chevron-icon shrink-0 ml-2"></i>
                    </button>
                    <div class="custom-select-menu hidden absolute right-0 left-0 top-[calc(100%+6px)] bg-[#14131a]/95 backdrop-blur-xl border border-[#2a2731] rounded-xl p-1.5 shadow-2xl z-50 max-h-52 overflow-y-auto custom-scroll space-y-1">
                        <div class="custom-option px-2.5 py-1.5 rounded-lg text-xs text-stone-300 hover:text-white hover:bg-[#1e1c25] cursor-pointer flex items-center justify-between transition" data-value="all" data-text="All Roles">
                            <span>All Roles</span>
                            <i data-lucide="check" class="w-3.5 h-3.5 text-[#b08d57] hidden check-icon"></i>
                        </div>
                        @foreach ($roles as $r)
                            <div class="custom-option px-2.5 py-1.5 rounded-lg text-xs text-stone-300 hover:text-white hover:bg-[#1e1c25] cursor-pointer flex items-center justify-between transition" data-value="{{ strtolower($r->name) }}" data-text="{{ ucfirst($r->name) }}">
                                <span>{{ ucfirst($r->name) }}</span>
                                <i data-lucide="check" class="w-3.5 h-3.5 text-[#b08d57] hidden check-icon"></i>
                            </div>
                        @endforeach
                    </div>
                </div>

                <span id="staff-count-badge" class="px-3 py-1.5 rounded-xl bg-[#1e1c25] border border-[#2a2731] text-xs font-semibold text-stone-300 whitespace-nowrap">
                    Showing {{ $staff->count() }} of {{ $staff->count() }}
                </span>
            </div>
        </div>

        <div class="overflow-x-auto custom-scroll">
            <table class="w-full text-sm text-left" id="staff-table">
                <thead>
                    <tr class="text-stone-400 border-b border-[#2a2731] bg-[#0f0e13]/50">
                        <th class="py-3 px-4 sm:px-5 font-semibold">Name</th>
                        <th class="px-4 sm:px-5 font-semibold">Username</th>
                        <th class="px-4 sm:px-5 font-semibold">Role</th>
                        <th class="px-4 sm:px-5 font-semibold">Phone</th>
                    </tr>
                </thead>
                <tbody id="staff-table-body">
                    @foreach ($staff as $s)
                        <tr class="data-row border-b border-[#2a2731]/60 hover:bg-[#1e1c25]/40 transition"
                            data-fullname="{{ strtolower($s->fullname) }}"
                            data-username="{{ strtolower($s->username) }}"
                            data-role="{{ strtolower($s->role->name) }}"
                            data-phone="{{ strtolower($s->phone ?? '') }}">
                            <td class="py-3 px-4 sm:px-5 text-white font-medium whitespace-nowrap staff-name-cell">{{ $s->fullname }}</td>
                            <td class="px-4 sm:px-5 text-stone-400 whitespace-nowrap staff-username-cell">{{ $s->username }}</td>
                            <td class="px-4 sm:px-5 whitespace-nowrap"><span class="inline-flex items-center text-xs font-semibold text-[#b08d57] bg-[#b08d57]/10 border border-[#b08d57]/20 px-2.5 py-1 rounded-full staff-role-cell">{{ ucfirst($s->role->name) }}</span></td>
                            <td class="px-4 sm:px-5 text-stone-400 whitespace-nowrap staff-phone-cell">{{ $s->phone }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div id="staff-empty-state" class="hidden p-8 sm:p-12 text-center flex-col items-center justify-center space-y-3">
            <div class="w-12 h-12 rounded-2xl bg-[#1e1c25] flex items-center justify-center text-stone-500 mx-auto">
                <i data-lucide="user-x" class="w-6 h-6"></i>
            </div>
            <p class="text-white font-bold text-sm">No staff members found</p>
            <p class="text-stone-500 text-xs max-w-sm mx-auto">No members matched your search query or role filter.</p>
        </div>
    </div>
</section>