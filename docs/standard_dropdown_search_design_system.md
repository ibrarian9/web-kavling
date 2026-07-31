# 🔍 Panduan Standarisasi Input Search, Filter Dropdown & Form Controls (Dropdown & Search Design System)

Dokumen ini merupakan panduan resmi untuk **menyeragamkan tampilan, hirarki visual, ukuran, icon, efek fokus, state hover, dan responsivitas** pada komponen **Input Search (Pencarian Teks)**, **Select Filter Dropdown**, **Pop-over Dropdown Menu**, serta **Input Form Controls** di seluruh halaman aplikasi web **Kavling & Properti**.

Tujuan standarisasi ini adalah menciptakan antarmuka (UI) yang modern, bersih, intuitif, konsisten, serta ramah bagi pengguna di perangkat mobile maupun desktop.

---

## 🗂️ 1. Categorization & Specifications Summary

Berikut adalah 4 komponen kontrol formulir & pencarian utama dalam aplikasi:

| Komponen Kontrol | Fungsi Utama | Skema Warna & Style | Kelas CSS Utility / Tailwind |
| :--- | :--- | :--- | :--- |
| **Search Input Box** | Pencarian teks realtime (Kode unit, Nama pembeli, Transaksi) | `bg-slate-50 border-slate-200 focus:bg-white focus:ring-emerald-500/20` | `input-clean pl-8` (dengan icon SVG pencarian di sebelah kiri) |
| **Select Filter Dropdown** | Menyaring data berdasarkan kategori / status / tipe | `bg-slate-50 border-slate-200 font-semibold cursor-pointer` | `select-clean` atau `input-clean text-xs font-semibold` |
| **Popover Dropdown Menu** | Jendela melayang menu aksi (Floating menu / Profil / Filter lanjutan) | `bg-white border-slate-200 shadow-xl rounded-2xl p-1.5 backdrop-blur-md` | `dropdown-popover` |
| **Form Input (Modal & Edit)** | Input data numerik, teks, tanggal, & mata uang (Rp) | `bg-slate-50 border-slate-200 focus:ring-emerald-500` | `input-clean w-full` |

---

## 📐 2. Standard Layout & Anatomi Input Search (Pencarian Teks)

Setiap input pencarian wajib dibungkus dalam container `relative` dengan spesifikasi anatomi:
1. **Container Wrapper**: `relative w-full sm:w-48 md:w-64`
2. **Icon Search (Kiri)**: Icon Magnifying Glass `w-4 h-4 text-slate-400 absolute left-2.5 top-2.5 pointer-events-none`
3. **Input Field**: `input-clean w-full text-xs pl-8 pr-3 py-2 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500`
4. **Placeholder Text**: Teks panduan yang singkat & jelas (Contoh: `Cari kode unit...`, `Cari transaksi...`)

### 💡 Contoh Kode Blade Component:

```html
<!-- Input Search Standard dengan Icon Kiri -->
<div class="relative w-full sm:w-48">
    <input type="text" 
           wire:model.live.debounce.300ms="search" 
           placeholder="Cari kode unit..." 
           class="input-clean w-full text-xs pl-8 pr-3 py-2 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500">
    <svg class="w-4 h-4 text-slate-400 absolute left-2.5 top-2.5 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
    </svg>
</div>
```

---

## 🔽 3. Standard Select Filter Dropdown (Pilihan Filter Table & Toolbar)

Dropdown filter digunakan di samping kolom pencarian untuk menyaring data tabel secara presisi. Spesifikasi standar meliputi:
- **Background & Border**: `bg-slate-50 border border-slate-200 rounded-xl`
- **Ukuran Teks & Font**: `text-xs font-semibold text-slate-800`
- **Padding & Height**: `px-3 py-2`
- **State Focus**: `focus:bg-white focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500`
- **Cursor**: `cursor-pointer`

### 💡 Contoh Kode Blade Component:

```html
<!-- Select Filter Dropdown Standard (Filter Status Unit) -->
<div class="w-full sm:w-36">
    <select wire:model.live="statusFilter" class="select-clean w-full">
        <option value="">Semua Status Unit</option>
        <option value="tersedia">🟢 Tersedia</option>
        <option value="menunggu_persetujuan">🟡 Menunggu Approval</option>
        <option value="disetujui">🔵 Disetujui / Terjual</option>
        <option value="booked">🟠 Booked</option>
    </select>
</div>

<!-- Select Filter Dropdown Tipe Proyek / Unit -->
<div class="w-full sm:w-32">
    <select wire:model.live="typeFilter" class="select-clean w-full">
        <option value="">Semua Tipe</option>
        <option value="kavling">Kavling Tanah</option>
        <option value="rumah">Rumah Bangunan</option>
    </select>
</div>
```

---

## 🪟 4. Standard Floating Popover Dropdown Menu (Menu Aksi / Opsi Melayang)

