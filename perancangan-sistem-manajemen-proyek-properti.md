# Perancangan Sistem Informasi Manajemen Proyek Properti & Keuangan

> Dokumen ini disusun berdasarkan catatan hasil diskusi terbaru dengan client. Rancangan sistem telah diperbarui dengan modul **Pengawas Project**, **Master Tukang/Mandor & Penugasan**, **Piutang/Pinjaman Worker**, **Log Barang Mingguan**, **Penawaran Harga di Bawah HPP**, **Booking & DP (Project & Unit)**, **Kategori Kavling & Rumah**, **Kas Per-Project & Global**, serta **Tanda Tangan Elektronik**.

---

## 1. Ringkasan Sistem

Sebuah aplikasi web internal untuk mengelola **proyek perumahan/properti** (kavling dan rumah), mencakup:

- **Data Master Unit (Kavling & Rumah)**: pengelolaan tipe unit, luas tanah, kelebihan tanah, harga m², luas bangunan, dan spesifikasi fisik.
- **Master Mandor & Tukang**: pendaftaran pekerja, penugasan per proyek/perumahan dan per unit.
- **Pencatatan Piutang / Pinjaman Worker**: pengelolaan kas bon / pinjaman mandor atau tukang beserta pemotongan opname/cicilan.
- **Log Barang Mingguan Pengawas**: pencatatan mingguan pembelian/pengambilan barang atau material oleh mandor/tukang yang dicatat langsung oleh Pengawas Project.
- **Booking Fee & DP**: pencatatan pemesanan/DP baik di tingkat proyek (kolektif/lahan) maupun spesifik per unit.
- **Persetujuan Harga Jual & Penawaran**: alur pengajuan harga jual oleh Marketing (termasuk harga penawaran di bawah HPP dengan persetujuan ganda Pengawas & Founder).
- **Penerbitan Surat Resmi & Tanda Tangan Elektronik (E-Signature)**: pengesahan dokumen resmi pemesanan/penawaran dengan tanda tangan digital tersimpan.
- **Arus Kas Masuk & Keluar (Per-Project & Konsolidasi Global)**: rincian mutasi kas spesifik per proyek serta dashboard arus kas global seluruh perumahan.
- **Laporan Keuangan & Dashboard Multi-Role**: ringkasan eksekutif untuk 5 peran pengguna.

---

## 2. Tech Stack

| Layer | Teknologi | Catatan |
|---|---|---|
| Backend Framework | **Laravel 13** | Rilis 17 Maret 2026, minimum PHP 8.3 |
| Interaktivitas Frontend | **Livewire 4** | Single-File Component (`.blade.php` dengan logic+view) |
| Styling | **Tailwind CSS v4.x** | Konfigurasi CSS-first via `@theme` |
| Database | **MariaDB / MySQL** | Versi 10.6+ / 8.0+ |
| Autentikasi & Role | `spatie/laravel-permission` | Mengelola 5 role + hak akses per modul |
| Tanda Tangan Digital | **Signature Pad Canvas JS** | Untuk capture & simpan TTD elektronik pengguna |
| PDF Surat Resmi | `barryvdh/laravel-dompdf` atau `spatie/laravel-pdf` | Generate dokumen & kwitansi resmi ber-TTD |

---

## 3. Panduan Desain & Sistem Warna (Design System & Branding)

### 3.1. Skema Warna Utama (Palette & Theme)

| Kategori Warna | Tailwind Class / Hex | Penggunaan |
|---|---|---|
| **Primary Brand** | Emerald 600 (`#059669`), Emerald 500 (`#10b981`) | Tombol utama, kas masuk, elemen aktif |
| **Secondary Accent** | Indigo 600 (`#4f46e5`), Slate 900 (`#0f172a`) | Header, sidebar navigation, aksen UI |
| **Background Light** | Slate 50 (`#f8fafc`), White (`#ffffff`) | Latar belakang halaman & kartu UI |
| **Borders & Dividers** | Slate 200 (`#e2e8f0`) | Garis tepi tabel & form input |

---

### 3.2. Warna Identitas Aktor / Role (Role Color Coding)

Aplikasi mengidentifikasi 5 peran pengguna dengan badge dan aksen warna khusus:

