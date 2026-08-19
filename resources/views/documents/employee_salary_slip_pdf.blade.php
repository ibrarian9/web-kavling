<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>SLIP GAJI - {{ $salary->employee_name }}</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 25mm 20mm 20mm 20mm;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11pt;
            line-height: 1.5;
            color: #1e293b;
        }

        .header {
            text-align: center;
            border-bottom: 2.5px solid #0f766e;
            padding-bottom: 12px;
            margin-bottom: 20px;
        }

        .company-name {
            font-size: 16pt;
            font-weight: bold;
            color: #0f766e;
            letter-spacing: 1px;
        }

        .company-sub {
            font-size: 9pt;
            color: #475569;
            margin-top: 2px;
        }

        .document-title {
            text-align: center;
            font-size: 13pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            margin-bottom: 20px;
            color: #0f172a;
            border-bottom: 1px dashed #cbd5e1;
            padding-bottom: 8px;
        }

        .info-table {
            width: 100%;
            margin-bottom: 20px;
            border-collapse: collapse;
        }

        .info-table td {
            padding: 5px 0;
            vertical-align: top;
            font-size: 10pt;
        }

        .info-table td.label {
            width: 25%;
            font-weight: bold;
            color: #334155;
        }

        .info-table td.colon {
            width: 3%;
        }

        .info-table td.value {
            width: 72%;
        }

        .salary-box {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }

        .salary-box th {
            background-color: #0f766e;
            color: #ffffff;
            font-size: 10pt;
            font-weight: bold;
            text-transform: uppercase;
            padding: 8px 10px;
            text-align: left;
        }

        .salary-box td {
            padding: 8px 10px;
            border-bottom: 1px solid #e2e8f0;
            font-size: 10pt;
        }

        .salary-box tr.total-row td {
            background-color: #f0fdf4;
            font-weight: bold;
            font-size: 11pt;
            color: #065f46;
            border-top: 2px solid #059669;
            border-bottom: 2px solid #059669;
        }

        .text-right {
            text-align: right;
        }

        .notes-box {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 10px 12px;
            font-size: 9.5pt;
            color: #334155;
            margin-bottom: 30px;
        }

        .signature-table {
            width: 100%;
            margin-top: 30px;
        }

        .signature-table td {
            width: 50%;
            text-align: center;
            vertical-align: top;
            font-size: 10pt;
        }

        .signature-space {
            height: 65px;
        }
    </style>
</head>
<body>

    <div class="header">
        <div class="company-name">PT. ATLANTIK PERKASA ABADI</div>
        <div class="company-sub">Pengembang Properti Kavling Tanah & Perumahan Modern</div>
        <div class="company-sub">Jl. Utama Properti No. 88, Pekanbaru, Riau | Telp: (0761) 889900</div>
    </div>

    <div class="document-title">
        SLIP GAJI KARYAWAN / STAF<br>
        <span style="font-size: 10pt; font-weight: normal; color: #475569;">PERIODE BULAN {{ strtoupper($payment->employeeSalary->getIndonesianMonth($payment->payroll_month ?? date('n'))) }} {{ $payment->payroll_year }}</span>
    </div>

    <table class="info-table">
        <tr>
            <td class="label">No. Slip Gaji</td>
            <td class="colon">:</td>
            <td class="value" style="font-family: monospace; font-weight: bold; color: #0f766e;">SLIP/PAY/{{ strtoupper(substr($payment->uuid, 0, 8)) }}</td>
        </tr>
        <tr>
            <td class="label">Nama Karyawan</td>
            <td class="colon">:</td>
            <td class="value" style="font-weight: bold; color: #0f172a;">{{ $salary->employee_name }}</td>
        </tr>
        <tr>
            <td class="label">Jabatan / Posisi</td>
            <td class="colon">:</td>
            <td class="value">{{ $salary->position ?? 'Staf Karyawan' }} ({{ ucfirst($salary->employee_type) }})</td>
        </tr>
        <tr>
            <td class="label">Tanggal Bayar</td>
            <td class="colon">:</td>
            <td class="value">{{ format_id_full_date($payment->payment_date) }}</td>
        </tr>
        <tr>
            <td class="label">Metode Bayar</td>
            <td class="colon">:</td>
            <td class="value">
                {{ strtoupper($payment->payment_method) }}
                @if($payment->bank_name)
                    ({{ $payment->bank_name }} - {{ $payment->account_number }})
                @endif
            </td>
        </tr>
    </table>

    <table class="salary-box">
        <thead>
            <tr>
                <th>Komponen Penggajian</th>
                <th class="text-right">Nominal (Rp)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Gaji Pokok (Basic Salary)</td>
                <td class="text-right">Rp {{ number_format($payment->basic_salary, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Tunjangan Jabatan & Operasional</td>
                <td class="text-right">Rp {{ number_format($payment->allowance, 0, ',', '.') }}</td>
            </tr>
            @if($payment->bonus > 0)
                <tr>
                    <td>Bonus / Insentif Kinerja</td>
                    <td class="text-right">Rp {{ number_format($payment->bonus, 0, ',', '.') }}</td>
                </tr>
            @endif
            @if($payment->deductions > 0)
                <tr style="color: #991b1b;">
                    <td>Potongan (BPJS / Kasbon / Denda)</td>
                    <td class="text-right">- Rp {{ number_format($payment->deductions, 0, ',', '.') }}</td>
                </tr>
            @endif
            <tr class="total-row">
                <td>TOTAL GAJI BERSIH (NET SALARY)</td>
                <td class="text-right">Rp {{ number_format($payment->net_salary, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    @if($payment->notes)
        <div class="notes-box">
            <strong>Catatan Penggajian:</strong> {{ $payment->notes }}
        </div>
    @endif

    <table class="signature-table">
        <tr>
            <td>
                <div>Penerima Gaji (Karyawan),</div>
                <div class="signature-space"></div>
                <div><strong>({{ $salary->employee_name }})</strong></div>
            </td>
            <td>
                <div>Pekanbaru, {{ format_id_full_date($payment->payment_date) }}</div>
                <div>Pemberi Gaji (Founder / Direktur Utama),</div>
                <div class="signature-space"></div>
                <div><strong>(Founder PT. Atlantik Perkasa Abadi)</strong></div>
            </td>
        </tr>
    </table>

</body>
</html>
