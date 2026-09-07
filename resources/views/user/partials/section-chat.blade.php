<!-- SECTION 3: Real-Time Chat Support (Permanent Split on PC, Responsive on Mobile) -->
<div id="section-chat" class="page-section hidden flex flex-1 h-full min-h-0 bg-[#14131a]/40 w-full overflow-hidden" x-data="chatApp('customer')" x-init="init()">
    <div class="flex h-full w-full overflow-hidden">

        <!-- Conversation List (Left Pane) -->
        <div class="w-full md:w-80 border-r border-[#1e1c25] flex-col shrink-0 h-full overflow-hidden"
             :class="mobileView === 'thread' ? 'hidden md:flex' : 'flex'">
            
            <div class="p-4 sm:p-5 border-b border-[#1e1c25] shrink-0 bg-[#0f0e13]">
                <h1 class="text-lg sm:text-xl font-bold text-white tracking-tight">Chat Support</h1>
                <p class="text-stone-500 text-xs mt-0.5">Talk to your driver when an order is out for delivery</p>
            </div>
            
            <div class="flex-1 overflow-y-auto custom-scroll divide-y divide-[#1e1c25]/40">
                <template x-for="conv in conversations" :key="conv.order_id">
                    <button @click="openConversation(conv)"
                        class="w-full text-left p-4 hover:bg-[#1e1c25]/80 transition flex flex-col gap-1.5 cursor-pointer"
                        :class="activeOrderId === conv.order_id ? 'bg-[#1e1c25] border-l-4 border-[#b08d57]' : ''">
                        
                        <div class="flex items-center justify-between gap-2">
                            <span class="text-sm font-bold text-white truncate" x-text="'Order #' + conv.order_id + ' · ' + conv.other_party"></span>
                            <span class="w-2.5 h-2.5 rounded-full shrink-0" 
                                  :class="conv.can_chat ? 'bg-emerald-400 shadow-sm shadow-emerald-400/50' : 'bg-stone-600'"
                                  :title="conv.can_chat ? 'Driver is active' : 'Chat closed'"></span>
                        </div>
                        
                        <div class="flex items-center justify-between gap-2 text-xs">
                            <p class="text-stone-400 truncate flex-1" x-text="conv.last_message || 'Tap to open chat...'"></p>
                            <span class="text-stone-500 text-[10px] whitespace-nowrap" x-text="conv.last_at || ''"></span>
                        </div>
                    </button>
                </template>
                
                <div x-show="conversations.length === 0" class="p-8 text-center text-stone-500 text-xs flex flex-col items-center">
                    <i data-lucide="message-square-dashed" class="w-8 h-8 text-stone-600 mb-2"></i>
                    <span>No active or past chats yet.</span>
                    <span class="mt-1 text-[11px] text-stone-600">A chat automatically opens when a courier accepts your order.</span>
                </div>
            </div>
        </div>

        <!-- Thread / Messages (Right Pane) -->
        <div class="flex-1 flex-col min-h-0 h-full overflow-hidden bg-[#0f0e13]/60"
             :class="mobileView === 'list' ? 'hidden md:flex' : 'flex'">

            <!-- Blank state when no conversation is selected on desktop -->
            <template x-if="!activeOrderId">
                <div class="flex-1 flex flex-col items-center justify-center text-center p-6">
                    <div class="w-16 h-16 rounded-3xl bg-[#14131a] border border-[#2a2731] flex items-center justify-center text-[#b08d57] mb-4 shadow-xl">
                        <i data-lucide="messages-square" class="w-8 h-8"></i>
                    </div>
                    <h3 class="text-white font-bold text-base">Select a conversation</h3>
                    <p class="text-stone-500 text-xs mt-1 max-w-xs">Click on any order from the list on the left to start real-time messaging with your delivery driver.</p>
                </div>
            </template>

            <!-- Active Chat Thread -->
            <template x-if="activeOrderId">
                <div class="flex-1 flex flex-col min-h-0 h-full">
                    
                    <!-- Header -->
                    <div class="p-3.5 sm:p-4 border-b border-[#1e1c25] bg-[#0f0e13] flex items-center justify-between gap-3 shrink-0">
                        <div class="flex items-center gap-3 min-w-0">
                            <button @click="backToList()" class="md:hidden p-2 -ml-1 rounded-xl text-stone-400 hover:text-white hover:bg-[#1e1c25] transition">
                                <i data-lucide="arrow-left" class="w-5 h-5"></i>
                            </button>
                            
                            <div class="w-9 h-9 rounded-xl bg-[#b08d57]/20 border border-[#b08d57]/30 flex items-center justify-center text-[#b08d57] shrink-0">
                                <i data-lucide="user" class="w-4 h-4"></i>
                            </div>

                            <div class="min-w-0">
                                <h3 class="text-sm font-bold text-white truncate" x-text="activeOrderTitle"></h3>
                                <span class="text-[11px] text-stone-400 block" x-text="canSend ? 'Live with Courier' : 'Order finalized (Closed)'"></span>
                            </div>
                        </div>

                        <span class="text-[10px] uppercase font-extrabold px-2.5 py-1 rounded-full shrink-0 flex items-center gap-1.5"
                              :class="canSend ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/30' : 'bg-stone-800 text-stone-500 border border-[#2a2731]'">
                            <span class="w-1.5 h-1.5 rounded-full" :class="canSend ? 'bg-emerald-400 animate-pulse' : 'bg-stone-500'"></span>
                            <span x-text="canSend ? 'Live Delivery' : 'Closed'"></span>
                        </span>
                    </div>

                    <!-- Message Bubbles Scroll Area -->
                    <div x-ref="messageList" class="flex-1 overflow-y-auto custom-scroll p-4 sm:p-6 space-y-3.5">
                        <template x-for="msg in messages" :key="msg.id || msg.temp_id">
                            <div class="flex flex-col" :class="msg.is_me ? 'items-end' : 'items-start'">
                                <div class="max-w-[80%] sm:max-w-[65%] rounded-2xl px-4 py-2.5 text-sm shadow-md"
                                     :class="msg.is_me ? 'bg-[#b08d57] text-[#0f0e13] rounded-br-none font-medium' : 'bg-[#1e1c25] text-stone-200 rounded-bl-none border border-[#2a2731]'">
                                    <p x-text="msg.message" class="break-words leading-relaxed"></p>
                                    <div class="flex items-center justify-end gap-1 text-[10px] mt-1 opacity-70">
                                        <span x-text="msg.created_at"></span>
                                        <i x-show="msg.sending" data-lucide="clock" class="w-3 h-3 animate-spin"></i>
                                    </div>
                                </div>
                            </div>
                        </template>
                        
                        <div x-show="!loading && messages.length === 0" class="text-center text-stone-500 text-xs py-12 flex flex-col items-center">
                            <i data-lucide="smile" class="w-8 h-8 text-stone-600 mb-2"></i>
                            <span>No messages in this order yet. Send a message to say hello!</span>
                        </div>
                    </div>

                    <!-- Footer Input Box -->
                    <div class="p-3 sm:p-4 border-t border-[#1e1c25] bg-[#0f0e13] shrink-0">
                        <div x-show="!canSend" class="text-xs text-stone-500 text-center py-2 bg-[#14131a] rounded-xl border border-[#2a2731]">
                            Chat is closed. Real-time messaging is only available while the courier is delivering your meal.
                        </div>
                        
                        <form x-show="canSend" @submit.prevent="send()" class="flex items-center gap-2">
                            <input x-model="draft" type="text" placeholder="Type a message to your courier..."
                                class="cd-input flex-1 bg-[#14131a] border border-[#2a2731] rounded-xl px-4 py-3 text-sm text-white placeholder-stone-600 focus:outline-none transition">
                            <button type="submit" :disabled="!draft.trim()"
                                class="w-11 h-11 rounded-xl bg-[#b08d57] hover:bg-[#c9a36b] disabled:opacity-40 text-[#0f0e13] font-bold flex items-center justify-center transition shrink-0 shadow-lg shadow-[#b08d57]/20 active:scale-95">
                                <i data-lucide="send" class="w-4 h-4"></i>
                            </button>
                        </form>
                    </div>

                </div>
            </template>
        </div>
    </div>
</div>