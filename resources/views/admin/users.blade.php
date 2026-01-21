@extends('layouts.fann')

@section('title', 'Kelola Pengguna')

@section('content')
<!-- Filter & Search -->
<div class="mb-6">
    <form id="filterForm" method="GET" action="{{ route('admin.users') }}" class="grid grid-cols-1 lg:grid-cols-12 gap-3">
        <!-- Role Filter -->
        <div class="lg:col-span-2">
            <select onchange="document.getElementById('filterForm').submit()" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" name="role">
                <option value="">Semua Role</option>
                <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="pemilik" {{ request('role') == 'pemilik' ? 'selected' : '' }}>Pemilik Motor</option>
                <option value="penyewa" {{ request('role') == 'penyewa' ? 'selected' : '' }}>Penyewa</option>
            </select>
        </div>
        
        <!-- Status Filter -->
        <div class="lg:col-span-2">
            <select onchange="document.getElementById('filterForm').submit()" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" name="status">
                <option value="">Semua Status</option>
                <option value="verified" {{ request('status') == 'verified' ? 'selected' : '' }}>Terverifikasi</option>
                <option value="unverified" {{ request('status') == 'unverified' ? 'selected' : '' }}>Belum Verifikasi</option>
                <option value="blacklisted" {{ request('status') == 'blacklisted' ? 'selected' : '' }}>Blacklist</option>
            </select>
        </div>
        
        <!-- Search -->
        <div class="lg:col-span-5">
            <input type="text" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" name="search" value="{{ request('search') }}" placeholder="Cari nama atau email...">
        </div>
        
        <!-- Buttons -->
        <div class="lg:col-span-3 flex gap-2">
            <button class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition inline-flex items-center justify-center" type="submit">
                <i class="bi bi-search mr-2"></i>Cari
            </button>
            <a href="{{ route('admin.users') }}" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition inline-flex items-center justify-center">
                <i class="bi bi-arrow-clockwise"></i>
            </a>
            <button type="button" @click="$dispatch('open-add-modal')" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition inline-flex items-center justify-center">
                <i class="bi bi-person-plus"></i>
            </button>
        </div>
    </form>
</div>

<!-- Users Table -->
<div class="bg-white shadow-sm rounded-lg overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
        <h2 class="text-lg font-semibold text-gray-900">
            <i class="bi bi-people mr-2"></i>
            Daftar Pengguna ({{ $users->total() }} hasil)
        </h2>
        @if(request('role') || request('status') || request('search'))
            <div class="text-sm text-gray-600">
                Filter aktif:
                @if(request('role'))
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 ml-2">
                        Role: {{ ucfirst(request('role')) }}
                    </span>
                @endif
                @if(request('status'))
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 ml-2">
                        Status: {{ request('status') == 'verified' ? 'Terverifikasi' : (request('status') == 'unverified' ? 'Belum Verifikasi' : 'Blacklist') }}
                    </span>
                @endif
                @if(request('search'))
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800 ml-2">
                        Cari: {{ request('search') }}
                    </span>
                @endif
            </div>
        @endif
    </div>
    <div class="overflow-x-auto">
        @if($users->count() > 0)
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pengguna</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Telepon</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bergabung</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($users as $user)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center">
                                        <i class="bi bi-person-circle text-gray-400 text-2xl"></i>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $user->email }}</div>
                                    @if($user->isBlacklisted())
                                        <div class="text-xs text-red-600 mt-1"><i class="bi bi-shield-x mr-1"></i>Blacklisted</div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($user->role === 'admin')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Admin</span>
                            @elseif($user->role === 'pemilik')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Pemilik Motor</span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Penyewa</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $user->phone ?? '-' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $user->created_at->format('d M Y') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($user->status === 'verified')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Terverifikasi</span>
                            @elseif($user->status === 'blacklisted')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Blacklist</span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Belum Verifikasi</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div x-data="{ open: false }" class="relative inline-block text-left">
                                    <button @click="open = !open" type="button" class="inline-flex items-center p-2 text-gray-400 hover:text-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 rounded-full">
                                        <i class="bi bi-three-dots-vertical text-lg"></i>
                                    </button>

                                    <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" class="origin-top-right absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-10" style="display: none;">
                                        <div class="py-1">
                                            <button @click="open = false; showUserDetail({{ $user->id }})" class="group flex items-center w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900 transition-colors">
                                                <i class="bi bi-eye mr-3 text-gray-400 group-hover:text-gray-500"></i>Detail Pengguna
                                            </button>
                                            
                                            <div class="border-t border-gray-100"></div>
                                            
                                            @if($user->status !== 'verified' && !$user->isBlacklisted())
                                                <button @click="open = false; verifyUser({{ $user->id }})" class="group flex items-center w-full px-4 py-2 text-sm text-green-700 hover:bg-green-50 transition-colors">
                                                    <i class="bi bi-check-circle mr-3 text-green-500 group-hover:text-green-600"></i>Verifikasi User
                                                </button>
                                            @endif
                                            
                                            @if(!$user->isBlacklisted() && $user->id !== auth()->id())
                                                <button @click="open = false; blacklistUser({{ $user->id }})" class="group flex items-center w-full px-4 py-2 text-sm text-yellow-700 hover:bg-yellow-50 transition-colors">
                                                    <i class="bi bi-shield-x mr-3 text-yellow-500 group-hover:text-yellow-600"></i>Blacklist User
                                                </button>
                                            @elseif($user->isBlacklisted())
                                                <button @click="open = false; removeBlacklist({{ $user->id }})" class="group flex items-center w-full px-4 py-2 text-sm text-blue-700 hover:bg-blue-50 transition-colors">
                                                    <i class="bi bi-shield-check mr-3 text-blue-500 group-hover:text-blue-600"></i>Hapus Blacklist
                                                </button>
                                            @endif
                                            
                                            @if($user->id !== auth()->id())
                                                <div class="border-t border-gray-100"></div>
                                                <button @click="open = false; deleteUser({{ $user->id }})" class="group flex items-center w-full px-4 py-2 text-sm text-red-700 hover:bg-red-50 transition-colors">
                                                    <i class="bi bi-trash mr-3 text-red-500 group-hover:text-red-600"></i>Hapus User
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-12">
                <i class="bi bi-people text-gray-400" style="font-size: 4rem;"></i>
                <h6 class="mt-4 text-gray-600 font-medium">Tidak ada pengguna ditemukan</h6>
                <p class="text-gray-500 text-sm">Coba ubah filter pencarian Anda</p>
            </div>
        @endif
    </div>