| Peran (Role) | Palet & Badge Class | Kode Hex | Fungsi Utama |
|---|---|---|---|
| **Founder** | Violet / Purple (`bg-purple-100 text-purple-700 border-purple-200`) | `#7c3aed` | Approval final, kelola user, laporan keuangan global |
| **Pengawas Project** | Amber / Orange (`bg-amber-100 text-amber-800 border-amber-200`) | `#d97706` | Pengawasan lapangan, log barang mingguan, penugasan tukang |
| **Supervisor** | Cyan / Teal (`bg-cyan-100 text-cyan-700 border-cyan-200`) | `#0891b2` | Verifikasi unit fisik & approval harga penawaran |
| **Finance** | Emerald / Green (`bg-emerald-100 text-emerald-700 border-emerald-200`) | `#059669` | Penetapan HPP, arus kas project & global, pencatatan piutang |
| **Marketing** | Blue / Indigo (`bg-blue-100 text-blue-700 border-blue-200`) | `#2563eb` | Pengajuan harga & penawaran di bawah HPP, booking & DP |

---

### 3.3. Standardisasi Badge Status (Status Indicators)

| Status | Tailwind Badge Class | Arti & Penggunaan |
|---|---|---|
| `Draft` | `bg-slate-100 text-slate-700 border-slate-300` | Data awal unit/proposal |
| `Tersedia` | `bg-sky-50 text-sky-700 border-sky-200` | Unit siap dipasarkan, HPP terisi |
| `Booked` / `DP` | `bg-teal-50 text-teal-700 border-teal-200` | Unit/proyek telah di-booking / DP |
| `Menunggu Approval` | `bg-amber-50 text-amber-700 border-amber-200 animate-pulse` | Butuh persetujuan Founder/Pengawas |
| `Penawaran (< HPP)` | `bg-rose-50 text-rose-700 border-rose-200 font-semibold` | Harga usulan di bawah HPP (perlu approval ekstra) |
| `Disetujui` | `bg-emerald-50 text-emerald-700 border-emerald-200` | Pengajuan harga disetujui, siap cetak surat |
| `Terjual` | `bg-indigo-50 text-indigo-700 border-indigo-200` | Transaksi mengikat, surat resmi terbit |

---

## 4. Aktor & Peran Pengguna (5 Role)

| Role | Fokus Utama |
|---|---|
| **Founder** | Pemilik bisnis. Approval final harga normal & penawaran di bawah HPP, akses laporan keuangan global, kelola user. |
| **Pengawas Project** | Mengawasi proyek lapangan, mencatat log barang/material mingguan, mengelola penugasan mandor/tukang, dan mengecek piutang worker. |
| **Supervisor** | Mengawasi kualitas proyek, memvalidasi data fisik unit & kelayakan pengajuan harga penawaran bersama Founder. |
| **Finance** | Menetapkan HPP, mengelola kas masuk/keluar (per project & global), mencatat piutang worker & pembayaran cicilan pembeli. |
| **Marketing** | Mengelola calon pembeli, mengajukan harga jual/penawaran (termasuk di bawah HPP jika promo), menginput Booking Fee & DP. |

---

## 5. Modul Utama Sistem

1. **Manajemen Proyek & Perumahan** — CRUD proyek (nama, lokasi, standar luas tanah, tarif kelebihan m²).
2. **Manajemen Unit (Kavling & Rumah)** — data kategori (`kavling` / `rumah`), dimensi tanah, kelebihan tanah, luas bangunan, spesifikasi konstruksi, HPP, status.
3. **Master Mandor & Tukang** — pendaftaran worker (`/workers`) & menu penugasan tukang per perumahan/project serta per unit spesifik (`/worker-assignments`).
4. **Piutang & Pinjaman Worker** — pencatatan pinjaman/kas bon mandor atau tukang beserta riwayat pemotongan opname/pembayarannya.
5. **Log Barang Mingguan (Pengawas)** — penginputan mingguan barang/material yang dibeli atau diambil oleh mandor/tukang oleh Pengawas Project.
6. **Booking Fee & DP (Project & Unit)** — pencatatan booking/DP untuk proyek perumahan atau spesifik per unit.
7. **Pengajuan Harga Jual & Penawaran (< HPP)** — alur pengajuan harga oleh Marketing, termasuk indikator penawaran di bawah HPP yang memerlukan catatan alasan diskon & approval ganda.
8. **Penerbitan Surat Resmi & Tanda Tangan Elektronik** — pencetakan Surat Pemesanan/Penawaran dan Kwitansi ber-TTD digital pengesah.
9. **Arus Kas (Per-Project & Konsolidasi Global)** — rincian mutasi kas per proyek dan ringkasan arus kas global seluruh perumahan.
10. **Dashboard & Laporan Multi-Role** — Laba-Rugi per proyek, posisi piutang worker, rekap barang mingguan, dan status booking/penjualan.

---

## 6. Alur Kerja Pengajuan Harga & Penawaran di Bawah HPP

