<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>FannRental - @yield('title', 'Sistem Rental Motor')</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700,800&display=swap" rel="stylesheet" />
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <!-- Tailwind CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full font-sans antialiased bg-gray-50">
    <div class="flex h-full" x-data="{ sidebarOpen: false }">
        <!-- Mobile sidebar overlay -->
        <div x-show="sidebarOpen" 
             @click="sidebarOpen = false"
             x-cloak
             class="fixed inset-0 z-40 bg-black/50 lg:hidden"
             x-transition:enter="transition-opacity ease-linear duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">
        </div>

        <!-- Sidebar -->
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
             class="fixed inset-y-0 left-0 z-50 w-72 transform bg-gradient-to-br from-blue-600 to-blue-700 shadow-xl transition-transform duration-200 ease-in-out lg:static lg:translate-x-0 lg:shadow-none">
            
            <div class="flex h-full flex-col">
                <!-- Logo -->
                <div class="flex h-20 items-center justify-between px-6 border-b border-white/10">
                    <div>
                        <h1 class="text-2xl font-bold text-white">FannRental</h1>
                        <p class="text-xs text-blue-100">Rental Motor Terpercaya</p>
                    </div>
                    <button @click="sidebarOpen = false" class="lg:hidden text-white hover:text-gray-200 transition">
                        <i class="bi bi-x-lg text-xl"></i>
                    </button>
                </div>

                <!-- Navigation -->
                <nav class="flex-1 space-y-1 px-4 py-6 overflow-y-auto">
                    @php
                        $role = Auth::user()->role;
                        $currentRoute = Route::currentRouteName();
                    @endphp

                    @if($role === 'admin')
                        <div class="px-3 mb-3">
                            <p class="text-xs font-semibold text-blue-200 uppercase tracking-wider">Menu Admin</p>
                        </div>
                        <a href="{{ route('admin.dashboard') }}" 
                           class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition {{ str_starts_with($currentRoute, 'admin.dashboard') ? 'bg-white/20 text-white' : 'text-blue-100 hover:bg-white/10 hover:text-white' }}">
                            <i class="bi bi-speedometer2 mr-3 text-base"></i>
                            Dashboard
                        </a>
                        <a href="{{ route('admin.users') }}" 
                           class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition {{ str_starts_with($currentRoute, 'admin.users') ? 'bg-white/20 text-white' : 'text-blue-100 hover:bg-white/10 hover:text-white' }}">
                            <i class="bi bi-people mr-3 text-base"></i>
                            Manajemen Pengguna
                        </a>
                        <a href="{{ route('admin.motors') }}" 
                           class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition {{ str_starts_with($currentRoute, 'admin.motors') ? 'bg-white/20 text-white' : 'text-blue-100 hover:bg-white/10 hover:text-white' }}">
                            <i class="bi bi-motorcycle mr-3 text-base"></i>
                            Manajemen Motor
                        </a>
                        <a href="{{ route('admin.bookings') }}" 
                           class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition {{ str_starts_with($currentRoute, 'admin.bookings') ? 'bg-white/20 text-white' : 'text-blue-100 hover:bg-white/10 hover:text-white' }}">
                            <i class="bi bi-calendar-check mr-3 text-base"></i>
                            Manajemen Pemesanan
                        </a>
                        <a href="{{ route('admin.payments') }}" 
                           class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition {{ str_starts_with($currentRoute, 'admin.payments') ? 'bg-white/20 text-white' : 'text-blue-100 hover:bg-white/10 hover:text-white' }}">
                            <i class="bi bi-credit-card mr-3 text-base"></i>
                            Manajemen Pembayaran
                        </a>
                        <a href="{{ route('admin.financial-report') }}" 
                           class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition {{ str_starts_with($currentRoute, 'admin.financial') ? 'bg-white/20 text-white' : 'text-blue-100 hover:bg-white/10 hover:text-white' }}">
                            <i class="bi bi-file-earmark-bar-graph mr-3 text-base"></i>
                            Laporan Keuangan
                        </a>
                        <a href="{{ route('admin.reports') }}" 
                           class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition {{ str_starts_with($currentRoute, 'admin.reports') ? 'bg-white/20 text-white' : 'text-blue-100 hover:bg-white/10 hover:text-white' }}">
                            <i class="bi bi-file-earmark-text mr-3 text-base"></i>
                            Laporan
                        </a>
                    @elseif($role === 'pemilik')
                        <div class="px-3 mb-3">
                            <p class="text-xs font-semibold text-blue-200 uppercase tracking-wider">Menu Pemilik</p>
                        </div>
                        <a href="{{ route('pemilik.dashboard') }}" 
                           class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition {{ str_starts_with($currentRoute, 'pemilik.dashboard') ? 'bg-white/20 text-white' : 'text-blue-100 hover:bg-white/10 hover:text-white' }}">
                            <i class="bi bi-speedometer2 mr-3 text-base"></i>
                            Dashboard
                        </a>
                        <a href="{{ route('pemilik.motors') }}" 
                           class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition {{ str_starts_with($currentRoute, 'pemilik.motors') ? 'bg-white/20 text-white' : 'text-blue-100 hover:bg-white/10 hover:text-white' }}">
                            <i class="bi bi-motorcycle mr-3 text-base"></i>
                            Motor Saya
                        </a>
                        <a href="{{ route('pemilik.bookings') }}" 
                           class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition {{ str_starts_with($currentRoute, 'pemilik.bookings') ? 'bg-white/20 text-white' : 'text-blue-100 hover:bg-white/10 hover:text-white' }}">
                            <i class="bi bi-calendar-check mr-3 text-base"></i>
                            Pemesanan
                        </a>
                        <a href="{{ route('pemilik.revenue-report') }}" 
                           class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition {{ str_starts_with($currentRoute, 'pemilik.revenue') ? 'bg-white/20 text-white' : 'text-blue-100 hover:bg-white/10 hover:text-white' }}">
                            <i class="bi bi-file-earmark-bar-graph mr-3 text-base"></i>
                            Laporan Pendapatan
                        </a>
                    @else
                        <div class="px-3 mb-3">
                            <p class="text-xs font-semibold text-blue-200 uppercase tracking-wider">Menu Penyewa</p>
                        </div>
                        <a href="{{ route('penyewa.dashboard') }}" 
                           class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition {{ str_starts_with($currentRoute, 'penyewa.dashboard') ? 'bg-white/20 text-white' : 'text-blue-100 hover:bg-white/10 hover:text-white' }}">
                            <i class="bi bi-speedometer2 mr-3 text-base"></i>
                            Dashboard
                        </a>
                        <a href="{{ route('penyewa.motors') }}" 
                           class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition {{ str_starts_with($currentRoute, 'penyewa.motors') ? 'bg-white/20 text-white' : 'text-blue-100 hover:bg-white/10 hover:text-white' }}">
                            <i class="bi bi-motorcycle mr-3 text-base"></i>
                            Daftar Motor
                        </a>
                        <a href="{{ route('penyewa.bookings') }}" 
                           class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition {{ str_starts_with($currentRoute, 'penyewa.bookings') ? 'bg-white/20 text-white' : 'text-blue-100 hover:bg-white/10 hover:text-white' }}">
                            <i class="bi bi-calendar-check mr-3 text-base"></i>
                            Pemesanan Saya
                        </a>
                        <a href="{{ route('penyewa.payment-history') }}" 
                           class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition {{ str_starts_with($currentRoute, 'penyewa.payment') ? 'bg-white/20 text-white' : 'text-blue-100 hover:bg-white/10 hover:text-white' }}">
                            <i class="bi bi-credit-card mr-3 text-base"></i>
                            Riwayat Pembayaran
                        </a>
                        <a href="{{ route('penyewa.reports') }}" 
                           class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition {{ str_starts_with($currentRoute, 'penyewa.reports') ? 'bg-white/20 text-white' : 'text-blue-100 hover:bg-white/10 hover:text-white' }}">
                            <i class="bi bi-file-earmark-text mr-3 text-base"></i>
                            Laporan
                        </a>
                    @endif
                </nav>

                <!-- User Info -->
                <div class="border-t border-white/10 bg-white/5 p-4">
                    <div class="flex items-center mb-3">
                        <div class="flex-shrink-0">
                            <div class="h-10 w-10 rounded-full bg-white/20 flex items-center justify-center text-white font-semibold text-sm">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                        </div>
                        <div class="ml-3 flex-1 min-w-0">
                            <p class="text-sm font-semibold text-white truncate">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-blue-200 truncate">{{ Auth::user()->email }}</p>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" 
                                class="w-full flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-red-500/20 hover:bg-red-500 rounded-lg transition-colors duration-150 border border-red-400/20">
                            <i class="bi bi-box-arrow-right mr-2"></i>
                            Keluar
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex flex-1 flex-col min-w-0">
            <!-- Top bar -->
            <header class="bg-white border-b border-gray-200 sticky top-0 z-30">
                <div class="flex h-16 items-center px-4 sm:px-6 lg:px-8">
                    <button @click="sidebarOpen = true" 
                            class="lg:hidden -ml-2 mr-3 p-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition">
                        <i class="bi bi-list text-2xl"></i>
                    </button>
                    
                    @hasSection('header')
                        <div class="flex-1 min-w-0">
                            @yield('header')
                        </div>
                    @else
                        <div class="flex-1">
                            <h1 class="text-xl font-bold text-gray-900">@yield('title', 'Dashboard')</h1>
                        </div>
                    @endif
                    
                    @hasSection('actions')
                        <div class="ml-4 flex items-center space-x-3">
                            @yield('actions')
                        </div>
                    @endif
                </div>
            </header>

            <!-- Main content area -->
            <main class="flex-1 overflow-y-auto">
                <div class="px-4 py-6 sm:px-6 lg:px-8 max-w-7xl mx-auto">
                    @if(session('success'))
                        <div class="mb-6 rounded-lg bg-green-50 p-4 border-l-4 border-green-400 shadow-sm">
                            <div class="flex items-center">
                                <i class="bi bi-check-circle-fill text-green-500 text-xl"></i>
                                <p class="ml-3 text-sm font-medium text-green-800">{{ session('success') }}</p>
                            </div>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="mb-6 rounded-lg bg-red-50 p-4 border-l-4 border-red-400 shadow-sm">
                            <div class="flex items-center">
                                <i class="bi bi-exclamation-circle-fill text-red-500 text-xl"></i>
                                <p class="ml-3 text-sm font-medium text-red-800">{{ session('error') }}</p>
                            </div>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="mb-6 rounded-lg bg-red-50 p-4 border-l-4 border-red-400 shadow-sm">
                            <div class="flex">
                                <i class="bi bi-exclamation-triangle-fill text-red-500 text-xl"></i>
                                <div class="ml-3">
                                    <h3 class="text-sm font-semibold text-red-800 mb-2">Terdapat beberapa kesalahan:</h3>
                                    <ul class="list-disc list-inside text-sm text-red-700 space-y-1">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endif

                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
