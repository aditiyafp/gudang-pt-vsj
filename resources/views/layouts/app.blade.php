<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Sistem Gudang PT VSJ') }} - PT GUDANG KEBERSIHAN VSJ</title>

    <!-- Google Fonts Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- FontAwesome 6 Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body { font-family: 'Inter', sans-serif; }
        @media print {
            .no-print { display: none !important; }
            body { background: white !important; color: black !important; }
            .print-container { width: 100% !important; margin: 0 !important; padding: 0 !important; }
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased min-h-screen flex flex-col" x-data="{ sidebarOpen: false }">

    <!-- Top Navigation Header -->
    <header class="bg-indigo-900 text-white shadow-md z-30 sticky top-0 no-print">
        <div class="flex items-center justify-between px-4 py-3">
            <div class="flex items-center space-x-3">
                <button @click="sidebarOpen = !sidebarOpen" class="md:hidden text-white hover:text-indigo-200 focus:outline-none">
                    <i class="fa-solid fa-bars text-xl"></i>
                </button>
                <div class="flex items-center space-x-2">
                    <div class="bg-indigo-600 text-white p-2 rounded-lg font-black text-xl tracking-wider shadow">VSJ</div>
                    <div>
                        <h1 class="text-base font-bold tracking-wide leading-none">PT GUDANG KEBERSIHAN VSJ</h1>
                        <p class="text-xs text-indigo-200 mt-0.5">Sistem Informasi Manajemen Gudang</p>
                    </div>
                </div>
            </div>

            <!-- User Menu -->
            <div class="flex items-center space-x-4">
                <div class="hidden sm:flex items-center text-xs text-indigo-200 bg-indigo-950 px-3 py-1.5 rounded-full border border-indigo-700">
                    <i class="fa-regular fa-clock mr-1.5 text-indigo-400"></i>
                    <span>{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</span>
                </div>
                <div class="relative" x-data="{ dropdownOpen: false }">
                    <button @click="dropdownOpen = !dropdownOpen" class="flex items-center space-x-2 text-sm focus:outline-none bg-indigo-800 hover:bg-indigo-700 px-3 py-1.5 rounded-lg border border-indigo-600 transition">
                        <div class="w-7 h-7 rounded-full bg-indigo-500 flex items-center justify-center text-white font-bold text-xs uppercase">
                            {{ substr(Auth::user()->name ?? 'A', 0, 2) }}
                        </div>
                        <span class="font-medium text-white hidden md:inline">{{ Auth::user()->name ?? 'Admin' }}</span>
                        <i class="fa-solid fa-chevron-down text-xs text-indigo-300"></i>
                    </button>

                    <!-- Profile Dropdown -->
                    <div x-show="dropdownOpen" @click.away="dropdownOpen = false" x-transition class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-xl py-1 text-slate-700 border border-slate-100 z-50">
                        <div class="px-4 py-2 border-b border-slate-100">
                            <p class="text-xs text-slate-500 font-medium">Logged in as</p>
                            <p class="text-sm font-semibold truncate text-slate-800">{{ Auth::user()->name ?? 'User' }}</p>
                            <span class="inline-block mt-1 text-[10px] px-2 py-0.5 bg-indigo-100 text-indigo-700 rounded-full font-bold uppercase">{{ Auth::user()->role ?? 'Admin' }}</span>
                        </div>
                        <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm hover:bg-slate-50 flex items-center">
                            <i class="fa-regular fa-user mr-2 text-slate-400"></i> Edit Profile
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 flex items-center">
                                <i class="fa-solid fa-right-from-bracket mr-2"></i> Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="flex flex-1 relative">

        <!-- Sidebar Navigation (Matching exact Image 1 & 2 Structure) -->
        <aside class="w-64 bg-white border-r border-slate-200 min-h-screen flex flex-col transition-all duration-300 z-20 no-print"
               :class="{ 'fixed inset-y-0 left-0 translate-x-0': sidebarOpen, 'hidden md:flex': !sidebarOpen }">
            
            <div class="p-4 border-b border-slate-100 bg-slate-50">
                <p class="text-xs uppercase font-bold text-slate-400 tracking-wider">SISTEM GUDANG PT</p>
                <p class="text-sm font-bold text-slate-700">PT Kebersihan VSJ</p>
            </div>

            <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">

                <!-- 1. Dashboard -->
                <a href="{{ route('dashboard') }}" class="flex items-center px-3 py-2.5 rounded-lg text-sm font-semibold transition {{ request()->routeIs('dashboard') ? 'bg-indigo-50 text-indigo-700 font-bold border-l-4 border-indigo-600' : 'text-slate-600 hover:bg-slate-100' }}">
                    <i class="fa-solid fa-gauge-high w-6 text-slate-400 {{ request()->routeIs('dashboard') ? 'text-indigo-600' : '' }}"></i>
                    <span>Dashboard</span>
                </a>

                <!-- 2. Barang (Dropdown) -->
                <div x-data="{ open: {{ request()->routeIs('barang.*', 'kategori.*', 'satuan.*') ? 'true' : 'false' }} }">
                    <button @click="open = !open" class="w-full flex items-center justify-between px-3 py-2.5 rounded-lg text-sm font-semibold text-slate-600 hover:bg-slate-100 transition">
                        <div class="flex items-center">
                            <i class="fa-solid fa-boxes-stacked w-6 text-slate-400"></i>
                            <span>Barang</span>
                        </div>
                        <i class="fa-solid text-xs text-slate-400" :class="open ? 'fa-chevron-down' : 'fa-chevron-right'"></i>
                    </button>
                    <div x-show="open" x-transition class="pl-8 space-y-1 mt-1">
                        <a href="{{ route('barang.index') }}" class="block px-3 py-1.5 rounded-md text-sm {{ request()->routeIs('barang.*') ? 'text-indigo-600 font-bold bg-indigo-50' : 'text-slate-500 hover:text-slate-900' }}">
                            <i class="fa-solid fa-circle text-[6px] mr-2"></i> Data Barang
                        </a>
                        <a href="{{ route('kategori.index') }}" class="block px-3 py-1.5 rounded-md text-sm {{ request()->routeIs('kategori.*') ? 'text-indigo-600 font-bold bg-indigo-50' : 'text-slate-500 hover:text-slate-900' }}">
                            <i class="fa-solid fa-circle text-[6px] mr-2"></i> Kategori
                        </a>
                        <a href="{{ route('satuan.index') }}" class="block px-3 py-1.5 rounded-md text-sm {{ request()->routeIs('satuan.*') ? 'text-indigo-600 font-bold bg-indigo-50' : 'text-slate-500 hover:text-slate-900' }}">
                            <i class="fa-solid fa-circle text-[6px] mr-2"></i> Satuan
                        </a>
                    </div>
                </div>

                <!-- 3. Transaksi (Dropdown) -->
                <div x-data="{ open: {{ request()->routeIs('transaksi.*') ? 'true' : 'false' }} }">
                    <button @click="open = !open" class="w-full flex items-center justify-between px-3 py-2.5 rounded-lg text-sm font-semibold text-slate-600 hover:bg-slate-100 transition">
                        <div class="flex items-center">
                            <i class="fa-solid fa-arrow-right-arrow-left w-6 text-slate-400"></i>
                            <span>Transaksi</span>
                        </div>
                        <i class="fa-solid text-xs text-slate-400" :class="open ? 'fa-chevron-down' : 'fa-chevron-right'"></i>
                    </button>
                    <div x-show="open" x-transition class="pl-8 space-y-1 mt-1">
                        <a href="{{ route('transaksi.masuk.index') }}" class="block px-3 py-1.5 rounded-md text-sm {{ request()->routeIs('transaksi.masuk.*') ? 'text-emerald-600 font-bold bg-emerald-50' : 'text-slate-500 hover:text-slate-900' }}">
                            <i class="fa-solid fa-circle text-[6px] mr-2"></i> Barang Masuk
                        </a>
                        <a href="{{ route('transaksi.keluar.index') }}" class="block px-3 py-1.5 rounded-md text-sm {{ request()->routeIs('transaksi.keluar.*') ? 'text-blue-600 font-bold bg-blue-50' : 'text-slate-500 hover:text-slate-900' }}">
                            <i class="fa-solid fa-circle text-[6px] mr-2"></i> Barang Keluar (Surat Jalan)
                        </a>
                    </div>
                </div>

                <!-- 4. Supplier -->
                <a href="{{ route('supplier.index') }}" class="flex items-center px-3 py-2.5 rounded-lg text-sm font-semibold transition {{ request()->routeIs('supplier.*') ? 'bg-indigo-50 text-indigo-700 font-bold border-l-4 border-indigo-600' : 'text-slate-600 hover:bg-slate-100' }}">
                    <i class="fa-solid fa-truck-field w-6 text-slate-400 {{ request()->routeIs('supplier.*') ? 'text-indigo-600' : '' }}"></i>
                    <span>Supplier</span>
                </a>

                <!-- 5. Stok (Dropdown) -->
                <div x-data="{ open: {{ request()->routeIs('stok.*') ? 'true' : 'false' }} }">
                    <button @click="open = !open" class="w-full flex items-center justify-between px-3 py-2.5 rounded-lg text-sm font-semibold text-slate-600 hover:bg-slate-100 transition">
                        <div class="flex items-center">
                            <i class="fa-solid fa-warehouse w-6 text-slate-400"></i>
                            <span>Stok</span>
                        </div>
                        <i class="fa-solid text-xs text-slate-400" :class="open ? 'fa-chevron-down' : 'fa-chevron-right'"></i>
                    </button>
                    <div x-show="open" x-transition class="pl-8 space-y-1 mt-1">
                        <a href="{{ route('stok.tersedia') }}" class="block px-3 py-1.5 rounded-md text-sm {{ request()->routeIs('stok.tersedia') ? 'text-indigo-600 font-bold bg-indigo-50' : 'text-slate-500 hover:text-slate-900' }}">
                            <i class="fa-solid fa-circle text-[6px] mr-2"></i> Stok Tersedia
                        </a>
                        <a href="{{ route('stok.opname') }}" class="block px-3 py-1.5 rounded-md text-sm {{ request()->routeIs('stok.opname*') ? 'text-indigo-600 font-bold bg-indigo-50' : 'text-slate-500 hover:text-slate-900' }}">
                            <i class="fa-solid fa-circle text-[6px] mr-2"></i> Stok Opname
                        </a>
                    </div>
                </div>

                <!-- 6. Laporan (Dropdown) -->
                <div x-data="{ open: {{ request()->routeIs('laporan.*') ? 'true' : 'false' }} }">
                    <button @click="open = !open" class="w-full flex items-center justify-between px-3 py-2.5 rounded-lg text-sm font-semibold text-slate-600 hover:bg-slate-100 transition">
                        <div class="flex items-center">
                            <i class="fa-solid fa-file-invoice-dollar w-6 text-slate-400"></i>
                            <span>Laporan</span>
                        </div>
                        <i class="fa-solid text-xs text-slate-400" :class="open ? 'fa-chevron-down' : 'fa-chevron-right'"></i>
                    </button>
                    <div x-show="open" x-transition class="pl-8 space-y-1 mt-1">
                        <a href="{{ route('laporan.transaksi') }}" class="block px-3 py-1.5 rounded-md text-sm {{ request()->routeIs('laporan.transaksi') ? 'text-indigo-600 font-bold bg-indigo-50' : 'text-slate-500 hover:text-slate-900' }}">
                            <i class="fa-solid fa-circle text-[6px] mr-2"></i> Laporan Transaksi
                        </a>
                        <a href="{{ route('laporan.stok') }}" class="block px-3 py-1.5 rounded-md text-sm {{ request()->routeIs('laporan.stok') ? 'text-indigo-600 font-bold bg-indigo-50' : 'text-slate-500 hover:text-slate-900' }}">
                            <i class="fa-solid fa-circle text-[6px] mr-2"></i> Laporan Stok & Perbulan
                        </a>
                    </div>
                </div>

                <!-- 7. Pengguna -->
                <a href="{{ route('pengguna.index') }}" class="flex items-center px-3 py-2.5 rounded-lg text-sm font-semibold transition {{ request()->routeIs('pengguna.*') ? 'bg-indigo-50 text-indigo-700 font-bold border-l-4 border-indigo-600' : 'text-slate-600 hover:bg-slate-100' }}">
                    <i class="fa-solid fa-users-gear w-6 text-slate-400 {{ request()->routeIs('pengguna.*') ? 'text-indigo-600' : '' }}"></i>
                    <span>Pengguna</span>
                </a>

                <!-- 8. Backup Database -->
                <a href="{{ route('backup.index') }}" class="flex items-center px-3 py-2.5 rounded-lg text-sm font-semibold transition {{ request()->routeIs('backup.*') ? 'bg-indigo-50 text-indigo-700 font-bold border-l-4 border-indigo-600' : 'text-slate-600 hover:bg-slate-100' }}">
                    <i class="fa-solid fa-database w-6 text-slate-400 {{ request()->routeIs('backup.*') ? 'text-indigo-600' : '' }}"></i>
                    <span>Backup Database</span>
                </a>

                <!-- 8. Profile -->
                <a href="{{ route('profile.edit') }}" class="flex items-center px-3 py-2.5 rounded-lg text-sm font-semibold transition {{ request()->routeIs('profile.*') ? 'bg-indigo-50 text-indigo-700 font-bold border-l-4 border-indigo-600' : 'text-slate-600 hover:bg-slate-100' }}">
                    <i class="fa-solid fa-id-card w-6 text-slate-400 {{ request()->routeIs('profile.*') ? 'text-indigo-600' : '' }}"></i>
                    <span>Profile</span>
                </a>

                <!-- 9. Logout -->
                <form method="POST" action="{{ route('logout') }}" class="pt-4 mt-4 border-t border-slate-100">
                    @csrf
                    <button type="submit" class="w-full flex items-center px-3 py-2 rounded-lg text-sm font-semibold text-red-600 hover:bg-red-50 transition">
                        <i class="fa-solid fa-power-off w-6 text-red-500"></i>
                        <span>Logout</span>
                    </button>
                </form>

            </nav>
        </aside>

        <!-- Main Content Area -->
        <main class="flex-1 p-4 md:p-6 lg:p-8 max-w-7xl mx-auto w-full">

            <!-- Flash Alerts -->
            @if(session('success'))
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: "{{ session('success') }}",
                            timer: 3000,
                            showConfirmButton: false,
                            toast: true,
                            position: 'top-end'
                        });
                    });
                </script>
            @endif

            @if(session('error'))
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Perhatian!',
                            text: "{{ session('error') }}",
                            confirmButtonColor: '#4f46e5'
                        });
                    });
                </script>
            @endif

            {{ $slot }}
        </main>
    </div>

    <footer class="bg-white border-t border-slate-200 py-3 px-6 text-center text-xs text-slate-400 no-print">
        <p>&copy; {{ date('Y') }} PT GUDANG KEBERSIHAN VSJ. All rights reserved.</p>
    </footer>

</body>
</html>
