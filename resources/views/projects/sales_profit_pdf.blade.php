<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Penjualan & Profit Performance {{ $project->name }}</title>
    <style>
        @page {
            margin: 1.2cm;
            size: A4 landscape;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 10px;
            color: #1e293b;
            line-height: 1.4;
        }
        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            border-bottom: 2px solid #0f172a;
            padding-bottom: 8px;
        }
        .header-table td {
            vertical-align: middle;
        }
        .brand-title {
            font-size: 16px;
            font-weight: bold;
            color: #0f172a;
            letter-spacing: -0.5px;
            text-transform: uppercase;
        }
        .brand-subtitle {
            font-size: 9px;
            color: #64748b;
        }
        .doc-type {
            text-align: right;
            font-size: 13px;
            font-weight: bold;
            color: #059669;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .meta-box {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
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
            color: #64748b;
            font-weight: bold;
            width: 20%;
        }
        .meta-value {
            color: #0f172a;
            font-weight: bold;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        .data-table th {
            background-color: #065f46;
            color: #ffffff;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
            padding: 6px 8px;
            border: 1px solid #065f46;
        }
        .data-table td {
            padding: 6px 8px;
            border-bottom: 1px solid #e2e8f0;
            font-size: 9.5px;
        }
        .data-table tr:nth-child(even) {
            background-color: #f0fdf4;
        }
        .total-box {
            background-color: #ecfdf5;
            border: 1px solid #a7f3d0;
            border-radius: 6px;
            padding: 10px 12px;
            margin-bottom: 15px;
        }
        .total-table {
            width: 100%;
            border-collapse: collapse;
        }
        .total-table td {
            padding: 3px 5px;
            font-size: 10px;
        }
        .qr-section {
            text-align: center;
            margin-top: 15px;
            padding-top: 10px;
            border-top: 1px dashed #cbd5e1;
        }
        .qr-img {
            width: 75px;
            height: 75px;
            margin: 0 auto 4px auto;
            display: block;
        }
        .qr-text {
            font-size: 9px;
            color: #64748b;
            font-weight: bold;
        }
        .qr-subtext {
            font-size: 8px;
            color: #94a3b8;
        }
    </style>
</head>
<body>

    <table class="header-table">
        <tr>
            <td>
                <div class="brand-title">KAVLING & PROPERTI RESMI</div>
                <div class="brand-subtitle">Laporan Rekapitulasi Penjualan & Profit Margin Unit Proyek</div>
            </td>
            <td class="doc-type">
                PENJUALAN & PROFIT UNIT
            </td>
        </tr>
    </table>

    <div class="meta-box">
        <table class="meta-table">
            <tr>
                <td class="meta-label">Nama Proyek:</td>
                <td class="meta-value">{{ $project->name }}</td>
                <td class="meta-label">Total Unit Proyek:</td>
                <td class="meta-value">{{ $totalUnits }} Unit ({{ $soldCount }} Terjual)</td>
            </tr>
            <tr>
                <td class="meta-label">Lokasi:</td>
                <td class="meta-value">{{ $project->location }}</td>
                <td class="meta-label">Tanggal Cetak:</td>
                <td class="meta-value">{{ $printedAt }} (oleh {{ $printedBy }})</td>
            </tr>
        </table>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 4%; text-align: center;">#</th>
                <th style="width: 10%;">Kode Unit</th>
                <th style="width: 12%;">Kategori & Luas</th>
                <th style="width: 16%;">Nama Pembeli</th>
                <th style="width: 12%;">Status</th>
                <th style="width: 12%; text-align: right;">Harga Deal</th>
                <th style="width: 12%; text-align: right;">Sudah Dibayar</th>
                <th style="width: 11%; text-align: right;">Sisa Tagihan</th>
                <th style="width: 11%; text-align: right;">Profit Margin</th>
            </tr>
        </thead>
        <tbody>
            @forelse($unitPerformances as $index => $u)
                <tr>
                    <td style="text-align: center; color: #64748b;">{{ $index + 1 }}</td>
                    <td style="font-family: monospace; font-weight: bold; color: #047857;">{{ $u->code }}</td>
                    <td>{{ ucfirst($u->category) }} ({{ number_format($u->land_area, 0, ',', '.') }} m²)</td>
                    <td style="font-weight: bold;">{{ $u->buyer_name }}</td>
                    <td style="text-transform: uppercase; font-size: 9px; font-weight: bold;">{{ str_replace('_', ' ', $u->status) }}</td>
                    <td style="text-align: right; font-family: monospace; font-weight: bold;">
                        @if($u->selling_price > 0)
                            Rp {{ number_format($u->selling_price, 0, ',', '.') }}
                        @else
                            -
                        @endif
                    </td>
                    <td style="text-align: right; font-family: monospace; font-weight: bold; color: #0369a1;">
                        @if($u->paid_amount > 0)
                            Rp {{ number_format($u->paid_amount, 0, ',', '.') }}
                        @else
                            Rp 0
                        @endif
                    </td>
                    <td style="text-align: right; font-family: monospace; font-weight: bold; color: #b45309;">
                        @if($u->is_sold && $u->remaining_amount > 0)
                            Rp {{ number_format($u->remaining_amount, 0, ',', '.') }}
                        @elseif($u->is_sold && $u->remaining_amount == 0)
                            <span style="color: #059669;">LUNAS</span>
                        @else
                            -
                        @endif
                    </td>
                    <td style="text-align: right; font-family: monospace; font-weight: bold;">
                        @if($u->is_sold)
                            <span style="color: {{ $u->profit >= 0 ? '#059669' : '#dc2626' }};">
                                {{ $u->profit >= 0 ? '+' : '' }} Rp {{ number_format($u->profit, 0, ',', '.') }}
                            </span>
                        @else
                            <span style="color: #94a3b8; font-weight: normal; font-style: italic;">Belum Terjual</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" style="text-align: center; color: #94a3b8; padding: 20px;">
                        Belum ada unit kavling terdaftar pada proyek ini.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="total-box">
        <table class="total-table">
            <tr>
                <td style="color: #047857; font-weight: bold;">Total Nilai Deal Penjualan:</td>
                <td style="text-align: right; font-family: monospace; font-weight: bold; font-size: 11px;">Rp {{ number_format($totalSalesRevenue, 0, ',', '.') }}</td>
                <td style="color: #0369a1; font-weight: bold; padding-left: 20px;">Total Kas Masuk Terbayar:</td>
                <td style="text-align: right; font-family: monospace; font-weight: bold; font-size: 11px; color: #0369a1;">Rp {{ number_format($totalPaidRevenue, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td style="color: #b45309; font-weight: bold;">Total Sisa Piutang:</td>
                <td style="text-align: right; font-family: monospace; font-weight: bold; font-size: 11px; color: #b45309;">Rp {{ number_format($totalOutstandingReceivable, 0, ',', '.') }}</td>
                <td style="color: #047857; font-weight: bold; padding-left: 20px;">Total HPP Unit Terjual:</td>
                <td style="text-align: right; font-family: monospace; font-weight: bold; font-size: 11px;">Rp {{ number_format($totalHppSold, 0, ',', '.') }}</td>
            </tr>
        </table>
    </div>

    <div class="qr-section">
        <img src="{{ $qrCodeUrl }}" class="qr-img" alt="QR Code Verifikasi">
        <div class="qr-text">DOKUMEN RESMI TERVERIFIKASI SISTEM KAVLING & PROPERTI</div>
        <div class="qr-subtext">Scan QR Code di atas untuk memverifikasi keabsahan laporan rekapitulasi penjualan & profit per unit secara publik.</div>
    </div>

</body>
</html>