1. **Finance** mengisi HPP unit → unit berstatus `Tersedia`.
2. **Marketing** membuat Pengajuan Harga:
   - Jika `proposed_price >= hpp_price`: pengajuan harga normal.
   - Jika `proposed_price < hpp_price`: sistem menandai sebagai **Penawaran Khusus (`is_below_hpp = true`)** dan **wajib** mengisi alasan diskon (`discount_reason`).
3. **Approval Ganda (Pengawas/Supervisor & Founder)**:
   - Pada modal approval, sistem menampilkan badge peringatan risiko margin rugi (jika di bawah HPP).
   - Kedua pihak memberi keputusan (**Setuju** / **Tolak**) beserta catatan.
4. Status pengajuan:
   - **Disetujui**: apabila disetujui oleh kedua pengesah.
   - **Ditolak**: jika salah satu menolak (Marketing harus merevisi usulan).
5. Setelah **Disetujui**, fitur **Cetak Surat Penawaran / Pemesanan Resmi** dibuka dengan mencantumkan **Tanda Tangan Elektronik** dari pihak berwenang.

---

## 7. Perhitungan Kelebihan Tanah (Kavling & Rumah)

```
Kelebihan Luas (m²)   = Luas Aktual Unit − Luas Standar Proyek   (jika > 0)
Biaya Kelebihan Tanah = Kelebihan Luas (m²) × Harga per m² Kelebihan (per proyek)
HPP Final Unit        = Harga Dasar Standar Proyek + Biaya Kelebihan Tanah + (Biaya Bangunan jika Rumah)
```

---

## 8. Rancangan Database (Skema Tabel Terbaru)

### `users`
| Kolom | Tipe | Keterangan |
|---|---|---|
| id | bigint PK | |
| name, email, password | string | |
| role | enum(`founder`,`pengawas_project`,`supervisor`,`finance`,`marketing`) | |
| signature_path | string | Path file gambar tanda tangan elektronik (PNG/SVG) |
| is_active | boolean | Status aktif pengguna |

### `projects` (Perumahan)
| Kolom | Tipe | Keterangan |
|---|---|---|
| id | bigint PK | |
| name, location | string | Nama proyek perumahan |
| standard_land_area | decimal | Luas standar tanah di proyek ini (m²) |
| excess_price_per_sqm | decimal | Harga per m² kelebihan tanah |
| base_price | decimal | Harga dasar kavling standar |
| status | enum(`aktif`,`selesai`,`ditutup`) | |

### `units` (Kavling & Rumah)
| Kolom | Tipe | Keterangan |
|---|---|---|
| id | bigint PK | |
| project_id | FK projects | |
| code | string | Kode unit (misal `A-01`) |
| category | enum(`kavling`,`rumah`) | Kategori unit |
| land_area | decimal | Luas tanah total (m²) |
| building_area | decimal | Luas bangunan (m²) — khusus Rumah |
| floors_count | integer | Jumlah lantai — khusus Rumah |
| specifications | text | Spesifikasi bangunan (pondasi, dinding, atap) |
| hpp | decimal | Harga Pokok Penjualan |
| status | enum(`draft`,`tersedia`,`booked`,`menunggu_approval`,`disetujui`,`ditolak`,`terjual`) | |

### `workers` (Master Mandor & Tukang)
| Kolom | Tipe | Keterangan |
|---|---|---|
| id | bigint PK | |
| name, phone, address | string | Data pribadi worker |
| type | enum(`mandor`,`tukang`) | Jenis pekerja |
| specialty | string | Spesialisasi (batu, kayu, cat, struktur, dll) |
| status | enum(`active`,`inactive`) | Status aktif |

### `worker_assignments` (Penugasan Worker)
| Kolom | Tipe | Keterangan |
|---|---|---|
| id | bigint PK | |
| worker_id | FK workers | |
| project_id | FK projects | Ditugaskan di proyek ini |
| unit_id | FK units | Nullable (jika spesifik per unit) |
| assigned_role | string | Jabatan/tugas dalam proyek (misal: Mandor Utama, Tukang Finishing) |
| start_date, end_date | date | Periode tugas |

### `worker_loans` (Piutang / Pinjaman Worker)
| Kolom | Tipe | Keterangan |
|---|---|---|
| id | bigint PK | |
| worker_id | FK workers | |
| project_id | FK projects | Proyek terkait |
| loan_date | date | Tanggal peminjaman / kas bon |
| amount | decimal | Nominal pinjaman |
| purpose | string | Keperluan pinjaman |
| status | enum(`pending`,`approved`,`partially_paid`,`paid`) | Status pembayaran |
| approved_by | FK users | Disetujui oleh Finance / Founder |

