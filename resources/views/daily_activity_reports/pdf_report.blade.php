<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daily Activity Report - Laporan Aktivitas Harian Marketing</title>
    <style>
        @page {
            margin: 1.2cm;
            size: A4 portrait;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 10px;
            color: #000000;
            line-height: 1.4;
            background-color: #ffffff;
        }
        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            border-bottom: 2px solid #000000;
            padding-bottom: 8px;
        }
        .header-table td {
            vertical-align: middle;
        }
        .brand-title {
            font-size: 17px;
            font-weight: bold;
            color: #000000;
            letter-spacing: -0.5px;
            text-transform: uppercase;
        }
        .brand-subtitle {
            font-size: 9.5px;
            color: #444444;
            font-weight: normal;
        }
        .doc-type {
            text-align: right;
            font-size: 12px;
            font-weight: bold;
            color: #000000;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .meta-box {
            background-color: #ffffff;
            border: 1px solid #000000;
            border-radius: 4px;
            padding: 10px 12px;
            margin-bottom: 15px;
        }
        .meta-table {
            width: 100%;
            border-collapse: collapse;
        }
        .meta-table td {
            padding: 3px 5px;
            font-size: 10px;
        }
        .meta-label {
            color: #444444;
            font-weight: bold;
            width: 22%;
        }
        .meta-value {
            color: #000000;
            font-weight: bold;
        }
        .kpi-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        .kpi-card {
            background-color: #ffffff;
            border: 1px solid #000000;
            border-radius: 4px;
            padding: 8px 10px;
            text-align: center;
        }
        .kpi-title {
            font-size: 8.5px;
            font-weight: bold;
            color: #333333;
            text-transform: uppercase;
        }
        .kpi-value {
            font-size: 14px;
            font-weight: bold;
            font-family: monospace;
            color: #000000;
            margin-top: 2px;
        }
        .report-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        .report-table th {
            background-color: #000000;
            color: #ffffff;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
            padding: 7px 8px;
            border: 1px solid #000000;
        }
        .report-table td {
            padding: 6px 7px;
            border: 1px solid #cccccc;
            font-size: 9.5px;
            vertical-align: top;
            color: #000000;
        }
        .report-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .badge {
            display: inline-block;
            padding: 2px 5px;
            border-radius: 3px;
            font-size: 8px;
            font-weight: bold;
            text-transform: uppercase;
            background-color: #f0f0f0;
            color: #000000;
            border: 1px solid #999999;
        }
        .badge-hot {
            background-color: #e6e6e6;
            color: #000000;
            border: 1px solid #333333;
            font-weight: 900;
        }
        .badge-booking {
            background-color: #d9d9d9;
            color: #000000;
            border: 1px solid #000000;
            font-weight: 900;
        }
        .badge-lunas {
            background-color: #cccccc;
            color: #000000;
            border: 1px solid #000000;
            font-weight: 900;
        }
        .badge-warm {
            background-color: #f0f0f0;
            color: #000000;
            border: 1px solid #999999;
        }
        .badge-batal {
            background-color: #ffffff;
            color: #555555;
            border: 1px dashed #999999;
        }
        .badge-cold {
            background-color: #f9f9f9;
            color: #444444;
            border: 1px solid #cccccc;
        }
        .qr-section {
            text-align: center;
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px dashed #999999;
            page-break-inside: avoid;
        }
        .qr-img {
            width: 80px;
            height: 80px;
            margin: 0 auto 4px auto;
            display: block;
        }
        .qr-text {
            font-size: 9px;
            color: #000000;
            font-weight: bold;
        }
        .qr-subtext {
            font-size: 8px;
            color: #555555;
        }
    </style>
