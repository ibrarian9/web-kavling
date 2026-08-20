<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Surat Perintah Kerja (SPK) - {{ $spkNumber }}</title>
    <style>
        @page { size: A4 portrait; margin: 20px 25px; }
        body { font-family: Helvetica, Arial, sans-serif; color: #000000; font-size: 11px; line-height: 1.4; margin: 0; padding: 0; }
        
        .header-table { width: 100%; border-collapse: collapse; margin-bottom: 15px; border-bottom: 2px solid #000000; padding-bottom: 10px; }
        .company-title { font-size: 16px; font-weight: bold; color: #000000; text-transform: uppercase; tracking: 0.5px; }
        .company-subtitle { font-size: 10px; color: #334155; margin-top: 2px; }
        .doc-title { font-size: 15px; font-weight: bold; text-align: right; color: #000000; text-transform: uppercase; }
        .doc-no { font-size: 10px; text-align: right; color: #000000; font-family: monospace; font-weight: bold; margin-top: 2px; }
        
        .section-header { background-color: #f1f5f9; border-left: 4px solid #000000; padding: 5px 8px; font-weight: bold; font-size: 10px; color: #000000; text-transform: uppercase; margin-top: 10px; margin-bottom: 8px; }
        
        .info-table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        .info-table td { vertical-align: top; padding: 3px 6px; font-size: 10px; }
        .label { font-size: 9px; color: #475569; text-transform: uppercase; font-weight: bold; }
        .value { font-size: 10px; font-weight: bold; color: #000000; }

        .details-table { width: 100%; border-collapse: collapse; margin-bottom: 10px; margin-top: 5px; }
        .details-table th { background-color: #f8fafc; color: #000000; font-size: 9px; text-transform: uppercase; text-align: left; padding: 6px 8px; border: 1px solid #000000; }
        .details-table td { padding: 6px 8px; border: 1px solid #cbd5e1; font-size: 10px; color: #000000; }
        
        .terbilang-box { background-color: #f8fafc; border: 1px dashed #000000; padding: 6px 10px; border-radius: 4px; margin-bottom: 10px; font-style: italic; font-size: 10px; color: #000000; font-weight: bold; }
        
        .terms-box { background-color: #ffffff; border: 1px solid #cbd5e1; padding: 8px 12px; border-radius: 4px; margin-bottom: 15px; font-size: 9px; color: #1e293b; line-height: 1.5; }
        .terms-box ol { margin: 0; padding-left: 15px; }

        .verification-footer { text-align: center; margin-top: 25px; page-break-inside: avoid; }
        .qr-box { display: inline-block; padding: 6px; border: 1px solid #000000; background-color: #ffffff; border-radius: 4px; }
        .qr-box img { width: 90px; height: 90px; display: block; margin: 0 auto; }
    </style>
</head>
<body>

    <!-- Header / Kop Surat -->
    <table class="header-table">
        <tr>
            <td style="width: 55%;">
                <div class="company-title">PT. ATLANTIK PERKASA ABADI</div>
                <div class="company-subtitle">Developer Perumahan, Real Estate & Investor Kavling Properti</div>
                <div class="company-subtitle">Proyek: <strong>{{ $project->name }}</strong></div>
            </td>
            <td style="width: 45%;" class="text-right">
                <div class="doc-title">SURAT PERINTAH KERJA (SPK)</div>
                <div class="doc-no">{{ $spkNumber }}</div>
                <div style="font-size: 9px; color: #475569; margin-top: 2px;">Tanggal Terbit: {{ format_id_full_date($payroll->created_at) }}</div>
            </td>
        </tr>
    </table>

    <!-- Section 1: Identitas Para Pihak -->
    <div class="section-header">I. IDENTITAS PARA PIHAK KESEPAKATAN KERJA</div>
    <table class="info-table">
        <tr>
            <td style="width: 50%; border-right: 1px solid #cbd5e1; pr-3;">
                <div class="label">PIHAK PERTAMA (PEMBERI KERJA)</div>
                <div class="value">PT. ATLANTIK PERKASA ABADI</div>
                <div style="color: #334155; font-size: 9px;">Perwakilan: {{ $creator->name ?? 'Management / Supervisor Proyek' }}</div>
                <div style="color: #334155; font-size: 9px;">Jabatan: {{ ucfirst($creator->role ?? 'Management') }}</div>
                <div style="color: #334155; font-size: 9px;">Lokasi: {{ $project->name }}</div>
            </td>
            <td style="width: 50%; pl-3;">
                <div class="label">PIHAK KEDUA (PENERIMA KERJA / WORKER)</div>
                <div class="value">{{ $worker->name }}</div>
                <div style="color: #334155; font-size: 9px;">Tipe Pekerja: <strong>{{ strtoupper($worker->type) }}</strong> {{ $worker->specialty ? '('.$worker->specialty.')' : '' }}</div>
                <div style="color: #334155; font-size: 9px;">No. Telepon / WA: {{ $worker->phone ?: '-' }}</div>
                <div style="color: #334155; font-size: 9px;">Alamat: {{ $worker->address ?: '-' }}</div>
            </td>
        </tr>
    </table>

    <!-- Section 2: Objek Unit & Perincian Borongan -->
    <div class="section-header">II. OBJEK UNIT & PERINCIAN KONTRAK BORONGAN</div>
    <table class="details-table">
        <thead>
            <tr>
                <th style="width: 30%;">Komponen Objek</th>
                <th>Keterangan / Spesifikasi Objek</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><strong>Lokasi Proyek</strong></td>
                <td>{{ $project->name }}</td>
            </tr>
            @if($unit->category === 'infrastruktur' || $unit->status === 'infrastruktur')
                <tr>
                    <td><strong>Objek Fasilitas Umum</strong></td>
                    <td><strong style="font-size: 11px;">{{ $unit->code }}</strong></td>
                </tr>
                <tr>
                    <td><strong>Luas & Spesifikasi Pengerjaan</strong></td>
                    <td>
                        Luas Pengerjaan: <strong>{{ number_format($unit->land_area, 0, ',', '.') }} m²</strong>
                        @if($unit->specifications)
                            <br><span style="font-size: 9px; color: #334155;">Catatan Teknis: {{ $unit->specifications }}</span>
                        @endif
                    </td>
                </tr>
            @else
                <tr>
                    <td><strong>Kode Unit Kavling/Rumah</strong></td>
                    <td><strong style="font-size: 11px;">UNIT {{ $unit->code }}</strong></td>
                </tr>
                <tr>
                    <td><strong>Ukuran & Spesifikasi Unit</strong></td>
                    <td>Luas Tanah: {{ number_format($unit->land_area, 0, ',', '.') }} m² @if($unit->building_area) | Luas Bangunan: {{ number_format($unit->building_area, 0, ',', '.') }} m² @endif</td>
                </tr>
            @endif
            <tr>
                <td><strong>Lingkup & Catatan Pekerjaan</strong></td>
                <td>{{ $payroll->notes ?: 'Pekerjaan pembangunan, persiapan, finishing borongan unit sesuai instruksi pengawas lapangan.' }}</td>
            </tr>
        </tbody>
    </table>

    <!-- Section 3: Nilai Pembayaran Kontrak Borongan -->
    <div class="section-header">III. KESEPAKATAN NILAI KONTRAK & SKEMA PEMBAYARAN</div>
    <table class="details-table">
        <thead>
            <tr>
                <th>Komponen Kesepakatan</th>
                <th style="text-align: right; width: 40%;">Nilai & Keterangan</th>
            </tr>
        </thead>
        <tbody>
            <tr style="background-color: #f8fafc;">
                <td><strong>TOTAL NILAI PEMBAYARAN KONTRAK BORONGAN</strong></td>
                <td style="text-align: right; font-size: 12px; font-weight: bold; color: #000000;">Rp {{ number_format($payroll->agreed_salary, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Skema Pembayaran Kontrak</td>
                <td style="text-align: right; font-weight: bold; color: #000000;">{{ ucfirst($payroll->payment_frequency) }}</td>
            </tr>
            <tr>
                <td>Akumulasi Terbayar s/d Saat Ini</td>
                <td style="text-align: right; font-weight: bold; color: #000000;">Rp {{ number_format($payroll->paid_amount, 0, ',', '.') }} ({{ $payroll->progress_percentage }}%)</td>
            </tr>
            <tr>
                <td>Sisa Pembayaran Kontrak Belum Dibayar</td>
                <td style="text-align: right; font-weight: bold; color: #000000;">Rp {{ number_format($payroll->remaining_salary, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    <div class="terbilang-box">
        Terbilang Nominal Kontrak: {{ $terbilang }}
    </div>

    <!-- Section 4: Ketentuan SPK -->
    <div class="section-header">IV. KETENTUAN & KESEPAKATAN PEKERJAAN</div>
    <div class="terms-box">
        <ol>
            <li>Pihak Kedua (Penerima Kerja) bersedia melaksanakan seluruh pekerjaan borongan unit sesuai spesifikasi teknis dan petunjuk dari Pihak Pertama.</li>
            <li>Pembayaran kontrak borongan dilakukan secara bertahap berdasarkan realisasi fisik progres pekerjaan yang disetujui Pengawas Proyek.</li>
            <li>Surat Perintah Kerja (SPK) ini berlaku mengikat kedua belah pihak sejak tanggal diterbitkan hingga seluruh pekerjaan unit diselesaikan.</li>
        </ol>
    </div>

    <!-- Centered QR Code / Barcode Verification Block (No Manual Signatures) -->
    <div class="verification-footer">
        <div style="font-size: 9px; font-weight: bold; color: #000000; text-transform: uppercase; margin-bottom: 6px;">
            DOKUMEN SURAT PERINTAH KERJA (SPK) SAH TERVERIFIKASI SISTEM
        </div>
        <div class="qr-box">
            <img src="{{ $qrCodeUrl }}" alt="QR Code SPK">
        </div>
        <div style="font-size: 8px; color: #334155; margin-top: 6px; font-family: monospace; font-weight: bold;">
            SCAN QR CODE UNTUK VERIFIKASI KEABSAHAN DOKUMEN SPK RESMI
        </div>
        <div style="font-size: 8px; color: #64748b; margin-top: 2px;">
            PT. Atlantik Perkasa Abadi &bull; {{ $spkNumber }}
        </div>
    </div>

</body>
</html>
