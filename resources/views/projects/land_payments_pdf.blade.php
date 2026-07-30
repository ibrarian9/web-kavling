<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Rekapitulasi Pembayaran Lahan Proyek {{ $project->name }}</title>
    <style>
        @page {
            margin: 1.5cm;
            size: A4 portrait;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 11px;
            color: #1e293b;
            line-height: 1.5;
        }
        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            border-bottom: 2px solid #0f172a;
            padding-bottom: 10px;
        }
        .header-table td {
            vertical-align: middle;
        }
        .brand-title {
            font-size: 18px;
            font-weight: bold;
            color: #0f172a;
            letter-spacing: -0.5px;
            text-transform: uppercase;
        }
        .brand-subtitle {
            font-size: 10px;
            color: #64748b;
        }
        .doc-type {
            text-align: right;
            font-size: 14px;
            font-weight: bold;
            color: #7c3aed;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .meta-box {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 12px 15px;
            margin-bottom: 20px;
        }
        .meta-table {
            width: 100%;
            border-collapse: collapse;
        }
        .meta-table td {
            padding: 4px 6px;
            font-size: 11px;
        }
        .meta-label {
            color: #64748b;
            font-weight: bold;
            width: 25%;
        }
        .meta-value {
            color: #0f172a;
            font-weight: bold;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .data-table th {
            background-color: #581c87;
            color: #ffffff;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
            padding: 8px 10px;
            border: 1px solid #581c87;
        }
        .data-table td {
            padding: 8px 10px;
            border-bottom: 1px solid #e2e8f0;
            font-size: 10.5px;
        }
        .data-table tr:nth-child(even) {
            background-color: #faf5ff;
        }
        .total-box {
            background-color: #faf5ff;
            border: 1px solid #e9d5ff;
            border-radius: 8px;
            padding: 12px 15px;
            margin-bottom: 25px;
        }
        .total-table {
            width: 100%;
            border-collapse: collapse;
        }
        .total-table td {
            padding: 4px 6px;
            font-size: 11px;
        }
        .qr-section {
            text-align: center;
            margin-top: 25px;
            padding-top: 15px;
            border-top: 1px dashed #cbd5e1;
        }
        .qr-img {
            width: 90px;
            height: 90px;
            margin: 0 auto 6px auto;
            display: block;
        }
        .qr-text {
            font-size: 10px;
            color: #64748b;
            font-weight: bold;
        }
        .qr-subtext {
            font-size: 9px;
            color: #94a3b8;
        }
    </style>
</head>
<body>

    <table class="header-table">
        <tr>
            <td>
                <div class="brand-title">KAVLING & PROPERTI RESMI</div>
                <div class="brand-subtitle">Laporan Rekapitulasi Pembayaran Pembelian Lahan ke Penjual Tanah</div>
            </td>
            <td class="doc-type">
                REKAP BAYAR LAHAN
            </td>
        </tr>
    </table>

    <div class="meta-box">
        <table class="meta-table">
            <tr>
                <td class="meta-label">Nama Proyek:</td>
                <td class="meta-value">{{ $project->name }}</td>
                <td class="meta-label">Lokasi:</td>
                <td class="meta-value">{{ $project->location }}</td>
            </tr>
            <tr>
                <td class="meta-label">Harga Beli Lahan:</td>
                <td class="meta-value">Rp {{ number_format($project->total_project_price, 0, ',', '.') }}</td>
                <td class="meta-label">Luas Standar:</td>
                <td class="meta-value">{{ number_format($project->standard_land_area, 0, ',', '.') }} m²</td>
            </tr>
            <tr>
                <td class="meta-label">Dicetak Oleh:</td>
                <td class="meta-value">{{ $printedBy }}</td>
                <td class="meta-label">Tanggal Cetak:</td>
                <td class="meta-value">{{ $printedAt }}</td>
            </tr>
        </table>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 5%; text-align: center;">#</th>
                <th style="width: 15%;">Tanggal</th>
                <th style="width: 20%;">Metode</th>
                <th style="width: 35%;">Catatan / Keterangan</th>
                <th style="width: 25%; text-align: right;">Jumlah Dibayar (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @forelse($payments as $index => $pay)
                <tr>
                    <td style="text-align: center; color: #64748b;">{{ $index + 1 }}</td>
                    <td style="font-family: monospace; font-weight: bold;">{{ $pay->payment_date ? $pay->payment_date->format('d/m/Y') : '-' }}</td>
                    <td>{{ $pay->payment_method }}</td>
                    <td>{{ $pay->notes ?: '-' }}</td>
                    <td style="text-align: right; font-family: monospace; font-weight: bold; color: #7e22ce;">
                        Rp {{ number_format($pay->amount_paid, 0, ',', '.') }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center; color: #94a3b8; padding: 20px;">
                        Belum ada riwayat pembayaran lahan tercatat.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="total-box">
        <table class="total-table">
            <tr>
                <td style="color: #475569; font-weight: bold;">Total Harga Beli Lahan:</td>
                <td style="text-align: right; font-family: monospace; font-weight: bold;">Rp {{ number_format($project->total_project_price, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td style="color: #15803d; font-weight: bold;">Total Sudah Terbayar:</td>
                <td style="text-align: right; font-family: monospace; font-weight: bold; color: #15803d;">Rp {{ number_format($totalPaid, 0, ',', '.') }}</td>
            </tr>
            <tr style="border-top: 1px solid #e9d5ff;">
                <td style="color: #7e22ce; font-size: 12px; font-weight: bold; padding-top: 6px;">SISA HUTANG LAHAN:</td>
                <td style="text-align: right; font-family: monospace; font-size: 13px; font-weight: bold; color: #7e22ce; padding-top: 6px;">
                    Rp {{ number_format($remaining, 0, ',', '.') }}
                </td>
            </tr>
        </table>
    </div>

    <div class="qr-section">
        <img src="{{ $qrCodeUrl }}" class="qr-img" alt="QR Code Verifikasi">
        <div class="qr-text">DOKUMEN RESMI TERVERIFIKASI SISTEM KAVLING & PROPERTI</div>
        <div class="qr-subtext">Scan QR Code di atas untuk memverifikasi keabsahan laporan rekapitulasi pembayaran lahan secara publik.</div>
    </div>

</body>
</html>
