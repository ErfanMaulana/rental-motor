<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Motor;
use App\Models\Booking;
use App\Models\Notification;
use App\Models\RentalRate;
use App\Models\RevenueSharing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Artisan;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalUsers = User::count();
        $totalPenyewa = User::where('role', 'penyewa')->count();
        $totalPemilik = User::where('role', 'pemilik')->count();
        $totalMotors = Motor::count();
        $totalBookings = Booking::count();
        $totalRevenue = Booking::where('status', 'completed')->sum('price');
        $pendingMotorsCount = Motor::where('status', 'pending_verification')->count();
        $pendingMotors = Motor::where('status', 'pending_verification')->with('owner')->latest()->take(5)->get();
        $pendingBookingsList = Booking::where('status', 'pending')->with(['renter', 'motor'])->latest()->take(5)->get();
        $availableMotors = Motor::where('status', 'available')->count();
        $pendingBookings = Booking::where('status', 'pending')->count();
        $confirmedBookings = Booking::where('status', 'confirmed')->count();
        $activeBookings = Booking::whereIn('status', ['confirmed', 'ongoing'])->count();
        
        // Recent bookings for dashboard
        $recentBookings = Booking::with(['user', 'motor'])
            ->latest()
            ->take(5)
            ->get();

        // Data untuk grafik pendapatan bulanan
        $monthlyRevenue = Booking::selectRaw('MONTH(created_at) as month, YEAR(created_at) as year, SUM(price) as total_revenue, SUM(price) * 0.3 as admin_commission, SUM(price) * 0.7 as owner_share')
            ->where('status', 'completed')
            ->where('created_at', '>=', now()->subMonths(12))
            ->groupBy('year', 'month')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get();

        // Format data untuk Chart.js
        $chartLabels = $monthlyRevenue->map(function($item) {
            return date('M Y', mktime(0, 0, 0, $item->month, 1, $item->year));
        })->toArray();

        $chartData = [
            'labels' => $chartLabels,
            'total_revenue' => $monthlyRevenue->pluck('total_revenue')->toArray(),
            'admin_commission' => $monthlyRevenue->pluck('admin_commission')->toArray(),
            'owner_share' => $monthlyRevenue->pluck('owner_share')->toArray()
        ];

        return view('admin.dashboard', compact(
            'totalUsers', 'totalPenyewa', 'totalPemilik', 'totalMotors', 
            'totalBookings', 'totalRevenue', 'pendingMotorsCount', 'pendingMotors',
            'pendingBookingsList', 'availableMotors', 'pendingBookings', 'confirmedBookings',
            'activeBookings', 'recentBookings', 'chartData'
        ));
    }

    public function users(Request $request)
    {
        $query = User::query();

        // Apply filters
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(15);
        
        return view('admin.users', compact('users'));
    }

    public function getUserDetail($id)
    {
        try {
            $user = User::with(['verifier'])->findOrFail($id);
            
            // Add additional statistics
            $user->bookings_count = $user->bookings()->count();
            $user->motors_count = $user->ownedMotors()->count();
            
            return response()->json([
                'success' => true,
                'user' => $user
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'User tidak ditemukan.'
            ], 404);
        }
    }

    public function verifyUser($id)
    {
        try {
            $user = User::findOrFail($id);
            
            if ($user->status === 'verified') {
                return back()->with('error', 'User sudah terverifikasi.');
            }
            
            $user->update([
                'status' => 'verified',
                'verified_at' => now(),
                'verified_by' => Auth::id()
            ]);
            
            // Create notification for user
            \App\Models\Notification::create([
                'user_id' => $user->id,
                'title' => 'Akun Terverifikasi',
                'message' => 'Selamat! Akun Anda telah diverifikasi oleh admin. Anda sekarang memiliki akses penuh ke platform.',
                'type' => 'account_verification',
                'data' => json_encode([
                    'verified_by' => Auth::user()->name,
                    'verified_at' => now()
                ])
            ]);
            
            return back()->with('success', 'User berhasil diverifikasi.');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat memverifikasi user.');
        }
    }

    public function blacklistUser(Request $request, $id)
    {
        $request->validate([
            'blacklist_reason' => 'required|string|max:500'
        ]);
        
        try {
            $user = User::findOrFail($id);
            
            if ($user->id === Auth::id()) {
                return back()->with('error', 'Tidak dapat memblacklist akun sendiri.');
            }
            
            if ($user->status === 'blacklisted') {
                return back()->with('error', 'User sudah dalam blacklist.');
            }
            
            $user->update([
                'status' => 'blacklisted',
                'blacklist_reason' => $request->blacklist_reason,
                'verified_by' => Auth::id(),
                'verified_at' => now()
            ]);
            
            // Create notification for user
            \App\Models\Notification::create([
                'user_id' => $user->id,
                'title' => 'Akun Di-blacklist',
                'message' => 'Akun Anda telah di-blacklist oleh admin. Alasan: ' . $request->blacklist_reason,
                'type' => 'account_blacklist',
                'data' => json_encode([
                    'blacklisted_by' => Auth::user()->name,
                    'reason' => $request->blacklist_reason,
                    'blacklisted_at' => now()
                ])
            ]);
            
            return back()->with('success', 'User berhasil di-blacklist.');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat memblacklist user.');
        }
    }

    public function removeBlacklist($id)
    {
        try {
            $user = User::findOrFail($id);
            
            if ($user->status !== 'blacklisted') {
                return back()->with('error', 'User tidak dalam blacklist.');
            }
            
            $user->update([
                'status' => 'unverified',
                'blacklist_reason' => null,
                'verified_by' => Auth::id(),
                'verified_at' => now()
            ]);
            
            // Create notification for user
            \App\Models\Notification::create([
                'user_id' => $user->id,
                'title' => 'Blacklist Dihapus',
                'message' => 'Blacklist pada akun Anda telah dihapus oleh admin. Anda dapat menggunakan platform kembali.',
                'type' => 'blacklist_removal',
                'data' => json_encode([
                    'removed_by' => Auth::user()->name,
                    'removed_at' => now()
                ])
            ]);
            
            return back()->with('success', 'Blacklist berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat menghapus blacklist.');
        }
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|max:15',
            'role' => 'required|in:admin,pemilik,penyewa',
            'password' => 'required|string|min:8',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'role' => $request->role,
            'password' => Hash::make($request->password),
            'email_verified_at' => now(),
        ]);

        return redirect()->route('admin.users')->with('success', 'Pengguna berhasil ditambahkan');
    }

    public function showUser($id)
    {
        $user = User::findOrFail($id);
        return response()->json($user);
    }

    public function destroyUser(User $user)
    {
        if ($user->id === Auth::id()) {
            return back()->with('error', 'Tidak dapat menghapus akun sendiri.');
        }

        try {
            // Check if user has active bookings or motors
            $activeBookings = $user->bookings()->whereIn('status', ['pending', 'confirmed', 'active'])->count();
            $ownedMotors = $user->ownedMotors()->count();
            
            if ($activeBookings > 0) {
                return back()->with('error', 'Tidak dapat menghapus user yang memiliki booking aktif.');
            }
            
            if ($ownedMotors > 0) {
                return back()->with('error', 'Tidak dapat menghapus user yang memiliki motor terdaftar.');
            }
            
            $user->delete();
            return back()->with('success', 'User berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat menghapus user.');
        }
    }

    public function bookings(Request $request)
    {
        $query = Booking::with(['renter', 'motor']);

        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                  ->orWhereHas('renter', function($userQ) use ($search) {
                      $userQ->where('name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('motor', function($motorQ) use ($search) {
                      $motorQ->where('brand', 'like', "%{$search}%")
                             ->orWhere('plate_number', 'like', "%{$search}%");
                  });
            });
        }

        $bookings = $query->orderBy('created_at', 'desc')->paginate(15);

        $stats = [
            'pending' => Booking::where('status', 'pending')->count(),
            'confirmed' => Booking::where('status', 'confirmed')->count(),
            'ongoing' => Booking::where('status', 'active')->count(),
            'completed' => Booking::where('status', 'completed')->count()
        ];

        return view('admin.bookings.index', compact('bookings', 'stats'));
    }

    public function showBooking($id)
    {
        $booking = Booking::with(['renter', 'motor'])->findOrFail($id);
        return response()->json($booking);
    }

    /**
     * Get booking detail for AJAX modal
     */
    public function getBookingDetailAjax($id)
    {
        try {
            $booking = Booking::with([
                'renter',
                'motor',
                'motor.owner',
                'payment'
            ])->findOrFail($id);

            $statusBadges = [
                'pending' => '<span class="inline-flex px-2 py-1 text-xs font-medium bg-yellow-100 text-yellow-800 rounded">Pending</span>',
                'confirmed' => '<span class="inline-flex px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded">Confirmed</span>',
                'active' => '<span class="inline-flex px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded">Active</span>',
                'completed' => '<span class="inline-flex px-2 py-1 text-xs font-medium bg-gray-100 text-gray-800 rounded">Completed</span>',
                'cancelled' => '<span class="inline-flex px-2 py-1 text-xs font-medium bg-red-100 text-red-800 rounded">Cancelled</span>'
            ];

            $paymentMethodLabels = [
                'dana' => 'DANA',
                'gopay' => 'GoPay',
                'bank' => 'Transfer Bank',
                'shopeepay' => 'ShopeePay'
            ];

            return response()->json([
                'success' => true,
                'booking' => [
                    'id' => $booking->id,
                    'status' => $booking->status,
                    'status_badge' => $statusBadges[$booking->status] ?? '',
                    'duration' => $booking->duration,
                    'total_cost' => $booking->total_cost,
                    'formatted_total_cost' => number_format($booking->total_cost, 0, ',', '.'),
                    'formatted_rental_period' => $booking->start_date->format('d M Y') . ' - ' . $booking->end_date->format('d M Y'),
                    'formatted_created_at' => $booking->created_at->format('d M Y H:i'),
                    'payment_method' => $booking->payment_method,
                    'formatted_payment_method' => $paymentMethodLabels[$booking->payment_method] ?? '-',
                    'payment_status' => $booking->payment ? ($booking->payment->status === 'paid' ? 'Lunas' : 'Pending') : null,
                    'notes' => $booking->notes,
                    'renter' => [
                        'name' => $booking->renter->name,
                        'email' => $booking->renter->email,
                        'phone' => $booking->renter->phone ?? '-'
                    ],
                    'motor' => [
                        'brand' => $booking->motor->brand,
                        'model' => $booking->motor->model,
                        'type_cc' => $booking->motor->type_cc,
                        'plate_number' => $booking->motor->plate_number,
                        'owner' => [
                            'name' => $booking->motor->owner->name
                        ]
                    ]
                ]
            ]);
        } catch (\Exception $e) {
            Log::error("Error getting booking detail: " . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan saat mengambil data booking.'], 500);
        }
    }

    public function updateBookingStatus(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);
        
        $request->validate([
            'status' => 'required|in:pending,confirmed,active,completed,cancelled'
        ]);

        $oldStatus = $booking->status;
        $booking->update(['status' => $request->status]);

        $statusMessages = [
            'confirmed' => 'Booking berhasil dikonfirmasi',
            'cancelled' => 'Booking berhasil dibatalkan',
            'active' => 'Rental berhasil diaktifkan',
            'completed' => 'Rental berhasil diselesaikan'
        ];

        return redirect()->route('admin.bookings')
            ->with('success', $statusMessages[$request->status] ?? 'Status booking berhasil diupdate');
    }

    public function reports(Request $request)
    {
        $query = Booking::with(['renter', 'motor'])->where('status', 'completed');

        $transactions = $query->orderBy('created_at', 'desc')->paginate(15);

        $completedBookings = $query->get();
        $totalRevenue = $completedBookings->sum('price');
        $adminCommission = $totalRevenue * 0.3; // 30% untuk admin
        $ownerShare = $totalRevenue * 0.7; // 70% untuk pemilik

        $summary = [
            'total_revenue' => $totalRevenue,
            'admin_commission' => $adminCommission,
            'owner_amount' => $ownerShare,
            'total_bookings' => $completedBookings->count()
        ];

        $topMotors = collect();
        
        // Real chart data from database
        $monthlyRevenue = Booking::selectRaw('MONTH(created_at) as month, YEAR(created_at) as year, SUM(price) as total_revenue')
            ->where('status', 'completed')
            ->where('created_at', '>=', now()->subMonths(12))
            ->groupBy('year', 'month')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get();

        $chartData = [
            'labels' => $monthlyRevenue->map(function($item) {
                return date('M Y', mktime(0, 0, 0, $item->month, 1, $item->year));
            })->toArray(),
            'revenue' => $monthlyRevenue->pluck('total_revenue')->toArray(),
            'admin_commission' => $monthlyRevenue->map(function($item) {
                return $item->total_revenue * 0.3; // 30% admin commission
            })->toArray(),
            'owner_share' => $monthlyRevenue->map(function($item) {
                return $item->total_revenue * 0.7; // 70% owner share
            })->toArray()
        ];
        
        $ownerSummary = collect();

        // Additional variables for the view
        $commissionRate = 30; // New commission rate 30%
        $ownerRevenue = $ownerShare;
        $totalTransactions = $completedBookings->count();

        // Get revenue sharing data
        $revenueSharing = RevenueSharing::with(['owner', 'booking.motor'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.reports', compact(
            'transactions', 'summary', 'topMotors', 'chartData', 'ownerSummary',
            'totalRevenue', 'adminCommission', 'ownerRevenue', 'commissionRate',
            'totalTransactions', 'revenueSharing'
        ));
    }

    public function motors(Request $request)
    {
        $query = Motor::with(['owner', 'rentalRate']);

        // Filter by status
        if ($request->filled('status')) {
            $status = $request->status;
            
            if ($status === 'rented') {
                // Motor sedang disewa: punya booking dengan status confirmed dan tanggal sedang berlangsung
                $query->whereHas('bookings', function($q) {
                    $q->where('status', 'confirmed')
                      ->where('start_date', '<=', now()->format('Y-m-d'))
                      ->where('end_date', '>=', now()->format('Y-m-d'));
                });
            } else {
                // Status lainnya: pending_verification, available, maintenance
                $query->where('status', $status);
                
                // Untuk available, pastikan tidak sedang disewa
                if ($status === 'available') {
                    $query->whereDoesntHave('bookings', function($q) {
                        $q->where('status', 'confirmed')
                          ->where('start_date', '<=', now()->format('Y-m-d'))
                          ->where('end_date', '>=', now()->format('Y-m-d'));
                    });
                }
            }
        }

        // Filter by CC
        if ($request->filled('cc')) {
            $ccValue = $request->cc . 'cc'; // Add 'cc' suffix to match database enum format
            $query->where('type_cc', $ccValue);
        }

        // Search by brand, model, or plate number
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('brand', 'like', "%{$search}%")
                  ->orWhere('model', 'like', "%{$search}%")
                  ->orWhere('plate_number', 'like', "%{$search}%");
            });
        }

        $motors = $query->orderBy('created_at', 'desc')->paginate(10);

        // Add query parameters to pagination links
        $motors->appends($request->query());

        // Count statistics for badges
        $pendingCount = Motor::where('status', 'pending_verification')->count();
        
        // Count verified motors (available yang tidak sedang disewa + maintenance)
        $verifiedCount = Motor::where(function($q) {
            $q->where('status', 'available')
              ->whereDoesntHave('bookings', function($q2) {
                  $q2->where('status', 'confirmed')
                     ->where('start_date', '<=', now()->format('Y-m-d'))
                     ->where('end_date', '>=', now()->format('Y-m-d'));
              });
        })->orWhere('status', 'maintenance')->count();

        return view('admin.motors', compact('motors', 'pendingCount', 'verifiedCount'));
    }

    /**
     * Show motor detail for admin
     */
    public function motorDetail($id)
    {
        $motor = Motor::with(['owner', 'rentalRate', 'bookings.user'])
            ->findOrFail($id);

        return view('admin.motor-detail-new', compact('motor'));
    }

    /**
     * Get motor detail for AJAX/modal (admin)
     */
    public function getMotorDetailAjax($id)
    {
        try {
            Log::info("AdminController getMotorDetailAjax called with ID: " . $id);
            
            $motor = Motor::with(['owner', 'rentalRate', 'bookings' => function($query) {
                    $query->with('user')->latest()->limit(5);
                }])
                ->findOrFail($id);

            Log::info("Motor found: " . $motor->brand . " " . $motor->model);

            // Calculate some statistics
            $totalBookings = $motor->bookings()->count();
            $activeBookings = $motor->bookings()->whereIn('status', ['confirmed', 'active'])->count();
            $completedBookings = $motor->bookings()->where('status', 'completed')->count();
            
            // Calculate total earnings
            $totalEarnings = $motor->bookings()
                ->where('bookings.status', 'completed')
                ->join('payments', 'bookings.id', '=', 'payments.booking_id')
                ->where('payments.status', 'completed')
                ->sum('payments.amount');

            $response = [
                'motor' => $motor,
                'stats' => [
                    'total_bookings' => $totalBookings,
                    'active_bookings' => $activeBookings,
                    'completed_bookings' => $completedBookings,
                    'total_earnings' => $totalEarnings
                ]
            ];

            Log::info("Returning JSON response", $response);

            return response()->json($response);
        } catch (\Exception $e) {
            Log::error("Error in getMotorDetailAjax: " . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function verifyMotor(Request $request, Motor $motor)
    {
        // Validate pricing if provided
        $request->validate([
            'daily_rate' => 'required|numeric|min:10000',
            'weekly_rate' => 'nullable|numeric|min:50000',
            'monthly_rate' => 'nullable|numeric|min:200000'
        ]);

        // Calculate rates if not provided
        $dailyRate = $request->daily_rate;
        $weeklyRate = $request->weekly_rate ?: ($dailyRate * 7 * 0.9); // 10% discount for weekly
        $monthlyRate = $request->monthly_rate ?: ($dailyRate * 30 * 0.8); // 20% discount for monthly

        // Update motor status
        $motor->update([
            'status' => 'available',
            'verified_by' => Auth::id(),
            'verified_at' => Carbon::now()
        ]);

        // Create or update rental rate
        $motor->rentalRate()->updateOrCreate(
            ['motor_id' => $motor->id],
            [
                'daily_rate' => $dailyRate,
                'weekly_rate' => $weeklyRate,
                'monthly_rate' => $monthlyRate
            ]
        );
        
        // Check if request is AJAX
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Motor berhasil diverifikasi dan harga sewa telah ditetapkan',
                'motor' => $motor->fresh(['rentalRate'])
            ]);
        }
        
        return redirect()->back()->with('success', 'Motor berhasil diverifikasi dan harga sewa telah ditetapkan');
    }

    /**
     * Update motor rental price
     */
    public function updateMotorPrice(Request $request, $id)
    {
        try {
            $motor = Motor::findOrFail($id);
            
            // Validate pricing
            $request->validate([
                'daily_rate' => 'required|numeric|min:10000',
                'weekly_rate' => 'required|numeric|min:50000',
                'monthly_rate' => 'required|numeric|min:200000'
            ]);

            // Update rental rate
            $motor->rentalRate()->updateOrCreate(
                ['motor_id' => $motor->id],
                [
                    'daily_rate' => $request->daily_rate,
                    'weekly_rate' => $request->weekly_rate,
                    'monthly_rate' => $request->monthly_rate
                ]
            );
            
            return response()->json([
                'success' => true,
                'message' => 'Harga sewa motor berhasil diperbarui',
                'motor' => $motor->fresh(['rentalRate'])
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui harga sewa: ' . $e->getMessage()
            ], 500);
        }
    }

    public function financialReport(Request $request)
    {
        // Base query untuk revenue sharing yang sudah dibuat (payment sudah diverifikasi)
        $query = RevenueSharing::with(['booking.renter', 'booking.motor.owner', 'owner'])
            ->whereHas('booking')
            ->whereHas('owner')
            ->whereIn('status', ['pending', 'paid']); // Include both approved payments and completed rentals

        // Filter berdasarkan bulan jika ada
        if ($request->filled('month')) {
            $month = $request->month;
            $year = now()->year; // Tahun sekarang
            $query->whereMonth('created_at', $month)
                  ->whereYear('created_at', $year);
        }

        // Get transactions dengan pagination
        $transactions = $query->orderBy('created_at', 'desc')->paginate(15);
        $transactions->appends($request->query());

        // Hitung summary menggunakan aggregate functions (lebih cepat dari get()->sum())
        $summaryQuery = RevenueSharing::selectRaw('
                COUNT(*) as total_bookings,
                SUM(total_amount) as total_revenue,
                SUM(admin_commission) as admin_commission,
                SUM(owner_amount) as owner_amount,
                SUM(CASE WHEN status = "pending" THEN 1 ELSE 0 END) as pending_settlements,
                SUM(CASE WHEN status = "paid" THEN 1 ELSE 0 END) as completed_settlements
            ')
            ->whereIn('status', ['pending', 'paid']);
        
        // Apply month filter to summary if selected
        if ($request->filled('month')) {
            $month = $request->month;
            $year = now()->year;
            $summaryQuery->whereMonth('created_at', $month)
                        ->whereYear('created_at', $year);
        }
        
        $summaryData = $summaryQuery->first();

        $summary = [
            'total_revenue' => $summaryData->total_revenue ?? 0,
            'admin_commission' => $summaryData->admin_commission ?? 0,
            'owner_amount' => $summaryData->owner_amount ?? 0,
            'total_bookings' => $summaryData->total_bookings ?? 0,
            'pending_settlements' => $summaryData->pending_settlements ?? 0,
            'completed_settlements' => $summaryData->completed_settlements ?? 0
        ];

        // Data untuk chart - revenue per bulan dari revenue sharing (12 bulan terakhir) - REAL TIME
        $monthlyRevenue = RevenueSharing::selectRaw('MONTH(created_at) as month, YEAR(created_at) as year, SUM(total_amount) as total, SUM(admin_commission) as admin_total, SUM(owner_amount) as owner_total')
            ->whereIn('status', ['pending', 'paid'])
            ->where('created_at', '>=', now()->subMonths(12))
            ->groupBy('year', 'month')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get();

        $chartData = [
            'labels' => $monthlyRevenue->map(function($item) {
                return date('M Y', mktime(0, 0, 0, $item->month, 1, $item->year));
            })->toArray(),
            'revenue' => $monthlyRevenue->pluck('total')->toArray(),
            'admin_commission' => $monthlyRevenue->pluck('admin_total')->toArray(),
            'owner_share' => $monthlyRevenue->pluck('owner_total')->toArray()
        ];

        // Top motors berdasarkan revenue sharing (lebih akurat)
        $topMotorsQuery = RevenueSharing::join('bookings', 'revenue_sharings.booking_id', '=', 'bookings.id')
            ->join('motors', 'bookings.motor_id', '=', 'motors.id')
            ->select('motors.id', 'motors.brand', 'motors.model', 'motors.plate_number',
                     DB::raw('COUNT(*) as booking_count'),
                     DB::raw('SUM(revenue_sharings.total_amount) as total_revenue'))
            ->whereIn('revenue_sharings.status', ['pending', 'paid']);
        
        // Apply month filter if selected
        if ($request->filled('month')) {
            $month = $request->month;
            $year = now()->year;
            $topMotorsQuery->whereMonth('revenue_sharings.created_at', $month)
                          ->whereYear('revenue_sharings.created_at', $year);
        }
        
        $topMotors = $topMotorsQuery->groupBy('motors.id', 'motors.brand', 'motors.model', 'motors.plate_number')
            ->orderBy('total_revenue', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($item) {
                return [
                    'motor' => (object)[
                        'id' => $item->id,
                        'brand' => $item->brand,
                        'model' => $item->model,
                        'plate_number' => $item->plate_number
                    ],
                    'booking_count' => $item->booking_count,
                    'total_revenue' => $item->total_revenue
                ];
            });

        // Revenue sharing per pemilik dengan eager loading dan join
        $ownerSummaryQuery = RevenueSharing::select('revenue_sharings.owner_id',
                                               DB::raw('COUNT(*) as transaction_count'), 
                                               DB::raw('SUM(total_amount) as total_revenue'),
                                               DB::raw('SUM(owner_amount) as owner_earned'),
                                               DB::raw('SUM(admin_commission) as admin_earned'))
            ->join('users', 'revenue_sharings.owner_id', '=', 'users.id')
            ->selectRaw('users.name as owner_name, users.email as owner_email')
            ->selectRaw('(SELECT COUNT(*) FROM motors WHERE motors.owner_id = revenue_sharings.owner_id) as motor_count')
            ->whereIn('revenue_sharings.status', ['pending', 'paid']);
        
        // Apply month filter if selected
        if ($request->filled('month')) {
            $month = $request->month;
            $year = now()->year;
            $ownerSummaryQuery->whereMonth('revenue_sharings.created_at', $month)
                             ->whereYear('revenue_sharings.created_at', $year);
        }
        
        $ownerSummary = $ownerSummaryQuery->groupBy('revenue_sharings.owner_id', 'users.name', 'users.email')
            ->orderBy('total_revenue', 'desc')
            ->limit(10)
            ->get()
            ->map(function($item) {
                $item->owner = (object)[
                    'id' => $item->owner_id,
                    'name' => $item->owner_name,
                    'email' => $item->owner_email
                ];
                return $item;
            });

        return view('admin.financial-report', compact('transactions', 'summary', 'chartData', 'topMotors', 'ownerSummary'));
    }

    /**
     * Export financial report to PDF
     */
    public function exportFinancialReportPDF(Request $request)
    {
        // Base query untuk revenue sharing yang sudah dibuat (payment sudah diverifikasi)
        $query = RevenueSharing::with(['booking.renter', 'booking.motor.owner', 'owner'])
            ->whereHas('booking')
            ->whereHas('owner')
            ->whereIn('status', ['pending', 'paid']);

        // Filter berdasarkan bulan jika ada
        $dateRange = null;
        if ($request->filled('month')) {
            $month = $request->month;
            $year = now()->year;
            $query->whereMonth('created_at', $month)
                  ->whereYear('created_at', $year);
            
            $monthNames = [
                1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
            ];
            $dateRange = $monthNames[$month] . ' ' . $year;
        }

        // Get all transactions untuk PDF (tanpa pagination)
        $transactions = $query->orderBy('created_at', 'desc')->get();

        // Hitung summary dari semua revenue sharing yang sudah dibuat
        $allRevenueSharing = RevenueSharing::whereIn('status', ['pending', 'paid']);
        
        // Apply same month filter untuk summary
        if ($request->filled('month')) {
            $month = $request->month;
            $year = now()->year;
            $allRevenueSharing->whereMonth('created_at', $month)
                             ->whereYear('created_at', $year);
        }
        
        $filteredRevenueSharing = $allRevenueSharing->get();
        $totalRevenue = $filteredRevenueSharing->sum('total_amount');
        $adminCommission = $filteredRevenueSharing->sum('admin_commission');
        $ownerShare = $filteredRevenueSharing->sum('owner_amount');

        $summary = [
            'total_revenue' => $totalRevenue,
            'admin_commission' => $adminCommission,
            'owner_amount' => $ownerShare,
            'total_bookings' => $filteredRevenueSharing->count(),
            'pending_settlements' => $filteredRevenueSharing->where('status', 'pending')->count(),
            'completed_settlements' => $filteredRevenueSharing->where('status', 'paid')->count()
        ];

        // Top motors berdasarkan revenue sharing dalam periode yang dipilih
        $topMotorsQuery = RevenueSharing::join('bookings', 'revenue_sharings.booking_id', '=', 'bookings.id')
            ->join('motors', 'bookings.motor_id', '=', 'motors.id')
            ->select('motors.id', 'motors.brand', 'motors.model', 'motors.plate_number',
                     DB::raw('COUNT(*) as booking_count'),
                     DB::raw('SUM(revenue_sharings.total_amount) as total_revenue'))
            ->whereIn('revenue_sharings.status', ['pending', 'paid']);
            
        if ($request->filled('month')) {
            $month = $request->month;
            $year = now()->year;
            $topMotorsQuery->whereMonth('revenue_sharings.created_at', $month)
                          ->whereYear('revenue_sharings.created_at', $year);
        }
            
        $topMotors = $topMotorsQuery->groupBy('motors.id', 'motors.brand', 'motors.model', 'motors.plate_number')
            ->orderBy('total_revenue', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($item) {
                return [
                    'motor' => (object)[
                        'id' => $item->id,
                        'brand' => $item->brand,
                        'model' => $item->model,
                        'plate_number' => $item->plate_number
                    ],
                    'booking_count' => $item->booking_count,
                    'total_revenue' => $item->total_revenue
                ];
            });

        // Revenue sharing per pemilik dalam periode yang dipilih
        $ownerSummaryQuery = RevenueSharing::select('revenue_sharings.owner_id',
                                               DB::raw('COUNT(*) as transaction_count'), 
                                               DB::raw('SUM(total_amount) as total_revenue'),
                                               DB::raw('SUM(owner_amount) as owner_earned'),
                                               DB::raw('SUM(admin_commission) as admin_earned'))
            ->join('users', 'revenue_sharings.owner_id', '=', 'users.id')
            ->selectRaw('users.name as owner_name, users.email as owner_email')
            ->selectRaw('(SELECT COUNT(*) FROM motors WHERE motors.owner_id = revenue_sharings.owner_id) as motor_count')
            ->whereIn('revenue_sharings.status', ['pending', 'paid']);
            
        if ($request->filled('month')) {
            $month = $request->month;
            $year = now()->year;
            $ownerSummaryQuery->whereMonth('revenue_sharings.created_at', $month)
                             ->whereYear('revenue_sharings.created_at', $year);
        }
            
        $ownerSummary = $ownerSummaryQuery->groupBy('revenue_sharings.owner_id', 'users.name', 'users.email')
            ->orderBy('total_revenue', 'desc')
            ->get()
            ->map(function($item) {
                $item->owner = (object)[
                    'id' => $item->owner_id,
                    'name' => $item->owner_name,
                    'email' => $item->owner_email
                ];
                return $item;
            });

        // Generate PDF
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.financial-report-pdf', compact(
            'transactions', 
            'summary', 
            'topMotors', 
            'ownerSummary', 
            'dateRange'
        ));

        // Set paper size dan orientation
        $pdf->setPaper('A4', 'portrait');

        // Generate filename
        $filename = 'Laporan_Keuangan_' . date('Y-m-d_H-i-s') . '.pdf';

        // Download PDF
        return $pdf->download($filename);
    }

    /**
     * Get notifications for admin
     */
    public function getNotifications()
    {
        $notifications = Notification::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        return response()->json([
            'notifications' => $notifications,
            'unread_count' => $notifications->where('read_at', null)->count()
        ]);
    }

    /**
     * Mark notification as read
     */
    public function markNotificationAsRead($id)
    {
        $notification = Notification::where('user_id', Auth::id())->findOrFail($id);
        $notification->markAsRead();

        return response()->json(['success' => true]);
    }

    /**
     * Mark all notifications as read
     */
    public function markAllNotificationsAsRead()
    {
        Notification::where('user_id', Auth::id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return response()->json(['success' => true]);
    }

    /**
     * Display payments for verification
     */
    public function payments(Request $request)
    {
        $query = \App\Models\Payment::with(['booking', 'booking.renter', 'booking.motor', 'verifiedBy']);

        // Filter by status
        if ($request->has('status') && $request->status !== '') {
            if ($request->status === 'verified') {
                $query->whereNotNull('verified_at');
            } elseif ($request->status === 'unverified') {
                $query->whereNull('verified_at');
            } else {
                $query->where('status', $request->status);
            }
        }

        // Filter by payment method
        if ($request->has('payment_method') && $request->payment_method !== '') {
            $query->where('payment_method', $request->payment_method);
        }

        // Search by penyewa name or booking ID
        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('booking.renter', function($q2) use ($search) {
                    $q2->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                })->orWhere('id', 'like', "%{$search}%")
                  ->orWhereHas('booking', function($q2) use ($search) {
                      $q2->where('id', 'like', "%{$search}%");
                  });
            });
        }

        $payments = $query->orderBy('created_at', 'desc')->paginate(15);

        // Summary data
        $summary = [
            'total_payments' => \App\Models\Payment::count(),
            'unverified_payments' => \App\Models\Payment::whereNull('verified_at')->count(),
            'verified_payments' => \App\Models\Payment::whereNotNull('verified_at')->count(),
            'pending_amount' => \App\Models\Payment::whereNull('verified_at')->sum('amount'),
            'verified_amount' => \App\Models\Payment::whereNotNull('verified_at')->sum('amount'),
        ];

        return view('admin.payments.index', compact('payments', 'summary'));
    }

    /**
     * Show payment detail for verification
     */
    public function showPayment($id)
    {
        $payment = \App\Models\Payment::with([
            'booking', 
            'booking.renter', 
            'booking.motor', 
            'booking.motor.owner',
            'verifiedBy'
        ])->findOrFail($id);

        return view('admin.payments.show', compact('payment'));
    }

    /**
     * Verify payment
     */
    public function verifyPayment(Request $request, $id)
    {
        $request->validate([
            'verification_notes' => 'nullable|string|max:500',
            'rejection_reason' => 'nullable|string|max:500',
            'action' => 'nullable|in:approve,reject',
            'status' => 'nullable|in:paid,failed'
        ]);

        $payment = \App\Models\Payment::with(['booking'])->findOrFail($id);

        if ($payment->verified_at) {
            return back()->with('error', 'Pembayaran sudah diverifikasi sebelumnya.');
        }

        // Support both 'action' and 'status' parameters
        $isApproved = false;
        if ($request->has('action')) {
            $isApproved = $request->action === 'approve';
        } elseif ($request->has('status')) {
            $isApproved = $request->status === 'paid';
        }

        $notes = $request->verification_notes ?? $request->rejection_reason;

        $payment->update([
            'verified_at' => now(),
            'verified_by' => Auth::id(),
            'payment_notes' => $notes,
            'status' => $isApproved ? 'paid' : 'failed'
        ]);

        // Update booking status based on verification
        if ($isApproved) {
            $payment->booking->update(['status' => 'confirmed']);
            
            // Create revenue sharing record when payment is approved
            $booking = $payment->booking->load('motor');
            $totalAmount = $booking->price;
            $ownerAmount = $totalAmount * 0.7; // 70% untuk pemilik
            $adminCommission = $totalAmount * 0.3; // 30% untuk admin

            // Check if revenue sharing record already exists to avoid duplicates
            $existingRevenue = RevenueSharing::where('booking_id', $booking->id)->first();
            if (!$existingRevenue) {
                RevenueSharing::create([
                    'booking_id' => $booking->id,
                    'owner_id' => $booking->motor->owner_id,
                    'total_amount' => $totalAmount,
                    'owner_amount' => $ownerAmount,
                    'admin_commission' => $adminCommission,
                    'owner_percentage' => 70.00,
                    'admin_percentage' => 30.00,
                    'status' => 'pending', // Will be 'paid' when booking completed
                    'settled_at' => null // Will be set when booking completed
                ]);

                Log::info('Revenue sharing created for approved payment', [
                    'booking_id' => $booking->id,
                    'payment_id' => $payment->id,
                    'total_amount' => $totalAmount,
                    'admin_commission' => $adminCommission,
                    'owner_amount' => $ownerAmount
                ]);
            }
            
            $message = 'Pembayaran berhasil diverifikasi dan disetujui. Data keuangan telah dicatat.';
        } else {
            $payment->booking->update(['status' => 'payment_rejected']);
            $message = 'Pembayaran ditolak.';
        }

        // Create notification for renter
        \App\Models\Notification::create([
            'user_id' => $payment->booking->renter_id,
            'title' => 'Status Pembayaran Diupdate',
            'message' => $isApproved
                ? 'Pembayaran Anda telah diverifikasi dan disetujui. Booking dikonfirmasi.'
                : 'Pembayaran Anda ditolak. Silakan hubungi admin untuk informasi lebih lanjut.',
            'type' => 'payment_verification',
            'data' => json_encode([
                'payment_id' => $payment->id,
                'booking_id' => $payment->booking->id,
                'status' => $isApproved ? 'approved' : 'rejected'
            ])
        ]);

        return redirect()->route('admin.payments')
            ->with('success', $message);
    }

    /**
     * Get payment detail for AJAX modal
     */
    public function getPaymentDetailAjax($id)
    {
        try {
            $payment = \App\Models\Payment::with([
                'booking', 
                'booking.renter', 
                'booking.motor', 
                'booking.motor.owner',
                'verifiedBy'
            ])->findOrFail($id);

            $statusBadge = '';
            if ($payment->verified_at) {
                if ($payment->status === 'paid') {
                    $statusBadge = '<span class="px-2 py-1 text-xs bg-green-100 text-green-700 rounded inline-flex items-center"><i class="bi bi-check-circle mr-1"></i>Diverifikasi</span>';
                } else {
                    $statusBadge = '<span class="px-2 py-1 text-xs bg-red-100 text-red-700 rounded inline-flex items-center"><i class="bi bi-x-circle mr-1"></i>Ditolak</span>';
                }
            } else {
                $statusBadge = '<span class="px-2 py-1 text-xs bg-yellow-100 text-yellow-700 rounded inline-flex items-center"><i class="bi bi-clock mr-1"></i>Menunggu Verifikasi</span>';
            }

            return response()->json([
                'success' => true,
                'payment' => [
                    'id' => $payment->id,
                    'amount' => $payment->amount,
                    'formatted_amount' => number_format($payment->amount, 0, ',', '.'),
                    'payment_method' => $payment->payment_method,
                    'formatted_payment_method' => $payment->formatted_payment_method,
                    'status' => $payment->status,
                    'status_badge' => $statusBadge,
                    'payment_notes' => $payment->payment_notes,
                    'proof_image_url' => $payment->payment_proof ? asset('storage/' . $payment->payment_proof) : null,
                    'formatted_date' => $payment->created_at->format('d M Y H:i'),
                    'verified_at' => $payment->verified_at,
                    'formatted_verified_at' => $payment->verified_at ? $payment->verified_at->format('d M Y H:i') : null,
                    'verified_by' => $payment->verifiedBy ? [
                        'name' => $payment->verifiedBy->name,
                        'email' => $payment->verifiedBy->email
                    ] : null,
                    'booking' => [
                        'id' => $payment->booking->id,
                        'duration' => $payment->booking->duration,
                        'formatted_rental_period' => $payment->booking->start_date->format('d M Y') . ' - ' . $payment->booking->end_date->format('d M Y'),
                        'renter' => [
                            'name' => $payment->booking->renter->name,
                            'email' => $payment->booking->renter->email,
                            'phone' => $payment->booking->renter->phone ?? '-'
                        ],
                        'motor' => [
                            'brand' => $payment->booking->motor->brand,
                            'model' => $payment->booking->motor->model,
                            'type_cc' => $payment->booking->motor->type_cc,
                            'plate_number' => $payment->booking->motor->plate_number
                        ]
                    ]
                ]
            ]);
        } catch (\Exception $e) {
            Log::error("Error getting payment detail: " . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan saat mengambil data pembayaran.'], 500);
        }
    }

    /**
     * Delete motor by admin
     */
    public function deleteMotor($id)
    {
        try {
            $motor = Motor::with(['bookings', 'rentalRate', 'owner'])->findOrFail($id);
            
            // Check if motor has active bookings
            $activeBookings = $motor->bookings()
                ->whereIn('status', ['pending', 'confirmed', 'active'])
                ->count();
                
            if ($activeBookings > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Motor tidak dapat dihapus karena masih memiliki booking aktif.'
                ], 400);
            }
            
            // Check if motor has completed bookings (for data integrity)
            $completedBookings = $motor->bookings()
                ->where('status', 'completed')
                ->count();
                
            if ($completedBookings > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Motor tidak dapat dihapus karena memiliki riwayat booking yang sudah selesai. Hal ini diperlukan untuk menjaga integritas data keuangan.'
                ], 400);
            }
            
            // Store motor info for notification
            $motorInfo = [
                'owner_id' => $motor->owner_id,
                'brand' => $motor->brand,
                'model' => $motor->model,
                'license_plate' => $motor->license_plate
            ];
            
            // Delete related files if exist
            if ($motor->photo) {
                Storage::disk('public')->delete($motor->photo);
            }
            if ($motor->document) {
                Storage::disk('public')->delete($motor->document);
            }
            
            // Delete related records (with foreign key constraints)
            // Delete payments first (they reference bookings)
            DB::table('payments')->whereIn('booking_id', $motor->bookings->pluck('id'))->delete();
            
            // Delete bookings
            $motor->bookings()->delete();
            
            // Delete rental rate
            if ($motor->rentalRate) {
                $motor->rentalRate->delete();
            }
            
            // Delete motor
            $motor->delete();
            
            // Create notification for motor owner
            Notification::create([
                'user_id' => $motorInfo['owner_id'],
                'title' => 'Motor Dihapus oleh Admin',
                'message' => "Motor {$motorInfo['brand']} {$motorInfo['model']} dengan plat nomor {$motorInfo['license_plate']} telah dihapus oleh admin.",
                'type' => 'motor_deleted',
                'is_read' => false
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Motor berhasil dihapus!'
            ]);
            
        } catch (\Exception $e) {
            Log::error("Error deleting motor: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus motor: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update motor status berdasarkan booking realtime
     */
    public function updateMotorStatusRealtime()
    {
        try {
            // Jalankan command update motor status
            Artisan::call('motor:update-status');
            
            $output = Artisan::output();
            
            return response()->json([
                'success' => true,
                'message' => 'Status motor berhasil diperbarui',
                'output' => $output
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get realtime motor status
     */
    public function getMotorStatusRealtime()
    {
        try {
            $motors = \App\Models\Motor::with(['bookings' => function($query) {
                $query->where('status', 'confirmed')
                      ->where('start_date', '<=', now()->format('Y-m-d'))
                      ->where('end_date', '>=', now()->format('Y-m-d'))
                      ->with('renter');
            }])->get();

            $motorStatus = $motors->map(function($motor) {
                $currentBooking = $motor->getCurrentBooking();
                return [
                    'id' => $motor->id,
                    'brand' => $motor->brand,
                    'type_cc' => $motor->type_cc,
                    'plate_number' => $motor->plate_number,
                    'database_status' => $motor->status,
                    'realtime_status' => $motor->getCurrentStatus(),
                    'is_currently_rented' => $motor->isCurrentlyRented(),
                    'current_booking' => $currentBooking ? [
                        'id' => $currentBooking->id,
                        'renter_name' => $currentBooking->renter->name,
                        'start_date' => $currentBooking->start_date,
                        'end_date' => $currentBooking->end_date,
                        'status' => $currentBooking->status
                    ] : null
                ];
            });

            return response()->json([
                'success' => true,
                'motors' => $motorStatus,
                'timestamp' => now()->format('Y-m-d H:i:s')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Export bookings to PDF
     */
    public function exportBookingsPdf(Request $request)
    {
        // Base query for bookings
        $query = Booking::with(['renter', 'motor.owner']);

        // Apply filters (sama seperti di method bookings)
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                  ->orWhereHas('renter', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Date filters
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        // Period filters
        if ($request->filled('period')) {
            switch ($request->period) {
                case 'today':
                    $query->whereDate('created_at', today());
                    break;
                case 'week':
                    $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                    break;
                case 'month':
                    $query->whereMonth('created_at', now()->month)
                          ->whereYear('created_at', now()->year);
                    break;
            }
        }

        // Get all bookings for PDF (without pagination)
        $bookings = $query->orderBy('created_at', 'desc')->get();

        // Calculate summary
        $totalBookings = $bookings->count();
        $pendingBookings = $bookings->where('status', 'pending')->count();
        $confirmedBookings = $bookings->where('status', 'confirmed')->count();
        $activeBookings = $bookings->where('status', 'active')->count();
        $completedBookings = $bookings->where('status', 'completed')->count();
        $cancelledBookings = $bookings->where('status', 'cancelled')->count();

        // Generate filter description
        $filterDescription = [];
        if ($request->filled('status')) {
            $filterDescription[] = 'Status: ' . ucfirst($request->status);
        }
        if ($request->filled('period')) {
            $filterDescription[] = 'Periode: ' . ucfirst($request->period);
        }
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $filterDescription[] = 'Tanggal: ' . $request->start_date . ' s/d ' . $request->end_date;
        }

        $filterText = !empty($filterDescription) ? implode(', ', $filterDescription) : 'Semua Data';

        $summary = [
            'total_bookings' => $totalBookings,
            'pending_bookings' => $pendingBookings,
            'confirmed_bookings' => $confirmedBookings,
            'active_bookings' => $activeBookings,
            'completed_bookings' => $completedBookings,
            'cancelled_bookings' => $cancelledBookings,
            'filter_text' => $filterText
        ];

        // Generate PDF
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.bookings-pdf', compact(
            'bookings', 
            'summary'
        ));

        // Set paper size dan orientation
        $pdf->setPaper('A4', 'landscape'); // landscape untuk tabel yang lebar

        // Generate filename
        $filename = 'Laporan_Bookings_' . date('Y-m-d_H-i-s') . '.pdf';

        // Download PDF
        return $pdf->download($filename);
    }
}