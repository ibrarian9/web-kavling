<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Invoice Setoran Cicilan Pembeli</title>
    <style>
        @page {
            margin: 28pt 32pt;
        }
        body {
            font-family: Helvetica, Arial, sans-serif;
            font-size: 10pt;
            color: #1e293b;
            line-height: 1.4;
        }
        .header {
            border-bottom: 2.5px solid #2563eb;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }
        .company-name {
            font-size: 16pt;
            font-weight: bold;
            color: #0f172a;
            letter-spacing: 0.5px;
        }
        .invoice-title {
            font-size: 13pt;
            font-weight: bold;
            color: #2563eb;
            text-transform: uppercase;
            margin-top: 3px;
        }
        .meta-table {
            width: 100%;
            margin-bottom: 15px;
        }
        .meta-table td {
            font-size: 9.5pt;
            padding: 3px 0;
            vertical-align: top;
        }
        .box-info {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 12px;
            margin-bottom: 15px;
        }
        .table-detail {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            margin-bottom: 15px;
        }
        .table-detail th {
            background-color: #f1f5f9;
            color: #334155;
            font-size: 8.5pt;
            font-weight: bold;
            text-transform: uppercase;
            padding: 8px;
            border-top: 1px solid #cbd5e1;
            border-bottom: 1px solid #cbd5e1;
            text-align: left;
        }
        .table-detail td {
            padding: 9px 8px;
            border-bottom: 1px solid #f1f5f9;
            font-size: 9pt;
        }
        .font-mono {
            font-family: 'Courier New', Courier, monospace;
            font-weight: bold;
        }
        .amount-box {
            background-color: #eff6ff;
            border: 1px solid #bfdbfe;
            border-radius: 8px;
            padding: 10px 14px;
            margin-top: 10px;
            margin-bottom: 20px;
        }
        .qr-section {
            margin-top: 35px;
            text-align: center;
        }
    </style>
</head>
<body>

    <!-- Header -->
    <div class="header">
        <table style="width: 100%;">
            <tr>
                <td>
                    <div class="company-name">MANAJEMEN PROPERTI & KAVLING</div>
                    <div class="invoice-title">Invoice Setoran Cicilan Pembeli</div>
                </td>
                <td style="text-align: right; font-size: 8.5pt; color: #64748b;">
                    No. Ref: <strong class="font-mono text-slate-800">{{ substr($payment->uuid, 0, 8) }}</strong><br>
                    Tanggal Cetak: {{ now()->translatedFormat('d F Y H:i') }} WIB
                </td>
            </tr>
        </table>
    </div>

    <!-- Informasi Pembeli & Unit -->
    <div class="box-info">
        <table class="meta-table" style="margin-bottom: 0;">
            <tr>
                <td style="width: 20%; font-weight: bold; color: #475569;">Nama Pembeli:</td>
                <td style="width: 30%; font-weight: bold; color: #0f172a;">
                    {{ $installment->buyer_name }}
                </td>
                <td style="width: 20%; font-weight: bold; color: #475569;">Kode Unit & Tipe:</td>
                <td style="width: 30%; font-weight: bold; color: #2563eb;">
                    Unit {{ $unit->code }} ({{ ucfirst($unit->category) }} - {{ $unit->type }})
                </td>
            </tr>
            <tr>
                <td style="font-weight: bold; color: #475569;">Proyek:</td>
                <td>{{ $project->name }}</td>
                <td style="font-weight: bold; color: #475569;">Tanggal Pembayaran:</td>
                <td class="font-mono">{{ $payment->payment_date ? $payment->payment_date->format('d F Y') : '-' }}</td>
            </tr>
            <tr>
                <td style="font-weight: bold; color: #475569;">Metode Bayar:</td>
                <td>{{ $payment->payment_method }}</td>
                <td style="font-weight: bold; color: #475569;">Petugas Pencatat:</td>
                <td>{{ $payment->creator->name ?? 'Finance Administrator' }}</td>
            </tr>
        </table>
    </div>

    <!-- Table Rincian Setoran -->
    <table class="table-detail">
        <thead>
            <tr>
                <th style="width: 45%;">Deskripsi Transaksi</th>
                <th style="width: 20%;">Tanggal Transaksi</th>
                <th style="width: 35%; text-align: right;">Nominal Setoran (Rp)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="font-weight: bold;">
                    Pembayaran Setoran Cicilan Unit {{ $unit->code }}
                    @if($payment->notes)
                        <br><span style="font-size: 8pt; color: #64748b; font-weight: normal;">Catatan: {{ $payment->notes }}</span>
                    @endif
                </td>
                <td class="font-mono">{{ $payment->payment_date ? $payment->payment_date->format('d/m/Y') : '-' }}</td>
                <td style="text-align: right; font-size: 11pt; color: #16a34a;" class="font-mono">
                    Rp {{ number_format($payment->amount_paid, 0, ',', '.') }}
                </td>
            </tr>
        </tbody>
    </table>

    <!-- Amount Box (Terbilang & Ringkasan Piutang) -->
    <div class="amount-box">
        <table style="width: 100%;">
            <tr>
                <td>
                    <span style="font-size: 8pt; color: #64748b; font-weight: bold; uppercase;">Terbilang Nominal:</span><br>
                    <strong style="font-size: 10pt; color: #1e3a8a;"># {{ $terbilang }} #</strong>
                </td>
            </tr>
        </table>
    </div>

    <!-- Ringkasan Status Skema Cicilan Unit -->
    <div style="border: 1px solid #e2e8f0; border-radius: 8px; padding: 10px 14px; background-color: #fafafa;">
        <table style="width: 100%; font-size: 8.5pt;">
            <tr>
                <td style="width: 33%;">
                    <span style="color: #64748b;">Total Harga Deal Unit:</span><br>
                    <strong class="font-mono" style="font-size: 9.5pt;">Rp {{ number_format($installment->total_price, 0, ',', '.') }}</strong>
                </td>
                <td style="width: 33%; text-align: center;">
                    <span style="color: #64748b;">Total Akumulasi Terbayar:</span><br>
                    <strong class="font-mono" style="font-size: 9.5pt; color: #16a34a;">Rp {{ number_format($totalPaid, 0, ',', '.') }}</strong>
                </td>
                <td style="width: 34%; text-align: right;">
                    <span style="color: #64748b;">Sisa Uang Belum Terbayar:</span><br>
                    <strong class="font-mono" style="font-size: 9.5pt; color: #b45309;">Rp {{ number_format($remainingUnpaid, 0, ',', '.') }}</strong>
                </td>
            </tr>
        </table>
    </div>

    <!-- Centered QR Code (Tanpa TTD Manual) -->
    <div class="qr-section">
        @if(isset($qrCodeUrl))
            <img src="{{ $qrCodeUrl }}" style="width: 90px; height: 90px; margin: 0 auto; display: block;" alt="Scan QR Verification">
        @endif
        <div style="font-size: 8pt; font-weight: bold; color: #475569; margin-top: 6px; letter-spacing: 0.5px; text-transform: uppercase;">
            SCAN QR CODE VERIFIKASI KEABSAHAN INVOICE
        </div>
    </div>

</body>
</html>
