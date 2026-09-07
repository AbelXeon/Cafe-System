<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Dashboard | CraveDash</title>

    <!-- Laravel Vite Bundled Tailwind CSS & JS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Google Fonts & Lucide Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

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
    </style>
</head>
<body class="bg-[#0f0e13] text-stone-200 h-screen overflow-hidden selection:bg-[#b08d57] selection:text-[#0f0e13]">

<!-- Mobile Top Navigation Bar (Visible on mobile/tablet < lg) -->
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

    {{-- Sidebar & Navigation Partial --}}
    @include('admin.partials.sidebar')

    {{-- Main Content Area --}}
    <main class="flex-1 p-4 sm:p-6 lg:p-10 overflow-y-auto custom-scroll">
        @include('admin.partials.section-overview')
        @include('admin.partials.section-products')
        @include('admin.partials.section-staff')
        @include('admin.partials.section-extras')
    </main>

</div>

<script>
const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

// ---- Charts Initialization ----
let revenueChartInstance = null;
let categoryChartInstance = null;

function initCharts() {
    const chartLabels = @json($chartLabels);
    const chartRevenue = @json($chartRevenue);
    const chartOrders = @json($chartOrders);
    const categoryLabels = @json($categoryLabels);
    const categoryCounts = @json($categoryCounts);

    // 1. Revenue & Orders Trend Chart
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
                        pointHoverRadius: 6,
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
                        pointHoverRadius: 6,
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
                        labels: { color: '#a8a29e', font: { family: 'Plus Jakarta Sans', size: 11, weight: '600' } }
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
                                    label += Number(context.parsed.y).toLocaleString() + ' ETB';
                                } else {
                                    label += context.parsed.y;
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
                        grid: { color: '#1e1c25' },
                        ticks: {
                            color: '#78716c',
                            font: { size: 11 },
                            callback: (v) => v.toLocaleString() + ' ETB'
                        }
                    },
                    y1: {
                        position: 'right',
                        grid: { drawOnChartArea: false },
                        ticks: { color: '#a855f7', font: { size: 11 }, precision: 0 }
                    }
                }
            }
        });
    }

    // 2. Category Distribution Donut Chart
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

// ---- Image Picker & Preview Handling ----
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
    imageInput.value = '';
    previewImg.src = '';
    previewName.textContent = '';
    previewContainer.classList.add('hidden');
    placeholder.classList.remove('hidden');
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

// ---- Customized Dropdowns Management ----
function initCustomSelects() {
    document.querySelectorAll('.custom-select-wrapper').forEach(wrapper => {
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

// ---- Initialize DataTables ----
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
    errorEl.textContent = '';

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
        errorEl.textContent = 'Please fill out all required fields.';
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
            errorEl.textContent = firstError;
            return;
        }

        onSuccess(data);
        formEl.reset();
        resetImagePreview();
    } catch (err) {
        errorEl.textContent = 'Network error. Try again.';
    }
}

// ---- Product Form Submit ----
document.getElementById('product-form').addEventListener('submit', function (e) {
    e.preventDefault();
    submitForm(this, "{{ route('admin.products.store') }}", (data) => {
        const p = data.product;
        const row = document.createElement('tr');
        row.className = 'data-row border-b border-[#2a2731]/60 hover:bg-[#1e1c25]/40 transition';
        row.setAttribute('data-name', (p.name || '').toLowerCase());
        row.setAttribute('data-category', (p.category?.name || '').toLowerCase());
        row.setAttribute('data-price', p.price);
        row.setAttribute('data-status', p.is_available ? 'available' : 'unavailable');
        
        row.innerHTML = `
            <td class="py-3 px-4 sm:px-5"><img src="/storage/${p.image}" class="w-10 h-10 object-cover rounded-lg bg-[#0f0e13]"></td>
            <td class="px-4 sm:px-5 text-white font-medium whitespace-nowrap">${p.name}</td>
            <td class="px-4 sm:px-5 text-stone-400 whitespace-nowrap">${p.category.name}</td>
            <td class="px-4 sm:px-5 text-[#b08d57] font-bold whitespace-nowrap">${Number(p.price).toFixed(2)} ETB</td>
            <td class="px-4 sm:px-5 whitespace-nowrap">${p.is_available ? '<span class="inline-flex items-center gap-1.5 text-xs font-semibold text-emerald-400 bg-emerald-500/10 border border-emerald-500/20 px-2.5 py-1 rounded-full"><i data-lucide="check" class="w-3 h-3"></i>Yes</span>' : '<span class="inline-flex items-center gap-1.5 text-xs font-semibold text-rose-400 bg-rose-500/10 border border-rose-500/20 px-2.5 py-1 rounded-full"><i data-lucide="x" class="w-3 h-3"></i>No</span>'}</td>
        `;
        document.getElementById('products-table-body').prepend(row);
        if (productsDataTable) productsDataTable.refresh();
        setTimeout(() => lucide.createIcons(), 50);
    });
});

