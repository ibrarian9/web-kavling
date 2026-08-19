<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekapitulasi Pembayaran Cicilan - Unit {{ $unit->code }} - {{ $installment->buyer_name }}</title>
    <style>
        @page {
            margin: 26pt 30pt;
        }
        body {
            font-family: Helvetica, Arial, sans-serif;
            font-size: 9pt;
            color: #1e293b;
            line-height: 1.35;
        }
        .header {
            border-bottom: 2.5px solid #059669;
            padding-bottom: 8px;
            margin-bottom: 12px;
        }
        .company-name {
            font-size: 15pt;
            font-weight: bold;
            color: #0f172a;
            letter-spacing: 0.5px;
        }
        .doc-title {
            font-size: 12pt;
            font-weight: bold;
            color: #059669;
            text-transform: uppercase;
            margin-top: 2px;
        }
        .doc-subtitle {
            font-size: 8.5pt;
            color: #64748b;
        }
        .meta-table {
            width: 100%;
            margin-bottom: 10px;
        }
        .meta-box {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 8px 10px;
            vertical-align: top;
        }
        .meta-box-title {
            font-size: 8pt;
            font-weight: bold;
            text-transform: uppercase;
            color: #475569;
            border-bottom: 1px solid #cbd5e1;
            padding-bottom: 3px;
            margin-bottom: 5px;
        }
        .meta-row {
            font-size: 8.5pt;
            padding: 2px 0;
        }
        .meta-label {
            color: #64748b;
            width: 110px;
            display: inline-block;
        }
        .meta-val {
            font-weight: bold;
            color: #0f172a;
        }
        .stat-grid {
            width: 100%;
            margin-bottom: 12px;
            border-collapse: separate;
            border-spacing: 5px;
        }
        .stat-card {
            border-radius: 6px;
            padding: 6px 8px;
            text-align: center;
        }
        .stat-label {
            font-size: 7.5pt;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 2px;
        }
        .stat-val {
            font-family: 'Courier New', Courier, monospace;
            font-size: 11pt;
            font-weight: bold;
        }
        .table-detail {
            width: 100%;
            border-collapse: collapse;
            margin-top: 6px;
            margin-bottom: 10px;
        }
        .table-detail th {
            background-color: #f1f5f9;
            color: #334155;
            font-size: 8pt;
            font-weight: bold;
            text-transform: uppercase;
            padding: 6px 5px;
            border-top: 1px solid #cbd5e1;
            border-bottom: 1.5px solid #94a3b8;
            text-align: left;
        }
        .table-detail td {
            padding: 5px 5px;
            border-bottom: 1px solid #e2e8f0;
            font-size: 8pt;
        }
        .table-detail tr:nth-child(even) td {
            background-color: #fafbfc;
        }
        .font-mono {
            font-family: 'Courier New', Courier, monospace;
            font-weight: bold;
        }
        .summary-box {
            background-color: #ecfdf5;
            border: 1px solid #a7f3d0;
            border-radius: 6px;
            padding: 8px 12px;
            margin-top: 8px;
            margin-bottom: 12px;
        }
        .terbilang-box {
            font-size: 8pt;
            font-style: italic;
            color: #065f46;
            margin-top: 4px;
        }
        .badge {
            display: inline-block;
            padding: 2px 6px;
            font-size: 7.5pt;
            font-weight: bold;
            border-radius: 4px;
            text-transform: uppercase;
        }
        .badge-lunas {
            background-color: #d1fae5;
            color: #065f46;
            border: 1px solid #6ee7b7;
        }
        .badge-berjalan {
            background-color: #eff6ff;
            color: #1e40af;
            border: 1px solid #bfdbfe;
        }
        .badge-menunggak {
            background-color: #ffe4e6;
            color: #9f1239;
            border: 1px solid #fecdd3;
        }
        .signature-table {
            width: 100%;
            margin-top: 15px;
        }
        .signature-cell {
            width: 33.33%;
            text-align: center;
            vertical-align: top;
            font-size: 8pt;
        }
        .signature-space {
            height: 48px;
        }
        .signature-name {
            font-weight: bold;
            text-decoration: underline;
            color: #0f172a;
        }
        .footer-note {
            font-size: 7pt;
            color: #94a3b8;
            margin-top: 15px;
            border-top: 1px dashed #cbd5e1;
            padding-top: 5px;
        }
    </style>
