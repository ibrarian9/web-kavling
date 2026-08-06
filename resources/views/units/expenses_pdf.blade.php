<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>REKAPITULASI TABEL BIAYA UNIT {{ $unit->code }}</title>
    <style>
        @page {
            margin: 1.2cm 1.5cm;
            size: A4 portrait;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 10.5px;
            color: #1e293b;
            line-height: 1.4;
        }
        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
            border-bottom: 2px solid #0f766e;
            padding-bottom: 8px;
        }
        .company-title {
            font-size: 15px;
            font-weight: bold;
            color: #0f766e;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .doc-title {
            text-align: right;
            font-size: 12px;
            font-weight: bold;
            color: #0f172a;
            text-transform: uppercase;
        }
        .meta-strip {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 8px 12px;
            margin-bottom: 14px;
        }
        .meta-table {
            width: 100%;
            border-collapse: collapse;
        }
        .meta-table td {
            padding: 2px 4px;
            font-size: 10px;
        }
        .meta-label {
            color: #64748b;
            font-weight: bold;
        }
        .meta-value {
            color: #0f172a;
            font-weight: bold;
        }
        .expenses-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 16px;
        }
        .expenses-table th {
            background-color: #0f766e;
            color: #ffffff;
            font-size: 9.5px;
            font-weight: bold;
            text-transform: uppercase;
            padding: 6px 8px;
            border: 1px solid #0f766e;
        }
        .expenses-table td {
            padding: 6px 8px;
            border-bottom: 1px solid #e2e8f0;
            font-size: 10px;
        }
        .expenses-table tr:nth-child(even) {
            background-color: #f8fafc;
        }
        .badge {
            display: inline-block;
            padding: 2px 5px;
            border-radius: 4px;
            font-size: 8.5px;
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
            border: 1.5px solid #bbf7d0;
            border-radius: 6px;
            padding: 10px 12px;
            margin-bottom: 20px;
        }
        .total-table {
            width: 100%;
            border-collapse: collapse;
        }
        .total-table td {
            padding: 3px 5px;
            font-size: 10.5px;
        }
        .qr-section {
            text-align: center;
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px dashed #cbd5e1;
        }
        .qr-img {
            width: 80px;
            height: 80px;
            margin: 0 auto 5px auto;
            display: block;
        }
        .qr-text {
            font-size: 9px;
            color: #475569;
            font-weight: bold;
        }
    </style>
</head>
<body>

    <!-- Header Banner -->
    <table class="header-table">
        <tr>
            <td>
                <div class="company-title">PT. ATLANTIK PERKASA ABADI</div>
                <div style="font-size: 9px; color: #475569;">Sistem Informasi Properti & Pengembang Kavling</div>
            </td>
            <td class="doc-title">
                REKAP BIAYA UNIT {{ $unit->code }}
            </td>
        </tr>
    </table>

    <!-- Info Meta Ringkas -->
    <div class="meta-strip">
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
                <td class="meta-label">HPP Pokok:</td>
                <td class="meta-value">Rp {{ number_format($unit->hpp, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td class="meta-label">Tanggal Rekap:</td>
                <td class="meta-value">{{ date('d F Y') }}</td>
                <td class="meta-label">Status Unit:</td>
                <td class="meta-value" style="text-transform: uppercase;">{{ str_replace('_', ' ', $unit->status) }}</td>
            </tr>
        </table>
    </div>

    <!-- Tabel Rekap Pengeluaran & Belanja Unit -->
    <table class="expenses-table">
        <thead>
            <tr>
                <th style="width: 5%; text-align: center;">#</th>
                <th style="width: 14%;">Tanggal</th>
                <th style="width: 18%;">Jenis Biaya</th>
                <th style="width: 43%;">Uraian Pengeluaran / Belanja Material</th>
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
                    <td colspan="5" style="text-align: center; color: #94a3b8; padding: 15px;">
                        Belum ada riwayat pengeluaran atau belanja material tercatat untuk unit ini.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Ringkasan Total Biaya -->
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
                <td style="color: #15803d; font-size: 12px; font-weight: bold; padding-top: 4px;">TOTAL REKAPITULASI BIAYA UNIT:</td>
                <td style="text-align: right; font-family: monospace; font-size: 13px; font-weight: bold; color: #15803d; padding-top: 4px;">
                    Rp {{ number_format($totalExpenses, 0, ',', '.') }}
                </td>
            </tr>
        </table>
    </div>

    <!-- QR Code Verifikasi Resmi -->
    <div class="qr-section">
        <img src="{{ $qrCodeUrl }}" class="qr-img" alt="QR Code Verifikasi">
        <div class="qr-text">DOKUMEN RESMI REKAPITULASI BIAYA TERVERIFIKASI SISTEM</div>
    </div>

</body>
</html>
