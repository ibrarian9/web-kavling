<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Surat Perjanjian Jual Beli (SPJB) - {{ $spjbNumber }}</title>
    <style>
        @page { size: A4 portrait; margin: 20px 25px; }
        body { font-family: Helvetica, Arial, sans-serif; color: #0f172a; font-size: 10.5px; line-height: 1.45; margin: 0; padding: 0; }
        
        .header-table { width: 100%; border-collapse: collapse; margin-bottom: 12px; border-bottom: 2px solid #0f172a; padding-bottom: 8px; }
        .company-title { font-size: 15px; font-weight: bold; color: #0f172a; text-transform: uppercase; letter-spacing: 0.5px; }
        .company-subtitle { font-size: 9.5px; color: #475569; margin-top: 1px; }
        .doc-title { font-size: 13.5px; font-weight: bold; text-align: right; color: #0f172a; text-transform: uppercase; }
        .doc-no { font-size: 9.5px; text-align: right; color: #0f172a; font-family: monospace; font-weight: bold; margin-top: 2px; }
        
        .title-banner { text-align: center; margin: 10px 0 14px 0; border-bottom: 1px solid #cbd5e1; padding-bottom: 8px; }
        .banner-main { font-size: 14px; font-weight: bold; text-transform: uppercase; color: #0f172a; letter-spacing: 0.5px; }
        .banner-sub { font-size: 9.5px; color: #475569; margin-top: 3px; font-style: italic; }

        .pasal-header { background-color: #f1f5f9; border-left: 4px solid #0f172a; padding: 4px 8px; font-weight: bold; font-size: 9.5px; color: #0f172a; text-transform: uppercase; margin-top: 10px; margin-bottom: 6px; }
        
        .info-table { width: 100%; border-collapse: collapse; margin-bottom: 8px; }
        .info-table td { vertical-align: top; padding: 3px 5px; font-size: 9.5px; }
        .label { font-size: 8.5px; color: #475569; text-transform: uppercase; font-weight: bold; }
        .value { font-size: 9.5px; font-weight: bold; color: #0f172a; }

        .details-table { width: 100%; border-collapse: collapse; margin-bottom: 8px; margin-top: 4px; }
        .details-table th { background-color: #f8fafc; color: #0f172a; font-size: 8.5px; text-transform: uppercase; text-align: left; padding: 5px 7px; border: 1px solid #0f172a; }
        .details-table td { padding: 5px 7px; border: 1px solid #cbd5e1; font-size: 9.5px; color: #0f172a; }
        
        .terbilang-box { background-color: #f8fafc; border: 1px dashed #0f172a; padding: 5px 8px; border-radius: 4px; margin-bottom: 8px; font-style: italic; font-size: 9.5px; color: #0f172a; font-weight: bold; }
        
        .terms-box { background-color: #ffffff; border: 1px solid #cbd5e1; padding: 6px 10px; border-radius: 4px; margin-bottom: 10px; font-size: 8.5px; color: #1e293b; line-height: 1.45; }
        .terms-box ol { margin: 0; padding-left: 14px; }
        .terms-box li { margin-bottom: 3px; }

        .signatures-table { width: 100%; border-collapse: collapse; margin-top: 20px; page-break-inside: avoid; }
        .signatures-table td { vertical-align: bottom; text-align: center; font-size: 8.5px; width: 50%; }
        .sig-space { height: 55px; }
        .sig-name { font-weight: bold; font-size: 9.5px; text-decoration: underline; color: #0f172a; }
        .sig-role { font-size: 8.5px; color: #475569; margin-top: 1px; }
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
                <div class="doc-title">SURAT PERJANJIAN JUAL BELI</div>
                <div class="doc-no">{{ $spjbNumber }}</div>
                <div style="font-size: 8.5px; color: #475569; margin-top: 2px;">Tanggal Terbit: {{ format_id_full_date($doc->issued_at ?? $doc->created_at) }}</div>
            </td>
        </tr>
    </table>

    <!-- Banner Judul Perjanjian -->
    <div class="title-banner">
        <div class="banner-main">SURAT PERJANJIAN JUAL BELI (SPJB) PROPERTI</div>
        <div class="banner-sub">Unit {{ $unit->code }} &bull; {{ $project->name }} &bull; PT. Atlantik Perkasa Abadi</div>
    </div>

    <!-- PASAL I: IDENTITAS PARA PIHAK -->
    <div class="pasal-header">PASAL 1: IDENTITAS PARA PIHAK KESEPAKATAN JUAL BELI</div>
    <p style="font-size: 9px; margin-top: 2px; margin-bottom: 6px; color: #334155;">
        Pada hari ini, <strong>{{ format_id_full_date($doc->issued_at ?? $doc->created_at) }}</strong>, telah disepakati Perjanjian Jual Beli antara Pihak-Pihak berikut:
    </p>
    <table class="info-table">
        <tr>
            <td style="width: 50%; border-right: 1px solid #cbd5e1; padding-right: 8px;">
                <div class="label">PIHAK PERTAMA (PENJUAL / FOUNDER)</div>
                <div class="value">{{ $doc->effective_seller_name }}</div>
                <div style="color: #0f172a; font-size: 9px;">No. KTP / NIK: <strong style="font-family: monospace;">{{ $doc->effective_seller_nik }}</strong></div>
                <div style="color: #334155; font-size: 8.5px;">Jabatan: {{ $doc->effective_seller_position }}</div>
                <div style="color: #475569; font-size: 8.5px;">Alamat Penjual: {{ $doc->effective_seller_address }}</div>
            </td>
            <td style="width: 50%; padding-left: 8px;">
                <div class="label">PIHAK KEDUA (PEMBELI)</div>
                <div class="value">{{ $doc->buyer_name }}</div>
                <div style="color: #0f172a; font-size: 9px;">No. KTP / NIK: <strong style="font-family: monospace;">{{ $doc->effective_buyer_nik }}</strong></div>
                <div style="color: #334155; font-size: 8.5px;">No. Telepon / WA: {{ $doc->buyer_contact ?: '-' }}</div>
                <div style="color: #475569; font-size: 8.5px;">Alamat Pembeli: {{ $doc->buyer_address ?: '-' }}</div>
            </td>
        </tr>
    </table>

    <!-- PASAL II: OBJEK PERJANJIAN & SPESIFIKASI UNIT -->
    <div class="pasal-header">PASAL 2: OBJEK PERJANJIAN & SPESIFIKASI UNIT PROPERTI</div>
    <p style="font-size: 9px; margin-top: 2px; margin-bottom: 4px; color: #334155;">
        Pihak Pertama menyatakan menjual dan menyerahkan kepada Pihak Kedua, dan Pihak Kedua menyatakan membeli objek unit berupa:
    </p>
    <table class="details-table">
        <thead>
            <tr>
                <th style="width: 30%;">Komponen Objek</th>
                <th>Keterangan / Spesifikasi Unit Properti</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><strong>Lokasi Proyek / Perumahan</strong></td>
                <td><strong>{{ $project->name }}</strong> (Lokasi: {{ $project->location ?: 'Pekanbaru, Riau' }})</td>
            </tr>
            <tr>
                <td><strong>Kode Unit Kavling/Rumah</strong></td>
                <td><strong style="font-size: 10.5px;">UNIT {{ $unit->code }}</strong> (Kategori: {{ ucfirst($unit->category) }} - Tipe {{ $unit->type }})</td>
            </tr>
            <tr>
                <td><strong>Dimensi & Luas Tanah</strong></td>
                <td>
                    Panjang {{ $unit->land_length ?? '-' }}m x Lebar {{ $unit->land_width ?? '-' }}m &bull; 
                    <strong>Luas Total: {{ number_format($unit->land_area, 0, ',', '.') }} m²</strong>
                    @if($unit->excess_land_area > 0) 
                        (Kelebihan Tanah: {{ number_format($unit->excess_land_area, 0, ',', '.') }} m² @if($unit->excess_cost > 0) - Biaya Rp {{ number_format($unit->excess_cost, 0, ',', '.') }} @endif) 
                    @endif
                </td>
            </tr>
            @if($unit->category === 'rumah' || $unit->building_area)
                <tr>
                    <td><strong>Spesifikasi Bangunan</strong></td>
                    <td>Luas Bangunan: <strong>{{ number_format($unit->building_area, 0, ',', '.') }} m²</strong></td>
                </tr>
            @endif
        </tbody>
    </table>

    <!-- PASAL III: HARGA JUAL & SKEMA PEMBAYARAN -->
    <div class="pasal-header">PASAL 3: HARGA JUAL & SKEMA PEMBAYARAN</div>
    <table class="details-table">
        <thead>
            <tr>
                <th>Komponen Pembayaran</th>
                <th style="text-align: right; width: 45%;">Nominal & Keterangan</th>
            </tr>
        </thead>
        <tbody>
            <tr style="background-color: #f8fafc;">
                <td><strong>TOTAL HARGA DEAL JUAL BELI UNIT</strong></td>
                <td style="text-align: right; font-size: 11px; font-weight: bold; color: #0f172a;">Rp {{ number_format($agreedPrice, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Skema Pembayaran Disepakati</td>
                <td style="text-align: right; font-weight: bold; color: #0f172a;">{{ $paymentScheme }}</td>
            </tr>
            <tr>
                <td>Setoran Uang Muka (DP)</td>
                <td style="text-align: right; font-weight: bold; color: #0f172a;">Rp {{ number_format($dpAmount, 0, ',', '.') }}</td>
            </tr>
            @if($unit->installment)
                <tr>
                    <td>Sisa Piutang & Tenor Cicilan</td>
                    <td style="text-align: right; font-weight: bold; color: #0f172a;">Rp {{ number_format($unit->installment->remaining_balance, 0, ',', '.') }} ({{ $unit->installment->installment_count }} Bulan @ Rp {{ number_format($unit->installment->installment_amount, 0, ',', '.') }}/bulan)</td>
                </tr>
            @endif
        </tbody>
    </table>

    <div class="terbilang-box">
        Terbilang Nominal Deal: {{ $terbilang }}
    </div>

    <!-- PASAL IV & V: HAK, KEWAJIBAN & PENUTUP -->
    <div class="pasal-header">PASAL 4: HAK, KEWAJIBAN & SERAH TERIMA DOKUMEN</div>
    <div class="terms-box">
        <ol>
            <li><strong>Jaminan Penjual:</strong> Pihak Pertama menjamin bahwa objek unit yang dijual bebas dari sengketa, tuntutan pihak ketiga, maupun sengketa hukum.</li>
            <li><strong>Kewajiban Pembeli:</strong> Pihak Kedua wajib menyelesaikan skema pembayaran sesuai kesepakatan tepat pada waktunya.</li>
            <li><strong>Serah Terima:</strong> Serah terima fisik unit dan pengurusan sertifikat/dokumen legalitas kepemilikan akan dilaksanakan setelah Pihak Kedua melunasi seluruh pembayaran.</li>
            <li><strong>Penyelesaian Perselisihan:</strong> Apabila terjadi perbedaan pendapat atau perselisihan di kemudian hari, kedua belah pihak sepakat mengutamakan musyawarah untuk mufakat.</li>
        </ol>
    </div>

    <!-- SIGNATURES TABLE FOR BOTH PARTIES (FOUNDER SELLER & BUYER - NO QR CODE) -->
    <table class="signatures-table">
        <tr>
            <td style="width: 50%; text-align: center;">
                <div class="sig-role">PIHAK KEDUA (PEMBELI)</div>
                <div class="sig-space"></div>
                <div class="sig-name">{{ $doc->buyer_name }}</div>
                <div style="font-size: 8px; color: #475569; font-family: monospace;">No. KTP: {{ $doc->effective_buyer_nik }}</div>
            </td>
            <td style="width: 50%; text-align: center;">
                <div class="sig-role">PIHAK PERTAMA (PENJUAL)</div>
                <div class="sig-space"></div>
                <div class="sig-name">{{ $doc->effective_seller_name }}</div>
                <div style="font-size: 8.5px; color: #475569;">{{ $doc->effective_seller_position }}</div>
                <div style="font-size: 7.5px; color: #64748b; font-family: monospace;">No. KTP: {{ $doc->effective_seller_nik }}</div>
            </td>
        </tr>
    </table>

</body>
</html>