</div>

<!-- Pagination -->
<div class="mt-6 flex justify-center">
    {{ $users->appends(request()->query())->links() }}
</div>


<!-- Add User Modal -->
<div x-data="{ showAddModal: false }" @open-add-modal.window="showAddModal = true" x-show="showAddModal" @keydown.escape.window="showAddModal = false" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div x-show="showAddModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" @click="showAddModal = false"></div>

        <div x-show="showAddModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-lg shadow-xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="px-6 pt-5 pb-4 bg-white">
                <div class="flex items-center justify-between pb-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Tambah User Baru</h3>
                    <button @click="showAddModal = false" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>
                <form method="POST" action="{{ route('admin.users.store') }}">
                    @csrf
                    <div class="mt-4 space-y-4">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                            <input type="text" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" id="name" name="name" required>
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input type="email" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" id="email" name="email" required>
                        </div>
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Telepon</label>
                            <input type="text" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" id="phone" name="phone">
                        </div>
                        <div>
                            <label for="role" class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                            <select class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" id="role" name="role" required>
                                <option value="">Pilih Role</option>
                                <option value="pemilik">Pemilik Motor</option>
                                <option value="penyewa">Penyewa</option>
                            </select>
                        </div>
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                            <input type="password" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" id="password" name="password" required>
                        </div>
                    </div>
                    <div class="flex justify-end gap-3 mt-6 pt-4 border-t border-gray-200">
                        <button type="button" @click="showAddModal = false" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">Batal</button>
                        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors">Tambah User</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<!-- Delete Confirmation Modal -->
<div id="deleteModalContainer" class="fixed inset-0 z-50 overflow-y-auto hidden">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" onclick="closeDeleteModal()"></div>

        <div class="inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-lg shadow-xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="px-6 pt-5 pb-4 bg-white">
                <div class="flex items-center justify-between pb-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Konfirmasi Hapus</h3>
                    <button onclick="closeDeleteModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>
                <div class="mt-4">
                    <p class="text-gray-600">Apakah Anda yakin ingin menghapus pengguna ini? Tindakan ini tidak dapat dibatalkan.</p>
                </div>
                <div class="flex justify-end gap-3 mt-6 pt-4 border-t border-gray-200">
                    <button type="button" onclick="closeDeleteModal()" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">Batal</button>
                    <form id="deleteForm" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 transition-colors">Hapus User</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- User Detail Modal -->
<div id="userDetailModalContainer" class="fixed inset-0 z-50 overflow-y-auto hidden">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" onclick="closeDetailModal()"></div>

        <div class="inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-lg shadow-xl sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
            <div class="px-6 pt-5 pb-4 bg-white">
                <div class="flex items-center justify-between pb-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Detail Pengguna</h3>
                    <button onclick="closeDetailModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>
                <div class="mt-4" id="userDetailContent">
                    <div class="text-center">
                        <div class="inline-block w-8 h-8 border-4 border-blue-600 border-t-transparent rounded-full animate-spin"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Verify User Modal -->
