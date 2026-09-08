<!-- SECTION 5: Account Settings -->
<div id="section-profile" class="page-section hidden flex-1 overflow-y-auto p-4 sm:p-8 lg:p-12 custom-scroll bg-[#14131a]/40 w-full" x-data="profileApp()" x-init="init()">
    <div class="max-w-4xl mx-auto pb-20 space-y-6 sm:space-y-8">

        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">Account Settings</h1>
                <p class="text-stone-400 text-xs sm:text-sm mt-1">Manage your personal profile, security credentials, and app preferences</p>
            </div>
            <div class="flex items-center gap-2 self-start sm:self-auto bg-[#14131a] border border-[#2a2731] rounded-xl px-3 py-1.5 shadow-inner">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                <span class="text-xs font-semibold text-stone-300">Verified Customer</span>
            </div>
        </div>

        <!-- Overview Quick Stats Row -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3.5 sm:gap-4">
            <div class="bg-[#14131a] border border-[#2a2731] rounded-2xl p-4 sm:p-5 flex items-center gap-4 shadow-lg">
                <div class="w-12 h-12 rounded-xl bg-[#b08d57]/15 border border-[#b08d57]/30 flex items-center justify-center text-[#b08d57] shrink-0">
                    <i data-lucide="shopping-bag" class="w-6 h-6"></i>
                </div>
                <div>
                    <span class="text-xs text-stone-500 font-medium block">Total Orders</span>
                    <span class="text-lg sm:text-xl font-black text-white" x-text="$store.orders.list.length"></span>
                </div>
            </div>

            <div class="bg-[#14131a] border border-[#2a2731] rounded-2xl p-4 sm:p-5 flex items-center gap-4 shadow-lg">
                <div class="w-12 h-12 rounded-xl bg-[#b08d57]/15 border border-[#b08d57]/30 flex items-center justify-center text-[#b08d57] shrink-0">
                    <i data-lucide="map-pin" class="w-6 h-6"></i>
                </div>
                <div>
                    <span class="text-xs text-stone-500 font-medium block">Saved Locations</span>
                    <span class="text-lg sm:text-xl font-black text-white" x-text="$store.addresses.list.length"></span>
                </div>
            </div>

            <div class="bg-[#14131a] border border-[#2a2731] rounded-2xl p-4 sm:p-5 flex items-center gap-4 shadow-lg">
                <div class="w-12 h-12 rounded-xl bg-[#b08d57]/15 border border-[#b08d57]/30 flex items-center justify-center text-[#b08d57] shrink-0">
                    <i data-lucide="calendar" class="w-6 h-6"></i>
                </div>
                <div>
                    <span class="text-xs text-stone-500 font-medium block">Member Since</span>
                    <span class="text-sm sm:text-base font-bold text-white">{{ Auth::user()->created_at ? Auth::user()->created_at->format('M Y') : 'Active Member' }}</span>
                </div>
            </div>
        </div>

        <!-- Profile Information Card -->
        <div class="bg-[#14131a] border border-[#2a2731] rounded-2xl p-5 sm:p-7 shadow-xl">
            <div class="flex items-center justify-between pb-5 border-b border-[#1e1c25]">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-[#b08d57]/20 border border-[#b08d57]/30 flex items-center justify-center text-[#b08d57]">
                        <i data-lucide="user" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <h3 class="text-base sm:text-lg font-bold text-white">Personal Information</h3>
                        <p class="text-xs text-stone-500">Update your public identity and contact information</p>
                    </div>
                </div>
            </div>

            <form @submit.prevent="saveProfile()" class="mt-6 space-y-4 sm:space-y-5">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-stone-400 mb-1.5">Full Name</label>
                        <div class="relative">
                            <i data-lucide="user" class="w-4 h-4 text-stone-500 absolute left-3.5 top-1/2 -translate-y-1/2 pointer-events-none"></i>
                            <input x-model="profileForm.name" type="text" required placeholder="Your full name"
                                class="cd-input w-full bg-[#0f0e13] border border-[#2a2731] rounded-xl pl-10 pr-4 py-2.5 text-sm text-white placeholder-stone-600 focus:outline-none transition">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-stone-400 mb-1.5">Phone Number</label>
                        <div class="relative">
                            <i data-lucide="phone" class="w-4 h-4 text-stone-500 absolute left-3.5 top-1/2 -translate-y-1/2 pointer-events-none"></i>
                            <input x-model="profileForm.phone" type="tel" placeholder="e.g. +1 (555) 000-0000"
                                class="cd-input w-full bg-[#0f0e13] border border-[#2a2731] rounded-xl pl-10 pr-4 py-2.5 text-sm text-white placeholder-stone-600 focus:outline-none transition">
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-stone-400 mb-1.5">Email Address</label>
                    <div class="relative">
                        <i data-lucide="mail" class="w-4 h-4 text-stone-500 absolute left-3.5 top-1/2 -translate-y-1/2 pointer-events-none"></i>
                        <input x-model="profileForm.email" type="email" required placeholder="you@domain.com"
                            class="cd-input w-full bg-[#0f0e13] border border-[#2a2731] rounded-xl pl-10 pr-4 py-2.5 text-sm text-white placeholder-stone-600 focus:outline-none transition">
                    </div>
                </div>

                <div x-show="profileError" x-cloak class="p-3 bg-rose-500/10 border border-rose-500/30 rounded-xl text-xs text-rose-400" x-text="profileError"></div>

                <div class="flex justify-end pt-2">
                    <button type="submit" :disabled="savingProfile"
                        class="bg-[#b08d57] hover:bg-[#c9a36b] disabled:opacity-50 text-[#0f0e13] font-bold text-xs sm:text-sm px-6 py-2.5 rounded-xl transition shadow-lg shadow-[#b08d57]/20 flex items-center gap-2 cursor-pointer active:scale-95">
                        <i x-show="savingProfile" data-lucide="loader-2" class="w-4 h-4 animate-spin"></i>
                        <i x-show="!savingProfile" data-lucide="check" class="w-4 h-4 stroke-[2.5]"></i>
                        <span x-text="savingProfile ? 'Saving...' : 'Save Profile Changes'"></span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Security & Password Card -->
        <div class="bg-[#14131a] border border-[#2a2731] rounded-2xl p-5 sm:p-7 shadow-xl">
            <div class="flex items-center justify-between pb-5 border-b border-[#1e1c25]">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-[#b08d57]/20 border border-[#b08d57]/30 flex items-center justify-center text-[#b08d57]">
                        <i data-lucide="lock" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <h3 class="text-base sm:text-lg font-bold text-white">Security & Password</h3>
                        <p class="text-xs text-stone-500">Ensure your account uses a secure password</p>
                    </div>
                </div>
            </div>

            <form @submit.prevent="updatePassword()" class="mt-6 space-y-4">
                <div>
                    <label class="block text-xs font-semibold text-stone-400 mb-1.5">Current Password</label>
                    <div class="relative">
                        <i data-lucide="key-round" class="w-4 h-4 text-stone-500 absolute left-3.5 top-1/2 -translate-y-1/2 pointer-events-none"></i>
                        <input x-model="passwordForm.current_password" :type="showPasswords ? 'text' : 'password'" required placeholder="Enter current password"
                            class="cd-input w-full bg-[#0f0e13] border border-[#2a2731] rounded-xl pl-10 pr-4 py-2.5 text-sm text-white placeholder-stone-600 focus:outline-none transition">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-stone-400 mb-1.5">New Password</label>
                        <div class="relative">
                            <i data-lucide="shield-check" class="w-4 h-4 text-stone-500 absolute left-3.5 top-1/2 -translate-y-1/2 pointer-events-none"></i>
                            <input x-model="passwordForm.password" :type="showPasswords ? 'text' : 'password'" required minlength="8" placeholder="Minimum 8 characters"
                                class="cd-input w-full bg-[#0f0e13] border border-[#2a2731] rounded-xl pl-10 pr-4 py-2.5 text-sm text-white placeholder-stone-600 focus:outline-none transition">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-stone-400 mb-1.5">Confirm New Password</label>
                        <div class="relative">
                            <i data-lucide="shield-check" class="w-4 h-4 text-stone-500 absolute left-3.5 top-1/2 -translate-y-1/2 pointer-events-none"></i>
                            <input x-model="passwordForm.password_confirmation" :type="showPasswords ? 'text' : 'password'" required minlength="8" placeholder="Re-type new password"
                                class="cd-input w-full bg-[#0f0e13] border border-[#2a2731] rounded-xl pl-10 pr-4 py-2.5 text-sm text-white placeholder-stone-600 focus:outline-none transition">
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-between pt-1">
                    <label class="flex items-center gap-2 cursor-pointer select-none">
                        <input type="checkbox" x-model="showPasswords" class="rounded bg-[#0f0e13] border-[#2a2731] text-[#b08d57] focus:ring-0 w-4 h-4">
                        <span class="text-xs text-stone-400">Show passwords</span>
                    </label>

                    <button type="submit" :disabled="savingPassword"
                        class="bg-[#1e1c25] hover:bg-[#2a2731] border border-[#2a2731] hover:border-[#b08d57]/40 text-stone-200 font-bold text-xs sm:text-sm px-5 py-2.5 rounded-xl transition flex items-center gap-2 cursor-pointer active:scale-95">
                        <i x-show="savingPassword" data-lucide="loader-2" class="w-4 h-4 animate-spin"></i>
                        <i x-show="!savingPassword" data-lucide="lock" class="w-4 h-4"></i>
                        <span x-text="savingPassword ? 'Updating...' : 'Update Password'"></span>
                    </button>
                </div>

                <div x-show="passwordError" x-cloak class="p-3 bg-rose-500/10 border border-rose-500/30 rounded-xl text-xs text-rose-400" x-text="passwordError"></div>
            </form>
        </div>

        <!-- Preferences & Audio Notifications Card -->
        <div class="bg-[#14131a] border border-[#2a2731] rounded-2xl p-5 sm:p-7 shadow-xl">
            <div class="flex items-center gap-3 pb-5 border-b border-[#1e1c25]">
                <div class="w-10 h-10 rounded-xl bg-[#b08d57]/20 border border-[#b08d57]/30 flex items-center justify-center text-[#b08d57]">
                    <i data-lucide="bell-ring" class="w-5 h-5"></i>
                </div>
                <div>
                    <h3 class="text-base sm:text-lg font-bold text-white">App & Sound Preferences</h3>
                    <p class="text-xs text-stone-500">Configure how you receive order status updates</p>
                </div>
            </div>

            <div class="mt-5 space-y-4">
                <div class="flex items-center justify-between p-3.5 bg-[#0f0e13] border border-[#2a2731] rounded-xl">
                    <div class="pr-4">
                        <h4 class="text-sm font-semibold text-white">Order Status Sound Chimes</h4>
                        <p class="text-xs text-stone-500 mt-0.5">Play an instant sound notification when your order moves to kitchen or out for delivery</p>
                    </div>
                    <button type="button" @click="toggleSoundChime()"
                        class="w-12 h-6 flex items-center rounded-full p-1 transition-colors duration-200 ease-in-out cursor-pointer"
                        :class="preferences.sound ? 'bg-[#b08d57]' : 'bg-[#2a2731]'">
                        <div class="bg-[#0f0e13] w-4 h-4 rounded-full shadow-md transform transition-transform duration-200 ease-in-out"
                             :class="preferences.sound ? 'translate-x-6' : 'translate-x-0'"></div>
                    </button>
                </div>

                <div class="flex items-center justify-between p-3.5 bg-[#0f0e13] border border-[#2a2731] rounded-xl">
                    <div class="pr-4">
                        <h4 class="text-sm font-semibold text-white">Instant Courier Chat Alerts</h4>
                        <p class="text-xs text-stone-500 mt-0.5">Automatically show toast notifications when a driver sends you a message</p>
                    </div>
                    <button type="button" @click="preferences.chatAlerts = !preferences.chatAlerts; savePreferences()"
                        class="w-12 h-6 flex items-center rounded-full p-1 transition-colors duration-200 ease-in-out cursor-pointer"
                        :class="preferences.chatAlerts ? 'bg-[#b08d57]' : 'bg-[#2a2731]'">
                        <div class="bg-[#0f0e13] w-4 h-4 rounded-full shadow-md transform transition-transform duration-200 ease-in-out"
                             :class="preferences.chatAlerts ? 'translate-x-6' : 'translate-x-0'"></div>
                    </button>
                </div>
            </div>
        </div>

        <!-- Danger Zone / Sign Out -->
        <div class="bg-[#14131a] border border-rose-900/30 rounded-2xl p-5 sm:p-7 shadow-xl">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h3 class="text-base font-bold text-white">Session Management</h3>
                    <p class="text-xs text-stone-500 mt-0.5">Log out of your CraveDash session securely from this browser</p>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="w-full sm:w-auto bg-rose-500/10 hover:bg-rose-500/20 border border-rose-500/30 text-rose-400 font-bold text-xs sm:text-sm px-5 py-2.5 rounded-xl transition flex items-center justify-center gap-2 cursor-pointer">
                        <i data-lucide="log-out" class="w-4 h-4"></i>
                        <span>Sign Out of CraveDash</span>
                    </button>
                </form>
            </div>
        </div>

    </div>
</div>