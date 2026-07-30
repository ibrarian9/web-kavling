<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Rincian Biaya Pengeluaran Unit {{ $unit->code }}</title>
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
            font-size: 14px;
            font-weight: bold;
            color: #0d9488;
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
            width: 30%;
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
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
            padding: 8px 10px;
            border: 1px solid #0f172a;
        }
        .expenses-table td {
            padding: 8px 10px;
            border-bottom: 1px solid #e2e8f0;
            font-size: 10.5px;
        }
        .expenses-table tr:nth-child(even) {
            background-color: #f8fafc;
        }
        .badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .badge-material {
            background-color: #fef3c7;
            color: #92400e;
        }
        .badge-salary {
            background-color: #d1fae5;
            color: #065f46;
        }
        .total-box {
            background-color: #f0fdf4;
            border: 1px solid #bbf7d0;
            border-radius: 8px;
            padding: 12px 15px;
            margin-bottom: 30px;
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
            margin-top: 30px;
            padding-top: 15px;
            border-t: 1px dashed #cbd5e1;
        }
        .qr-img {
            width: 100px;
            height: 100px;
            margin: 0 auto 8px auto;
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
                <div class="brand-subtitle">Laporan Rekapitulasi Pengeluaran & Belanja Per-Unit</div>
            </td>
            <td class="doc-type">
                RINCIAN BIAYA UNIT
            </td>
        </tr>
    </table>

    <div class="meta-box">
        <table class="meta-table">
            <tr>
                <td class="meta-label">Kode Unit:</td>
                <td class="meta-value">{{ $unit->code }} ({{ ucfirst($unit->category ?? $unit->type) }})</td>
                <td class="meta-label">Proyek:</td>
                <td class="meta-value">{{ $project->name }}</td>
            </tr>
            <tr>
                <td class="meta-label">Luas Tanah:</td>
                <td class="meta-value">{{ number_format($unit->land_area, 0, ',', '.') }} m²</td>
                <td class="meta-label">Harga Dasar / HPP:</td>
                <td class="meta-value">Rp {{ number_format($unit->hpp, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td class="meta-label">Tanggal Cetak:</td>
                <td class="meta-value">{{ date('d F Y') }}</td>
                <td class="meta-label">Status Unit:</td>
                <td class="meta-value" style="text-transform: uppercase;">{{ str_replace('_', ' ', $unit->status) }}</td>
            </tr>
        </table>
    </div>

    <table class="expenses-table">
        <thead>
            <tr>
                <th style="width: 5%; text-align: center;">#</th>
                <th style="width: 15%;">Tanggal</th>
                <th style="width: 20%;">Jenis</th>
                <th style="width: 40%;">Uraian Pengeluaran / Belanja</th>
                <th style="width: 20%; text-align: right;">Nominal (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @forelse($combinedExpenses as $index => $exp)
                <tr>
                    <td style="text-align: center; color: #64748b;">{{ $index + 1 }}</td>
                    <td style="font-family: monospace;">{{ $exp->date ? $exp->date->format('d/m/Y') : '-' }}</td>
                    <td>
                        @if($exp->source_type === 'material')
                            <span class="badge badge-material">Belanja Material</span>
                        @else
                            <span class="badge badge-salary">Gaji Worker</span>
                        @endif
                    </td>
                    <td>{{ $exp->description }}</td>
                    <td style="text-align: right; font-family: monospace; font-weight: bold; color: #0f172a;">
                        Rp {{ number_format($exp->amount, 0, ',', '.') }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center; color: #94a3b8; padding: 20px;">
                        Belum ada riwayat pengeluaran atau belanja material tercatat untuk unit ini.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="total-box">
        <table class="total-table">
            <tr>
                <td style="color: #475569; font-weight: bold;">Subtotal Belanja Material:</td>
                <td style="text-align: right; font-family: monospace; font-weight: bold;">Rp {{ number_format($totalMaterialCost, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td style="color: #475569; font-weight: bold;">Subtotal Gaji Worker Terbayar:</td>
                <td style="text-align: right; font-family: monospace; font-weight: bold;">Rp {{ number_format($totalSalaryCost, 0, ',', '.') }}</td>
            </tr>
            <tr style="border-top: 1px solid #bbf7d0;">
                <td style="color: #15803d; font-size: 13px; font-weight: bold; padding-top: 6px;">TOTAL PENGELUARAN & BELANJA UNIT:</td>
                <td style="text-align: right; font-family: monospace; font-size: 14px; font-weight: bold; color: #15803d; padding-top: 6px;">
                    Rp {{ number_format($totalExpenses, 0, ',', '.') }}
                </td>
            </tr>
        </table>
    </div>

    <!-- Centered QR Code Official Verification -->
    <div class="qr-section">
        <img src="{{ $qrCodeUrl }}" class="qr-img" alt="QR Code Verifikasi Resmi">
        <div class="qr-text">DOKUMEN RESMI TERVERIFIKASI SISTEM KAVLING & PROPERTI</div>
        <div class="qr-subtext">Scan QR Code di atas untuk memverifikasi keabsahan laporan rekapitulasi pengeluaran unit secara publik.</div>
    </div>

</body>
</html>
