# 🎨 Panduan & Rencana Pembaruan UI/UX (Clean, Modern, User-Friendly & Konsisten)

Dokumen ini berisi standar panduan desain antarmuka pengguna (UI/UX) dan langkah-langkah penyelarasan tampilan untuk seluruh modul pada aplikasi **Kavling Pro - Property Management System**.

---

## 🌟 1. Prinsip Utama Desain (Core Design Principles)

### 🌿 Clean & Uncluttered Layout
- Penggunaan ruang putih (*white space*) yang proporsional untuk memisahkan antar kelompok data tanpa menggunakan garis pemisah (*border*) yang terlalu tebal.
- Warna latar belakang canvas utama menggunakan `bg-slate-50` yang lembut dan sejuk di mata, dipadukan dengan kartu putih `bg-white` berbayangan halus (`shadow-sm hover:shadow-md transition-all`).

### 💎 Konsistensi Komponen (Component Consistency)
- **Typografi**: Menggunakan font **Plus Jakarta Sans** untuk teks UI utama dan **JetBrains Mono** untuk angka nominal mata uang (Rupiah), nomor dokumen, dan kode unit.
- **Radius Sudut (Border Radius)**: 
  - Card & Container utama: `rounded-2xl`
  - Input field, select, & modal content: `rounded-xl`
  - Button & Badge: `rounded-xl` / `rounded-full`
- **Warna Aksesibilitas Role**:
  - 👑 **Founder**: Accent Purple (`bg-purple-50 text-purple-700 border-purple-200`)
  - 👷 **Pengawas Project**: Accent Amber (`bg-amber-50 text-amber-700 border-amber-200`)
  - 🔍 **Supervisor**: Accent Cyan (`bg-cyan-50 text-cyan-700 border-cyan-200`)
  - 💵 **Finance**: Accent Emerald (`bg-emerald-50 text-emerald-700 border-emerald-200`)
  - 📈 **Marketing**: Accent Blue (`bg-blue-50 text-blue-700 border-blue-200`)

---

## 📐 2. Standardisasi Komponen Antarmuka (UI Components Standard)

### A. Stat Metrics Summary Card
Semua halaman utama modul dilengkapi dengan 3 hingga 4 kartu ringkasan di bagian atas dengan format seragam.

### B. Filter & Search Toolbar Baris Tunggal
Filter pencarian dan aksi dipisahkan secara rapi dalam satu baris toolbar:
- Pencarian kata kunci di sebelah kiri (`w-72` / `w-96` responsif).
- Dropdown filter kategori/proyek di tengah.
- Tombol Tambah Data / Primary Action di sebelah kanan dengan warna solid `bg-emerald-600 hover:bg-emerald-700 text-white font-semibold shadow-sm`.

### C. Data Table & List View Modern
- **Header**: `bg-slate-50/80 text-slate-500 uppercase text-[11px] font-bold tracking-wider py-3.5 px-4`.
- **Row**: `hover:bg-slate-50/60 transition-colors border-b border-slate-100 text-sm`.
- **Nominal Rupiah**: Selalu diformat `font-mono font-semibold text-slate-800`.
- **Badge Status**: `status-draft`, `status-tersedia`, `status-booked`, `status-disetujui`, `status-ditolak`, `status-terjual`, `status-lunas`.

### D. Modal Form Interaktif & Backdrop Blur
- Backdrop modal dengan efek blur `backdrop-blur-sm bg-slate-900/40`.
- Input field dengan focus ring halus `focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500`.

### E. Responsive Empty State
Jika data kosong atau pencarian tidak ditemukan, tampilkan ilustrasi ikon bulat besar dengan teks panduan dan tombol reset filter.

---

## 🗺️ 3. Rencana Eksekusi Overhaul per Modul

| No | Modul / Halaman | Prioritas Pembaruan UI |
|---|---|---|
| 1 | **Shell Layout & Sidebar** | Mempercantik sidebar dark mode, header bar, user role badge, dan switch role demo. |
| 2 | **Dashboard (`/dashboard`)** | Redesain stat cards, widget aktivitas terbaru, dan ringkasan arus kas global. |
| 3 | **Proyek & Unit (`/projects`, `/units`)** | Kartu spesifikasi unit (Kavling vs Rumah), badge status keterisian, & modal kalkulator HPP. |
| 4 | **Worker & Penugasan (`/workers`, `/worker-assignments`)** | Profil ringkas worker, badge tipe mandor/tukang, dan indikator unit yang dikerjakan. |
| 5 | **Log Mingguan & Piutang (`/weekly-log`, `/worker-loans`)** | Layout form log barang mingguan, toggle pembebanan piutang, dan ringkasan saldo pinjaman. |
| 6 | **Booking & DP (`/bookings`)** | Card status booking fee, deadline pembayaran DP, dan riwayat pembeli. |
| 7 | **Pengajuan Harga & Proposal (`/proposals`)** | Badge risk alert "Penawaran Dibawah HPP" yang kontras tanpa mewajibkan alasan. |
| 8 | **Arus Kas & Global (`/cashflow`)** | Tab switcher Kas Per-Project vs Kas Global Perusahaan dengan grafik statistik visual. |
| 9 | **Digital E-Signature (`/signature`)** | Canvas pad tanda tangan digital yang lebih luas, bersih, dan mudah digunakan di HP/PC. |
