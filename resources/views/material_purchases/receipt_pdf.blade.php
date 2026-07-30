<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Resi Kuitansi Belanja Material #{{ $material->id }}</title>
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
            color: #d97706;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .meta-box {
            background-color: #fffbeb;
            border: 1px solid #fef3c7;
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
            color: #78350f;
            font-weight: bold;
            width: 30%;
        }
        .meta-value {
            color: #0f172a;
            font-weight: bold;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .items-table th {
            background-color: #0f172a;
            color: #ffffff;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
            padding: 8px 10px;
            border: 1px solid #0f172a;
        }
        .items-table td {
            padding: 8px 10px;
            border-bottom: 1px solid #e2e8f0;
            font-size: 11px;
        }
        .photo-box {
            margin-top: 15px;
            text-align: center;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 10px;
            background-color: #f8fafc;
        }
        .photo-img {
            max-width: 80%;
            max-height: 250px;
            border-radius: 6px;
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
                <div class="brand-subtitle">Kuitansi Bukti Pembelian & Belanja Material</div>
            </td>
            <td class="doc-type">
                KUITANSI MATERIAL
            </td>
        </tr>
    </table>

    <div class="meta-box">
        <table class="meta-table">
            <tr>
                <td class="meta-label">Nomor Transaksi:</td>
                <td class="meta-value">RESI-MAT-{{ $material->id }}</td>
                <td class="meta-label">Tanggal Pembelian:</td>
                <td class="meta-value">{{ $material->purchase_date ? $material->purchase_date->format('d F Y') : '-' }}</td>
            </tr>
            <tr>
                <td class="meta-label">Proyek / Unit:</td>
                <td class="meta-value">{{ $project->name ?? 'Global' }} (Unit: {{ $unit->code ?? '-' }})</td>
                <td class="meta-label">Dibelikan Oleh:</td>
                <td class="meta-value">{{ $worker->name ?? ($pengawas->name ?? 'Pengawas Lapangan') }}</td>
            </tr>
        </table>
    </div>

    <table class="items-table">
        <thead>
            <tr>
                <th style="width: 40%;">Nama Barang / Material</th>
                <th style="width: 15%; text-align: center;">Jumlah</th>
                <th style="width: 15%; text-align: center;">Satuan</th>
                <th style="width: 15%; text-align: right;">Harga Satuan</th>
                <th style="width: 15%; text-align: right;">Total Harga</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="font-weight: bold; color: #0f172a;">{{ $material->item_name }}</td>
                <td style="text-align: center; font-family: monospace;">{{ number_format($material->quantity, 0, ',', '.') }}</td>
                <td style="text-align: center; text-transform: uppercase;">{{ $material->unit_measure }}</td>
                <td style="text-align: right; font-family: monospace;">Rp {{ number_format($material->unit_price, 0, ',', '.') }}</td>
                <td style="text-align: right; font-family: monospace; font-weight: bold; color: #d97706;">
                    Rp {{ number_format($material->total_price, 0, ',', '.') }}
                </td>
            </tr>
        </tbody>
    </table>

    <div style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 10px 15px; margin-bottom: 20px;">
        <div style="font-size: 10px; color: #64748b; font-weight: bold; uppercase">Terbilang:</div>
        <div style="font-size: 12px; font-weight: bold; color: #0f172a; font-style: italic; margin-top: 2px;">
            "{{ $terbilang }}"
        </div>
        @if($material->notes)
            <div style="font-size: 10px; color: #64748b; font-weight: bold; margin-top: 8px;">Catatan:</div>
            <div style="font-size: 11px; color: #334155;">{{ $material->notes }}</div>
        @endif
    </div>

    @if($receiptPhotoBase64)
        <div class="photo-box">
            <div style="font-size: 10px; font-weight: bold; color: #64748b; margin-bottom: 6px;">FOTO STRUK / NOTA BELANJA:</div>
            <img src="{{ $receiptPhotoBase64 }}" class="photo-img" alt="Foto Struk Belanja">
        </div>
    @endif

    <!-- Centered QR Code Official Verification -->
    <div class="qr-section">
        <img src="{{ $qrCodeUrl }}" class="qr-img" alt="QR Code Verifikasi Resmi">
        <div class="qr-text">DOKUMEN RESMI TERVERIFIKASI SISTEM KAVLING & PROPERTI</div>
        <div class="qr-subtext">Scan QR Code di atas untuk memverifikasi keabsahan kuitansi belanja material secara publik.</div>
    </div>

</body>
</html>
