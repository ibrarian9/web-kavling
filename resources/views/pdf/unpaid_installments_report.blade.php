<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Tunggakan Cicilan Pembeli Bulan {{ $periodName }}</title>
    <style>
        @page {
            margin: 25pt 30pt;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 10pt;
            color: #1e293b;
            line-height: 1.4;
            margin: 0;
            padding: 0;
        }
        .header-table {
            width: 100%;
            border-bottom: 2px solid #e2e8f0;
            padding-bottom: 12px;
            margin-bottom: 15px;
        }
        .logo-title {
            font-size: 16pt;
            font-weight: 800;
            color: #0f172a;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .company-subtitle {
            font-size: 9pt;
            color: #64748b;
            font-weight: 600;
        }
        .doc-badge {
            background-color: #ffe4e6;
            color: #9f1239;
            border: 1px solid #fecdd3;
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 8.5pt;
            font-weight: 800;
            text-transform: uppercase;
        }
        .summary-box {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 10px 15px;
            margin-bottom: 15px;
        }
        .summary-table {
            width: 100%;
        }
        .summary-title {
            font-size: 8.5pt;
            text-transform: uppercase;
            color: #64748b;
            font-weight: 700;
        }
        .summary-value {
            font-size: 14pt;
            font-weight: 800;
            font-family: 'Courier', monospace;
            color: #0f172a;
        }
        .summary-value-danger {
            color: #be123c;
        }
        .report-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .report-table th {
            background-color: #0f172a;
            color: #ffffff;
            font-size: 8pt;
            font-weight: 800;
            text-transform: uppercase;
            padding: 8px 10px;
            text-align: left;
            border: 1px solid #0f172a;
        }
        .report-table td {
            padding: 7px 10px;
            font-size: 8.5pt;
            border-bottom: 1px solid #e2e8f0;
            vertical-align: middle;
        }
        .report-table tr:nth-child(even) td {
            background-color: #f8fafc;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .font-mono {
            font-family: 'Courier', monospace;
            font-weight: 700;
        }
        .badge-danger {
            background-color: #ffe4e6;
            color: #9f1239;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 7.5pt;
            font-weight: 800;
        }
        .footer-table {
            width: 100%;
            margin-top: 25px;
            page-break-inside: avoid;
        }
        .signature-title {
            font-size: 8.5pt;
            font-weight: 700;
            color: #475569;
            margin-bottom: 50px;
        }
        .signature-name {
            font-size: 9.5pt;
            font-weight: 800;
            color: #0f172a;
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <!-- Header Section -->
    <table class="header-table">
        <tr>
            <td>
                <div class="logo-title">PT. ATLANTIK PERKASA ABADI</div>
                <div class="company-subtitle">Sistem Pengelolaan Properti, Kavling & Keuangan Terpadu</div>
                <div style="font-size: 8pt; color: #94a3b8; margin-top: 2px;">Cetak Dokumen: {{ $generatedAt }}</div>
            </td>
            <td class="text-right" style="vertical-align: top;">
                <span class="doc-badge">LAPORAN TUNGGAKAN CICILAN</span>
                <div style="font-size: 8.5pt; font-weight: 700; color: #334155; margin-top: 6px;">Periode: {{ $periodName }}</div>
                <div style="font-size: 8pt; color: #64748b;">Filter Proyek: {{ $projectInfo }}</div>
            </td>
        </tr>
    </table>

    <!-- Executive Summary Cards -->
    <div class="summary-box">
        <table class="summary-table">
            <tr>
                <td style="width: 33%;">
                    <div class="summary-title">Pembeli Menunggak</div>
                    <div class="summary-value summary-value-danger">{{ $totalUnpaidCount }} Konsumen</div>
                </td>
                <td style="width: 33%;">
                    <div class="summary-title">Est. Tagihan Bulan Ini</div>
                    <div class="summary-value summary-value-danger">Rp {{ number_format($totalUnpaidAmount, 0, ',', '.') }}</div>
                </td>
                <td style="width: 34%;">
                    <div class="summary-title">Total Sisa Piutang Berjalan</div>
                    <div class="summary-value">Rp {{ number_format($totalRemainingBalance, 0, ',', '.') }}</div>
                </td>
            </tr>
        </table>
    </div>

    <!-- Data Table -->
    <table class="report-table">
        <thead>
            <tr>
                <th style="width: 4%;" class="text-center">No</th>
                <th style="width: 12%;">Kode Unit</th>
                <th style="width: 22%;">Nama Konsumen</th>
                <th style="width: 18%;">Proyek Properti</th>
                <th style="width: 15%;" class="text-right">Cicilan / Bulan</th>
                <th style="width: 14%;" class="text-right">Terbayar (Total)</th>
                <th style="width: 15%;" class="text-right">Sisa Piutang</th>
            </tr>
        </thead>
        <tbody>
            @forelse($installments as $index => $inst)
                <tr>
                    <td class="text-center font-mono">{{ $index + 1 }}</td>
                    <td class="font-mono" style="color: #0f172a;">Unit {{ $inst->unit->code ?? '-' }}</td>
                    <td>
                        <strong style="color: #0f172a;">{{ $inst->officialDocument->buyer_name ?? 'Konsumen Pembeli' }}</strong>
                        @if($inst->officialDocument->buyer_contact ?? false)
                            <div style="font-size: 7.5pt; color: #64748b; font-family: 'Courier', monospace;">{{ $inst->officialDocument->buyer_contact }}</div>
                        @endif
                    </td>
                    <td style="color: #334155;">{{ $inst->unit->project->name ?? '-' }}</td>
                    <td class="text-right font-mono" style="color: #be123c;">Rp {{ number_format($inst->installment_amount, 0, ',', '.') }}</td>
                    <td class="text-right font-mono" style="color: #047857;">Rp {{ number_format($inst->total_paid, 0, ',', '.') }}</td>
                    <td class="text-right font-mono" style="color: #b91c1c;">Rp {{ number_format($inst->remaining_balance, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center" style="padding: 20px; color: #047857; font-weight: bold;">
                        Alhamdulillah, seluruh konsumen pembeli telah melunasi setoran cicilan untuk bulan {{ $periodName }}!
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Footer Signature & QR Code Block -->
    <table class="footer-table">
        <tr>
            <td style="width: 60%; vertical-align: bottom;">
                <table style="width: 100%;">
                    <tr>
                        <td style="width: 80px;">
                            <img src="{{ $qrCodeUrl }}" style="width: 75px; height: 75px; border: 1px solid #cbd5e1; padding: 2px; border-radius: 4px;" alt="QR Verify">
                        </td>
                        <td style="vertical-align: middle; padding-left: 8px;">
                            <div style="font-size: 8pt; font-weight: 800; color: #0f172a; text-transform: uppercase;">Verifikasi Digital Sistem</div>
                            <div style="font-size: 7.5pt; color: #64748b; margin-top: 2px;">Dokumen laporan ini dicetak secara otomatis dari Sistem Manajemen Keuangan PT. Atlantik Perkasa Abadi dan sah tanpa tanda tangan basah.</div>
                        </td>
                    </tr>
                </table>
            </td>
            <td style="width: 40%; text-align: right; vertical-align: bottom;">
                <div class="signature-title">Pekanbaru, {{ now()->locale('id')->isoFormat('DD MMMM YYYY') }}<br>Tim Keuangan & Founder</div>
                <div class="signature-name">PT. ATLANTIK PERKASA ABADI</div>
            </td>
        </tr>
    </table>

</body>
</html>
