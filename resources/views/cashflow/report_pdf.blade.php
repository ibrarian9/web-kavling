<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Arus Kas</title>
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
            border-bottom: 2px solid #0284c7;
            padding-bottom: 8px;
            margin-bottom: 15px;
        }
        .company-name {
            font-size: 16pt;
            font-weight: bold;
            color: #0f172a;
            letter-spacing: 0.5px;
        }
        .report-title {
            font-size: 12pt;
            font-weight: bold;
            color: #0284c7;
            text-transform: uppercase;
            margin-top: 4px;
        }
        .meta-table {
            width: 100%;
            margin-bottom: 15px;
        }
        .meta-table td {
            font-size: 9pt;
            padding: 2px 0;
        }
        .summary-box {
            width: 100%;
            margin-bottom: 20px;
            border-collapse: separate;
            border-spacing: 8px;
        }
        .summary-card {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 10px;
            text-align: center;
        }
        .summary-title {
            font-size: 8pt;
            font-weight: bold;
            text-transform: uppercase;
            color: #64748b;
        }
        .summary-amount {
            font-size: 13pt;
            font-weight: bold;
            font-family: 'Courier New', Courier, monospace;
            margin-top: 4px;
        }
        .text-masuk { color: #16a34a; }
        .text-keluar { color: #dc2626; }
        .text-net { color: #0f172a; }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .data-table th {
            background-color: #f1f5f9;
            color: #334155;
            font-size: 8pt;
            font-weight: bold;
            text-transform: uppercase;
            padding: 8px 6px;
            border-top: 1px solid #cbd5e1;
            border-bottom: 1px solid #cbd5e1;
            text-align: left;
        }
        .data-table td {
            padding: 7px 6px;
            border-bottom: 1px solid #f1f5f9;
            font-size: 8.5pt;
        }
        .data-table tr:nth-child(even) td {
            background-color: #fafafa;
        }
        .font-mono {
            font-family: 'Courier New', Courier, monospace;
            font-weight: bold;
        }
        .badge-masuk {
            background-color: #dcfce7;
            color: #15803d;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 7.5pt;
            font-weight: bold;
        }
        .badge-keluar {
            background-color: #fee2e2;
            color: #b91c1c;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 7.5pt;
            font-weight: bold;
        }
        .footer-sig {
            margin-top: 35px;
            width: 100%;
        }
        .footer-sig td {
            text-align: center;
            font-size: 9pt;
        }
        .sig-space {
            height: 50px;
        }
    </style>
</head>
<body>

    <!-- Header -->
    <div class="header">
        <table style="width: 100%;">
            <tr>
                <td>
                    <div class="company-name">SISTEM KAVLING & PROPERTY</div>
                    <div class="report-title">Laporan Rekapitulasi Arus Kas (Cashflow)</div>
                </td>
                <td style="text-align: right; font-size: 8.5pt; color: #64748b;">
                    Dicetak: {{ $printedAt }}<br>
                    Oleh: {{ $printedBy }}
                </td>
            </tr>
        </table>
    </div>

    <!-- Meta Information -->
    <table class="meta-table">
        <tr>
            <td style="width: 15%; font-weight: bold;">Cakupan Mode:</td>
            <td style="width: 35%;">
                @if($viewMode === 'global')
                    Konsolidasi Global Seluruh Proyek & Unit
                @elseif($unit)
                    Per-Unit: {{ $unit->code }} ({{ $unit->project->name }})
                @elseif($project)
                    Per-Proyek: {{ $project->name }}
                @else
                    Per-Proyek / Unit
                @endif
            </td>
            <td style="width: 15%; font-weight: bold;">Periode Bulan:</td>
            <td style="width: 35%;">
                @if($month)
                    {{ format_id_month_year($month) }}
                @else
                    Semua Periode Transaksi
                @endif
            </td>
        </tr>
    </table>

    <!-- Summary Cards -->
    <table class="summary-box">
        <tr>
            <td class="summary-card" style="width: 33%;">
                <div class="summary-title">Total Kas Masuk</div>
                <div class="summary-amount text-masuk">Rp {{ number_format($totalMasuk, 0, ',', '.') }}</div>
            </td>
            <td class="summary-card" style="width: 33%;">
                <div class="summary-title">Total Kas Keluar</div>
                <div class="summary-amount text-keluar">Rp {{ number_format($totalKeluar, 0, ',', '.') }}</div>
            </td>
            <td class="summary-card" style="width: 33%; background-color: #f1f5f9;">
                <div class="summary-title">Saldo Kas Bersih</div>
                <div class="summary-amount text-net">Rp {{ number_format($netCashflow, 0, ',', '.') }}</div>
            </td>
        </tr>
    </table>

    <!-- Data Table -->
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 4%;">No</th>
                <th style="width: 12%;">Tanggal</th>
                <th style="width: 16%;">Proyek</th>
                <th style="width: 12%;">Tipe</th>
                <th style="width: 14%;">Kategori</th>
                <th style="width: 26%;">Keterangan</th>
                <th style="width: 16%; text-align: right;">Nominal (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transactions as $index => $trx)
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td class="font-mono">{{ format_id_date($trx->transaction_date) }}</td>
                    <td style="font-weight: bold;">{{ $trx->project->name ?? 'Global' }}</td>
                    <td>
                        @if($trx->type === 'masuk')
                            <span class="badge-masuk">Kas Masuk</span>
                        @else
                            <span class="badge-keluar">Kas Keluar</span>
                        @endif
                    </td>
                    <td style="text-transform: capitalize;">{{ str_replace('_', ' ', $trx->category) }}</td>
                    <td>{{ $trx->description }}</td>
                    <td style="text-align: right;" class="font-mono {{ $trx->type === 'masuk' ? 'text-masuk' : 'text-keluar' }}">
                        {{ $trx->type === 'masuk' ? '+' : '-' }}Rp {{ number_format($trx->amount, 0, ',', '.') }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center; color: #94a3b8; padding: 20px;">
                        Tidak ada transaksi mutasi kas pada periode dan filter ini.
                    </td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr style="font-weight: bold; background-color: #f1f5f9; border-top: 2px solid #cbd5e1;">
                <td colspan="6" style="text-align: right; padding: 8px;">Total Kas Bersih (Net Cashflow):</td>
                <td style="text-align: right; padding: 8px;" class="font-mono">
                    Rp {{ number_format($netCashflow, 0, ',', '.') }}
                </td>
            </tr>
        </tfoot>
    </table>

    <!-- Official System QR Verification (Centering Minimalist) -->
    <div style="margin-top: 30px; text-align: center;">
        @if(isset($qrCodeUrl))
            <img src="{{ $qrCodeUrl }}" style="width: 90px; height: 90px; margin: 0 auto; display: block;" alt="Scan QR Verification">
        @endif
        <div style="font-size: 8pt; font-weight: bold; color: #475569; margin-top: 6px; letter-spacing: 0.5px; text-transform: uppercase;">
            Scan QR Code Verifikasi Keabsahan Dokumen
        </div>
    </div>

</body>
</html>