</head>
<body>

    <!-- Header Banner -->
    <table class="header-table">
        <tr>
            <td>
                <div class="brand-title">KAVLING & PROPERTI RESMI</div>
                <div class="brand-subtitle">Sistem Informasi Manajemen Real Estate & Pemasaran Properti</div>
            </td>
            <td class="doc-type">
                DAILY ACTIVITY REPORT
            </td>
        </tr>
    </table>

    <!-- Meta Information Box -->
    <div class="meta-box">
        <table class="meta-table">
            <tr>
                <td class="meta-label">Filter Periode:</td>
                <td class="meta-value">{{ $periodLabel }}</td>
                <td class="meta-label">Petugas Marketing:</td>
                <td class="meta-value">{{ $staffInfo }}</td>
            </tr>
            <tr>
                <td class="meta-label">Tahap Prospek:</td>
                <td class="meta-value">{{ $leadStageLabel ?? 'Semua Tahap Prospek' }}</td>
                <td class="meta-label">Sumber Lead:</td>
                <td class="meta-value">{{ $leadSourceLabel ?? 'Semua Sumber Lead' }}</td>
            </tr>
            <tr>
                <td class="meta-label">Kawasan Proyek:</td>
                <td class="meta-value">{{ $projectInfo }}</td>
                <td class="meta-label">Waktu Cetak / Oleh:</td>
                <td class="meta-value">{{ $printedAt }} ({{ $printedBy }})</td>
            </tr>
        </table>
    </div>

    <!-- KPI Summary Box -->
    <table class="kpi-table">
        <tr>
            <td style="width: 32%; padding-right: 5px;">
                <div class="kpi-card">
                    <div class="kpi-title">Total Aktivitas Marketing</div>
                    <div class="kpi-value">{{ number_format($totalReports, 0, ',', '.') }} Laporan</div>
                </div>
            </td>
            <td style="width: 34%; padding-left: 3px; padding-right: 3px;">
                <div class="kpi-card">
                    <div class="kpi-title">Prospek Hot Deal / Closing</div>
                    <div class="kpi-value">{{ number_format($hotDealsCount, 0, ',', '.') }} Prospek</div>
                </div>
            </td>
            <td style="width: 34%; padding-left: 5px;">
                <div class="kpi-card">
                    <div class="kpi-title">Total Volume Deal / Closing</div>
                    <div class="kpi-value">Rp {{ number_format($totalDealVolume, 0, ',', '.') }}</div>
                </div>
            </td>
        </tr>
    </table>

    <!-- Data Table -->
    <table class="report-table">
        <thead>
            <tr>
                <th style="width: 4%; text-align: center;">#</th>
                <th style="width: 10%;">Tgl & Jam</th>
                <th style="width: 18%;">Sales / Petugas</th>
                <th style="width: 18%;">Konsumen / Lead</th>
                <th style="width: 15%;">Sumber & Interaksi</th>
                <th style="width: 12%;">Proyek / Unit</th>
                <th style="width: 11%; text-align: center;">Status Prospek</th>
                <th style="width: 12%; text-align: right;">Deal (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @forelse($reports as $index => $rep)
                @php
                    $badgeClass = match ($rep->lead_stage) {
                        'hot_deal' => 'badge-hot',
                        'booking' => 'badge-booking',
                        'cash_lunas' => 'badge-lunas',
                        'warm' => 'badge-warm',
                        'batal' => 'badge-batal',
                        default => 'badge-cold',
                    };
                @endphp
                <tr>
                    <td style="text-align: center; font-weight: bold;">{{ $index + 1 }}</td>
                    <td style="font-family: monospace;">
                        <strong>{{ $rep->report_date ? $rep->report_date->format('d/m/Y') : '-' }}</strong>
                        <div style="font-size: 8px; color: #555555;">{{ $rep->created_at ? $rep->created_at->format('H:i') : '' }}</div>
                    </td>
                    <td>
                        <strong style="color: #000000;">{{ $rep->user->name ?? 'Marketing' }}</strong>
                        <div style="font-size: 8.5px; color: #333333; font-family: monospace;">
                            HP: {{ $rep->user->phone_number ?? '-' }}
                        </div>
                    </td>
                    <td>
                        <strong style="color: #000000;">{{ $rep->client_name }}</strong>
                        <div style="font-size: 8.5px; color: #333333; font-family: monospace;">
                            HP: {{ $rep->client_phone }}
                        </div>
                    </td>
                    <td>
                        <div style="font-weight: bold; color: #000000;">{{ $rep->lead_source_label }}</div>
                        <div style="font-size: 8.5px; color: #444444;">{{ $rep->interaction_type_label }}</div>
                    </td>
                    <td>
                        <strong style="color: #000000;">{{ $rep->project->name ?? 'General' }}</strong>
                        @if($rep->unit)
                            <div style="font-size: 8.5px; color: #000000; font-weight: bold;">Unit: {{ $rep->unit->code }}</div>
                        @endif
                    </td>
                    <td style="text-align: center;">
                        <span class="badge {{ $badgeClass }}">
                            {{ $rep->lead_stage_label }}
                        </span>
                        @if($rep->follow_up_date)
                            <div style="font-size: 7.5px; color: #333333; margin-top: 2px;">
                                FU: {{ $rep->follow_up_date->format('d/m/Y') }}
                            </div>
                        @endif
                    </td>
                    <td style="text-align: right; font-family: monospace; font-weight: bold; color: #000000;">
                        {{ $rep->deal_amount > 0 ? 'Rp ' . number_format($rep->deal_amount, 0, ',', '.') : '-' }}
                    </td>
                </tr>
                @if($rep->notes)
                    <tr style="background-color: #fdfdfd;">
                        <td colspan="2" style="border: 1px solid #cccccc; font-size: 8px; color: #555555; font-style: italic; text-align: right; padding-right: 5px;">
                            Catatan Follow Up:
                        </td>
                        <td colspan="6" style="border: 1px solid #cccccc; font-size: 8.5px; color: #111111; padding-left: 5px;">
                            {{ $rep->notes }}
                        </td>
                    </tr>
                @endif
            @empty
                <tr>
                    <td colspan="8" style="text-align: center; color: #666666; padding: 20px;">
                        Tidak ada laporan aktivitas harian yang ditemukan untuk kriteria ini.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- QR Code Verification -->
    <div class="qr-section">
        <img src="{{ $qrCodeUrl }}" class="qr-img" alt="QR Code Verifikasi Resmi">
        <div class="qr-text">DOKUMEN RESMI TERVERIFIKASI SISTEM KAVLING & PROPERTI</div>
        <div class="qr-subtext">Scan QR Code untuk memverifikasi keabsahan laporan aktivitas harian secara publik di sistem.</div>
    </div>

</body>
</html>
