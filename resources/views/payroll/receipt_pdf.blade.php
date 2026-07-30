<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Resi Gaji - RESI-{{ substr($payment->uuid, 0, 8) }}</title>
    <style>
        @page { size: A4 portrait; margin: 20px; }
        body { font-family: Helvetica, Arial, sans-serif; color: #1e293b; font-size: 12px; line-height: 1.4; margin: 0; padding: 0; }
        .header-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; border-bottom: 2px solid #0284c7; padding-bottom: 10px; }
        .company-title { font-size: 18px; font-weight: bold; color: #0369a1; text-transform: uppercase; }
        .company-subtitle { font-size: 10px; color: #64748b; }
        .doc-title { font-size: 16px; font-weight: bold; text-align: right; color: #0f172a; text-transform: uppercase; }
        .doc-no { font-size: 10px; text-align: right; color: #0284c7; font-family: monospace; font-weight: bold; }
        
        .info-box { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        .info-box td { vertical-align: top; padding: 4px; }
        .label { font-size: 9px; color: #64748b; text-transform: uppercase; font-weight: bold; }
        .value { font-size: 11px; font-weight: bold; color: #0f172a; }

        .details-table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        .details-table th { background-color: #f1f5f9; color: #334155; font-size: 10px; text-transform: uppercase; text-align: left; padding: 8px; border-bottom: 1px solid #cbd5e1; }
        .details-table td { padding: 8px; border-bottom: 1px solid #e2e8f0; font-size: 11px; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }

        .terbilang-box { background-color: #f8fafc; border: 1px dashed #cbd5e1; padding: 8px 12px; border-radius: 4px; margin-bottom: 15px; font-style: italic; font-size: 11px; color: #334155; }
        
        .footer-table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .footer-table td { vertical-align: bottom; text-align: center; font-size: 10px; }
        .qr-code img { width: 90px; height: 90px; }

        .attachment-section { margin-top: 25px; page-break-inside: avoid; }
        .attachment-title { font-size: 11px; font-weight: bold; color: #475569; border-bottom: 1px solid #cbd5e1; padding-bottom: 4px; margin-bottom: 10px; text-transform: uppercase; }
        .attachment-img { max-width: 100%; max-height: 250px; border: 1px solid #cbd5e1; border-radius: 4px; display: block; margin: 0 auto; }
    </style>
</head>
<body>

    <!-- Header -->
    <table class="header-table">
        <tr>
            <td style="width: 60%;">
                <div class="company-title">REKAP PENGGAJIAN TUKANG & MANDOR</div>
                <div class="company-subtitle">Sistem Keuangan Manajemen Konstruksi Properti Kavling</div>
                <div class="company-subtitle">{{ $project->name }}</div>
            </td>
            <td style="width: 40%;" class="text-right">
                <div class="doc-title">RESI GAJI</div>
                <div class="doc-no">REF: {{ substr($payment->uuid, 0, 13) }}</div>
                <div style="font-size: 9px; color: #64748b;">Tanggal: {{ $payment->payment_date ? $payment->payment_date->format('d/m/Y') : date('d/m/Y') }}</div>
            </td>
        </tr>
    </table>

    <!-- Info Box -->
    <table class="info-box">
        <tr>
            <td style="width: 50%;">
                <div class="label">Penerima (Pekerja)</div>
                <div class="value">{{ $worker->name }}</div>
                <div style="font-size: 10px; color: #475569;">Jabatan: {{ ucfirst($worker->type) }} {{ $worker->specialty ? '('.$worker->specialty.')' : '' }}</div>
                @if($worker->phone)
                    <div style="font-size: 9px; color: #64748b;">Telp: {{ $worker->phone }}</div>
                @endif
            </td>
            <td style="width: 50%;">
                <div class="label">Lokasi / Unit Kerja</div>
                <div class="value">{{ $project->name }}</div>
                <div style="font-size: 10px; color: #0284c7; font-weight: bold;">
                    {{ $unit ? 'Unit '.$unit->code.' (Type '.$unit->type.')' : 'Area Umum Proyek' }}
                </div>
            </td>
        </tr>
    </table>

    <!-- Table Details -->
    <table class="details-table">
        <thead>
            <tr>
                <th>Deskripsi Komponen Gaji</th>
                <th class="text-right">Nominal</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <strong>Kesepakatan Total Gaji Borongan Unit</strong><br>
                    <span style="font-size: 9px; color: #64748b;">Total Kontrak Pekerja untuk Unit ini</span>
                </td>
                <td class="text-right">Rp {{ number_format($payroll->agreed_salary, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>
                    <strong>Pencairan Gaji Pekerja</strong><br>
                    <span style="font-size: 9px; color: #64748b;">Tahap Pembayaran ({{ ucfirst($payroll->payment_frequency) }}) - {{ str_replace('_', ' ', strtoupper($payment->payment_method)) }}</span>
                </td>
                <td class="text-right">Rp {{ number_format($payment->amount_paid, 0, ',', '.') }}</td>
            </tr>
            <tr style="background-color: #f0fdf4; font-weight: bold; font-size: 12px; color: #15803d;">
                <td>TOTAL DITERIMA PEKERJA</td>
                <td class="text-right">Rp {{ number_format($payment->amount_paid, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td colspan="2" style="font-size: 9px; color: #64748b;">
                    Akumulasi Dibayar s/d Saat Ini: <strong>Rp {{ number_format($payroll->paid_amount, 0, ',', '.') }}</strong> 
                    ({{ $payroll->progress_percentage }}%) | Sisa Kontrak: <strong>Rp {{ number_format($payroll->remaining_salary, 0, ',', '.') }}</strong>
                </td>
            </tr>
        </tbody>
    </table>

    <!-- Terbilang -->
    <div class="terbilang-box">
        <strong>Terbilang:</strong> {{ $terbilang }}
    </div>

    <!-- QR Code Verification Section (No Signatures) -->
    <table class="footer-table" style="margin-top: 25px;">
        <tr>
            <td class="text-center qr-code">
                <img src="{{ $qrCodeUrl }}" alt="QR Code Verifikasi">
                <div style="font-size: 9px; color: #0284c7; margin-top: 6px; font-weight: bold; text-transform: uppercase;">
                    Dokumen Resi Penggajian Sah Terverifikasi Sistem
                </div>
                <div style="font-size: 8px; color: #64748b; margin-top: 2px;">
                    Scan QR Code untuk memverifikasi keabsahan dokumen pembayaran gaji worker secara resmi.
                </div>
            </td>
        </tr>
    </table>

    <!-- Embedded Receipt Photo Attachment if exists -->
    @if($receiptPhotoBase64)
    <div class="attachment-section">
        <div class="attachment-title">Lampiran Foto Struk Transfer Bank (Bukti Pembayaran)</div>
        <img src="{{ $receiptPhotoBase64 }}" class="attachment-img" alt="Struk Transfer Bank">
    </div>
    @endif

</body>
</html>
