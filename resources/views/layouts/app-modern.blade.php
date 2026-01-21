<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-gray-50">
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
<body class="h-full font-sans antialiased" x-data="{ sidebarOpen: false }">
    <div class="flex h-full">
        <!-- Sidebar for mobile -->
        <div x-show="sidebarOpen" 
             @click="sidebarOpen = false"
             class="fixed inset-0 z-40 bg-gray-900 bg-opacity-50 lg:hidden"
             x-transition:enter="transition-opacity ease-linear duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">
        </div>

        <!-- Sidebar -->
        <div :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
             class="fixed inset-y-0 left-0 z-50 w-72 transform bg-gradient-to-br from-blue-600 to-blue-700 transition-transform duration-300 ease-in-out lg:static lg:translate-x-0">
            
            <div class="flex h-full flex-col">
                <!-- Logo -->
                <div class="flex h-20 items-center justify-between px-6 border-b border-blue-500/30">
                    <div>
                        <h1 class="text-2xl font-bold text-white tracking-tight">FannRental</h1>
                        <p class="text-xs text-blue-100 mt-0.5">Rental Motor Terpercaya</p>
                    </div>
                    <button @click="sidebarOpen = false" class="lg:hidden text-white hover:text-gray-200">
                        <i class="bi bi-x-lg text-xl"></i>
                    </button>
                </div>

                <!-- Navigation -->
                <nav class="flex-1 space-y-1 px-4 py-6 overflow-y-auto">
                    @yield('navigation')
                </nav>

                <!-- User Info -->
                <div class="border-t border-blue-500/30 bg-blue-800/30 p-4">
                    <div class="flex items-center mb-3">
                        <div class="flex-shrink-0">
                            <div class="h-10 w-10 rounded-full bg-blue-500 flex items-center justify-center text-white font-semibold">
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
                                class="w-full flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-red-500/20 hover:bg-red-500 rounded-lg transition duration-150 border border-red-400/30">
                            <i class="bi bi-box-arrow-right mr-2"></i>
                            Keluar
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex flex-1 flex-col overflow-hidden">
            <!-- Top bar -->
            <header class="bg-white shadow-sm z-10">
                <div class="flex h-16 items-center justify-between px-4 sm:px-6 lg:px-8">
                    <button @click="sidebarOpen = true" 
                            class="lg:hidden text-gray-500 hover:text-gray-600">
                        <i class="bi bi-list text-2xl"></i>
                    </button>
                    
                    <div class="flex-1 lg:flex lg:items-center lg:justify-between">
                        <div class="flex-1 min-w-0">
                            @yield('header')
                        </div>
                        
                        <div class="ml-4 flex items-center space-x-4">
                            @yield('actions')
                        </div>
                    </div>
                </div>
            </header>

            <!-- Main content area -->
            <main class="flex-1 overflow-y-auto bg-gray-50">
                <div class="px-4 py-6 sm:px-6 lg:px-8">
                    @if(session('success'))
                        <div class="mb-6 rounded-lg bg-green-50 p-4 border border-green-200">
                            <div class="flex">
                                <i class="bi bi-check-circle-fill text-green-400 text-xl"></i>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="mb-6 rounded-lg bg-red-50 p-4 border border-red-200">
                            <div class="flex">
                                <i class="bi bi-exclamation-circle-fill text-red-400 text-xl"></i>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
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