<div id="verifyModalContainer" class="fixed inset-0 z-50 overflow-y-auto hidden">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" onclick="closeVerifyModal()"></div>

        <div class="inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-lg shadow-xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="px-6 pt-5 pb-4 bg-white">
                <div class="flex items-center justify-between pb-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Verifikasi Pengguna</h3>
                    <button onclick="closeVerifyModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>
                <div class="mt-4">
                    <p class="text-gray-700">Apakah Anda yakin ingin memverifikasi pengguna ini?</p>
                    <p class="text-sm text-gray-500 mt-2">Pengguna yang terverifikasi akan mendapat akses penuh ke platform.</p>
                </div>
                <div class="flex justify-end gap-3 mt-6 pt-4 border-t border-gray-200">
                    <button type="button" onclick="closeVerifyModal()" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">Batal</button>
                    <form id="verifyForm" method="POST" style="display: inline;">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700 transition-colors inline-flex items-center">
                            <i class="bi bi-check-circle mr-1"></i>Verifikasi
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Blacklist User Modal -->
<div id="blacklistModalContainer" class="fixed inset-0 z-50 overflow-y-auto hidden">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" onclick="closeBlacklistModal()"></div>

        <div class="inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-lg shadow-xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="px-6 pt-5 pb-4 bg-white">
                <div class="flex items-center justify-between pb-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Blacklist Pengguna</h3>
                    <button onclick="closeBlacklistModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>
                <form id="blacklistForm" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="mt-4">
                        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-4">
                            <div class="flex">
                                <i class="bi bi-exclamation-triangle text-yellow-400 mr-2"></i>
                                <div>
                                    <strong class="text-yellow-800">Peringatan!</strong>
                                    <p class="text-sm text-yellow-700 mt-1">Pengguna yang di-blacklist tidak akan bisa mengakses platform.</p>
                                </div>
                            </div>
                        </div>
                        <div>
                            <label for="blacklist_reason" class="block text-sm font-medium text-gray-700 mb-1">Alasan Blacklist *</label>
                            <textarea class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" id="blacklist_reason" name="blacklist_reason" rows="3" placeholder="Jelaskan alasan pengguna di-blacklist..." required></textarea>
                        </div>
                    </div>
                    <div class="flex justify-end gap-3 mt-6 pt-4 border-t border-gray-200">
                        <button type="button" onclick="closeBlacklistModal()" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">Batal</button>
                        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 transition-colors inline-flex items-center">
                            <i class="bi bi-shield-x mr-1"></i>Blacklist User
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Remove Blacklist Modal -->
<div id="removeBlacklistModalContainer" class="fixed inset-0 z-50 overflow-y-auto hidden">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" onclick="closeRemoveBlacklistModal()"></div>

        <div class="inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-lg shadow-xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="px-6 pt-5 pb-4 bg-white">
                <div class="flex items-center justify-between pb-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Hapus Blacklist</h3>
                    <button onclick="closeRemoveBlacklistModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>
                <div class="mt-4">
                    <p class="text-gray-700">Apakah Anda yakin ingin menghapus blacklist untuk pengguna ini?</p>
                    <p class="text-sm text-gray-500 mt-2">Pengguna akan kembali mendapat akses ke platform.</p>
                </div>
                <div class="flex justify-end gap-3 mt-6 pt-4 border-t border-gray-200">
                    <button type="button" onclick="closeRemoveBlacklistModal()" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">Batal</button>
                    <form id="removeBlacklistForm" method="POST" style="display: inline;">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors inline-flex items-center">
                            <i class="bi bi-shield-check mr-1"></i>Hapus Blacklist
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Modal helper functions
function closeDeleteModal() {
    document.getElementById('deleteModalContainer').classList.add('hidden');
}

function closeDetailModal() {
    document.getElementById('userDetailModalContainer').classList.add('hidden');
}

function closeVerifyModal() {
    document.getElementById('verifyModalContainer').classList.add('hidden');
}

function closeBlacklistModal() {
    document.getElementById('blacklistModalContainer').classList.add('hidden');
}

function closeRemoveBlacklistModal() {
    document.getElementById('removeBlacklistModalContainer').classList.add('hidden');
}

function deleteUser(userId) {
    const form = document.getElementById('deleteForm');
    form.action = `/admin/users/${userId}`;
    
    document.getElementById('deleteModalContainer').classList.remove('hidden');
}