// ---- Staff Form Submit ----
document.getElementById('staff-form').addEventListener('submit', function (e) {
    e.preventDefault();
    submitForm(this, "{{ route('admin.staff.store') }}", (data) => {
        const s = data.staff;
        const row = document.createElement('tr');
        row.className = 'data-row border-b border-[#2a2731]/60 hover:bg-[#1e1c25]/40 transition';
        row.setAttribute('data-fullname', (s.fullname || '').toLowerCase());
        row.setAttribute('data-username', (s.username || '').toLowerCase());
        row.setAttribute('data-role', (s.role?.name || '').toLowerCase());
        row.setAttribute('data-phone', (s.phone || '').toLowerCase());

        row.innerHTML = `
            <td class="py-3 px-4 sm:px-5 text-white font-medium whitespace-nowrap">${s.fullname}</td>
            <td class="px-4 sm:px-5 text-stone-400 whitespace-nowrap">${s.username}</td>
            <td class="px-4 sm:px-5 whitespace-nowrap"><span class="inline-flex items-center text-xs font-semibold text-[#b08d57] bg-[#b08d57]/10 border border-[#b08d57]/20 px-2.5 py-1 rounded-full">${s.role.name.charAt(0).toUpperCase() + s.role.name.slice(1)}</span></td>
            <td class="px-4 sm:px-5 text-stone-400 whitespace-nowrap">${s.phone ?? ''}</td>
        `;
        document.getElementById('staff-table-body').prepend(row);
        if (staffDataTable) staffDataTable.refresh();
        setTimeout(() => lucide.createIcons(), 50);
    });
});

// ---- Extra Form Submit ----
document.getElementById('extra-form').addEventListener('submit', function (e) {
    e.preventDefault();
    submitForm(this, "{{ route('admin.extras.store') }}", (data) => {
        const ex = data.extra;
        const row = document.createElement('tr');
        row.className = 'data-row border-b border-[#2a2731]/60 hover:bg-[#1e1c25]/40 transition';
        row.setAttribute('data-name', (ex.name || '').toLowerCase());
        row.setAttribute('data-price', ex.price);
        row.setAttribute('data-status', ex.is_available ? 'available' : 'unavailable');

        row.innerHTML = `
            <td class="py-3 px-4 sm:px-5 text-white font-medium whitespace-nowrap">${ex.name}</td>
            <td class="px-4 sm:px-5 text-[#b08d57] font-bold whitespace-nowrap">${Number(ex.price).toFixed(2)} ETB</td>
            <td class="px-4 sm:px-5 whitespace-nowrap">${ex.is_available ? '<span class="inline-flex items-center gap-1.5 text-xs font-semibold text-emerald-400 bg-emerald-500/10 border border-emerald-500/20 px-2.5 py-1 rounded-full"><i data-lucide="check" class="w-3 h-3"></i>Yes</span>' : '<span class="inline-flex items-center gap-1.5 text-xs font-semibold text-rose-400 bg-rose-500/10 border border-rose-500/20 px-2.5 py-1 rounded-full"><i data-lucide="x" class="w-3 h-3"></i>No</span>'}</td>
        `;
        document.getElementById('extras-table-body').prepend(row);
        if (extrasDataTable) extrasDataTable.refresh();
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