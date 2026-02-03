<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ $payment->id }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
            padding: 20px;
        }
        .invoice-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .invoice-header h1 {
            font-size: 28px;
            margin-bottom: 5px;
        }
        .invoice-header h4 {
            font-size: 18px;
            margin-top: 10px;
        }
        .company-info {
            text-align: right;
            font-size: 14px;
        }
        .status-badge {
            text-align: center;
            margin: 20px 0;
        }
        .badge {
            display: inline-block;
            padding: 10px 30px;
            background-color: #28a745;
            color: white;
            border-radius: 5px;
            font-size: 16px;
            font-weight: bold;
        }
        .section {
            margin-bottom: 20px;
            padding: 15px;
            background-color: #f8f9fa;
            border-left: 4px solid #667eea;
        }
        .section h3 {
            color: #667eea;
            font-size: 14px;
            margin-bottom: 10px;
        }
        .info-row {
            margin-bottom: 8px;
        }
        .info-label {
            font-weight: bold;
            display: inline-block;
            width: 150px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }
        table th {
            background-color: #667eea;
            color: white;
            padding: 10px;
            text-align: left;
        }
        table td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }
        .total-section {
            text-align: right;
            margin-top: 20px;
            padding: 15px;
            background-color: #f8f9fa;
        }
        .total-amount {
            font-size: 24px;
            font-weight: bold;
            color: #28a745;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        .row {
            display: table;
            width: 100%;
        }
        .col-6 {
            display: table-cell;
            width: 50%;
            vertical-align: top;
        }
    </style>
</head>
<body>
    <!-- Invoice Header -->
    <div class="invoice-header">
        <div class="row">
            <div class="col-6">
                <h1>INVOICE</h1>
                <h4>#{{ $payment->id }}</h4>
            </div>
            <div class="col-6 company-info">
                <h2 style="margin-bottom: 5px;">FannRental</h2>
                <p>Sistem Rental Motor</p>
                <p>{{ now()->format('d F Y') }}</p>
            </div>
        </div>
    </div>
    
    <!-- Status Badge -->
    <div class="status-badge">
        <span class="badge">✓ LUNAS</span>
    </div>
    
    <!-- Customer & Owner Info -->
    <div class="row">
        <div class="col-6" style="padding-right: 10px;">
            <div class="section">
                <h3>Informasi Penyewa</h3>
                <div class="info-row">
                    <strong>{{ $payment->booking->renter->name }}</strong>
                </div>
                <div class="info-row">{{ $payment->booking->renter->email }}</div>
                <div class="info-row">{{ $payment->booking->renter->phone ?? '-' }}</div>
            </div>
        </div>
        <div class="col-6" style="padding-left: 10px;">
            <div class="section">
                <h3>Informasi Pemilik Motor</h3>
                <div class="info-row">
                    <strong>{{ $payment->booking->motor->owner->name }}</strong>
                </div>
                <div class="info-row">{{ $payment->booking->motor->owner->email }}</div>
                <div class="info-row">{{ $payment->booking->motor->owner->phone ?? '-' }}</div>
            </div>
        </div>
    </div>
    
    <!-- Booking Details -->
    <div class="section">
        <h3>Detail Booking</h3>
        <div class="info-row">
            <span class="info-label">Booking ID:</span>
            #{{ $payment->booking->id }}
        </div>
        <div class="info-row">
            <span class="info-label">Tanggal Booking:</span>
            {{ $payment->booking->created_at->format('d M Y H:i') }}
        </div>
        <div class="info-row">
            <span class="info-label">Tanggal Mulai Sewa:</span>
            {{ $payment->booking->start_date->format('d M Y') }}
        </div>
        <div class="info-row">
            <span class="info-label">Tanggal Selesai Sewa:</span>
            {{ $payment->booking->end_date->format('d M Y') }}
        </div>
        <div class="info-row">
            <span class="info-label">Durasi:</span>
            {{ $payment->booking->start_date->diffInDays($payment->booking->end_date) }} hari
        </div>
    </div>
    
    <!-- Motor Details -->
    <div class="section">
        <h3>Detail Motor</h3>
        <table>
            <thead>
                <tr>
                    <th>Motor</th>
                    <th>Tipe</th>
                    <th>Durasi</th>
                    <th>Harga/Hari</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $payment->booking->motor->brand }} {{ $payment->booking->motor->model }}</td>
                    <td>{{ $payment->booking->motor->type_cc }}</td>
                    <td>{{ $payment->booking->start_date->diffInDays($payment->booking->end_date) }} hari</td>
                    <td>Rp {{ number_format($payment->booking->price / $payment->booking->start_date->diffInDays($payment->booking->end_date), 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($payment->booking->price, 0, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>
    </div>
    
    <!-- Payment Details -->
    <div class="section">
        <h3>Detail Pembayaran</h3>
        <div class="info-row">
            <span class="info-label">Metode Pembayaran:</span>
            @if($payment->payment_method === 'dana')
                DANA
            @elseif($payment->payment_method === 'gopay')
                GoPay
            @elseif($payment->payment_method === 'shopeepay')
                ShopeePay
            @elseif($payment->payment_method === 'bank')
                Transfer Bank
            @else
                {{ ucfirst($payment->payment_method) }}
            @endif
        </div>
        <div class="info-row">
            <span class="info-label">Tanggal Pembayaran:</span>
            {{ $payment->paid_at ? $payment->paid_at->format('d M Y H:i') : '-' }}
        </div>
        <div class="info-row">
            <span class="info-label">Status:</span>
            LUNAS
        </div>
    </div>
    
    <!-- Total -->
    <div class="total-section">
        <div style="margin-bottom: 10px;">
            <span style="font-size: 16px;">Total Pembayaran:</span>
        </div>
        <div class="total-amount">
            Rp {{ number_format($payment->amount, 0, ',', '.') }}
        </div>
    </div>
    
    <!-- Footer -->
    <div class="footer">
        <p>Terima kasih telah menggunakan layanan FannRental</p>
        <p>Invoice ini digenerate secara otomatis pada {{ now()->format('d F Y H:i') }}</p>
        <p>Untuk pertanyaan, hubungi: support@fannrental.com</p>
    </div>
</body>
</html>
