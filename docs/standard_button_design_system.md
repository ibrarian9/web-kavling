# 🎨 Panduan Standarisasi Tampilan Tombol (Button Design System)

Dokumen ini merupakan panduan resmi untuk **menyeragamkan tampilan, warna, ukuran, dan penamaan tombol** di seluruh menu aplikasi web **Kavling & Properti**. Tujuan standarisasi ini adalah menciptakan antarmuka (UI) yang konsisten, modern, premium, dan intuitif bagi pengguna.

---

## 🗂️ 1. Hirarki & Kategorisasi Tombol

| Kategori Aksi | Warna Dasar | Border / Hover | Contoh Penggunaan | Sintaks Kelas Tailwind |
| :--- | :--- | :--- | :--- | :--- |
| **Primary Action** | Emerald Solid (`#059669`) | `hover:bg-emerald-700` | Tambah Data, Simpan Form, Proyek Baru | `btn-primary` atau `bg-emerald-600 hover:bg-emerald-700 text-white` |
| **Secondary Action** | Slate Light (`#f1f5f9`) | `hover:bg-slate-200` | Batal, Tutup Modal, Reset Filter | `btn-secondary` atau `bg-slate-100 hover:bg-slate-200 text-slate-700` |
| **Edit / Update** | Amber Soft (`#fffbeb`) | `bg-amber-50 hover:bg-amber-100 text-amber-800 border-amber-200` | Edit Unit, Ubah Material, Edit Gaji | `bg-amber-50 text-amber-800 border border-amber-200 hover:bg-amber-100` |
| **Destructive / Hapus** | Rose Soft (`#fff1f2`) | `bg-rose-50 hover:bg-rose-100 text-rose-700 border-rose-200` | Hapus Unit, Hapus Transaksi, Batal DP | `bg-rose-50 text-rose-700 border border-rose-200 hover:bg-rose-100` |
| **Lihat PDF / Document** | Sky Soft / Rose Light | `bg-sky-50 hover:bg-sky-100 text-sky-800 border-sky-200` | Lihat PDF Rekap, Unduh Resi, PDF Kuitansi | `bg-sky-50 text-sky-800 border border-sky-200 hover:bg-sky-100` |
| **Detail / Audit Trail** | Teal Soft (`#f0fdf4`) | `bg-teal-50 hover:bg-teal-100 text-teal-800 border-teal-200` | Detail Alur Keuangan, Detail Proyek | `bg-teal-50 text-teal-800 border border-teal-200 hover:bg-teal-100` |
| **QR Code Verification** | Emerald Soft (`#ecfdf5`) | `bg-emerald-50 hover:bg-emerald-100 text-emerald-800 border-emerald-200` | Scan QR Code, Verifikasi Kuitansi | `bg-emerald-50 text-emerald-800 border border-emerald-200 hover:bg-emerald-100` |
| **Disabled / Empty Data** | Slate Muted (`#f1f5f9`) | `bg-slate-100 text-slate-400 border-slate-200 cursor-not-allowed` | PDF Rekap (Belum Ada Data), Akses Terkunci | `bg-slate-100 text-slate-400 border border-slate-200 cursor-not-allowed opacity-75` |

---

## 📐 2. Standard Ukuran & Radius Tombol (Button Sizing Standard)

### 🔹 A. Ukuran Baris Tabel (Table Action Buttons - Compact)
Digunakan pada kolom **Aksi**, **Resi**, atau **Audit** di tabel data (tabel mutasi kas, tabel unit, tabel belanja, dsb).
- **Height / Padding:** `px-2.5 py-1`
- **Ukuran Teks:** `text-[11px] font-bold`
- **Border Radius:** `rounded-lg` (8px)
- **Ukuran Icon:** `w-3.5 h-3.5`

#### Contoh Blade View:
```html
<!-- Tombol Detail / Audit Trail -->
<button class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-teal-50 text-teal-800 hover:bg-teal-100 border border-teal-200 text-[11px] font-bold transition">
    <svg class="w-3.5 h-3.5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
    <span>Detail</span>
</button>

<!-- Tombol Edit -->
<button class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-amber-50 text-amber-800 hover:bg-amber-100 border border-amber-200 text-[11px] font-bold transition">
    <svg class="w-3.5 h-3.5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
    <span>Edit</span>
</button>

<!-- Tombol Hapus -->
<button class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-rose-50 text-rose-700 hover:bg-rose-100 border border-rose-200 text-[11px] font-bold transition">
    <svg class="w-3.5 h-3.5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
    <span>Hapus</span>
</button>
```