Menu melayang (*floating popover*) digunakan untuk dropdown profil pengguna, opsi ekspor data, atau menu tindakan bertingkat. Anatomi standar meliputi:
1. **Container Menu**: `dropdown-popover absolute right-0 mt-2 w-48 bg-white border border-slate-200 rounded-2xl shadow-xl z-50 p-1.5 space-y-1 backdrop-blur-md`
2. **Menu Items**: `dropdown-item flex items-center gap-2 px-3 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-100/80 rounded-xl transition-colors`
3. **Menu Item Destruktif (Hapus/Keluar)**: `hover:bg-rose-50 text-rose-700 hover:text-rose-800`

### 💡 Contoh Kode Blade Component:

```html
<!-- Floating Popover Dropdown Menu -->
<div x-data="{ open: false }" class="relative">
    <button @click="open = !open" class="btn-secondary text-xs flex items-center gap-1.5">
        <span>Opsi Filter & Export</span>
        <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
    </button>

    <div x-show="open" 
         @click.away="open = false" 
         x-transition:enter="transition ease-out duration-100"
         x-transition:enter-start="transform opacity-0 scale-95"
         x-transition:enter-end="transform opacity-100 scale-100"
         class="dropdown-popover absolute right-0 mt-2 w-48">
        
        <a href="#" class="dropdown-item">
            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            <span>Unduh Excel / CSV</span>
        </a>

        <a href="#" class="dropdown-item">
            <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
            <span>Cetak PDF Rekap</span>
        </a>
    </div>
</div>
```

---

## 📝 5. Standard Form Input Controls (Form Modal & Entry Data)

Form input di dalam modal atau halaman edit wajib mematuhi panduan hirarki teks:

1. **Label Field**: `block font-semibold text-slate-700 mb-1 text-xs uppercase tracking-wider`
2. **Indikator Wajib Isil (Required)**: `<span class="text-rose-500">*</span>`
3. **Teks Validasi Error**: `@error('field_name') <span class="text-[10px] sm:text-xs text-rose-500 mt-1 block font-medium">{{ $message }}</span> @enderror`
4. **Input Prefix Nominal Mata Uang (Rp)**:
   - Container `flex rounded-xl shadow-xs`
   - Prefix Badge: `bg-slate-100 border border-r-0 border-slate-200 px-3 py-2 text-slate-500 font-mono text-xs font-bold flex items-center rounded-l-xl`
   - Input Nominal: `input-clean rounded-r-xl rounded-l-none font-mono font-bold w-full`

### 💡 Contoh Kode Input Nominal (Mata Uang Rp):

```html
<div>
    <label class="block font-semibold text-slate-700 mb-1 text-xs uppercase tracking-wider">
        Nominal Pembayaran <span class="text-rose-500">*</span>
    </label>
    <div class="flex rounded-xl shadow-xs">
        <span class="bg-slate-100 border border-r-0 border-slate-200 px-3 py-2 text-slate-500 font-mono text-xs font-bold flex items-center rounded-l-xl">
            Rp
        </span>
        <input type="number" 
               wire:model="payment_amount" 
               placeholder="10.000.000" 
               class="input-clean rounded-r-xl rounded-l-none font-mono font-bold text-sm w-full">
    </div>
    @error('payment_amount') 
        <span class="text-[10px] sm:text-xs text-rose-500 mt-1 block font-medium">{{ $message }}</span> 
    @enderror
</div>
```

---

## 🛠️ 6. Kelas CSS Terpusat di `resources/css/app.css`

Seluruh helper utility kelas pendukung telah tersedia secara terpusat di file CSS aplikasi:

```css
/* Helper Form & Dropdown Classes di app.css */
.input-clean {
  @apply bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-sm text-slate-800 focus:bg-white focus:outline-hidden focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all placeholder:text-slate-400;
}

.select-clean {
  @apply bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold text-slate-800 focus:bg-white focus:outline-hidden focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all cursor-pointer;
}

.dropdown-popover {
  @apply bg-white border border-slate-200 rounded-2xl shadow-xl p-1.5 space-y-1 z-50 backdrop-blur-md;
}

.dropdown-item {
  @apply flex items-center gap-2 px-3 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-100/80 rounded-xl transition-colors cursor-pointer;
}
```

---

## 📋 7. Ringkasan Prinsip Implementasi

> [!IMPORTANT]
> 1. **Gunakan Kelas Terpusat**: Selalu utamakan penggunaan `.select-clean`, `.input-clean`, `.dropdown-popover`, dan `.dropdown-item`.
> 2. **Responsif Mobile & Desktop**: Gunakan kelas grid/flex responsif seperti `w-full sm:w-48` agar tampilan pencarian dan dropdown tertata rapi di layar HP maupun komputer.
> 3. **Indikator Visual Intuitive**: Tambahkan emoji atau titik warna pada pilihan dropdown filter (seperti 🟢 Tersedia, 🟡 Menunggu) untuk memudahkan identifikasi cepat pengguna.
