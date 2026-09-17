<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Dashboard | CraveDash</title>

    <!-- Laravel Vite Bundled Tailwind CSS & JS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@0.474.0/dist/umd/lucide.js" defer></script>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js" defer></script>

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .custom-scroll::-webkit-scrollbar { width: 5px; height: 5px; }
        .custom-scroll::-webkit-scrollbar-track { background: transparent; }
        .custom-scroll::-webkit-scrollbar-thumb { background: #2a2731; border-radius: 9999px; }
        .custom-scroll::-webkit-scrollbar-thumb:hover { background: #3a3741; }
        .nav-link { color: #a8a29e; transition: all 0.15s ease-in-out; }
        .nav-link:hover { color: #f5f5f4; background: #1e1c25; }
        .nav-link.active { background: #b08d57; color: #0f0e13; font-weight: 700; }
        .cd-input:focus { border-color: #b08d57; box-shadow: 0 0 0 3px rgba(176, 141, 87, 0.18); }
        .cd-input { background-color: #0f0e13; border: 1px solid #2a2731; }
        .cd-input::placeholder { color: #57534e; }
        
        /* Highlight flash on updated rows */
        @keyframes flashRow {
            0% { background-color: rgba(176, 141, 87, 0.4); }
            100% { background-color: transparent; }
        }
        .row-highlight {
            animation: flashRow 1.8s cubic-bezier(0.2, 0.8, 0.2, 1);
        }

        /* Generic Edit Drawers CSS Transitions (Products, Staff, Extras) */
        .edit-drawer-closed {
            opacity: 0;
            max-height: 0;
            transform: translateY(-16px) scale(0.98);
            overflow: hidden;
            margin-bottom: 0 !important;
            pointer-events: none;
            transition: all 0.35s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .edit-drawer-open {
            opacity: 1;
            max-height: 1000px;
            transform: translateY(0) scale(1);
            overflow: visible;
            margin-bottom: 2rem !important;
            pointer-events: auto;
            transition: all 0.35s cubic-bezier(0.16, 1, 0.3, 1);
        }

        /* Pure CSS Bottom Right Toast Notification */
        #toast-notification {
            position: fixed !important;
            bottom: 24px !important;
            right: 24px !important;
            z-index: 99999 !important;
            display: flex !important;
            align-items: center;
            opacity: 0;
            visibility: hidden;
            transform: translate3d(0, 32px, 0) scale(0.94);
            transition: opacity 0.35s cubic-bezier(0.16, 1, 0.3, 1), 
                        transform 0.35s cubic-bezier(0.16, 1, 0.3, 1),
                        visibility 0.35s !important;
            pointer-events: none;
        }
        #toast-notification.toast-active {
            opacity: 1 !important;
            visibility: visible !important;
            transform: translate3d(0, 0, 0) scale(1) !important;
            pointer-events: auto !important;
        }
    </style>
</head>
<body class="bg-[#0f0e13] text-stone-200 h-screen overflow-hidden selection:bg-[#b08d57] selection:text-[#0f0e13] relative">

<!-- Mobile Top Navigation Bar -->
<header class="lg:hidden bg-[#0f0e13] border-b border-[#1e1c25] px-4 py-3.5 flex items-center justify-between z-30 shrink-0">
    <div class="flex items-center gap-3">
        <button id="open-sidebar-btn" type="button" class="p-2 -ml-2 rounded-xl text-stone-400 hover:text-white hover:bg-[#1e1c25] transition" aria-label="Open Menu">
            <i data-lucide="menu" class="w-6 h-6"></i>
        </button>
        <div class="flex items-center gap-2.5">
            <div class="w-8 h-8 rounded-lg bg-[#b08d57] flex items-center justify-center text-[#0f0e13]">
                <i data-lucide="utensils" class="w-4 h-4 stroke-[2.5]"></i>
            </div>
            <div>
                <span class="text-white font-bold text-sm block leading-tight">Crave<span class="text-[#b08d57]">Dash</span></span>
                <span class="text-[10px] text-stone-500 font-medium">Admin Panel</span>
            </div>
        </div>
    </div>
</header>

<div class="flex h-[calc(100vh-57px)] lg:h-full w-full relative">

    {{-- Sidebar Partial --}}
    @include('admin.partials.sidebar')

    {{-- Main Content Area --}}
    <main class="flex-1 p-4 sm:p-6 lg:p-10 overflow-y-auto custom-scroll" id="main-scroll-container">
        @include('admin.partials.section-overview')
        @include('admin.partials.section-products')
        @include('admin.partials.section-staff')
        @include('admin.partials.section-extras')
    </main>

</div>

<!-- High-Visibility Bottom-Right Toast Notification -->
<div id="toast-notification" class="gap-3 bg-[#14131a]/95 backdrop-blur-xl border border-[#b08d57]/50 text-stone-200 px-4 py-3.5 rounded-2xl shadow-[0_12px_40px_rgba(0,0,0,0.85)] max-w-sm">
    <div id="toast-icon-wrapper" class="w-8 h-8 rounded-xl bg-[#b08d57]/20 border border-[#b08d57]/40 flex items-center justify-center text-[#b08d57] shrink-0">
        <svg class="w-4 h-4 stroke-current" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="20 6 9 17 4 12"></polyline>
        </svg>
    </div>
    <div class="flex-1 min-w-0">
        <h4 id="toast-title" class="text-xs font-bold text-white tracking-wider uppercase">Success</h4>
        <p id="toast-message" class="text-xs text-stone-400 mt-0.5 truncate">Action completed successfully.</p>
    </div>
    <button type="button" id="toast-close-btn" class="text-stone-500 hover:text-stone-200 transition p-1 -mr-1" aria-label="Close notification">
        <svg class="w-4 h-4 stroke-current" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <line x1="18" y1="6" x2="6" y2="18"></line>
            <line x1="6" y1="6" x2="18" y2="18"></line>
        </svg>
    </button>
</div>

<script>
const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

// ---- Toast Notification Engine ----
let toastTimeout = null;

function showToast(message, title = 'Success', type = 'success') {
    const toast = document.getElementById('toast-notification');
    const toastTitle = document.getElementById('toast-title');
    const toastMessage = document.getElementById('toast-message');
    const toastIcon = document.getElementById('toast-icon-wrapper');

    if (!toast) return;

    clearTimeout(toastTimeout);

    toastTitle.textContent = title;
    toastMessage.textContent = message;

    if (type === 'error') {
        toastIcon.className = "w-8 h-8 rounded-xl bg-rose-500/20 border border-rose-500/40 flex items-center justify-center text-rose-400 shrink-0";
        toastIcon.innerHTML = `<svg class="w-4 h-4 stroke-current" fill="none" viewBox="0 0 24 24" stroke-width="2.5"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>`;
    } else {
        toastIcon.className = "w-8 h-8 rounded-xl bg-[#b08d57]/20 border border-[#b08d57]/40 flex items-center justify-center text-[#b08d57] shrink-0";
        toastIcon.innerHTML = `<svg class="w-4 h-4 stroke-current" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>`;
    }

    toast.classList.add('toast-active');

    toastTimeout = setTimeout(() => {
        hideToast();
    }, 3800);
}

function hideToast() {
    const toast = document.getElementById('toast-notification');
    if (toast) {
        toast.classList.remove('toast-active');
    }
}

document.getElementById('toast-close-btn')?.addEventListener('click', hideToast);

// ---- Charts Initialization ----
let revenueChartInstance = null;
let categoryChartInstance = null;

function initCharts() {
    const chartLabels = @json($chartLabels);
    const chartRevenue = @json($chartRevenue);
    const chartOrders = @json($chartOrders);
    const categoryLabels = @json($categoryLabels);
    const categoryCounts = @json($categoryCounts);

    const revCtx = document.getElementById('revenueTrendChart')?.getContext('2d');
    if (revCtx) {
        if (revenueChartInstance) revenueChartInstance.destroy();

        const goldGradient = revCtx.createLinearGradient(0, 0, 0, 300);
        goldGradient.addColorStop(0, 'rgba(176, 141, 87, 0.45)');
        goldGradient.addColorStop(1, 'rgba(176, 141, 87, 0.0)');

        revenueChartInstance = new Chart(revCtx, {
            type: 'line',
            data: {
                labels: chartLabels,
                datasets: [
                    {
                        label: 'Revenue (ETB)',
                        data: chartRevenue,
                        borderColor: '#b08d57',
                        backgroundColor: goldGradient,
                        borderWidth: 2.5,
                        fill: true,
                        tension: 0.35,
                        pointBackgroundColor: '#b08d57',
                        pointBorderColor: '#0f0e13',
                        pointRadius: 3,
                        pointHoverRadius: 7,
                        yAxisID: 'y'
                    },
                    {
                        label: 'Orders Count',
                        data: chartOrders,
                        borderColor: '#a855f7',
                        backgroundColor: 'transparent',
                        borderWidth: 2,
                        borderDash: [5, 5],
                        pointBackgroundColor: '#a855f7',
                        pointBorderColor: '#0f0e13',
                        pointRadius: 3,
                        pointHoverRadius: 7,
                        tension: 0.35,
                        yAxisID: 'y1'
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: { mode: 'index', intersect: false },
                plugins: {
                    legend: {
                        labels: {
                            color: '#a8a29e',
                            font: { family: 'Plus Jakarta Sans', size: 11, weight: '600' }
                        }
                    },
                    tooltip: {
                        backgroundColor: '#14131a',
                        titleColor: '#fff',
                        bodyColor: '#a8a29e',
                        borderColor: '#2a2731',
                        borderWidth: 1,
                        padding: 10,
                        cornerRadius: 10,
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) label += ': ';
                                if (context.datasetIndex === 0) {
                                    label += Number(context.parsed.y || 0).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + ' ETB';
                                } else {
                                    label += Number(context.parsed.y || 0).toLocaleString();
                                }
                                return label;
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { color: '#1e1c25' },
                        ticks: { color: '#78716c', font: { size: 11 } }
                    },
                    y: {
                        position: 'left',
                        beginAtZero: true,
                        grid: { color: '#1e1c25' },
                        ticks: {
                            color: '#78716c',
                            font: { size: 11 },
                            callback: value => Number(value).toLocaleString() + ' ETB'
                        }
                    },
                    y1: {
                        position: 'right',
                        beginAtZero: true,
                        grid: { drawOnChartArea: false },
                        ticks: {
                            color: '#a855f7',
                            font: { size: 11 },
                            precision: 0,
                            callback: value => Number(value).toLocaleString()
                        }
                    }
                }
            }
        });
    }

    const catCtx = document.getElementById('categoryDonutChart')?.getContext('2d');
    if (catCtx) {
        if (categoryChartInstance) categoryChartInstance.destroy();

        const defaultColors = ['#b08d57', '#3b82f6', '#10b981', '#f59e0b', '#ec4899', '#8b5cf6', '#64748b'];

        categoryChartInstance = new Chart(catCtx, {
            type: 'doughnut',
            data: {
                labels: categoryLabels.length > 0 ? categoryLabels : ['No Categories'],
                datasets: [{
                    data: categoryCounts.length > 0 ? categoryCounts : [1],
                    backgroundColor: defaultColors.slice(0, Math.max(categoryLabels.length, 1)),
                    borderColor: '#14131a',
                    borderWidth: 3,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '72%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            color: '#a8a29e',
                            boxWidth: 10,
                            padding: 12,
                            font: { family: 'Plus Jakarta Sans', size: 11 }
                        }
                    },
                    tooltip: {
                        backgroundColor: '#14131a',
                        titleColor: '#fff',
                        bodyColor: '#a8a29e',
                        borderColor: '#2a2731',
                        borderWidth: 1,
                        cornerRadius: 10
                    }
                }
            }
        });
    }
}

// ---- Mobile Drawer Controls ----
const sidebar = document.getElementById('sidebar');
const sidebarBackdrop = document.getElementById('sidebar-backdrop');
const openSidebarBtn = document.getElementById('open-sidebar-btn');
const closeSidebarBtn = document.getElementById('close-sidebar-btn');

function openMobileSidebar() {
    sidebar.classList.remove('-translate-x-full');
    sidebarBackdrop.classList.remove('hidden');
    setTimeout(() => lucide.createIcons(), 50);
}

function closeMobileSidebar() {
    sidebar.classList.add('-translate-x-full');
    sidebarBackdrop.classList.add('hidden');
}

if (openSidebarBtn) openSidebarBtn.addEventListener('click', openMobileSidebar);
if (closeSidebarBtn) closeSidebarBtn.addEventListener('click', closeMobileSidebar);
if (sidebarBackdrop) sidebarBackdrop.addEventListener('click', closeMobileSidebar);

// ---- Sidebar Navigation Switching ----
const navLinks = document.querySelectorAll('.nav-link');
const sections = document.querySelectorAll('.page-section');

function showSection(target) {
    sections.forEach(s => s.classList.add('hidden'));
    document.getElementById('section-' + target).classList.remove('hidden');
    navLinks.forEach(l => l.classList.remove('active'));
    document.querySelectorAll(`.nav-link[data-target="${target}"]`).forEach(l => l.classList.add('active'));
    
    closeMobileSidebar();
    setTimeout(() => {
        lucide.createIcons();
        if (target === 'overview') {
            initCharts();
        }
    }, 50);
}

navLinks.forEach(link => {
    link.addEventListener('click', () => showSection(link.dataset.target));
});
showSection('overview');

// ---- Image Picker & Preview Handling (Create Product) ----
const imageInput = document.getElementById('product-image-input');
const dropzone = document.getElementById('image-dropzone');
const placeholder = document.getElementById('image-placeholder');
const previewContainer = document.getElementById('image-preview-container');
const previewImg = document.getElementById('image-preview-img');
const previewName = document.getElementById('image-preview-name');
const removeImageBtn = document.getElementById('remove-image-btn');

function showImagePreview(file) {
    if (file && file.type.startsWith('image/')) {
        const reader = new FileReader();
        reader.onload = (e) => {
            previewImg.src = e.target.result;
            previewName.textContent = file.name;
            placeholder.classList.add('hidden');
            previewContainer.classList.remove('hidden');
            setTimeout(() => lucide.createIcons(), 50);
        };
        reader.readAsDataURL(file);
    }
}

function resetImagePreview() {
    if (imageInput) imageInput.value = '';
    if (previewImg) previewImg.src = '';
    if (previewName) previewName.textContent = '';
    if (previewContainer) previewContainer.classList.add('hidden');
    if (placeholder) placeholder.classList.remove('hidden');
    setTimeout(() => lucide.createIcons(), 50);
}

if (imageInput) {
    imageInput.addEventListener('change', function () {
        if (this.files && this.files[0]) {
            showImagePreview(this.files[0]);
        } else {
            resetImagePreview();
        }
    });
}

if (dropzone) {
    dropzone.addEventListener('click', (e) => {
        if (!e.target.closest('#remove-image-btn')) {
            imageInput.click();
        }
    });

    ['dragenter', 'dragover'].forEach(eventName => {
        dropzone.addEventListener(eventName, (e) => {
            e.preventDefault();
            dropzone.classList.add('border-[#b08d57]', 'bg-[#1e1c25]/80');
        });
    });

    ['dragleave', 'drop'].forEach(eventName => {
        dropzone.addEventListener(eventName, (e) => {
            e.preventDefault();
            dropzone.classList.remove('border-[#b08d57]', 'bg-[#1e1c25]/80');
        });
    });

    dropzone.addEventListener('drop', (e) => {
        const files = e.dataTransfer.files;
        if (files && files.length > 0) {
            imageInput.files = files;
            showImagePreview(files[0]);
        }
    });
}

if (removeImageBtn) {
    removeImageBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        resetImagePreview();
    });
}

// Edit Product Image Picker
const editProductImageInput = document.getElementById('edit-product-image-input');
const editImageDropzone = document.getElementById('edit-image-dropzone');
const editImagePreviewThumb = document.getElementById('edit-image-preview-thumb');
const editImagePlaceholderText = document.getElementById('edit-image-placeholder-text');

if (editImageDropzone && editProductImageInput) {
    editImageDropzone.addEventListener('click', () => editProductImageInput.click());
    editProductImageInput.addEventListener('change', function () {
        if (this.files && this.files[0]) {
            const file = this.files[0];
            const reader = new FileReader();
            reader.onload = (e) => {
                editImagePreviewThumb.src = e.target.result;
                editImagePlaceholderText.textContent = file.name;
            };
            reader.readAsDataURL(file);
        }
    });
}

// ---- Customized Dropdowns Management ----
function initCustomSelects(container = document) {
    container.querySelectorAll('.custom-select-wrapper').forEach(wrapper => {
        if (wrapper.dataset.initialized) return;
        wrapper.dataset.initialized = "true";

        const trigger = wrapper.querySelector('.custom-select-trigger');
        const menu = wrapper.querySelector('.custom-select-menu');
        const label = wrapper.querySelector('.custom-select-label');
        const triggerDot = wrapper.querySelector('.trigger-dot');
        const chevron = wrapper.querySelector('.chevron-icon');
        const nativeSelect = wrapper.querySelector('.custom-native-select');
        const options = wrapper.querySelectorAll('.custom-option');
        const placeholderText = label.getAttribute('data-placeholder') || label.textContent;

        function toggleMenu(show) {
            const isOpen = show !== undefined ? show : menu.classList.contains('hidden');
            if (isOpen) {
                document.querySelectorAll('.custom-select-menu').forEach(m => {
                    if (m !== menu) {
                        m.classList.add('hidden');
                        const otherChevron = m.closest('.custom-select-wrapper')?.querySelector('.chevron-icon');
                        if (otherChevron) otherChevron.classList.remove('rotate-180');
                    }
                });
                menu.classList.remove('hidden');
                if (chevron) chevron.classList.add('rotate-180');
                trigger.classList.add('border-[#b08d57]', 'ring-2', 'ring-[#b08d57]/20');
            } else {
                menu.classList.add('hidden');
                if (chevron) chevron.classList.remove('rotate-180');
                trigger.classList.remove('border-[#b08d57]', 'ring-2', 'ring-[#b08d57]/20');
            }
        }

        trigger.addEventListener('click', (e) => {
            e.stopPropagation();
            toggleMenu();
        });

        options.forEach(opt => {
            opt.addEventListener('click', (e) => {
                e.stopPropagation();
                const val = opt.getAttribute('data-value');
                const text = opt.getAttribute('data-text');

                nativeSelect.value = val;
                nativeSelect.dispatchEvent(new Event('change', { bubbles: true }));

                label.textContent = text;
                label.classList.remove('text-stone-500');
                label.classList.add('text-white', 'font-semibold');
                if (triggerDot) triggerDot.classList.replace('bg-stone-600', 'bg-[#b08d57]');

                options.forEach(o => {
                    const check = o.querySelector('.check-icon');
                    const dot = o.querySelector('.dot-indicator');
                    if (o === opt) {
                        o.classList.add('bg-[#1e1c25]', 'text-white');
                        if (check) check.classList.remove('hidden');
                        if (dot) dot.classList.replace('bg-stone-600', 'bg-[#b08d57]');
                    } else {
                        o.classList.remove('bg-[#1e1c25]', 'text-white');
                        if (check) check.classList.add('hidden');
                        if (dot) dot.classList.replace('bg-[#b08d57]', 'bg-stone-600');
                    }
                });

                toggleMenu(false);
            });
        });

        const parentForm = wrapper.closest('form');
        if (parentForm) {
            parentForm.addEventListener('reset', () => {
                setTimeout(() => {
                    nativeSelect.value = '';
                    label.textContent = placeholderText;
                    label.classList.add('text-stone-500');
                    label.classList.remove('text-white', 'font-semibold');
                    if (triggerDot) triggerDot.classList.replace('bg-[#b08d57]', 'bg-stone-600');
                    options.forEach(o => {
                        o.classList.remove('bg-[#1e1c25]', 'text-white');
                        const check = o.querySelector('.check-icon');
                        const dot = o.querySelector('.dot-indicator');
                        if (check) check.classList.add('hidden');
                        if (dot) dot.classList.replace('bg-[#b08d57]', 'bg-stone-600');
                    });
                }, 10);
            });
        }
    });

    document.addEventListener('click', () => {
        document.querySelectorAll('.custom-select-menu').forEach(m => m.classList.add('hidden'));
        document.querySelectorAll('.chevron-icon').forEach(c => c.classList.remove('rotate-180'));
        document.querySelectorAll('.custom-select-trigger').forEach(t => t.classList.remove('border-[#b08d57]', 'ring-2', 'ring-[#b08d57]/20'));
    });
}

function setCustomSelectValue(wrapperId, value) {
    const wrapper = document.getElementById(wrapperId);
    if (!wrapper) return;
    const option = wrapper.querySelector(`.custom-option[data-value="${value}"]`);
    if (option) {
        option.click();
    }
}

// ---- Reusable DataTable Filter & Buffer Engine ----
function setupDataTable({ searchInputId, clearBtnClass, tableBodyId, emptyStateId, countBadgeId, getFilters, rowMatcher }) {
    const searchInput = document.getElementById(searchInputId);
    const tableBody = document.getElementById(tableBodyId);
    const emptyState = document.getElementById(emptyStateId);
    const countBadge = document.getElementById(countBadgeId);
    const container = searchInput?.closest('.relative');
    const searchIcon = container?.querySelector('.search-icon');
    const spinner = container?.querySelector('.buffer-spinner');
    const clearBtn = container?.querySelector('.clear-search-btn');

    let debounceTimer = null;

    function applyFilter() {
        const query = searchInput ? searchInput.value.trim().toLowerCase() : '';
        const filters = getFilters ? getFilters() : {};
        const rows = tableBody.querySelectorAll('tr.data-row');
        let visibleCount = 0;
        const totalCount = rows.length;

        rows.forEach(row => {
            const matches = rowMatcher(row, query, filters);
            if (matches) {
                row.classList.remove('hidden');
                visibleCount++;
            } else {
                row.classList.add('hidden');
            }
        });

        if (countBadge) countBadge.textContent = `Showing ${visibleCount} of ${totalCount}`;

        if (emptyState) {
            if (visibleCount === 0 && totalCount > 0) {
                emptyState.classList.remove('hidden');
                emptyState.classList.add('flex');
            } else {
                emptyState.classList.add('hidden');
                emptyState.classList.remove('flex');
            }
        }

        if (spinner && searchIcon) {
            spinner.classList.add('hidden');
            searchIcon.classList.remove('hidden');
        }

        if (clearBtn) {
            if (query.length > 0) clearBtn.classList.remove('hidden');
            else clearBtn.classList.add('hidden');
        }
    }

    function triggerBufferedFilter() {
        if (spinner && searchIcon) {
            searchIcon.classList.add('hidden');
            spinner.classList.remove('hidden');
        }
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => {
            applyFilter();
        }, 180);
    }

    if (searchInput) searchInput.addEventListener('input', triggerBufferedFilter);

    if (clearBtn) {
        clearBtn.addEventListener('click', () => {
            searchInput.value = '';
            triggerBufferedFilter();
            searchInput.focus();
        });
    }

    return { refresh: applyFilter };
}

let productsDataTable, staffDataTable, extrasDataTable;

function initAllDataTables() {
    const prodCategorySelect = document.getElementById('products-filter-category');
    const prodStatusSelect = document.getElementById('products-filter-status');

    productsDataTable = setupDataTable({
        searchInputId: 'products-search-input',
        tableBodyId: 'products-table-body',
        emptyStateId: 'products-empty-state',
        countBadgeId: 'products-count-badge',
        getFilters: () => ({
            category: prodCategorySelect ? prodCategorySelect.value.toLowerCase() : 'all',
            status: prodStatusSelect ? prodStatusSelect.value.toLowerCase() : 'all'
        }),
        rowMatcher: (row, query, filters) => {
            const name = row.getAttribute('data-name') || '';
            const category = row.getAttribute('data-category') || '';
            const price = row.getAttribute('data-price') || '';
            const status = row.getAttribute('data-status') || '';

            const matchesQuery = !query || name.includes(query) || category.includes(query) || price.includes(query);
            const matchesCategory = filters.category === 'all' || category === filters.category;
            const matchesStatus = filters.status === 'all' || status === filters.status;

            return matchesQuery && matchesCategory && matchesStatus;
        }
    });

    if (prodCategorySelect) prodCategorySelect.addEventListener('change', () => productsDataTable.refresh());
    if (prodStatusSelect) prodStatusSelect.addEventListener('change', () => productsDataTable.refresh());

    const staffRoleSelect = document.getElementById('staff-filter-role');

    staffDataTable = setupDataTable({
        searchInputId: 'staff-search-input',
        tableBodyId: 'staff-table-body',
        emptyStateId: 'staff-empty-state',
        countBadgeId: 'staff-count-badge',
        getFilters: () => ({
            role: staffRoleSelect ? staffRoleSelect.value.toLowerCase() : 'all'
        }),
        rowMatcher: (row, query, filters) => {
            const fullname = row.getAttribute('data-fullname') || '';
            const username = row.getAttribute('data-username') || '';
            const phone = row.getAttribute('data-phone') || '';
            const role = row.getAttribute('data-role') || '';

            const matchesQuery = !query || fullname.includes(query) || username.includes(query) || phone.includes(query) || role.includes(query);
            const matchesRole = filters.role === 'all' || role === filters.role;

            return matchesQuery && matchesRole;
        }
    });

    if (staffRoleSelect) staffRoleSelect.addEventListener('change', () => staffDataTable.refresh());

    const extrasStatusSelect = document.getElementById('extras-filter-status');

    extrasDataTable = setupDataTable({
        searchInputId: 'extras-search-input',
        tableBodyId: 'extras-table-body',
        emptyStateId: 'extras-empty-state',
        countBadgeId: 'extras-count-badge',
        getFilters: () => ({
            status: extrasStatusSelect ? extrasStatusSelect.value.toLowerCase() : 'all'
        }),
        rowMatcher: (row, query, filters) => {
            const name = row.getAttribute('data-name') || '';
            const price = row.getAttribute('data-price') || '';
            const status = row.getAttribute('data-status') || '';

            const matchesQuery = !query || name.includes(query) || price.includes(query);
            const matchesStatus = filters.status === 'all' || status === filters.status;

            return matchesQuery && matchesStatus;
        }
    });

    if (extrasStatusSelect) extrasStatusSelect.addEventListener('change', () => extrasDataTable.refresh());
}

// ---- AJAX Form Submissions ----
async function submitForm(formEl, url, onSuccess) {
    const errorEl = formEl.querySelector('.form-error');
    if (errorEl) errorEl.textContent = '';

    let hasSelectError = false;
    formEl.querySelectorAll('.custom-native-select[required]').forEach(select => {
        if (!select.value) {
            hasSelectError = true;
            const trigger = select.closest('.custom-select-wrapper')?.querySelector('.custom-select-trigger');
            if (trigger) {
                trigger.classList.add('border-rose-500', 'ring-2', 'ring-rose-500/20');
                setTimeout(() => trigger.classList.remove('border-rose-500', 'ring-2', 'ring-rose-500/20'), 2500);
            }
        }
    });

    if (hasSelectError) {
        if (errorEl) errorEl.textContent = 'Please fill out all required fields.';
        return;
    }

    const formData = new FormData(formEl);

    try {
        const res = await fetch(url, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
            body: formData,
        });
        const data = await res.json();

        if (!res.ok) {
            const firstError = data.errors ? Object.values(data.errors)[0][0] : (data.message || 'Something went wrong.');
            if (errorEl) errorEl.textContent = firstError;
            showToast(firstError, 'Error', 'error');
            return;
        }

        onSuccess(data);
    } catch (err) {
        if (errorEl) errorEl.textContent = 'Network error. Try again.';
        showToast('Network connection failed. Try again.', 'Network Error', 'error');
    }
}

// ==========================================
// 1. STAFF EDIT CONTROLS
// ==========================================
const staffEditContainer = document.getElementById('staff-edit-container');
const staffEditForm = document.getElementById('staff-edit-form');
const cancelStaffEditBtn = document.getElementById('cancel-edit-staff-btn');
const cancelStaffEditBtnX = document.getElementById('cancel-edit-staff-btn-x');

function openStaffEditor(data) {
    document.getElementById('edit-staff-id').value = data.id;
    document.getElementById('edit-staff-fullname').value = data.fullname || '';
    document.getElementById('edit-staff-username').value = data.username || '';
    document.getElementById('edit-staff-email').value = data.email || '';
    document.getElementById('edit-staff-phone').value = data.phone || '';
    document.getElementById('edit-staff-password').value = '';

    setCustomSelectValue('staff-edit-role-wrapper', data.roleId);

    staffEditContainer.classList.remove('edit-drawer-closed');
    staffEditContainer.classList.add('edit-drawer-open');
    staffEditContainer.scrollIntoView({ behavior: 'smooth', block: 'start' });
    setTimeout(() => lucide.createIcons(), 50);
}

function closeStaffEditor() {
    staffEditContainer.classList.remove('edit-drawer-open');
    staffEditContainer.classList.add('edit-drawer-closed');
    setTimeout(() => {
        staffEditForm.reset();
        const err = staffEditForm.querySelector('.form-error');
        if (err) err.textContent = '';
    }, 350);
}

if (cancelStaffEditBtn) cancelStaffEditBtn.addEventListener('click', closeStaffEditor);
if (cancelStaffEditBtnX) cancelStaffEditBtnX.addEventListener('click', closeStaffEditor);

document.addEventListener('click', (e) => {
    const editBtn = e.target.closest('.edit-staff-btn');
    if (editBtn) {
        openStaffEditor({
            id: editBtn.dataset.id,
            fullname: editBtn.dataset.fullname,
            username: editBtn.dataset.username,
            email: editBtn.dataset.email,
            phone: editBtn.dataset.phone,
            roleId: editBtn.dataset.roleId,
            roleName: editBtn.dataset.roleName,
        });
    }
});

if (staffEditForm) {
    staffEditForm.addEventListener('submit', function (e) {
        e.preventDefault();
        const staffId = document.getElementById('edit-staff-id').value;
        const updateUrl = `/admin/staff/${staffId}`;

        submitForm(this, updateUrl, (data) => {
            const s = data.staff;
            const targetRow = document.getElementById(`staff-row-${s.id}`);

            if (targetRow) {
                targetRow.setAttribute('data-fullname', (s.fullname || '').toLowerCase());
                targetRow.setAttribute('data-username', (s.username || '').toLowerCase());
                targetRow.setAttribute('data-role', (s.role?.name || '').toLowerCase());
                targetRow.setAttribute('data-role-id', s.role_id);
                targetRow.setAttribute('data-email', s.email ?? '');
                targetRow.setAttribute('data-phone', (s.phone || '').toLowerCase());

                targetRow.querySelector('.staff-name-cell').textContent = s.fullname;
                targetRow.querySelector('.staff-username-cell').textContent = s.username;
                targetRow.querySelector('.staff-role-cell').textContent = s.role.name.charAt(0).toUpperCase() + s.role.name.slice(1);
                targetRow.querySelector('.staff-phone-cell').textContent = s.phone ?? '';

                const btn = targetRow.querySelector('.edit-staff-btn');
                if (btn) {
                    btn.dataset.fullname = s.fullname;
                    btn.dataset.username = s.username;
                    btn.dataset.email = s.email ?? '';
                    btn.dataset.phone = s.phone ?? '';
                    btn.dataset.roleId = s.role_id;
                    btn.dataset.roleName = s.role.name.charAt(0).toUpperCase() + s.role.name.slice(1);
                }

                targetRow.classList.remove('row-highlight');
                void targetRow.offsetWidth;
                targetRow.classList.add('row-highlight');
            }

            closeStaffEditor();
            showToast(`Staff member "${s.fullname}" updated successfully!`, 'Updated', 'success');
            if (staffDataTable) staffDataTable.refresh();
        });
    });
}

// ==========================================
// 2. PRODUCT EDIT CONTROLS
// ==========================================
const productEditContainer = document.getElementById('product-edit-container');
const productEditForm = document.getElementById('product-edit-form');
const cancelProductEditBtn = document.getElementById('cancel-edit-product-btn');
const cancelProductEditBtnX = document.getElementById('cancel-edit-product-btn-x');

function openProductEditor(data) {
    document.getElementById('edit-product-id').value = data.id;
    document.getElementById('edit-product-name').value = data.name || '';
    document.getElementById('edit-product-price').value = data.price || '';
    document.getElementById('edit-product-description').value = data.description || '';
    document.getElementById('edit-product-available').checked = (data.isAvailable === '1');
    
    // Set current thumbnail
    if (editImagePreviewThumb) {
        editImagePreviewThumb.src = `/storage/${data.image}`;
    }
    if (editImagePlaceholderText) {
        editImagePlaceholderText.textContent = 'Click to replace image...';
    }
    if (editProductImageInput) {
        editProductImageInput.value = '';
    }

    setCustomSelectValue('edit-product-category-wrapper', data.categoryId);

    productEditContainer.classList.remove('edit-drawer-closed');
    productEditContainer.classList.add('edit-drawer-open');
    productEditContainer.scrollIntoView({ behavior: 'smooth', block: 'start' });
    setTimeout(() => lucide.createIcons(), 50);
}

function closeProductEditor() {
    productEditContainer.classList.remove('edit-drawer-open');
    productEditContainer.classList.add('edit-drawer-closed');
    setTimeout(() => {
        productEditForm.reset();
        const err = productEditForm.querySelector('.form-error');
        if (err) err.textContent = '';
    }, 350);
}

if (cancelProductEditBtn) cancelProductEditBtn.addEventListener('click', closeProductEditor);
if (cancelProductEditBtnX) cancelProductEditBtnX.addEventListener('click', closeProductEditor);

document.addEventListener('click', (e) => {
    const editBtn = e.target.closest('.edit-product-btn');
    if (editBtn) {
        openProductEditor({
            id: editBtn.dataset.id,
            name: editBtn.dataset.name,
            categoryId: editBtn.dataset.categoryId,
            categoryName: editBtn.dataset.categoryName,
            price: editBtn.dataset.price,
            description: editBtn.dataset.description,
            image: editBtn.dataset.image,
            isAvailable: editBtn.dataset.isAvailable,
        });
    }
});

if (productEditForm) {
    productEditForm.addEventListener('submit', function (e) {
        e.preventDefault();
        const productId = document.getElementById('edit-product-id').value;
        const updateUrl = `/admin/products/${productId}`;

        submitForm(this, updateUrl, (data) => {
            const p = data.product;
            const targetRow = document.getElementById(`product-row-${p.id}`);

            if (targetRow) {
                targetRow.setAttribute('data-name', (p.name || '').toLowerCase());
                targetRow.setAttribute('data-category', (p.category?.name || '').toLowerCase());
                targetRow.setAttribute('data-category-id', p.category_id);
                targetRow.setAttribute('data-price', p.price);
                targetRow.setAttribute('data-description', p.description ?? '');
                targetRow.setAttribute('data-image', p.image);
                targetRow.setAttribute('data-status', p.is_available ? 'available' : 'unavailable');

                targetRow.querySelector('.product-image-cell').src = `/storage/${p.image}?t=${Date.now()}`;
                targetRow.querySelector('.product-name-cell').textContent = p.name;
                targetRow.querySelector('.product-cat-cell').textContent = p.category?.name;
                targetRow.querySelector('.product-price-cell').textContent = `${Number(p.price).toFixed(2)} ETB`;
                
                targetRow.querySelector('.product-status-cell').innerHTML = p.is_available 
                    ? '<span class="inline-flex items-center gap-1.5 text-xs font-semibold text-emerald-400 bg-emerald-500/10 border border-emerald-500/20 px-2.5 py-1 rounded-full"><i data-lucide="check" class="w-3 h-3"></i>Yes</span>'
                    : '<span class="inline-flex items-center gap-1.5 text-xs font-semibold text-rose-400 bg-rose-500/10 border border-rose-500/20 px-2.5 py-1 rounded-full"><i data-lucide="x" class="w-3 h-3"></i>No</span>';

                const btn = targetRow.querySelector('.edit-product-btn');
                if (btn) {
                    btn.dataset.name = p.name;
                    btn.dataset.categoryId = p.category_id;
                    btn.dataset.categoryName = p.category?.name;
                    btn.dataset.price = p.price;
                    btn.dataset.description = p.description ?? '';
                    btn.dataset.image = p.image;
                    btn.dataset.isAvailable = p.is_available ? '1' : '0';
                }

                targetRow.classList.remove('row-highlight');
                void targetRow.offsetWidth;
                targetRow.classList.add('row-highlight');
            }

            closeProductEditor();
            showToast(`Product "${p.name}" updated successfully!`, 'Updated', 'success');
            if (productsDataTable) productsDataTable.refresh();
            setTimeout(() => lucide.createIcons(), 50);
        });
    });
}

// ==========================================
// 3. EXTRAS EDIT CONTROLS
// ==========================================
const extraEditContainer = document.getElementById('extra-edit-container');
const extraEditForm = document.getElementById('extra-edit-form');
const cancelExtraEditBtn = document.getElementById('cancel-edit-extra-btn');
const cancelExtraEditBtnX = document.getElementById('cancel-edit-extra-btn-x');

function openExtraEditor(data) {
    document.getElementById('edit-extra-id').value = data.id;
    document.getElementById('edit-extra-name').value = data.name || '';
    document.getElementById('edit-extra-price').value = data.price || '';
    document.getElementById('edit-extra-available').checked = (data.isAvailable === '1');

    extraEditContainer.classList.remove('edit-drawer-closed');
    extraEditContainer.classList.add('edit-drawer-open');
    extraEditContainer.scrollIntoView({ behavior: 'smooth', block: 'start' });
    setTimeout(() => lucide.createIcons(), 50);
}

function closeExtraEditor() {
    extraEditContainer.classList.remove('edit-drawer-open');
    extraEditContainer.classList.add('edit-drawer-closed');
    setTimeout(() => {
        extraEditForm.reset();
        const err = extraEditForm.querySelector('.form-error');
        if (err) err.textContent = '';
    }, 350);
}

if (cancelExtraEditBtn) cancelExtraEditBtn.addEventListener('click', closeExtraEditor);
if (cancelExtraEditBtnX) cancelExtraEditBtnX.addEventListener('click', closeExtraEditor);

document.addEventListener('click', (e) => {
    const editBtn = e.target.closest('.edit-extra-btn');
    if (editBtn) {
        openExtraEditor({
            id: editBtn.dataset.id,
            name: editBtn.dataset.name,
            price: editBtn.dataset.price,
            isAvailable: editBtn.dataset.isAvailable,
        });
    }
});

if (extraEditForm) {
    extraEditForm.addEventListener('submit', function (e) {
        e.preventDefault();
        const extraId = document.getElementById('edit-extra-id').value;
        const updateUrl = `/admin/extras/${extraId}`;

        submitForm(this, updateUrl, (data) => {
            const ex = data.extra;
            const targetRow = document.getElementById(`extra-row-${ex.id}`);

            if (targetRow) {
                targetRow.setAttribute('data-name', (ex.name || '').toLowerCase());
                targetRow.setAttribute('data-price', ex.price);
                targetRow.setAttribute('data-status', ex.is_available ? 'available' : 'unavailable');

                targetRow.querySelector('.extra-name-cell').textContent = ex.name;
                targetRow.querySelector('.extra-price-cell').textContent = `${Number(ex.price).toFixed(2)} ETB`;
                
                targetRow.querySelector('.extra-status-cell').innerHTML = ex.is_available 
                    ? '<span class="inline-flex items-center gap-1.5 text-xs font-semibold text-emerald-400 bg-emerald-500/10 border border-emerald-500/20 px-2.5 py-1 rounded-full"><i data-lucide="check" class="w-3 h-3"></i>Yes</span>'
                    : '<span class="inline-flex items-center gap-1.5 text-xs font-semibold text-rose-400 bg-rose-500/10 border border-rose-500/20 px-2.5 py-1 rounded-full"><i data-lucide="x" class="w-3 h-3"></i>No</span>';

                const btn = targetRow.querySelector('.edit-extra-btn');
                if (btn) {
                    btn.dataset.name = ex.name;
                    btn.dataset.price = ex.price;
                    btn.dataset.isAvailable = ex.is_available ? '1' : '0';
                }

                targetRow.classList.remove('row-highlight');
                void targetRow.offsetWidth;
                targetRow.classList.add('row-highlight');
            }

            closeExtraEditor();
            showToast(`Extra item "${ex.name}" updated successfully!`, 'Updated', 'success');
            if (extrasDataTable) extrasDataTable.refresh();
            setTimeout(() => lucide.createIcons(), 50);
        });
    });
}

// ==========================================
// 4. CREATE FORM HANDLERS (PRODUCTS, STAFF, EXTRAS)
// ==========================================
document.getElementById('product-form')?.addEventListener('submit', function (e) {
    e.preventDefault();
    submitForm(this, "{{ route('admin.products.store') }}", (data) => {
        const p = data.product;
        const row = document.createElement('tr');
        row.id = `product-row-${p.id}`;
        row.className = 'data-row border-b border-[#2a2731]/60 hover:bg-[#1e1c25]/40 transition row-highlight group/row';
        row.setAttribute('data-id', p.id);
        row.setAttribute('data-name', (p.name || '').toLowerCase());
        row.setAttribute('data-category', (p.category?.name || '').toLowerCase());
        row.setAttribute('data-category-id', p.category_id);
        row.setAttribute('data-price', p.price);
        row.setAttribute('data-description', p.description ?? '');
        row.setAttribute('data-image', p.image);
        row.setAttribute('data-status', p.is_available ? 'available' : 'unavailable');
        
        row.innerHTML = `
            <td class="py-3 px-4 sm:px-5"><img src="/storage/${p.image}" class="w-10 h-10 object-cover rounded-lg bg-[#0f0e13] product-image-cell"></td>
            <td class="px-4 sm:px-5 text-white font-medium whitespace-nowrap product-name-cell">${p.name}</td>
            <td class="px-4 sm:px-5 text-stone-400 whitespace-nowrap product-cat-cell">${p.category.name}</td>
            <td class="px-4 sm:px-5 text-[#b08d57] font-bold whitespace-nowrap product-price-cell">${Number(p.price).toFixed(2)} ETB</td>
            <td class="px-4 sm:px-5 whitespace-nowrap product-status-cell">${p.is_available ? '<span class="inline-flex items-center gap-1.5 text-xs font-semibold text-emerald-400 bg-emerald-500/10 border border-emerald-500/20 px-2.5 py-1 rounded-full"><i data-lucide="check" class="w-3 h-3"></i>Yes</span>' : '<span class="inline-flex items-center gap-1.5 text-xs font-semibold text-rose-400 bg-rose-500/10 border border-rose-500/20 px-2.5 py-1 rounded-full"><i data-lucide="x" class="w-3 h-3"></i>No</span>'}</td>
            <td class="px-4 sm:px-5 text-right whitespace-nowrap">
                <button type="button" 
                    class="edit-product-btn inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold text-stone-300 bg-[#1e1c25] hover:bg-[#b08d57] hover:text-[#0f0e13] border border-[#2a2731] hover:border-[#b08d57] transition-all duration-200 active:scale-95"
                    data-id="${p.id}"
                    data-name="${p.name}"
                    data-category-id="${p.category_id}"
                    data-category-name="${p.category.name}"
                    data-price="${p.price}"
                    data-description="${p.description ?? ''}"
                    data-image="${p.image}"
                    data-is-available="${p.is_available ? '1' : '0'}">
                    <i data-lucide="square-pen" class="w-3.5 h-3.5"></i>
                    <span>Edit</span>
                </button>
            </td>
        `;
        document.getElementById('products-table-body').prepend(row);
        this.reset();
        resetImagePreview();
        if (productsDataTable) productsDataTable.refresh();
        showToast(`Product "${p.name}" created!`, 'Created');
        setTimeout(() => lucide.createIcons(), 50);
    });
});

document.getElementById('staff-form')?.addEventListener('submit', function (e) {
    e.preventDefault();
    submitForm(this, "{{ route('admin.staff.store') }}", (data) => {
        const s = data.staff;
        const row = document.createElement('tr');
        row.id = `staff-row-${s.id}`;
        row.className = 'data-row border-b border-[#2a2731]/60 hover:bg-[#1e1c25]/40 transition row-highlight group/row';
        row.setAttribute('data-id', s.id);
        row.setAttribute('data-fullname', (s.fullname || '').toLowerCase());
        row.setAttribute('data-username', (s.username || '').toLowerCase());
        row.setAttribute('data-role', (s.role?.name || '').toLowerCase());
        row.setAttribute('data-role-id', s.role_id);
        row.setAttribute('data-email', s.email ?? '');
        row.setAttribute('data-phone', (s.phone || '').toLowerCase());

        const roleCap = s.role.name.charAt(0).toUpperCase() + s.role.name.slice(1);

        row.innerHTML = `
            <td class="py-3 px-4 sm:px-5 text-white font-medium whitespace-nowrap staff-name-cell">${s.fullname}</td>
            <td class="px-4 sm:px-5 text-stone-400 whitespace-nowrap staff-username-cell">${s.username}</td>
            <td class="px-4 sm:px-5 whitespace-nowrap"><span class="inline-flex items-center text-xs font-semibold text-[#b08d57] bg-[#b08d57]/10 border border-[#b08d57]/20 px-2.5 py-1 rounded-full staff-role-cell">${roleCap}</span></td>
            <td class="px-4 sm:px-5 text-stone-400 whitespace-nowrap staff-phone-cell">${s.phone ?? ''}</td>
            <td class="px-4 sm:px-5 text-right whitespace-nowrap">
                <button type="button" 
                    class="edit-staff-btn inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold text-stone-300 bg-[#1e1c25] hover:bg-[#b08d57] hover:text-[#0f0e13] border border-[#2a2731] hover:border-[#b08d57] transition-all duration-200 active:scale-95"
                    data-id="${s.id}"
                    data-fullname="${s.fullname}"
                    data-username="${s.username}"
                    data-email="${s.email ?? ''}"
                    data-phone="${s.phone ?? ''}"
                    data-role-id="${s.role_id}"
                    data-role-name="${roleCap}">
                    <i data-lucide="square-pen" class="w-3.5 h-3.5"></i>
                    <span>Edit</span>
                </button>
            </td>
        `;
        document.getElementById('staff-table-body').prepend(row);
        this.reset();
        if (staffDataTable) staffDataTable.refresh();
        showToast(`Staff account "${s.fullname}" created!`, 'Created');
        setTimeout(() => lucide.createIcons(), 50);
    });
});

document.getElementById('extra-form')?.addEventListener('submit', function (e) {
    e.preventDefault();
    submitForm(this, "{{ route('admin.extras.store') }}", (data) => {
        const ex = data.extra;
        const row = document.createElement('tr');
        row.id = `extra-row-${ex.id}`;
        row.className = 'data-row border-b border-[#2a2731]/60 hover:bg-[#1e1c25]/40 transition row-highlight group/row';
        row.setAttribute('data-id', ex.id);
        row.setAttribute('data-name', (ex.name || '').toLowerCase());
        row.setAttribute('data-price', ex.price);
        row.setAttribute('data-status', ex.is_available ? 'available' : 'unavailable');

        row.innerHTML = `
            <td class="py-3 px-4 sm:px-5 text-white font-medium whitespace-nowrap extra-name-cell">${ex.name}</td>
            <td class="px-4 sm:px-5 text-[#b08d57] font-bold whitespace-nowrap extra-price-cell">${Number(ex.price).toFixed(2)} ETB</td>
            <td class="px-4 sm:px-5 whitespace-nowrap extra-status-cell">${ex.is_available ? '<span class="inline-flex items-center gap-1.5 text-xs font-semibold text-emerald-400 bg-emerald-500/10 border border-emerald-500/20 px-2.5 py-1 rounded-full"><i data-lucide="check" class="w-3 h-3"></i>Yes</span>' : '<span class="inline-flex items-center gap-1.5 text-xs font-semibold text-rose-400 bg-rose-500/10 border border-rose-500/20 px-2.5 py-1 rounded-full"><i data-lucide="x" class="w-3 h-3"></i>No</span>'}</td>
            <td class="px-4 sm:px-5 text-right whitespace-nowrap">
                <button type="button" 
                    class="edit-extra-btn inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold text-stone-300 bg-[#1e1c25] hover:bg-[#b08d57] hover:text-[#0f0e13] border border-[#2a2731] hover:border-[#b08d57] transition-all duration-200 active:scale-95"
                    data-id="${ex.id}"
                    data-name="${ex.name}"
                    data-price="${ex.price}"
                    data-is-available="${ex.is_available ? '1' : '0'}">
                    <i data-lucide="square-pen" class="w-3.5 h-3.5"></i>
                    <span>Edit</span>
                </button>
            </td>
        `;
        document.getElementById('extras-table-body').prepend(row);
        this.reset();
        if (extrasDataTable) extrasDataTable.refresh();
        showToast(`Extra item "${ex.name}" added!`, 'Created');
        setTimeout(() => lucide.createIcons(), 50);
    });
});

// Initialize on page load
document.addEventListener('DOMContentLoaded', () => {
    initCustomSelects();
    initAllDataTables();
    initCharts();
    lucide.createIcons();
});
</script>
</body>
</html>