---

### 🔹 B. Ukuran Header Section / Toolbar Card (Medium Action)
Digunakan pada **Header Card**, **Filter Toolbar**, dan **Jendela Pengaturan Section**.
- **Height / Padding:** `px-3.5 py-2`
- **Ukuran Teks:** `text-xs font-bold`
- **Border Radius:** `rounded-xl` (12px)
- **Ukuran Icon:** `w-4 h-4`

#### Contoh Blade View:
```html
<!-- Tombol Lihat PDF Rekap (Aktif dengan Modal Preview) -->
<button wire:click="openViewerModal('pdf', '...', 'Pratinjau Laporan PDF')" class="px-3.5 py-2 bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 rounded-xl text-xs font-bold inline-flex items-center gap-1.5 transition shadow-2xs">
    <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
    <span>Lihat PDF Rekap</span>
</button>

<!-- Tombol Lihat PDF Rekap (Disabled / Data Kosong) -->
<button disabled class="px-3.5 py-2 bg-slate-100 text-slate-400 border border-slate-200 rounded-xl text-xs font-bold inline-flex items-center gap-1.5 cursor-not-allowed opacity-75" title="Belum ada data pengeluaran/belanja untuk digenerate PDF">
    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
    <span>PDF Rekap (Belum Ada Data)</span>
</button>
```

---

### 🔹 C. Ukuran Modal Form & Main Page Toolbar (Large Primary)
Digunakan pada **Tombol Submit Form Modal** dan **Tombol Tambah Utama di Halaman**.
- **Height / Padding:** `px-4 py-2.5`
- **Ukuran Teks:** `text-sm font-semibold`
- **Border Radius:** `rounded-xl` (12px)
- **Ukuran Icon:** `w-4.5 h-4.5` atau `w-5 h-5`

#### Contoh Blade View:
```html
<!-- Tombol Primary Tambah Data -->
<button class="btn-primary">
    <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
    <span>+ Buat Proyek Baru</span>
</button>

<!-- Tombol Secondary Tutup / Batal -->
<button class="btn-secondary">
    <span>Batal</span>
</button>
```

---

## 🏷️ 3. Pedoman Penamaan Label Tombol (Naming Conventions)

Untuk mencegah **redudansi data & teks yang terlalu panjang**, penamaan tombol harus mengikuti kaidah berikut:

1. **Gunakan kata kerja tunggal dan padat:**
   - ✅ `Detail` *(bukan "Lihat Detail Alur Keuangan Selengkapnya")*
   - ✅ `Edit` *(bukan "Edit Data Belanja Material Ini")*
   - ✅ `Hapus` *(bukan "Hapus Data Ini Dari Sistem")*
   - ✅ `Struk` / `PDF` / `QR` *(bukan "Buka Foto Struk Pembelian Material")*
2. **Gunakan tanda `+` untuk aksi penambahan:**
   - ✅ `+ Proyek Baru`
   - ✅ `+ Unit`
   - ✅ `+ Supervisor`
   - ✅ `+ Catat Belanja`
3. **Sertakan indikator status jika tombol di-disable:**
   - ✅ `PDF Rekap (Belum Ada Data)`

---

## 🛠️ 4. Kelas CSS Rekomendasi di `resources/css/app.css`

Daftar kelas bantuan CSS di bawah ini telah disiapkan untuk dipakai secara terpusat:

```css
/* Helper Button Classes di app.css */
.btn-action-detail {
  @apply inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-teal-50 text-teal-800 hover:bg-teal-100 border border-teal-200 text-[11px] font-bold transition;
}

.btn-action-edit {
  @apply inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-amber-50 text-amber-800 hover:bg-amber-100 border border-amber-200 text-[11px] font-bold transition;
}

.btn-action-delete {
  @apply inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-rose-50 text-rose-700 hover:bg-rose-100 border border-rose-200 text-[11px] font-bold transition;
}

.btn-action-pdf {
  @apply inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-sky-50 text-sky-800 hover:bg-sky-100 border border-sky-200 text-[11px] font-bold transition;
}

.btn-action-qr {
  @apply inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-800 hover:bg-emerald-100 border border-emerald-200 text-[11px] font-bold transition;
}
```

---

## 📋 5. Ringkasan Implementasi

Setiap penambahan menu atau tombol baru di website wajib menggunakan acuan di atas agar **skema warna, ukuran icon, ketebalan font, dan interaksi hover** selaras di seluruh halaman.