### `worker_loan_payments` (Riwayat Bayar Piutang Worker)
| Kolom | Tipe | Keterangan |
|---|---|---|
| id | bigint PK | |
| worker_loan_id | FK worker_loans | |
| payment_date | date | Tanggal bayar / pemotongan |
| amount_paid | decimal | Nominal dibayar |
| payment_method | string | Metode (potong opname / tunai) |
| created_by | FK users | |

### `weekly_material_purchases` (Log Barang Mingguan Pengawas)
| Kolom | Tipe | Keterangan |
|---|---|---|
| id | bigint PK | |
| project_id | FK projects | Proyek perumahan |
| unit_id | FK units | Nullable |
| worker_id | FK workers | Mandor/tukang pembeli/pengambil |
| pengawas_id | FK users | Pengawas project yang mencatat |
| purchase_date | date | Tanggal transaksi |
| item_name | string | Nama barang / material |
| quantity | decimal | Jumlah barang |
| unit_measure | string | Satuan (sak, m3, batang, pcs) |
| unit_price, total_price | decimal | Harga satuan & total |
| is_deducted_from_loan | boolean | Flag apakah dicatat sebagai piutang worker |

### `bookings` (Booking Fee & DP)
| Kolom | Tipe | Keterangan |
|---|---|---|
| id | bigint PK | |
| project_id | FK projects | Proyek perumahan |
| unit_id | FK units | Nullable (jika booking kolektif project) |
| buyer_name, buyer_phone | string | Identitas pemesan |
| booking_type | enum(`project`,`unit`) | Jenis booking |
| booking_amount | decimal | Nominal booking fee |
| dp_amount | decimal | Nominal DP |
| booking_date, expiry_date | date | Masa berlaku booking |
| status | enum(`active`,`converted`,`cancelled`,`refunded`) | Status booking |

### `price_proposals` & `approvals`
- Menampung pengajuan harga normal maupun penawaran (`is_below_hpp`, `discount_reason`), serta catatan keputusan Founder & Pengawas/Supervisor.

### `cashflow_transactions` (Arus Kas Per-Project & Global)
- Menampung transaksi kas masuk & keluar terhubung ke `project_id`.
- Tampilan Global melakukan agregasi konsolidasi seluruh `project_id`.

---

## 9. Matriks Hak Akses (5 Role)

| Aktivitas | Founder | Pengawas Project | Supervisor | Finance | Marketing |
|---|:---:|:---:|:---:|:---:|:---:|
| Lihat Laporan Kas Global | ✅ | ❌ | ❌ | ✅ | ❌ |
| Arus Kas Per-Project | ✅ | Lihat saja | Lihat saja | ✅ | ❌ |
| Master Data Tukang/Mandor | ✅ | ✅ | Lihat saja | Lihat saja | ❌ |
| Penugasan Worker per Project/Unit | ✅ | ✅ | ✅ | ❌ | ❌ |
| Catat Piutang/Pinjaman Worker | ✅ | ✅ (ajukan) | ❌ | ✅ (eksekusi) | ❌ |
| Input Log Barang Mingguan | ✅ | ✅ | ❌ | Lihat saja | ❌ |
| Input Booking Fee & DP | ✅ | ❌ | ❌ | ✅ | ✅ |
| Ajukan Harga/Penawaran (< HPP) | ❌ | ❌ | ❌ | ❌ | ✅ |
| Approval Harga/Penawaran | ✅ wajib | ✅ wajib | ✅ wajib | ❌ | ❌ |
| Kelola Tanda Tangan Digital | ✅ | ✅ | ✅ | ✅ | ✅ |

---

## 10. Status Konfirmasi Bisnis Terbaru

1. **Role Pengawas Project**: Resmi ditambahkan untuk fokus pada pengawasan operasional lapangan, penugasan worker, dan pencatatan mingguan material.
2. **Tukang & Mandor (Piutang & Penugasan)**: Sistem memisahkan pendaftaran master worker (`/workers`), penugasan (`/worker-assignments`), dan modul piutang/pinjaman (`/worker-loans`).
3. **Log Barang Mingguan**: Pengawas mencatat setiap pengeluaran/pengambilan material mingguan per worker.
4. **Penawaran di Bawah HPP**: Marketing diizinkan membuat pengajuan harga di bawah HPP untuk program promo khusus, namun wajib melewati approval persetujuan Founder & Pengawas dengan indikator peringatan margin.
5. **Booking & DP**: Mendukung skema booking di tingkat unit maupun kolektif proyek.
6. **Kas Per-Project & Kas Global**: Menyediakan filter detail per perumahan serta dashboard executive kas global.
7. **Tanda Tangan Elektronik**: Setiap pengguna berhak menyimpan TTD digital untuk pengesahan otomatis pada PDF resmi.

---

*Dokumen spesifikasi ini menjadi acuan utama pengembangan sistem.*
