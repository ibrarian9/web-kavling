<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kuitansi Pembayaran Lahan - {{ $project->name }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 11pt;
            color: #1e293b;
            line-height: 1.5;
            margin: 0;
            padding: 20px;
        }
        .header {
            border-b: 2px solid #6b21a8;
            padding-bottom: 12px;
            margin-bottom: 20px;
        }
        .company-name {
            font-size: 16pt;
            font-weight: bold;
            color: #581c87;
            letter-spacing: 0.5px;
        }
        .company-sub {
            font-size: 9pt;
            color: #64748b;
            text-transform: uppercase;
        }
        .receipt-title {
            text-align: center;
            margin: 15px 0;
        }
        .receipt-title h2 {
            margin: 0;
            font-size: 14pt;
            color: #4c1d95;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .receipt-no {
            font-size: 9pt;
            color: #64748b;
            font-family: monospace;
        }
        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .details-table td {
            padding: 8px 10px;
            vertical-align: top;
        }
        .label {
            width: 30%;
            font-weight: bold;
            color: #475569;
            background-color: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
        }
        .value {
            width: 70%;
            border-bottom: 1px solid #e2e8f0;
            color: #0f172a;
        }
        .amount-box {
            background-color: #f3e8ff;
            border: 1px dashed #7e22ce;
            padding: 12px 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .amount-val {
            font-size: 16pt;
            font-weight: bold;
            color: #6b21a8;
            font-family: monospace;
        }
        .terbilang-text {
            font-style: italic;
            color: #581c87;
            font-size: 10pt;
            margin-top: 4px;
        }
        .qr-section {
            width: 100%;
            margin-top: 25px;
            page-break-inside: avoid;
        }
        .qr-box {
            width: 45%;
            float: left;
            text-align: center;
            border: 1px solid #e2e8f0;
            padding: 10px;
            border-radius: 8px;
            background-color: #faf5ff;
        }
        .qr-box img {
            width: 110px;
            height: 110px;
        }
        .sign-box {
            width: 45%;
            float: right;
            text-align: center;
            font-size: 10pt;
        }
        .clear {
            clear: both;
        }
        .photo-section {
            margin-top: 30px;
            page-break-inside: avoid;
            text-align: center;
        }
        .photo-section h4 {
            margin-bottom: 8px;
            color: #475569;
            text-transform: uppercase;
            font-size: 9pt;
        }
        .photo-img {
            max-width: 80%;
            max-height: 250px;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            padding: 4px;
        }
    </style>
</head>
<body>

    <div class="header">
        <table style="width: 100%;">
            <tr>
                <td style="width: 70%;">
                    <div class="company-name">PT. ATLANTIK PERKASA ABADI</div>
                    <div class="company-sub">Pengembang Properti & Perumahan Kavling</div>
                </td>
                <td style="width: 30%; text-align: right; font-size: 9pt; color: #64748b;">
                    Tanggal Cetak: {{ format_id_datetime(now(), false) }}
                </td>
            </tr>
        </table>
    </div>

    <div class="receipt-title">
        <h2>KUITANSI PEMBAYARAN LAHAN PROYEK</h2>
        <div class="receipt-no">NO: RESI-LAHAN-{{ strtoupper(substr($payment->uuid, 0, 8)) }}</div>
    </div>

    <table class="details-table">
        <tr>
            <td class="label">Nama Proyek</td>
            <td class="value"><strong>{{ $project->name }}</strong> ({{ $project->location }})</td>
        </tr>
        <tr>
            <td class="label">Tanggal Pembayaran</td>
            <td class="value">{{ format_id_full_date($payment->payment_date) }}</td>
        </tr>
        <tr>
            <td class="label">Metode Pembayaran</td>
            <td class="value">{{ $payment->payment_method }}</td>
        </tr>
        <tr>
            <td class="label">Penerima / Pemilik Lahan</td>
            <td class="value">Penjual Lahan Proyek {{ $project->name }}</td>
        </tr>
        <tr>
            <td class="label">Dicatat Oleh</td>
            <td class="value">{{ $payment->creator->name ?? 'System' }}</td>
        </tr>
        <tr>
            <td class="label">Catatan / Keterangan</td>
            <td class="value">{{ $payment->notes ?: '-' }}</td>
        </tr>
    </table>

    <div class="amount-box">
        <div style="font-size: 9pt; text-transform: uppercase; color: #7e22ce; font-weight: bold;">Jumlah Terbayar Ke Penjual:</div>
        <div class="amount-val">Rp {{ number_format($payment->amount_paid, 0, ',', '.') }}</div>
        <div class="terbilang-text">Terbilang: # {{ $terbilang }} #</div>
    </div>

    <div class="qr-section">
        <div class="qr-box">
            <img src="{{ $qrCodeUrl }}" alt="QR Code Verifikasi">
            <div style="font-size: 8pt; color: #6b21a8; margin-top: 4px; font-weight: bold;">PINDAI UNTUK VERIFIKASI KEABSAHAN</div>
            <div style="font-size: 7pt; color: #64748b;">Sistem Keuangan PT. Atlantik Perkasa Abadi</div>
        </div>

        <div class="sign-box" style="border: 1px solid #e2e8f0; padding: 10px; border-radius: 8px; background-color: #faf5ff;">
            <div style="font-size: 9pt; font-weight: bold; color: #581c87; text-transform: uppercase;">DOKUMEN TERVERIFIKASI DIGITAL</div>
            <div style="font-size: 8pt; color: #64748b; margin-top: 4px;">PT. ATLANTIK PERKASA ABADI</div>
            <div style="font-size: 8pt; color: #475569; margin-top: 8px;">Pekanbaru, {{ format_id_full_date(now()) }}</div>
            <div style="font-size: 8pt; color: #64748b; margin-top: 4px;">Pencatat: <strong>{{ $payment->creator->name ?? 'Bagian Keuangan' }}</strong></div>
            <div style="font-size: 7.5pt; color: #7e22ce; margin-top: 6px; font-style: italic;">Sah secara digital via QR Code tanpa tanda tangan basah</div>
        </div>

        <div class="clear"></div>
    </div>

    @if($receiptPhotoBase64)
        <div class="photo-section">
            <h4>Lampiran Resi Pembayaran</h4>
            <img src="{{ $receiptPhotoBase64 }}" class="photo-img" alt="Foto Resi">
        </div>
    @endif

</body>
</html>
