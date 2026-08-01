<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Resi Booking Fee {{ $booking->buyer_name }}</title>
    <style>
        * {
            box-sizing: border-box;
        }
        @page {
            size: A5 landscape;
            margin: 8mm 10mm;
        }
        html, body {
            margin: 0;
            padding: 0;
            height: 100%;
        }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 10px;
            color: #1e293b;
            line-height: 1.3;
            page-break-inside: avoid;
        }
        table, tr, td, div {
            page-break-inside: avoid;
        }
        .header {
            border-bottom: 2px solid #059669;
            padding-bottom: 5px;
            margin-bottom: 8px;
        }
        .brand-title {
            font-size: 14px;
            font-weight: bold;
            color: #065f46;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }
        .brand-sub {
            font-size: 8.5px;
            color: #64748b;
        }
        .doc-title {
            text-align: center;
            font-size: 12px;
            font-weight: bold;
            color: #0f172a;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            background: #f0fdf4;
            padding: 4px;
            border: 1px solid #a7f3d0;
            border-radius: 5px;
        }
        .table-data {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
        }
        .table-data td {
            padding: 3px 6px;
            vertical-align: top;
        }
        .label {
            width: 30%;
            font-weight: bold;
            color: #475569;
        }
        .colon {
            width: 2%;
        }
        .value {
            width: 68%;
            color: #0f172a;
        }
        .amount-box {
            background-color: #ecfdf5;
            border: 1px dashed #059669;
            padding: 6px 10px;
            border-radius: 6px;
            margin-bottom: 8px;
        }
        .amount-num {
            font-size: 14px;
            font-weight: bold;
            color: #047857;
            font-family: monospace;
        }
        .terbilang {
            font-style: italic;
            font-size: 9px;
            color: #065f46;
            margin-top: 2px;
        }
        .footer-section {
            width: 100%;
            margin-top: 6px;
        }
        .footer-note {
            font-size: 8.5px;
            color: #64748b;
            width: 65%;
            float: left;
        }
        .qr-section {
            width: 30%;
            float: right;
            text-align: center;
        }
        .qr-img {
            width: 58px;
            height: 58px;
            border: 1px solid #cbd5e1;
            padding: 2px;
            background: #fff;
            border-radius: 4px;
        }
        .qr-label {
            font-size: 8px;
            color: #475569;
            margin-top: 2px;
            font-weight: bold;
        }
        .clear {
            clear: both;
        }
    </style>
</head>
<body>

    <!-- Header Section -->
    <div class="header">
        <table style="width: 100%;">
            <tr>
                <td style="width: 65%;">
                    <div class="brand-title">PT. ATLANTIK PERKASA ABADI</div>
                    <div class="brand-sub">Pengembangan Kawasan Kavling & Perumahan Modern</div>
                    <div class="brand-sub">Kota Pekanbaru, Provinsi Riau | Telp/WA: 0813-3484-0193</div>
                </td>
                <td style="width: 35%; text-align: right;">
                    <div style="font-size: 10px; font-weight: bold; color: #047857;">INVOICE PEMBAYARAN RESMI</div>
                    <div style="font-size: 9px; font-family: monospace; color: #475569;">
                        No: INV-BKG/{{ $booking->created_at ? $booking->created_at->format('Y/m') : date('Y/m') }}/{{ str_pad($booking->id, 3, '0', STR_PAD_LEFT) }}
                    </div>
                    <div style="font-size: 9px; color: #64748b;">Tgl: {{ $booking->booking_date ? $booking->booking_date->format('d/m/Y') : date('d/m/Y') }}</div>
                </td>
            </tr>
        </table>
    </div>

    <!-- Title -->
    <div class="doc-title">INVOICE PEMBAYARAN</div>

    <!-- Transaction Data Table -->
    <table class="table-data">
        <tr>
            <td class="label">Telah Diterima Dari</td>
            <td class="colon">:</td>
            <td class="value"><strong>{{ $booking->buyer_name }}</strong> (Telp: {{ $booking->buyer_phone }})</td>
        </tr>
        <tr>
            <td class="label">Untuk Pembayaran</td>
            <td class="colon">:</td>
            <td class="value">
                Tanda Jadi / Booking Fee Pemesanan Unit Kavling Properti
            </td>
        </tr>
        <tr>
            <td class="label">Proyek / Kawasan</td>
            <td class="colon">:</td>
            <td class="value"><strong>{{ $project->name ?? 'Proyek Properti' }}</strong> ({{ $project->location ?? 'Pekanbaru' }})</td>
        </tr>
        @if($unit)
            <tr>
                <td class="label">Unit Spesifik</td>
                <td class="colon">:</td>
                <td class="value"><strong>Kode Unit {{ $unit->code }}</strong> — Tipe {{ ucfirst($unit->category ?? $unit->type) }} (Luas: {{ number_format($unit->land_area, 0, ',', '.') }} m²)</td>
            </tr>
        @endif
        <tr>
            <td class="label">Status Verifikasi Kas</td>
            <td class="colon">:</td>
            <td class="value">
                @if($booking->status === 'converted')
                    <span style="color: #047857; font-weight: bold;">[ DIVERIFIKASI ]</span> - Dicatat oleh Finance
                @elseif($booking->status === 'cancelled')
                    <span style="color: #b91c1c; font-weight: bold;">[ DITOLAK ]</span> - Ditolak oleh Manajemen
                @elseif($booking->status === 'refunded')
                    <span style="color: #d97706; font-weight: bold;">[ DIREFUND ]</span> - DP Telah Dikembalikan
                @else
                    <span style="color: #2563eb; font-weight: bold;">[ MENUNGGU ACC FINANCE ]</span> - Menunggu Verifikasi
                @endif
            </td>
        </tr>
    </table>

    <!-- Amount Box -->
    <div class="amount-box">
        <div style="font-size: 9px; color: #047857; font-weight: bold; text-transform: uppercase;">Jumlah Uang:</div>
        <div class="amount-num">Rp {{ number_format($booking->booking_amount, 0, ',', '.') }}</div>
        <div class="terbilang">"{{ $terbilang }}"</div>
    </div>

    <!-- Footer & QR Code Section -->
    <div class="footer-section">
        <div class="footer-note">
            <strong>Catatan & Ketentuan Pembayaran:</strong>
            <ul style="margin: 3px 0 0 12px; padding: 0;">
                <li>Invoice ini merupakan bukti sah pembayaran Tanda Jadi / Booking Fee unit kavling.</li>
                <li>Pemesanan unit berlaku dan terkunci dalam sistem selama masa aktif booking.</li>
                <li>Dokumen ini diterbitkan secara otomatis dan terverifikasi digital.</li>
            </ul>
        </div>

        <div class="qr-section">
            <img src="{{ $qrCodeUrl }}" class="qr-img" alt="QR Code Keabsahan">
            <div class="qr-label">Scan QR Cek Keabsahan Invoice</div>
            <div style="font-size: 7px; color: #94a3b8; margin-top: 2px;">Terverifikasi Asli di Sistem Website</div>
        </div>
        <div class="clear"></div>
    </div>

</body>
</html>