function showUserDetail(userId) {
    const modal = document.getElementById('userDetailModalContainer');
    const content = document.getElementById('userDetailContent');
    
    // Show loading
    content.innerHTML = `
        <div class="text-center">
            <div class="inline-block w-8 h-8 border-4 border-blue-600 border-t-transparent rounded-full animate-spin"></div>
        </div>
    `;
    
    modal.classList.remove('hidden');
    
    // Fetch user detail
    fetch(`/admin/users/${userId}/detail`)
    .then(response => response.json())
    .then(data => {
        const user = data.user;
        
        content.innerHTML = `
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="text-center">
                    <div class="mb-3">
                        <i class="bi bi-person-circle text-gray-400" style="font-size: 5rem;"></i>
                    </div>
                    <h5 class="text-lg font-semibold text-gray-900">${user.name}</h5>
                    <p class="text-gray-600 text-sm mt-1">${user.email}</p>
                    <div class="mt-2">${getUserStatusBadge(user.status)}</div>
                </div>
                <div class="md:col-span-2">
                    <h6 class="text-base font-semibold text-gray-900 mb-3">Informasi Pengguna</h6>
                    <div class="space-y-2">
                        <div class="flex py-2 border-b border-gray-100">
                            <span class="font-medium text-gray-700 w-32">Role:</span>
                            <span>${getRoleBadge(user.role)}</span>
                        </div>
                        <div class="flex py-2 border-b border-gray-100">
                            <span class="font-medium text-gray-700 w-32">Telepon:</span>
                            <span class="text-gray-600">${user.phone || '-'}</span>
                        </div>
                        <div class="flex py-2 border-b border-gray-100">
                            <span class="font-medium text-gray-700 w-32">Bergabung:</span>
                            <span class="text-gray-600">${new Date(user.created_at).toLocaleDateString('id-ID')}</span>
                        </div>
                        ${user.verified_at ? `
                            <div class="flex py-2 border-b border-gray-100">
                                <span class="font-medium text-gray-700 w-32">Diverifikasi:</span>
                                <span class="text-gray-600">${new Date(user.verified_at).toLocaleDateString('id-ID')}</span>
                            </div>
                        ` : ''}
                        ${user.status === 'blacklisted' && user.blacklist_reason ? `
                            <div class="flex py-2 border-b border-gray-100">
                                <span class="font-medium text-gray-700 w-32">Alasan Blacklist:</span>
                                <span class="text-red-600 text-sm">${user.blacklist_reason}</span>
                            </div>
                        ` : ''}
                    </div>
                    
                    <h6 class="text-base font-semibold text-gray-900 mt-6 mb-3">Statistik</h6>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-blue-500 text-white rounded-lg p-4 text-center">
                            <div class="text-2xl font-bold">${user.bookings_count || 0}</div>
                            <div class="text-sm mt-1">Total Booking</div>
                        </div>
                        <div class="bg-green-500 text-white rounded-lg p-4 text-center">
                            <div class="text-2xl font-bold">${user.motors_count || 0}</div>
                            <div class="text-sm mt-1">Motor Dimiliki</div>
                        </div>
                    </div>
                </div>
            </div>
        `;
    })
    .catch(error => {
        console.error('Error fetching user detail:', error);
        content.innerHTML = `
            <div class="bg-red-50 border-l-4 border-red-400 p-4">
                <div class="flex">
                    <i class="bi bi-exclamation-triangle text-red-400 mr-2"></i>
                    <p class="text-red-700">Gagal memuat detail pengguna.</p>
                </div>
            </div>
        `;
    });
}

function verifyUser(userId) {
    const form = document.getElementById('verifyForm');
    form.action = `/admin/users/${userId}/verify`;
    
    document.getElementById('verifyModalContainer').classList.remove('hidden');
}

function blacklistUser(userId) {
    const form = document.getElementById('blacklistForm');
    form.action = `/admin/users/${userId}/blacklist`;
    
    document.getElementById('blacklistModalContainer').classList.remove('hidden');
}

function removeBlacklist(userId) {
    const form = document.getElementById('removeBlacklistForm');
    form.action = `/admin/users/${userId}/remove-blacklist`;
    
    document.getElementById('removeBlacklistModalContainer').classList.remove('hidden');
}

function getUserStatusBadge(status) {
    switch(status) {
        case 'verified': return '<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">Terverifikasi</span>';
        case 'blacklisted': return '<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">Blacklist</span>';
        default: return '<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Belum Verifikasi</span>';
    }
}

function getRoleBadge(role) {
    switch(role) {
        case 'admin': return '<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">Admin</span>';
        case 'pemilik': return '<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Pemilik Motor</span>';
        default: return '<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">Penyewa</span>';
    }
}
</script>
@endsection