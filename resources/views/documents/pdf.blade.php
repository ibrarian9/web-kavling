<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>SURAT PEMESANAN PROPERTI - {{ $doc->document_number }}</title>
    <style>
        @page {
            margin: 1.5cm;
            size: A4;
        }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            color: #1e293b;
            font-size: 11pt;
            line-height: 1.5;
        }
        .header {
            text-align: center;
            border-bottom: 3px double #0f172a;
            padding-bottom: 12px;
            margin-bottom: 20px;
        }
        .company-title {
            font-size: 16pt;
            font-weight: bold;
            color: #059669;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .doc-title {
            font-size: 13pt;
            font-weight: bold;
            margin-top: 15px;
            text-decoration: underline;
            text-transform: uppercase;
        }
        .doc-num {
            font-size: 10pt;
            color: #64748b;
            margin-top: 4px;
            font-family: monospace;
        }
        table.info-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            margin-bottom: 20px;
        }
        table.info-table td {
            padding: 6px 8px;
            vertical-align: top;
        }
        table.info-table td.label {
            width: 35%;
            font-weight: bold;
            color: #334155;
            background-color: #f8fafc;
        }
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            margin-bottom: 20px;
        }
        table.data-table th, table.data-table td {
            border: 1px solid #cbd5e1;
            padding: 8px;
            text-align: left;
        }
        table.data-table th {
            background-color: #f1f5f9;
            font-weight: bold;
        }
        .footer-section {
            margin-top: 30px;
            width: 100%;
            border-top: 1px dashed #cbd5e1;
            padding-top: 15px;
        }
        .footer-note {
            font-size: 9pt;
            color: #475569;
            width: 60%;
            float: left;
        }
        .qr-section {
            width: 35%;
            float: right;
            text-align: center;
        }
        .qr-img {
            width: 85px;
            height: 85px;
            border: 1px solid #cbd5e1;
            padding: 3px;
            background: #fff;
            border-radius: 6px;
        }
        .qr-label {
            font-size: 8pt;
            color: #0f172a;
            margin-top: 4px;
            font-weight: bold;
        }
        .clear {
            clear: both;
        }
    </style>
</head>
<body>

    <!-- Header -->
    <div class="header">
        <div class="doc-title">INVOICE PEMBAYARAN & PEMESANAN PROPERTI</div>
        <div class="doc-num">Nomor: {{ $doc->document_number }}</div>
    </div>

    <p>Yang bertanda tangan di bawah ini menerangkan bahwa telah disetujui pemesanan unit properti dengan rincian sebagai berikut:</p>

    <!-- Data Pembeli -->
    <table class="info-table">
        <tr>
            <td class="label">Nama Lengkap Pembeli</td>
            <td>: <strong>{{ $doc->buyer_name }}</strong></td>
        </tr>
        <tr>
            <td class="label">Nomor Kontak / WA</td>
            <td>: {{ $doc->buyer_contact }}</td>
        </tr>
        <tr>
            <td class="label">Alamat Pembeli</td>
            <td>: {{ $doc->buyer_address ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Tanggal Diterbitkan</td>
            <td>: {{ $doc->issued_at ? $doc->issued_at->translatedFormat('d F Y') : date('d F Y') }}</td>
        </tr>
    </table>

    <!-- Rincian Properti & Harga -->
    <table class="data-table">
        <thead>
            <tr>
                <th>Proyek</th>
                <th>Kode Unit</th>
                <th>Tipe</th>
                <th>Spesifikasi Luas</th>
                <th>Harga Jual Disetujui</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><strong>{{ $project->name }}</strong><br><small>{{ $project->location }}</small></td>
                <td><strong style="font-size: 12pt; color: #059669;">{{ $unit->code }}</strong></td>
                <td>{{ strtoupper($unit->type) }}</td>
                <td>
                    Dimensi: {{ $unit->land_length }}m &times; {{ $unit->land_width }}m<br>
                    Luas Total: <strong>{{ number_format($unit->land_area, 0) }} m²</strong>
                    @if($unit->excess_land_area > 0)
                        <br><small style="color: #d97706;">(Termasuk Kelebihan {{ number_format($unit->excess_land_area, 0) }} m²)</small>
                    @endif
                </td>
                <td style="font-family: monospace; font-weight: bold; font-size: 11pt; color: #047857;">
                    Rp {{ number_format($proposal->proposed_price, 0, ',', '.') }}
                </td>
            </tr>
        </tbody>
    </table>

    <!-- Otentikasi & QR Code Section -->
    <div class="footer-section">
        <div class="footer-note">
            <strong style="color: #047857;">Persetujuan & Otorisasi Sistem:</strong>
            <p style="margin-top: 4px; font-size: 9pt;">
                Invoice Pembayaran & Pemesanan Properti ini diterbitkan secara otomatis oleh sistem PT. Atlantik Perkasa Abadi dan telah melalui alur persetujuan paralel dari Field Supervisor dan Founder Executive.
            </p>
            <p style="font-size: 8pt; color: #64748b; margin-top: 6px;">
                * Keabsahan invoice ini terjamin secara digital tanpa memerlukan tanda tangan basah/gambar. Scan QR Code di samping untuk verifikasi langsung di website resmi perusahaan.
            </p>
        </div>

        <div class="qr-section">
            <img src="{{ $qrCodeUrl }}" class="qr-img" alt="QR Code Keabsahan Invoice">
            <div class="qr-label">Scan QR Cek Keabsahan Invoice</div>
            <div style="font-size: 7.5pt; color: #059669; font-weight: bold; margin-top: 2px;">Dokumen Terverifikasi Asli</div>
        </div>
        <div class="clear"></div>
    </div>

</body>
</html>
