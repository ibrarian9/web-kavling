<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Invoice Manual - {{ $invoice->invoice_number }}</title>
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
            border-bottom: 2.5px solid #0d9488;
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
            color: #0d9488;
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
            background-color: #f0fdf4;
            border: 1px solid #bbf7d0;
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
            background-color: #ccfbf1;
            border: 1px solid #99f6e4;
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
                    <div class="company-name">SISTEM KAVLING & PROPERTI</div>
                    <div class="invoice-title">
                        Invoice {{ $invoice->type === 'masuk' ? 'Tagihan / Penerimaan' : 'Pengeluaran / Pembayaran' }}
                    </div>
                </td>
                <td style="text-align: right; font-size: 8.5pt; color: #64748b;">
                    No. Invoice: <strong class="font-mono text-slate-800">{{ $invoice->invoice_number }}</strong><br>
                    Status: <strong style="color: {{ $invoice->status === 'lunas' ? '#16a34a' : '#d97706' }}; uppercase;">{{ strtoupper($invoice->status) }}</strong><br>
                    Tgl Cetak: {{ now()->translatedFormat('d F Y H:i') }} WIB
                </td>
            </tr>
        </table>
    </div>

    <!-- Informasi Penerima / Klien & Transaksi -->
    <div class="box-info">
        <table class="meta-table" style="margin-bottom: 0;">
            <tr>
                <td style="width: 20%; font-weight: bold; color: #475569;">Penerima:</td>
                <td style="width: 30%; font-weight: bold; color: #0f172a;">
                    {{ $invoice->recipient_name }}
                    @if($invoice->recipient_phone)
                        <br><span style="font-size: 8pt; color: #64748b; font-weight: normal;">Telp: {{ $invoice->recipient_phone }}</span>
                    @endif
                </td>
                <td style="width: 20%; font-weight: bold; color: #475569;">Tipe & Kategori:</td>
                <td style="width: 30%; font-weight: bold; color: #0d9488;">
                    {{ $invoice->type === 'masuk' ? 'Kas Masuk (Pemasukan)' : 'Kas Keluar (Pengeluaran)' }}
                    <br><span style="font-size: 8pt; color: #475569; font-weight: normal; text-transform: capitalize;">({{ str_replace('_', ' ', $invoice->category) }})</span>
                </td>
            </tr>
            <tr>
                <td style="font-weight: bold; color: #475569;">Proyek & Unit:</td>
                <td>
                    {{ $invoice->project->name ?? 'Konsolidasi Global' }}
                    {{ $invoice->unit ? ' - Unit ' . $invoice->unit->code : '' }}
                </td>
                <td style="font-weight: bold; color: #475569;">Tanggal Invoice:</td>
                <td class="font-mono">{{ $invoice->invoice_date ? $invoice->invoice_date->format('d F Y') : '-' }}</td>
            </tr>
            <tr>
                <td style="font-weight: bold; color: #475569;">Metode Pembayaran:</td>
                <td>{{ $invoice->payment_method }}</td>
                <td style="font-weight: bold; color: #475569;">Dibuat Oleh:</td>
                <td>{{ $invoice->creator->name ?? 'Finance Administrator' }}</td>
            </tr>
        </table>
    </div>

    <!-- Table Rincian Invoice -->
    <table class="table-detail">
        <thead>
            <tr>
                <th style="width: 50%;">Rincian Pekerjaan & Tagihan</th>
                <th style="width: 20%;">Tipe Mutasi</th>
                <th style="width: 30%; text-align: right;">Nominal (Rp)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="font-weight: bold;">
                    {{ $invoice->description ?: 'Pencatatan Invoice Manual Keuangan Properti' }}
                </td>
                <td style="text-transform: uppercase; font-weight: bold; color: {{ $invoice->type === 'masuk' ? '#16a34a' : '#dc2626' }};">
                    {{ $invoice->type === 'masuk' ? 'Kas Masuk' : 'Kas Keluar' }}
                </td>
                <td style="text-align: right; font-size: 11pt; color: {{ $invoice->type === 'masuk' ? '#16a34a' : '#dc2626' }};" class="font-mono">
                    {{ $invoice->type === 'masuk' ? '+' : '-' }}Rp {{ number_format($invoice->amount, 0, ',', '.') }}
                </td>
            </tr>
        </tbody>
    </table>

    <!-- Amount Box (Terbilang Rupiah) -->
    <div class="amount-box">
        <table style="width: 100%;">
            <tr>
                <td>
                    <span style="font-size: 8pt; color: #0f766e; font-weight: bold; uppercase;">Terbilang Nominal:</span><br>
                    <strong style="font-size: 10.5pt; color: #115e59;"># {{ $terbilang }} #</strong>
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
