<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Operasional Belanja & Gaji Worker</title>
    <style>
        @page {
            margin: 1.5cm;
            size: A4 portrait;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 10.5px;
            color: #1e293b;
            line-height: 1.5;
        }
        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            border-b: 2px solid #0f172a;
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
            font-size: 13px;
            font-weight: bold;
            color: #10b981;
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
            font-size: 10.5px;
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
        .expenses-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .expenses-table th {
            background-color: #0f172a;
            color: #ffffff;
            font-size: 9.5px;
            font-weight: bold;
            text-transform: uppercase;
            padding: 8px 10px;
            border: 1px solid #0f172a;
        }
        .expenses-table td {
            padding: 7px 9px;
            border-bottom: 1px solid #e2e8f0;
            font-size: 10px;
        }
        .expenses-table tr:nth-child(even) {
            background-color: #f8fafc;
        }
        .badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 8.5px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .badge-material {
            background-color: #e0f2fe;
            color: #0369a1;
        }
        .badge-salary {
            background-color: #fef3c7;
            color: #92400e;
        }
        .total-box {
            background-color: #ecfdf5;
            border: 1px solid #a7f3d0;
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
            font-size: 10.5px;
        }
        .qr-section {
            text-align: center;
            margin-top: 25px;
            padding-top: 15px;
            border-t: 1px dashed #cbd5e1;
        }
        .qr-img {
            width: 95px;
            height: 95px;
            margin: 0 auto 6px auto;
            display: block;
        }
        .qr-text {
            font-size: 9.5px;
            color: #64748b;
            font-weight: bold;
        }
        .qr-subtext {
            font-size: 8.5px;
            color: #94a3b8;
        }
    </style>
</head>
<body>

    <table class="header-table">
        <tr>
            <td>
                <div class="brand-title">KAVLING & PROPERTI RESMI</div>
                <div class="brand-subtitle">Laporan Operasional Belanja Material & Penggajian Worker Lapangan</div>
            </td>
            <td class="doc-type">
                REKAP BELANJA & GAJI
            </td>
        </tr>
    </table>

    <div class="meta-box">
        <table class="meta-table">
            <tr>
                <td class="meta-label">Filter Proyek:</td>
                <td class="meta-value">{{ $projectInfo }}</td>
                <td class="meta-label">Filter Unit:</td>
                <td class="meta-value">{{ $unitInfo }}</td>
            </tr>
            <tr>
                <td class="meta-label">Filter Tipe:</td>
                <td class="meta-value" style="text-transform: uppercase;">{{ $categoryFilter === 'all' ? 'Semua Tipe Transaksi' : ($categoryFilter === 'salary' ? 'Gaji Worker Saja' : 'Belanja Barang Saja') }}</td>
                <td class="meta-label">Periode Data:</td>
                <td class="meta-value">{{ $periodInfo ?? 'Semua Periode' }}</td>
            </tr>
            <tr>
                <td class="meta-label">Tanggal Cetak:</td>
                <td class="meta-value">{{ format_id_datetime(now()) }}</td>
                <td class="meta-label">Status Dokumen:</td>
                <td class="meta-value" style="color: #10b981;">RESMI & TEREKAP SISTEM</td>
            </tr>
        </table>
    </div>

    <table class="expenses-table">
        <thead>
            <tr>
                <th style="width: 5%; text-align: center;">#</th>
                <th style="width: 12%;">Tanggal</th>
                <th style="width: 15%;">Tipe</th>
                <th style="width: 20%;">Proyek & Unit</th>
                <th style="width: 33%;">Uraian Barang / Gaji Worker</th>
                <th style="width: 15%; text-align: right;">Total Biaya (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @forelse($expenses as $index => $item)
                <tr>
                    <td style="text-align: center; color: #64748b;">{{ $index + 1 }}</td>
                    <td style="font-family: monospace;">{{ format_id_date($item->date) }}</td>
                    <td>
                        @if($item->type === 'material')
                            <span class="badge badge-material">Belanja Barang</span>
                        @else
                            <span class="badge badge-salary">Gaji Worker</span>
                        @endif
                    </td>
                    <td>
                        <strong style="color: #0f172a;">{{ $item->project_name }}</strong>
                        <div style="font-size: 8.5px; color: #059669;">Unit: {{ $item->unit_code }}</div>
                    </td>
                    <td style="font-weight: bold; color: #1e293b;">{{ $item->title }}</td>
                    <td style="text-align: right; font-family: monospace; font-weight: bold; color: #0f172a;">
                        Rp {{ number_format($item->amount, 0, ',', '.') }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center; color: #94a3b8; padding: 20px;">
                        Belum ada riwayat transaksi pengeluaran belanja material atau penggajian worker.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="total-box">
        <table class="total-table">
            <tr>
                <td style="color: #475569; font-weight: bold;">Total Akumulasi Gaji Worker:</td>
                <td style="text-align: right; font-family: monospace; font-weight: bold;">Rp {{ number_format($totalSalary, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td style="color: #475569; font-weight: bold;">Total Akumulasi Belanja Material:</td>
                <td style="text-align: right; font-family: monospace; font-weight: bold;">Rp {{ number_format($totalMaterial, 0, ',', '.') }}</td>
            </tr>
            <tr style="border-top: 1px solid #a7f3d0;">
                <td style="color: #047857; font-size: 12px; font-weight: bold; padding-top: 6px;">TOTAL KESELURUHAN PENGELUARAN LAPANGAN:</td>
                <td style="text-align: right; font-family: monospace; font-size: 13px; font-weight: bold; color: #047857; padding-top: 6px;">
                    Rp {{ number_format($totalExpenses, 0, ',', '.') }}
                </td>
            </tr>
        </table>
    </div>

    <!-- Centered QR Code Official Verification -->
    <div class="qr-section">
        <img src="{{ $qrCodeUrl }}" class="qr-img" alt="QR Code Verifikasi Resmi">
        <div class="qr-text">DOKUMEN RESMI TERVERIFIKASI SISTEM KAVLING & PROPERTI</div>
        <div class="qr-subtext">Scan QR Code di atas untuk memverifikasi keabsahan laporan belanja & gaji worker secara publik.</div>
    </div>

</body>
</html>
