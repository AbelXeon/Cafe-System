<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-950 text-slate-200">
<div class="flex min-h-screen">

    {{-- Sidebar --}}
    <aside class="w-60 bg-slate-900 border-r border-slate-800 flex flex-col shrink-0">
        <div class="px-6 py-5 border-b border-slate-800">
            <h1 class="text-white font-bold text-lg">Admin</h1>
        </div>
        <nav class="flex-1 px-3 py-4 space-y-1">
            <button data-target="overview" class="nav-link w-full text-left px-3 py-2 rounded-lg text-sm font-medium">Overview</button>
            <button data-target="products" class="nav-link w-full text-left px-3 py-2 rounded-lg text-sm font-medium">Products</button>
            <button data-target="staff" class="nav-link w-full text-left px-3 py-2 rounded-lg text-sm font-medium">Staff</button>
            <button data-target="extras" class="nav-link w-full text-left px-3 py-2 rounded-lg text-sm font-medium">Extras</button>
        </nav>
        <form method="POST" action="{{ route('logout') }}" class="p-3 border-t border-slate-800">
            @csrf
            <button class="w-full text-left px-3 py-2 rounded-lg text-sm text-red-400 hover:bg-red-500/10">Logout</button>
        </form>
    </aside>

    {{-- Main --}}
    <main class="flex-1 p-8 overflow-y-auto">

        {{-- OVERVIEW --}}
        <section id="section-overview" class="page-section">
            <h2 class="text-xl font-bold text-white mb-6">Overview</h2>
            <div class="grid grid-cols-3 gap-4">
                <div class="bg-slate-900 border border-slate-800 rounded-xl p-5">
                    <p class="text-slate-400 text-sm">Products</p>
                    <p class="text-2xl font-bold text-white">{{ $products->count() }}</p>
                </div>
                <div class="bg-slate-900 border border-slate-800 rounded-xl p-5">
                    <p class="text-slate-400 text-sm">Staff / Delivery</p>
                    <p class="text-2xl font-bold text-white">{{ $staff->count() }}</p>
                </div>
                <div class="bg-slate-900 border border-slate-800 rounded-xl p-5">
                    <p class="text-slate-400 text-sm">Extras</p>
                    <p class="text-2xl font-bold text-white">{{ $extras->count() }}</p>
                </div>
            </div>
        </section>

        {{-- PRODUCTS --}}
        <section id="section-products" class="page-section hidden">
            <h2 class="text-xl font-bold text-white mb-6">Products</h2>

            <form id="product-form" class="bg-slate-900 border border-slate-800 rounded-xl p-6 grid grid-cols-2 gap-4 mb-8">
                <div>
                    <label class="block text-sm text-slate-300 mb-1">Category</label>
                    <select name="category_id" required class="w-full bg-slate-800 border border-slate-700 rounded-lg px-3 py-2">
                        <option value="">Select category</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm text-slate-300 mb-1">Name</label>
                    <input type="text" name="name" required class="w-full bg-slate-800 border border-slate-700 rounded-lg px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm text-slate-300 mb-1">Price</label>
                    <input type="number" step="0.01" name="price" required class="w-full bg-slate-800 border border-slate-700 rounded-lg px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm text-slate-300 mb-1">Image</label>
                    <input type="file" name="image" accept="image/*" required class="w-full bg-slate-800 border border-slate-700 rounded-lg px-3 py-2">
                </div>
                <div class="col-span-2">
                    <label class="block text-sm text-slate-300 mb-1">Description</label>
                    <textarea name="description" rows="2" class="w-full bg-slate-800 border border-slate-700 rounded-lg px-3 py-2"></textarea>
                </div>
                <div class="col-span-2 flex items-center justify-between">
                    <p class="text-red-400 text-sm form-error" data-form="product"></p>
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-500 text-white font-medium rounded-lg px-5 py-2">Create Product</button>
                </div>
            </form>

            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-slate-400 border-b border-slate-800">
                        <th class="py-2">Image</th><th>Name</th><th>Category</th><th>Price</th><th>Available</th>
                    </tr>
                </thead>
                <tbody id="products-table-body">
                    @foreach ($products as $p)
                        <tr class="border-b border-slate-800/50">
                            <td class="py-2"><img src="{{ asset('storage/' . $p->image) }}" class="w-10 h-10 object-cover rounded"></td>
                            <td>{{ $p->name }}</td>
                            <td>{{ $p->category->name }}</td>
                            <td>{{ number_format($p->price, 2) }}</td>
                            <td>{{ $p->is_available ? 'Yes' : 'No' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </section>

        {{-- STAFF --}}
        <section id="section-staff" class="page-section hidden">
            <h2 class="text-xl font-bold text-white mb-6">Staff & Delivery</h2>

            <form id="staff-form" class="bg-slate-900 border border-slate-800 rounded-xl p-6 grid grid-cols-2 gap-4 mb-8">
                <div>
                    <label class="block text-sm text-slate-300 mb-1">Role</label>
                    <select name="role_id" required class="w-full bg-slate-800 border border-slate-700 rounded-lg px-3 py-2">
                        <option value="">Select role</option>
                        @foreach ($roles as $r)
                            <option value="{{ $r->id }}">{{ ucfirst($r->name) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm text-slate-300 mb-1">Full name</label>
                    <input type="text" name="fullname" required class="w-full bg-slate-800 border border-slate-700 rounded-lg px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm text-slate-300 mb-1">Username</label>
                    <input type="text" name="username" required class="w-full bg-slate-800 border border-slate-700 rounded-lg px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm text-slate-300 mb-1">Email</label>
                    <input type="email" name="email" class="w-full bg-slate-800 border border-slate-700 rounded-lg px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm text-slate-300 mb-1">Phone</label>
                    <input type="text" name="phone" class="w-full bg-slate-800 border border-slate-700 rounded-lg px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm text-slate-300 mb-1">Password</label>
                    <input type="password" name="password" required class="w-full bg-slate-800 border border-slate-700 rounded-lg px-3 py-2">
                </div>
                <div class="col-span-2 flex items-center justify-between">
                    <p class="text-red-400 text-sm form-error" data-form="staff"></p>
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-500 text-white font-medium rounded-lg px-5 py-2">Create Staff</button>
                </div>
            </form>

            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-slate-400 border-b border-slate-800">
                        <th class="py-2">Name</th><th>Username</th><th>Role</th><th>Phone</th>
                    </tr>
                </thead>
                <tbody id="staff-table-body">
                    @foreach ($staff as $s)
                        <tr class="border-b border-slate-800/50">
                            <td class="py-2">{{ $s->fullname }}</td>
                            <td>{{ $s->username }}</td>
                            <td>{{ ucfirst($s->role->name) }}</td>
                            <td>{{ $s->phone }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </section>

        {{-- EXTRAS --}}
        <section id="section-extras" class="page-section hidden">
            <h2 class="text-xl font-bold text-white mb-6">Extras</h2>

            <form id="extra-form" class="bg-slate-900 border border-slate-800 rounded-xl p-6 grid grid-cols-2 gap-4 mb-8">
                <div>
                    <label class="block text-sm text-slate-300 mb-1">Name</label>
                    <input type="text" name="name" required class="w-full bg-slate-800 border border-slate-700 rounded-lg px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm text-slate-300 mb-1">Price</label>
                    <input type="number" step="0.01" name="price" required class="w-full bg-slate-800 border border-slate-700 rounded-lg px-3 py-2">
                </div>
                <div class="col-span-2 flex items-center justify-between">
                    <p class="text-red-400 text-sm form-error" data-form="extra"></p>
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-500 text-white font-medium rounded-lg px-5 py-2">Create Extra</button>
                </div>
            </form>

            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-slate-400 border-b border-slate-800">
                        <th class="py-2">Name</th><th>Price</th><th>Available</th>
                    </tr>
                </thead>
                <tbody id="extras-table-body">
                    @foreach ($extras as $e)
                        <tr class="border-b border-slate-800/50">
                            <td class="py-2">{{ $e->name }}</td>
                            <td>{{ number_format($e->price, 2) }}</td>
                            <td>{{ $e->is_available ? 'Yes' : 'No' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </section>

    </main>
</div>

<style>
    .nav-link { color: #94a3b8; }
    .nav-link.active { background: #4f46e5; color: #fff; }
</style>

<script>
const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

// ---- sidebar switching, no navigation ----
const navLinks = document.querySelectorAll('.nav-link');
const sections = document.querySelectorAll('.page-section');

function showSection(target) {
    sections.forEach(s => s.classList.add('hidden'));
    document.getElementById('section-' + target).classList.remove('hidden');
    navLinks.forEach(l => l.classList.remove('active'));
    document.querySelector(`.nav-link[data-target="${target}"]`).classList.add('active');
}

navLinks.forEach(link => {
    link.addEventListener('click', () => showSection(link.dataset.target));
});
showSection('overview');

// ---- generic AJAX submit helper ----
async function submitForm(formEl, url, onSuccess, isMultipart = false) {
    const formData = new FormData(formEl);
    const errorEl = formEl.querySelector('.form-error');
    errorEl.textContent = '';

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
    } catch (err) {
        errorEl.textContent = 'Network error. Try again.';
    }
}

// ---- product form ----
document.getElementById('product-form').addEventListener('submit', function (e) {
    e.preventDefault();
    submitForm(this, "{{ route('admin.products.store') }}", (data) => {
        const p = data.product;
        const row = document.createElement('tr');
        row.className = 'border-b border-slate-800/50';
        row.innerHTML = `
            <td class="py-2"><img src="/storage/${p.image}" class="w-10 h-10 object-cover rounded"></td>
            <td>${p.name}</td>
            <td>${p.category.name}</td>
            <td>${Number(p.price).toFixed(2)}</td>
            <td>${p.is_available ? 'Yes' : 'No'}</td>
        `;
        document.getElementById('products-table-body').prepend(row);
    });
});

// ---- staff form ----
document.getElementById('staff-form').addEventListener('submit', function (e) {
    e.preventDefault();
    submitForm(this, "{{ route('admin.staff.store') }}", (data) => {
        const s = data.staff;
        const row = document.createElement('tr');
        row.className = 'border-b border-slate-800/50';
        row.innerHTML = `
            <td class="py-2">${s.fullname}</td>
            <td>${s.username}</td>
            <td>${s.role.name.charAt(0).toUpperCase() + s.role.name.slice(1)}</td>
            <td>${s.phone ?? ''}</td>
        `;
        document.getElementById('staff-table-body').prepend(row);
    });
});

// ---- extra form ----
document.getElementById('extra-form').addEventListener('submit', function (e) {
    e.preventDefault();
    submitForm(this, "{{ route('admin.extras.store') }}", (data) => {
        const ex = data.extra;
        const row = document.createElement('tr');
        row.className = 'border-b border-slate-800/50';
        row.innerHTML = `
            <td class="py-2">${ex.name}</td>
            <td>${Number(ex.price).toFixed(2)}</td>
            <td>${ex.is_available ? 'Yes' : 'No'}</td>
        `;
        document.getElementById('extras-table-body').prepend(row);
    });
});
</script>
</body>
</html>