</head>
<body>

    <!-- Header -->
    <div class="header">
        <table style="width: 100%;">
            <tr>
                <td>
                    <div class="company-name">MANAJEMEN PROPERTI & KAVLING</div>
                    <div class="doc-title">Rekapitulasi Pembayaran Cicilan Konsumen</div>
                    <div class="doc-subtitle">Kartu Kontrol & Histori Pembayaran Setoran Unit Property Terpadu</div>
                </td>
                <td style="text-align: right; vertical-align: top; font-size: 8pt; color: #64748b;">
                    <div><strong>No. Dokumen:</strong> STAT/{{ strtoupper($unit->code) }}/{{ date('Ymd', strtotime($installment->created_at)) }}</div>
                    <div><strong>Tanggal Cetak:</strong> {{ $generatedAt }}</div>
                    <div style="margin-top: 3px;">
                        @if($installment->status === 'lunas')
                            <span class="badge badge-lunas">LUNAS TUNTAS</span>
                        @elseif($installment->status === 'konversi_cash')
                            <span class="badge badge-lunas">LUNAS CASH</span>
                        @elseif($installment->status === 'menunggak')
                            <span class="badge badge-menunggak">MENUNGGAK</span>
                        @else
                            <span class="badge badge-berjalan">CICILAN BERJALAN</span>
                        @endif
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <!-- Metadata 2-Column Boxes -->
    <table class="meta-table" style="border-collapse: separate; border-spacing: 8px 0;">
        <tr>
            <!-- Left: Unit & Project Info -->
            <td class="meta-box" style="width: 50%;">
                <div class="meta-box-title">Identitas Unit & Properti</div>
                <div class="meta-row"><span class="meta-label">Nama Proyek:</span> <span class="meta-val">{{ $project->name }}</span></div>
                <div class="meta-row"><span class="meta-label">Lokasi:</span> <span class="meta-val">{{ $project->location }}</span></div>
                <div class="meta-row"><span class="meta-label">Kode Unit:</span> <span class="meta-val" style="color: #059669; font-size: 9.5pt;">UNIT {{ $unit->code }}</span> ({{ ucfirst($unit->category ?? $unit->type) }})</div>
                <div class="meta-row"><span class="meta-label">Dimensi / Luas:</span> <span class="meta-val">{{ $unit->land_length }}m &times; {{ $unit->land_width }}m ({{ number_format($unit->land_area, 0, ',', '.') }} m²)</span></div>
                <div class="meta-row"><span class="meta-label">No. Berkas SPP:</span> <span class="meta-val">{{ $officialDoc?->document_number ?? '-' }}</span></div>
            </td>

            <!-- Right: Buyer Info -->
            <td class="meta-box" style="width: 50%;">
                <div class="meta-box-title">Identitas Konsumen / Pembeli</div>
                <div class="meta-row"><span class="meta-label">Nama Pembeli:</span> <span class="meta-val" style="font-size: 9.5pt;">{{ $installment->buyer_name }}</span></div>
                <div class="meta-row"><span class="meta-label">No. Telepon / WA:</span> <span class="meta-val font-mono">{{ $installment->buyer_phone ?? '-' }}</span></div>
                <div class="meta-row"><span class="meta-label">Tenor Cicilan:</span> <span class="meta-val">{{ $installment->installment_count }} Bulan</span></div>
                <div class="meta-row"><span class="meta-label">Cicilan / Bulan:</span> <span class="meta-val font-mono">Rp {{ number_format($installment->installment_amount, 0, ',', '.') }}</span></div>
                <div class="meta-row"><span class="meta-label">Mulai Cicilan:</span> <span class="meta-val">{{ $installment->start_date ? format_id_date($installment->start_date) : '-' }}</span></div>
            </td>
        </tr>
    </table>

    <!-- Financial Key Metrics Cards -->
    <table class="stat-grid">
        <tr>
            <td class="stat-card" style="background-color: #f8fafc; border: 1px solid #e2e8f0;">
                <div class="stat-label" style="color: #64748b;">Total Harga Deal</div>
                <div class="stat-val" style="color: #0f172a;">Rp {{ number_format($totalPrice, 0, ',', '.') }}</div>
            </td>
            <td class="stat-card" style="background-color: #eff6ff; border: 1px solid #bfdbfe;">
                <div class="stat-label" style="color: #1e40af;">Uang Muka (DP)</div>
                <div class="stat-val" style="color: #1d4ed8;">Rp {{ number_format($downPayment, 0, ',', '.') }}</div>
            </td>
            <td class="stat-card" style="background-color: #ecfdf5; border: 1px solid #a7f3d0;">
                <div class="stat-label" style="color: #065f46;">Total Sudah Terbayar</div>
                <div class="stat-val" style="color: #059669;">Rp {{ number_format($totalPaid, 0, ',', '.') }}</div>
            </td>
            <td class="stat-card" style="background-color: {{ $remainingBalance > 0 ? '#fffbeb' : '#f0fdf4' }}; border: 1px solid {{ $remainingBalance > 0 ? '#fde68a' : '#bbf7d0' }};">
                <div class="stat-label" style="color: {{ $remainingBalance > 0 ? '#92400e' : '#166534' }};">Sisa Saldo Tagihan</div>
                <div class="stat-val" style="color: {{ $remainingBalance > 0 ? '#b45309' : '#15803d' }};">Rp {{ number_format($remainingBalance, 0, ',', '.') }}</div>
            </td>
        </tr>
    </table>

    <!-- Table of Installment Payments History -->
    <div style="font-weight: bold; font-size: 8.5pt; text-transform: uppercase; color: #334155; margin-bottom: 2px;">
        Rincian Seluruh Transaksi Pembayaran & Histori Setoran:
    </div>

    <table class="table-detail">
        <thead>
            <tr>
                <th style="width: 24px; text-align: center;">No</th>
                <th style="width: 68px;">Tanggal</th>
                <th style="width: 105px;">Keterangan / Jenis</th>
                <th style="width: 75px;">Metode</th>
                <th style="width: 88px; text-align: right;">Jumlah Bayar</th>
                <th style="width: 88px; text-align: right;">Akumulasi</th>
                <th style="width: 88px; text-align: right;">Sisa Tagihan</th>
                <th style="width: 54px; text-align: center;">Petugas</th>
            </tr>
        </thead>
        <tbody>
            @php
                $runningPaid = 0;
                $rowNum = 1;
                $installmentPayIdx = 1;
            @endphp

            <!-- Row 1: Down Payment (DP) -->
            @if($downPayment > 0)
                @php
                    $runningPaid += $downPayment;
                    $rowRemaining = max(0, $totalPrice - $runningPaid);
                @endphp
                <tr style="background-color: #f0fdf4;">
                    <td style="text-align: center; font-weight: bold;">{{ $rowNum++ }}</td>
                    <td class="font-mono">{{ $installment->start_date ? format_id_date($installment->start_date) : '-' }}</td>
                    <td><strong>Uang Muka (DP)</strong></td>
                    <td>Akad Beli</td>
                    <td style="text-align: right;" class="font-mono text-emerald-700">Rp {{ number_format($downPayment, 0, ',', '.') }}</td>
                    <td style="text-align: right;" class="font-mono">Rp {{ number_format($runningPaid, 0, ',', '.') }}</td>
                    <td style="text-align: right;" class="font-mono {{ $rowRemaining > 0 ? 'text-amber-700' : 'text-emerald-700' }}">Rp {{ number_format($rowRemaining, 0, ',', '.') }}</td>
                    <td style="text-align: center; font-size: 7.5pt; color: #64748b;">Admin</td>
                </tr>
            @endif

            <!-- Rows 2..N: Monthly Installment Payments -->
            @forelse($payments as $pay)
                @php
                    $runningPaid += (float)$pay->amount_paid;
                    $rowRemaining = max(0, $totalPrice - $runningPaid);
                @endphp
                <tr>
                    <td style="text-align: center; font-weight: bold;">{{ $rowNum++ }}</td>
                    <td class="font-mono">{{ format_id_date($pay->payment_date) }}</td>
                    <td>
                        <div>Setoran Cicilan #{{ $installmentPayIdx++ }}</div>
                        @if($pay->notes)
                            <div style="font-size: 6.8pt; color: #64748b; font-style: italic; line-height: 1.1;">{{ $pay->notes }}</div>
                        @endif
                    </td>
                    <td>{{ $pay->payment_method ?: 'Transfer' }}</td>
                    <td style="text-align: right;" class="font-mono" style="color: #047857;">Rp {{ number_format($pay->amount_paid, 0, ',', '.') }}</td>
                    <td style="text-align: right;" class="font-mono">Rp {{ number_format($runningPaid, 0, ',', '.') }}</td>
                    <td style="text-align: right;" class="font-mono {{ $rowRemaining > 0 ? 'text-amber-700' : 'text-emerald-700' }}">Rp {{ number_format($rowRemaining, 0, ',', '.') }}</td>
                    <td style="text-align: center; font-size: 7.5pt; color: #475569;">{{ $pay->creator?->name ?? 'Admin' }}</td>
                </tr>
            @empty
                @if($downPayment <= 0)
                    <tr>
                        <td colspan="8" style="text-align: center; color: #94a3b8; padding: 15px; font-style: italic;">
                            Belum ada riwayat setoran cicilan yang tercatat pada unit ini.
                        </td>
                    </tr>
                @endif
            @endforelse
        </tbody>
    </table>

    <!-- Financial Summary Box -->
    <div class="summary-box">
        <table style="width: 100%;">
            <tr>
                <td style="vertical-align: top; width: 60%;">
                    <div style="font-size: 8pt; font-weight: bold; text-transform: uppercase; color: #065f46;">Ringkasan Status Pelunasan:</div>
                    <div class="terbilang-box">
                        <strong>Terbilang Terbayar:</strong> {{ $terbilangTotalPaid }}
                    </div>
                    @if($remainingBalance > 0)
                        <div class="terbilang-box" style="color: #92400e; margin-top: 2px;">
                            <strong>Sisa Tagihan:</strong> {{ $terbilangRemaining }}
                        </div>
                    @else
                        <div class="terbilang-box" style="color: #047857; font-weight: bold; margin-top: 2px;">
                            SELURUH KEWAJIBAN PEMBAYARAN CICILAN TELAH LUNAS 100%
                        </div>
                    @endif
                </td>
                <td style="vertical-align: top; width: 40%; text-align: right;">
                    <table style="width: 100%; font-size: 8.5pt;">
                        <tr>
                            <td style="text-align: right; color: #475569;">Total Harga Kesepakatan:</td>
                            <td style="text-align: right; font-weight: bold; width: 110px;" class="font-mono">Rp {{ number_format($totalPrice, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td style="text-align: right; color: #047857; font-weight: bold;">Total Akumulasi Masuk:</td>
                            <td style="text-align: right; font-weight: bold; color: #047857;" class="font-mono">Rp {{ number_format($totalPaid, 0, ',', '.') }}</td>
                        </tr>
                        <tr style="border-top: 1px dashed #6ee7b7;">
                            <td style="text-align: right; font-weight: bold; color: {{ $remainingBalance > 0 ? '#b45309' : '#047857' }};">Sisa Saldo Tagihan:</td>
                            <td style="text-align: right; font-weight: bold; color: {{ $remainingBalance > 0 ? '#b45309' : '#047857' }};" class="font-mono">Rp {{ number_format($remainingBalance, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td style="text-align: right; color: #64748b; font-size: 7.5pt;">Progress Pelunasan:</td>
                            <td style="text-align: right; font-weight: bold; font-size: 8pt; color: #059669;" class="font-mono">{{ $progressPercentage }}%</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>

    <!-- Digital Validation & Security Stamp -->
    <table style="width: 100%; margin-top: 12px; border-top: 1px solid #e2e8f0; padding-top: 8px;">
        <tr>
            <td style="vertical-align: middle; width: 60px;">
                <img src="{{ $qrCodeUrl }}" style="width: 52px; height: 52px; display: block;" alt="QR Code">
            </td>
            <td style="vertical-align: middle; padding-left: 10px; font-size: 7.5pt; color: #64748b;">
                <div style="font-weight: bold; color: #059669; font-size: 8pt; text-transform: uppercase; margin-bottom: 2px;">
                    DOKUMEN REKAPITULASI RESMI &amp; TERVERIFIKASI DIGITAL
                </div>
                <div>Dokumen rekapitulasi kartu kontrol pembayaran cicilan ini diterbitkan secara otomatis dan sah oleh Sistem Informasi Manajemen Keuangan Properti &amp; Kavling.</div>
                <div style="color: #94a3b8; margin-top: 2px;">Pindai kode QR di samping untuk memverifikasi keaslian dan histori pembayaran secara real-time.</div>
            </td>
        </tr>
    </table>

    <!-- Footer Note -->
    <div class="footer-note">
        <table style="width: 100%;">
            <tr>
                <td>
                    * Dokumen rekapitulasi kartu kontrol cicilan ini dihasilkan otomatis oleh Sistem Informasi Manajemen Properti &amp; Kavling.
                </td>
                <td style="text-align: right; width: 140px;">
                    Waktu Cetak: {{ $generatedAt }}
                </td>
            </tr>
        </table>
    </div>

</body>
</html>
