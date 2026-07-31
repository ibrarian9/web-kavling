# 🎴 Panduan Standarisasi Card Status & Badge Status (Status Card Design System)

Dokumen ini merupakan panduan resmi untuk **menyeragamkan tampilan, warna, ukuran, hirarki visual, dan penamaan Card Status / KPI Summary serta Status Badges** di seluruh menu aplikasi web **Kavling & Properti**. Tujuan standarisasi ini adalah menciptakan antarmuka (UI) yang konsisten, modern, premium, intuitif, dan responsif bagi pengguna.

---

## 🗂️ 1. Kategorisasi Summary KPI Status Cards

Kartu ringkasan status di bagian atas halaman (Header Summary KPI Grid) dibagi menjadi 4 varian tema visual utama:

| Tema KPI Card | Skema Warna | Background & Border | Contoh Penggunaan | Kelas / Utility |
| :--- | :--- | :--- | :--- | :--- |
| **Emerald / Positive** | Emerald / Green | `bg-white border-slate-200/80` (Icon: `bg-emerald-50 text-emerald-600 border-emerald-100`) | Total Penjualan, Kas Masuk, Unit Lunas, Profit | `kpi-card-emerald` |
| **Rose / Alert / Cost** | Rose / Red | `bg-white border-slate-200/80` (Icon: `bg-rose-50 text-rose-600 border-rose-100`) | Kas Keluar, Total Biaya, Piutang Menunggak | `kpi-card-rose` |
| **Amber / Warning** | Amber / Yellow | `bg-white border-slate-200/80` (Icon: `bg-amber-50 text-amber-600 border-amber-100`) | Menunggu Approval, Sisa Tagihan, Booked | `kpi-card-amber` |
| **Blue / Indigo / Neutral** | Blue / Slate / Dark | `bg-slate-900 text-white` atau `bg-white` (Icon: `bg-blue-50 text-blue-600 border-blue-100`) | Total Proyek, Saldo Bersih, Total Unit | `kpi-card-dark` / `kpi-card-blue` |

---

## 📐 2. Standard Layout & Anatomi Summary Status Card

Setiap Summary KPI Status Card wajib memiliki anatomi standar sebagai berikut:
1. **Header Label**: `text-[11px] font-bold uppercase tracking-wider text-slate-400`
2. **Badge / Icon Box**: Container `p-2.5 rounded-xl` dengan warna lembut sesuai varian.
3. **Primary Number (Angka Utama)**: `text-2xl font-extrabold font-mono mt-2`
4. **Subtitle Context**: `text-[11px] text-slate-400 mt-1` (Penjelasan singkat isi data)

### Contoh Blade Component Layout:

```html
<!-- KPI Card Standard (Emerald / Positive) -->
<div class="card-clean p-5 relative overflow-hidden">
    <div class="flex items-center justify-between">
        <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Total Unit Terjual</span>
        <div class="p-2.5 rounded-xl bg-emerald-50 text-emerald-600 border border-emerald-100">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        </div>
    </div>
    <p class="text-2xl font-extrabold text-slate-900 font-mono mt-2">12 Unit</p>
    <p class="text-[11px] text-slate-400 mt-1">Akumulasi unit kavling & rumah disetujui</p>
</div>
```

---

## 🏷️ 3. Standard Badge Status (Status Pills di Tabel & Modal)

Status badge digunakan pada kolom tabel atau header unit/transaksi. Semua status badge memiliki spesifikasi:
- **Tinggi & Padding:** `px-2.5 py-0.5`
- **Teks:** `text-xs font-semibold`
- **Border Radius:** `rounded-full` (Pill format)
- **Border:** `border border-[color]-200` atau `300`

### Daftar Warna & Status Standard:

| Status | Warna Badge | Sintaks CSS / Tailwind | Contoh Penggunaan |
| :--- | :--- | :--- | :--- |
| `Tersedia` | Emerald Soft | `bg-emerald-50 text-emerald-700 border border-emerald-200` | Unit tersedia untuk booking |
| `Menunggu` / `Pending` | Amber Soft (Pulse) | `bg-amber-50 text-amber-700 border border-amber-300 animate-pulse` | Menunggu approval Founder |
| `Disetujui` / `Terjual` | Teal / Indigo Soft | `bg-teal-50 text-teal-700 border border-teal-200` | Proposal disetujui / Unit terjual |
| `Booked` | Blue / Amber Soft | `bg-amber-50 text-amber-700 border border-amber-200` | Unit ter-booking fee |
| `Lunas` | Emerald Solid Soft | `bg-emerald-100 text-emerald-800 border border-emerald-300` | Cicilan / Pembayaran Lunas 100% |
| `Menunggak` / `Ditolak` | Rose Soft | `bg-rose-50 text-rose-700 border border-rose-200` | Cicilan menunggak / Proposal ditolak |
| `Draft` / `Batal` | Slate Light | `bg-slate-100 text-slate-600 border border-slate-300` | Transaksi dibatalkan / Draft |

---

## 🛠️ 4. Pembagian Implementasi Bertahap (Phased Implementation Plan)

- **Fase 1**: Dokumentasi Sistem Desain (`docs/standard_status_card_design_system.md`) & Penambahan Kelas CSS pendukung di `resources/css/app.css`.
- **Fase 2**: Penerapan pada Dashboard Utama (`dashboard.blade.php`) & Index Proyek (`projects/index.blade.php`).
- **Fase 3**: Penerapan pada Detail Proyek (`projects/show.blade.php`) & Daftar Unit (`units/index.blade.php` & `units/show.blade.php`).
- **Fase 4**: Penerapan pada Menu Transaksi: Bookings (`bookings/index.blade.php`), Cashflow (`cashflow/index.blade.php`), dan Installments (`installments/index.blade.php`).
- **Fase 5**: Penerapan pada Menu Pengeluaran & Operasional: Field Expenses (`field-expenses/index.blade.php`), Workers (`workers/index.blade.php`), Proposals (`proposals/index.blade.php`), dan Manual Invoices (`manual-invoices/index.blade.php`).
