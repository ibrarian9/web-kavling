<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekapitulasi Biaya Proyek Luar - {{ $project->name }}</title>
    <style>
        @page { size: A4 portrait; margin: 20px 25px; }
        body { font-family: Helvetica, Arial, sans-serif; color: #000000; font-size: 10px; line-height: 1.4; margin: 0; padding: 0; }
        
        .header-table { width: 100%; border-collapse: collapse; margin-bottom: 12px; border-bottom: 2px solid #000000; padding-bottom: 8px; }
        .company-title { font-size: 15px; font-weight: bold; color: #000000; text-transform: uppercase; }
        .company-subtitle { font-size: 9px; color: #334155; margin-top: 2px; }
        .doc-title { font-size: 13px; font-weight: bold; text-align: right; color: #000000; text-transform: uppercase; }
        .doc-no { font-size: 9px; text-align: right; color: #000000; font-family: monospace; font-weight: bold; margin-top: 2px; }
        
        .section-header { background-color: #f1f5f9; border-left: 4px solid #000000; padding: 4px 6px; font-weight: bold; font-size: 9.5px; color: #000000; text-transform: uppercase; margin-top: 8px; margin-bottom: 6px; }
        
        .info-table { width: 100%; border-collapse: collapse; margin-bottom: 8px; }
        .info-table td { vertical-align: top; padding: 2px 4px; font-size: 9.5px; }
        .label { font-size: 8.5px; color: #475569; text-transform: uppercase; font-weight: bold; }
        .value { font-size: 9.5px; font-weight: bold; color: #000000; }

        .details-table { width: 100%; border-collapse: collapse; margin-bottom: 10px; margin-top: 4px; }
        .details-table th { background-color: #f8fafc; color: #000000; font-size: 8.5px; text-transform: uppercase; text-align: left; padding: 5px 6px; border: 1px solid #000000; }
        .details-table td { padding: 4px 6px; border: 1px solid #cbd5e1; font-size: 9px; color: #000000; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }

        .summary-box { width: 100%; border-collapse: collapse; margin-top: 8px; margin-bottom: 15px; }
        .summary-box td { padding: 4px 8px; font-size: 9.5px; border: 1px solid #000000; }
        
        .footer-note { font-size: 8px; color: #64748b; text-align: center; margin-top: 15px; border-top: 1px solid #cbd5e1; padding-top: 5px; }
    </style>
</head>
<body>

    <!-- Header / Kop Dokumen -->
    <table class="header-table">
        <tr>
            <td style="width: 55%;">
                <div class="company-title">PT. ATLANTIK PERKASA ABADI</div>
                <div class="company-subtitle">Pencatatan Biaya Proyek Non-Kavling (Material & Upah Tukang)</div>
            </td>
            <td style="width: 45%;" class="text-right">
                <div class="doc-title">REKAPITULASI BIAYA PROYEK LUAR</div>
                <div class="doc-no">Tgl Cetak: {{ date('d/m/Y H:i') }} WIB</div>
            </td>
        </tr>
    </table>

    <!-- Info Proyek Luar -->
    <table class="info-table">
        <tr>
            <td style="width: 50%; border-right: 1px solid #cbd5e1; padding-right: 10px;">
                <div class="label">NAMA PROYEK LUAR</div>
                <div class="value" style="font-size: 11px;">{{ $project->name }}</div>
                <div style="font-size: 8.5px; color: #334155; margin-top: 2px;">Lokasi: {{ $project->location ?: '-' }}</div>
                <div style="font-size: 8.5px; color: #334155;">Status: <strong>{{ strtoupper($project->status) }}</strong></div>
            </td>
            <td style="width: 50%; padding-left: 10px;">
                <div class="label">INFORMASI KLIEN / KONTRAK</div>
                <div class="value">Klien: {{ $project->client_name ?: '-' }} {{ $project->client_phone ? '('.$project->client_phone.')' : '' }}</div>
                @if($project->contract_value > 0)
                    <div style="font-size: 9px; color: #000000; margin-top: 2px;">Nilai Kontrak: <strong>Rp {{ number_format($project->contract_value, 0, ',', '.') }}</strong></div>
                @endif
                <div style="font-size: 8.5px; color: #475569;">Periode: {{ $project->start_date ? $project->start_date->format('d/m/Y') : '-' }} s/d {{ $project->end_date ? $project->end_date->format('d/m/Y') : 'Sekarang' }}</div>
            </td>
        </tr>
    </table>

    <!-- Section 1: Belanja Material / Barang -->
    <div class="section-header">I. RINCIAN PEMBELIAN MATERIAL & BARANG (TOTAL: Rp {{ number_format($totalMaterialCost, 0, ',', '.') }})</div>
    <table class="details-table">
        <thead>
            <tr>
                <th style="width: 5%;" class="text-center">No</th>
                <th style="width: 12%;">Tanggal</th>
                <th style="width: 33%;">Nama Material / Barang</th>
                <th style="width: 20%;">Supplier / Toko</th>
                <th style="width: 12%;" class="text-center">Qty / Satuan</th>
                <th style="width: 18%;" class="text-right">Total Biaya</th>
            </tr>
        </thead>
        <tbody>
            @forelse($project->materials as $idx => $m)
                <tr>
                    <td class="text-center">{{ $idx + 1 }}</td>
                    <td>{{ $m->purchase_date ? $m->purchase_date->format('d/m/Y') : '-' }}</td>
                    <td>
                        <strong>{{ $m->item_name }}</strong>
                        @if($m->notes)
                            <div style="font-size: 8px; color: #475569;">{{ $m->notes }}</div>
                        @endif
                    </td>
                    <td>{{ $m->supplier ?: '-' }}</td>
                    <td class="text-center font-mono">{{ number_format($m->quantity, 1, ',', '.') }} {{ $m->unit }}</td>
                    <td class="text-right font-mono font-bold">Rp {{ number_format($m->total_price, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center" style="color: #64748b; font-style: italic;">Belum ada data belanja material.</td>
                </tr>
            @endforelse
            <tr style="background-color: #f8fafc; font-weight: bold;">
                <td colspan="5" class="text-right">SUBTOTAL BIAYA MATERIAL:</td>
                <td class="text-right">Rp {{ number_format($totalMaterialCost, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    <!-- Section 2: Upah Tukang / Pekerja -->
    <div class="section-header">II. RINCIAN PEMBAYARAN UPAH TUKANG (TOTAL: Rp {{ number_format($totalWageCost, 0, ',', '.') }})</div>
    <table class="details-table">
        <thead>
            <tr>
                <th style="width: 5%;" class="text-center">No</th>
                <th style="width: 12%;">Tanggal</th>
                <th style="width: 33%;">Nama Tukang / Pekerja</th>
                <th style="width: 18%;">Peran / Posisi</th>
                <th style="width: 14%;">Skema Upah</th>
                <th style="width: 18%;" class="text-right">Nominal Upah</th>
            </tr>
        </thead>
        <tbody>
            @forelse($project->workerWages as $idx => $w)
                <tr>
                    <td class="text-center">{{ $idx + 1 }}</td>
                    <td>{{ $w->payment_date ? $w->payment_date->format('d/m/Y') : '-' }}</td>
                    <td>
                        <strong>{{ $w->worker_name }}</strong>
                        @if($w->notes)
                            <div style="font-size: 8px; color: #475569;">{{ $w->notes }}</div>
                        @endif
                    </td>
                    <td>{{ ucfirst($w->role_type) }}</td>
                    <td>{{ ucfirst($w->wage_type) }}</td>
                    <td class="text-right font-mono font-bold">Rp {{ number_format($w->amount, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center" style="color: #64748b; font-style: italic;">Belum ada data pembayaran upah tukang.</td>
                </tr>
            @endforelse
            <tr style="background-color: #f8fafc; font-weight: bold;">
                <td colspan="5" class="text-right">SUBTOTAL UPAH TUKANG:</td>
                <td class="text-right">Rp {{ number_format($totalWageCost, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    <!-- Section 3: Ringkasan Total Akumulasi -->
    <div class="section-header">III. REKAPITULASI TOTAL PENGELUARAN PROYEK</div>
    <table class="summary-box">
        <tr>
            <td style="width: 60%; background-color: #f8fafc;">Total Akumulasi Pembelian Material & Barang</td>
            <td style="width: 40%; text-align: right; font-weight: bold;">Rp {{ number_format($totalMaterialCost, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td style="background-color: #f8fafc;">Total Akumulasi Pembayaran Upah Tukang</td>
            <td style="text-align: right; font-weight: bold;">Rp {{ number_format($totalWageCost, 0, ',', '.') }}</td>
        </tr>
        <tr style="background-color: #f1f5f9;">
            <td><strong>TOTAL KESELURUHAN BIAYA PENGELUARAN PROYEK LUAR</strong></td>
            <td style="text-align: right; font-size: 11px; font-weight: bold; color: #000000;">Rp {{ number_format($totalExpenses, 0, ',', '.') }}</td>
        </tr>
        @if($contractValue > 0)
            <tr>
                <td>Nilai Kontrak / Anggaran Klien</td>
                <td style="text-align: right; font-weight: bold;">Rp {{ number_format($contractValue, 0, ',', '.') }}</td>
            </tr>
            <tr style="background-color: #f8fafc;">
                <td><strong>Sisa Anggaran / Margin Keuntungan Proyek</strong></td>
                <td style="text-align: right; font-weight: bold; color: {{ $marginBalance >= 0 ? '#166534' : '#991b1b' }};">
                    {{ $marginBalance >= 0 ? '+' : '' }} Rp {{ number_format($marginBalance, 0, ',', '.') }}
                </td>
            </tr>
        @endif
    </table>

    <div class="footer-note">
        Dokumen dicetak secara otomatis melalui Sistem Manajemen Properti PT. Atlantik Perkasa Abadi &bull; Laporan Pengeluaran Proyek Luar
    </div>

</body>
</html>
