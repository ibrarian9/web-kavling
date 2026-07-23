# 📊 Activity Diagram & Review Evaluasi Sistem Manajemen Proyek Properti

Dokumen ini berisi perancangan **Activity Diagram (UML)** lengkap untuk seluruh alur kerja utama aplikasi web **Sistem Manajemen Proyek Properti & Keuangan (Web Kavling)**, serta **Review & Evaluasi Komprehensif** terkait potensi celah keamanan, kelemahan logika bisnis, integritas database, dan rekomendasi perbaikan.

---

## 1. Ringkasan Sistem & Peran Pengguna (Role Architecture)

Aplikasi dibangun menggunakan **Laravel 13**, **Livewire 4**, dan **Tailwind CSS v4**, mengelola 5 peran pengguna (*roles*):

1. **Founder** (`#7c3aed` - Purple): Approval final pengajuan harga, manajemen pengguna, dan laporan arus kas global.
2. **Pengawas Project** (`#d97706` - Amber): Pengawasan lapangan, pencatatan log barang mingguan, penugasan tukang/mandor, dan approval proposal.
3. **Supervisor** (`#0891b2` - Cyan): Validasi kondisi unit fisik dan approval ganda penawaran harga.
4. **Finance** (`#059669` - Emerald): Penetapan HPP unit, pengolahan kas masuk/keluar, dan pengelolaan piutang/kas bon worker.
5. **Marketing** (`#2563eb` - Blue): Input pengajuan harga jual / penawaran (< HPP), pencatatan Booking Fee & DP.

---

## 2. Activity Diagrams (Diagram Aktivitas UML)

Semua diagram berikut digambarkan menggunakan **Mermaid UML Standard** sehingga dapat di-render langsung di Markdown viewer.

### 2.1. Activity Diagram 1: Autentikasi & Switch Role (Simulation System)

Diagram ini menjelaskan alur masuk pengguna serta fitur beralih peran (*switch role*) yang digunakan untuk simulasi pengujian antar-aktor.

```mermaid
flowchart TD
    Start([Mulai]) --> GuestCheck{Apakah User Sudah Login?}
    GuestCheck -- Ya --> RedirectDashboard[Redirect ke Dashboard]
    GuestCheck -- Tidak --> ShowLoginForm[Tampilkan Halaman Login]
    
    ShowLoginForm --> InputCredentials[User Mengisi Email & Password]
    InputCredentials --> SubmitLogin[Klik Tombol Login]
    SubmitLogin --> ValidateAuth{Validasi Kredensial}
    
    ValidateAuth -- Gagal --> ShowError[Tampilkan Pesan Error / Kredensial Salah]
    ShowError --> ShowLoginForm
    
    ValidateAuth -- Sukses --> RegenSession[Regenerasi Session & Token CSRF]
    RegenSession --> RedirectDashboard
    
    subgraph Role Switching Workflow (Demoware / Testing)
        RedirectDashboard --> UserAction{Aksi User}
        UserAction -- Pindah Role --> ClickSwitchRole[Panggil Route /switch-role/{role}]
        ClickSwitchRole --> FindTargetUser{Cek User dengan Role Target}
        FindTargetUser -- Tidak Ada --> FlashError[Flash Error: User Role Tidak Ditemukan]
        FlashError --> RedirectDashboard
        FindTargetUser -- Ada --> DirectLogin[Auth::login(targetUser)]
        DirectLogin --> UpdateSession[Regenerasi Session Role Baru]
        UpdateSession --> RefreshDashboard[Refresh Dashboard dengan Akses Role Baru]
    end
    
    UserAction -- Logout --> SubmitLogout[Klik Logout]
    SubmitLogout --> InvalidateSession[Invalidate Session & Token]
    InvalidateSession --> ShowLoginForm
```

---

### 2.2. Activity Diagram 2: Manajemen Proyek, Unit & Kalkulasi HPP

Alur input data proyek perumahan, pendaftaran unit (kavling/rumah), dan kalkulasi otomatis biaya kelebihan tanah serta penetapan HPP.

```mermaid
flowchart TD
    Start([Mulai: Kelola Proyek & Unit]) --> FinanceAction[Finance / Admin Buka Menu Projects / Units]
    
    subgraph Form Proyek Perumahan
        FinanceAction --> InputProject[Input Data Proyek: Nama, Lokasi, Standard Land Area, Excess Price/m², Base Price]
        InputProject --> SaveProject[Simpan Data Proyek ke Database]
    end
    
    subgraph Form Unit (Kavling & Rumah)
        SaveProject --> InputUnit[Input Unit: Code, Category (Kavling/Rumah), Land Width, Land Length, Land Area, Building Area, Specs]
        InputUnit --> TriggerRecalculate[Sistem Menjalankan recalculateLandAndHpp()]
        
        TriggerRecalculate --> CheckArea{Luas Tanah Total <= 0?}
        CheckArea -- Ya --> AutoCalcArea[Hitung: land_area = land_width × land_length]
        CheckArea -- Tidak --> CheckExcess
        AutoCalcArea --> CheckExcess
        
        CheckExcess --> ExcessCalc[Hitung Kelebihan Tanah: excess_land_area = max(0, land_area - standard_land_area)]
        ExcessCalc --> ExcessCostCalc[Hitung Biaya Kelebihan: excess_cost = excess_land_area × excess_price_per_sqm]
        ExcessCostCalc --> HppCalc{Apakah HPP Sudah Diisi manual?}
        
        HppCalc -- Belum (Null) --> AutoHPP[Rekomendasi HPP = base_price + excess_cost (+ building_cost jika Rumah)]
        HppCalc -- Sudah Ada --> KeepHPP[Gunakan Nilai HPP Inputan]
        
        AutoHPP --> SaveUnitDB[Simpan Model Unit ke Database]
        KeepHPP --> SaveUnitDB
    end
    
    SaveUnitDB --> SetDraftStatus[Status Unit Set to 'Tersedia' / Ready to Market]
    SetDraftStatus --> End([Selesai: Unit Siap Diajukan Harga])
```

---

### 2.3. Activity Diagram 3: Pengajuan Harga & Approval Penawaran (< HPP)

Alur pengajuan harga oleh Marketing, deteksi penawaran di bawah HPP, persetujuan berjenjang (Founder & Pengawas/Supervisor), hingga persetujuan final.

```mermaid
flowchart TD
    Start([Mulai: Pengajuan Harga Jual]) --> MarketingSelect[Marketing Memilih Unit Status 'Tersedia']
    MarketingSelect --> InputProposedPrice[Input proposed_price & proposal_notes]
    
    InputProposedPrice --> CheckHPP{proposed_price < unit.hpp ?}
    
    CheckHPP -- Ya (Penawaran Khusus < HPP) --> BelowHppFlag[Set is_below_hpp = TRUE]
    BelowHppFlag --> RequireDiscountReason[Wajib Mengisi discount_reason / Alasan Promo]
    RequireDiscountReason --> SubmitProposal
    
    CheckHPP -- Tidak (Harga Normal >= HPP) --> NormalFlag[Set is_below_hpp = FALSE]
    NormalFlag --> SubmitProposal[Submit Form Proposal]
    
    SubmitProposal --> CreateProposalRecord[Create Record PriceProposal (status: 'menunggu')]
    CreateProposalRecord --> UpdateUnitStatus[Update Unit Status: 'menunggu_persetujuan']
    UpdateUnitStatus --> NotifyApprovers[Tampilkan di Queue Approval Founder & Pengawas/Supervisor]
    
    subgraph Multi-Level Approval Process
        NotifyApprovers --> ApproverReview[Pengesah (Founder / Pengawas / Supervisor) Buka Modal Approval]
        ApproverReview --> CheckAlertBadge{is_below_hpp == TRUE?}
        CheckAlertBadge -- Ya --> DisplayWarningBadge[Tampilkan Badge Peringatan Risiko Kerugian Margin]
        CheckAlertBadge -- Tidak --> DisplayNormalBadge[Tampilkan Informational Margin]
        
        DisplayWarningBadge --> DecisionNode{Keputusan Pengesah}
        DisplayNormalBadge --> DecisionNode
        
        DecisionNode -- Tolak --> RecordRejection[Simpan Approval Decision: 'ditolak']
        RecordRejection --> UpdatePropRejected[Update Proposal Status: 'ditolak']
        UpdatePropRejected --> UpdateUnitRejected[Update Unit Status: 'ditolak']
        UpdateUnitRejected --> InformMarketing[Notifikasi Marketing: Proposal Ditolak & Isi Catatan]
        
        DecisionNode -- Setuju --> RecordApproval[Simpan Record Approval Decision: 'disetujui']
        RecordApproval --> CheckFullyApproved{Apakah SUDAH Disetujui Penuh? (Founder + Supervisor/Pengawas)}
        
        CheckFullyApproved -- Belum Penuh --> PartialApprovalMsg[Flash Msg: Menunggu Persetujuan Pengesah Lainnya]
        
        CheckFullyApproved -- Sudah Penuh --> FullApprovedState[Update Proposal Status: 'disetujui']
        FullApprovedState --> SetFinalPrice[Update Unit: status='disetujui', final_selling_price=proposed_price]
        SetFinalPrice --> EnableDocumentIssue[Buka Akses Fitur 'Penerbitan Surat Resmi']
    end
    
    InformMarketing --> EndRej([Selesai: Ditolak])
    EnableDocumentIssue --> EndApp([Selesai: Disetujui & Siap Terbit Surat])
```

---

### 2.4. Activity Diagram 4: Penerbitan Surat Pemesanan Properti & E-Signature PDF

Alur pembuatan Surat Pemesanan Properti (SPP) resmi ber-tanda tangan digital hingga pengunduhan PDF.

```mermaid
flowchart TD
    Start([Mulai Penerbitan Surat Resmi]) --> SelectApprovedUnit[Marketing / Finance Pilih Proposal Status 'Disetujui']
    SelectApprovedUnit --> OpenDocModal[Buka Form Issue Official Document]
    
    OpenDocModal --> InputBuyerInfo[Input Data Pembeli: Nama, Kontak, Alamat]
    InputBuyerInfo --> SubmitIssueDoc[Klik Terbitkan Surat]
    
    SubmitIssueDoc --> GenDocNumber[Generate Nomor Surat Otomatis: SPP/PROJECT/YYYY/MM/XXX]
    GenDocNumber --> FetchUserSignatures[Ambil Signature Path E-Signature Pengesah & User]
    
    FetchUserSignatures --> CreateOfficialDocDB[Simpan Record ke Tabel official_documents]
    CreateOfficialDocDB --> UpdateUnitSold[Update Status Unit: 'terjual']
    
    UpdateUnitSold --> GeneratePDFStream[DocumentPdfController::streamPdf()]
    GeneratePDFStream --> EmbedSignatures[Inject Canvas E-Signature & Template PDF HTML]
    EmbedSignatures --> RenderPDF[DomPDF Render File PDF Surat Resmi & Kwitansi]
    RenderPDF --> DownloadPDF([Download / Print Stream PDF Surat Resmi])
```

---

### 2.5. Activity Diagram 5: Pemesanan & Booking Fee / DP (Level Proyek & Unit)

Alur manajemen Booking Fee dan Down Payment (DP) baik pada tingkat unit spesifik maupun kolektif proyek.

```mermaid
flowchart TD
    Start([Mulai: Input Booking / DP]) --> SelectBookingType{Pilih Jenis Booking}
    
    SelectBookingType -- Level Unit --> SelectUnit[Pilih Unit Spesifik (Status 'Tersedia')]
    SelectBookingType -- Level Proyek --> SelectProject[Pilih Proyek Perumahan (Kolektif)]
    
    SelectUnit --> InputBuyer[Input Data Pemesan: Nama, Telepon, Tanggal, Masa Expired]
    SelectProject --> InputBuyer
    
    InputBuyer --> InputAmounts[Input Nominal Booking Fee & Nominal DP]
    InputAmounts --> SubmitBooking[Simpan Data Booking]
    
    SubmitBooking --> CreateBookingRecord[Record ke Tabel bookings (status: 'active')]
    CreateBookingRecord --> IsUnitBooking{Apakah Booking Unit?}
    
    IsUnitBooking -- Ya --> LockUnitStatus[Update Status Unit: 'booked' / 'DP']
    IsUnitBooking -- Tidak --> StandbyProject[Simpan Kuota Booking Proyek]
    
    LockUnitStatus --> WaitTransaction{Keputusan Lanjutan Pemesan}
    StandbyProject --> WaitTransaction
    
    WaitTransaction -- Lanjut Transaksi --> ConvertToSale[Ubah Status Booking: 'converted']
    ConvertToSale --> LinkToProposal[Hubungkan ke Pengajuan Harga / Pelunasan]
    
    WaitTransaction -- Batal / Expired --> CancelBooking[Ubah Status Booking: 'cancelled' / 'refunded']
    CancelBooking --> ReleaseUnit[Kembalikan Status Unit ke 'Tersedia']
    
    LinkToProposal --> EndSuccess([Selesai: Transaksi Mengikat])
    ReleaseUnit --> EndCancelled([Selesai: Booking Batal])
```

---

### 2.6. Activity Diagram 6: Manajemen Mandor/Tukang, Penugasan & Piutang Worker

Alur pengelolaan master data pekerja, penugasan proyek/unit, pencatatan pinjaman (kas bon), dan pemotongan cicilan opname.

```mermaid
flowchart TD
    Start([Mulai: Pengelolaan Worker]) --> WorkerMaster[Admin / Pengawas Akses Master Data Workers]
    
    subgraph Master Worker & Penugasan
        WorkerMaster --> RegisterWorker[Daftarkan Worker: Nama, No. HP, Jenis (Mandor/Tukang), Spesialisasi]
        RegisterWorker --> AssignWorker[Penugasan Worker: Pilih Proyek & Unit (WorkerAssignment)]
    end
    
    subgraph Pencatatan Pinjaman (Worker Loan / Kas Bon)
        AssignWorker --> RequestLoan[Input Pinjaman: Pilih Worker, Proyek, Tanggal, Nominal, Keperluan]
        RequestLoan --> SaveLoanDB[Simpan Record WorkerLoan (paid_amount=0, status='approved')]
    end
    
    subgraph Potongan Opname / Pembayaran Piutang
        SaveLoanDB --> RecordPayment[Buka Modal Payment: Open Payment Modal]
        RecordPayment --> InputPayment[Input Nominal Dibayar & Metode: potong_opname / tunai]
        InputPayment --> SavePaymentDB[Simpan Record WorkerLoanPayment]
        
        SavePaymentDB --> RecalculateBalance[Update paid_amount = paid_amount + amount_paid]
        RecalculateBalance --> CheckPaidStatus{paid_amount >= loan.amount ?}
        
        CheckPaidStatus -- Ya --> SetStatusPaid[Set Status WorkerLoan = 'paid']
        CheckPaidStatus -- Tidak --> SetStatusPartial[Set Status WorkerLoan = 'partially_paid']
    end
    
    SetStatusPaid --> EndLoan([Selesai: Piutang Lunas])
    SetStatusPartial --> EndPartial([Selesai: Piutang Terbayar Sebagian])
```

---

### 2.7. Activity Diagram 7: Log Barang Mingguan Pengawas Project

Alur penginputan pembelian/pengambilan barang atau material oleh mandor/tukang yang dicatat oleh Pengawas Project secara mingguan.

```mermaid
flowchart TD
    Start([Mulai: Log Barang Mingguan]) --> OpenWeeklyLog[Pengawas Project Buka Halaman Weekly Material Log]
    OpenWeeklyLog --> SelectProjectWorker[Pilih Proyek, Unit, Mandor/Tukang, & Tanggal Transaksi]
    
    SelectProjectWorker --> InputItemDetails[Input Nama Barang, Quantity, Satuan (sak/m3/pcs), & Harga Satuan]
    InputItemDetails --> AutoCalcTotal[Hitung Otomatis: Total Price = Quantity × Unit Price]
    
    AutoCalcTotal --> CheckLoanDeduction{Dipotongkan ke Piutang Worker? (is_deducted_from_loan)}
    
    CheckLoanDeduction -- Ya --> SetFlagTrue[Set is_deducted_from_loan = TRUE]
    SetFlagTrue --> AutoCreateLoanRecord[Otomatis Generate Entry WorkerLoan / Kas Bon Terkait]
    
    CheckLoanDeduction -- Tidak --> SetFlagFalse[Set is_deducted_from_loan = FALSE]
    
    AutoCreateLoanRecord --> SaveWeeklyLogRecord[Simpan Record ke tabel weekly_material_purchases]
    SetFlagFalse --> SaveWeeklyLogRecord
    
    SaveWeeklyLogRecord --> UpdateCashflowOut[Catat Pengeluaran Material pada Cashflow Proyek]
    UpdateCashflowOut --> EndLog([Selesai: Material Dicatat & Stok/Biaya Terupdate])
```

---

### 2.8. Activity Diagram 8: Arus Kas & Dashboard Konsolidasi (Per-Project & Global)

Alur pencatatan arus kas masuk/keluar, pemisahan kas per proyek perumahan, serta penggabungan konsolidasi pada Dashboard Global Founder.

```mermaid
flowchart TD
    Start([Mulai: Transaksi Arus Kas]) --> SelectContext{Pilih Akses Menu}
    
    SelectContext -- Finance / Pengawas Proyek --> PerProjectView[Modul Cashflow Per-Project]
    SelectContext -- Founder / Executive --> GlobalView[Dashboard Cashflow Global Konsolidasi]
    
    subgraph Transaksi Kas Per-Project
        PerProjectView --> InputCashflow[Input Mutasi Kas: Proyek, Tipe (Masuk/Keluar), Kategori, Nominal, Tanggal]
        InputCashflow --> SaveCashflowDB[Simpan Record ke tabel cashflow_transactions]
        SaveCashflowDB --> RecalcProjectBalance[Hitung Ulang Saldo Kas Proyek: Total Masuk - Total Keluar]
    end
    
    subgraph Dashboard Global Executive
        GlobalView --> AggregateProjects[Agregasi Data Mutasi dari Seluruh Proyek Perumahan]
        AggregateProjects --> CalcGlobalBalance[Hitung Total Kas Masuk Global, Total Kas Keluar Global, Net Cashflow]
        CalcGlobalBalance --> RenderKPIWidgets[Tampilkan KPI Card: Omset Global, Total Piutang Worker Outstanding, Unit Terjual]
    end
    
    RecalcProjectBalance --> EndCashflow([Selesai: Laporan Mutasi Terupdate])
    RenderKPIWidgets --> EndCashflow
```

---

## 3. Review & Evaluasi Sistem (Temuan & Hal yang Perlu Diperbaiki)

Berdasarkan analisis mendalam terhadap spesifikasi perancangan, skema database, controller Livewire, dan arsitektur kode saat ini, ditemukan **beberapa area kritis yang wajib diperbaiki** untuk menjamin keamanan, keandalan data, dan performa aplikasi.

---

### 3.1. Keamanan & Akses Kontrol (Security & RBAC)

#### 1. 🚨 CRITICAL: Celah Keamanan pada Route Switch Role (`/switch-role/{role}`)
- **Temuan**: Rute `Route::get('/switch-role/{role}', [AuthController::class, 'switchRole'])` terdaftar sebagai rute publik (di luar middleware `auth`). Siapapun dapat mengetik URL `/switch-role/founder` di browser dan **langsung mengambil alih akun Founder** tanpa verifikasi password.
- **Dampak**: Kerentanan keamanan tinggi (*Account Takeover*) jika sistem dideploy ke server produksi.
- **Rekomendasi Perbaikan**:
  Bungkus rute `switchRole` agar HANYA aktif pada environment pengujian (`local` / `testing`), atau hapus dari rute produksi:
  ```php
  // routes/web.php
  if (app()->environment('local', 'testing')) {
      Route::get('/switch-role/{role}', [AuthController::class, 'switchRole'])->name('switch-role');
  }
  ```

#### 2. ⚠️ HIGH: Belum Adanya Middleware Role/Policy pada Livewire Routes
- **Temuan**: Rute sensitif seperti `/cashflow`, `/costs`, `/worker-loans` di `routes/web.php` hanya dilindungi oleh middleware `['auth']`. Pengguna dengan peran `Marketing` atau `Tukang` masih bisa mengakses halaman Keuangan hanya dengan mengetikkan URL route-nya secara langsung.
- **Rekomendasi Perbaikan**:
  Gunakan middleware Spatie Permission atau pembatasan role pada grup route:
  ```php
  Route::middleware(['auth', 'role:founder|finance'])->group(function () {
      Route::get('/cashflow', CashflowIndex::class)->name('cashflow.index');
      Route::get('/costs', CostsIndex::class)->name('costs.index');
  });
  ```

---

### 3.2. Logika Bisnis & Consistency Bugs

#### 1. 🐛 CRITICAL: Inkonsistensi Method `isFullyApproved()` pada Model `PriceProposal`
- **Temuan**: Pada `app/Models/PriceProposal.php`:
  ```php
  public function isFullyApproved(): bool
  {
      $founderApproved = $this->approvals()->where('approver_role', 'founder')->where('decision', 'disetujui')->exists();
      $supervisorApproved = $this->approvals()->where('approver_role', 'supervisor')->where('decision', 'disetujui')->exists();

      return $founderApproved && $supervisorApproved;
  }
  ```
  Namun di `app/Livewire/Proposals/Index.php` dan spesifikasi bisnis, approval dapat dilakukan oleh `pengawas_project` **atau** `supervisor`. Apabila `pengawas_project` melakukan approval, `isFullyApproved()` mengembalikan `false` karena method tersebut **hanya mengecek ketersediaan role `supervisor`**.
- **Rekomendasi Perbaikan**: Perbarui logic pada `PriceProposal.php`:
  ```php
  public function isFullyApproved(): bool
  {
      $founderApproved = $this->approvals()->where('approver_role', 'founder')->where('decision', 'disetujui')->exists();
      $supervisorOrPengawasApproved = $this->approvals()
          ->whereIn('approver_role', ['supervisor', 'pengawas_project'])
          ->where('decision', 'disetujui')
          ->exists();

      return $founderApproved && $supervisorOrPengawasApproved;
  }
  ```

#### 2. 🚨 HIGH: Operasi Multi-Tabel Tanpa `DB::transaction()`
- **Temuan**: Pada `Proposals/Index.php` (method `submitApproval()` dan `issueDocument()`), serta pada `Loans.php` (method `savePayment()`), eksekusi pembacaan dan pembaruan beberapa tabel sekaligus dilakukan secara sekuensial tanpa transaksi database. Jika koneksi terputus saat mengupdate tabel kedua, data akan berada dalam status korup/inconsistent.
- **Rekomendasi Perbaikan**: Bungkus operasi multi-tabel dalam `DB::transaction`:
  ```php
  use Illuminate\Support\Facades\DB;

  public function issueDocument()
  {
      $this->validate([...]);

      DB::transaction(function () {
          $proposal = PriceProposal::with('unit.project')->findOrFail($this->doc_proposal_id);
          
          OfficialDocument::create([...]);
          $proposal->unit->update(['status' => 'terjual']);
      });
  }
  ```

#### 3. ⚠️ MEDIUM: Otomatisasi Piutang Worker dari Log Barang Mingguan
- **Temuan**: Tabel `weekly_material_purchases` memiliki kolom `is_deducted_from_loan`, tetapi controller `WeeklyLog.php` belum membuat record `WorkerLoan` secara otomatis saat checkbox `is_deducted_from_loan` dicentang oleh Pengawas.
- **Rekomendasi Perbaikan**: Tambahkan trigger pembuatan `WorkerLoan` otomatis pada saat pembeliaan material dengan opsi `is_deducted_from_loan = true`.

---

### 3.3. Database & Integritas Schema

#### 1. 💡 CRITICAL: Penjumlahan Nomor Surat Resmi Menggunakan `rand()`
- **Temuan**: Di `Proposals/Index.php` line 210:
  `$docNumber = 'SPP/' . ... . '/' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT);`
  Penggunaan angka acak `rand()` berisiko tinggi menghasilkan duplikasi nomor surat (*Unique Constraint Violation crash*) jika nomor yang sama ter-generate dua kali.
- **Rekomendasi Perbaikan**: Gunakan urutan nomor autoincrement bulanan per proyek:
  ```php
  $count = OfficialDocument::whereYear('created_at', now()->year)
      ->whereMonth('created_at', now()->month)
      ->count() + 1;
  $docNumber = 'SPP/' . strtoupper($projectCode) . '/' . date('Y/m') . '/' . str_pad($count, 4, '0', STR_PAD_LEFT);
  ```

#### 2. 💡 HIGH: Belum Menggunakan `SoftDeletes` pada Master Data Utama
- **Temuan**: Model `Project`, `Unit`, dan `Worker` saat ini menggunakan hard delete. Jika sebuah unit yang sudah memiliki transaksi diputus/dihapus, data riwayat kas dan laporan keuangan akan rusak/hilang.
- **Rekomendasi Perbaikan**: Tambahkan `use SoftDeletes;` pada tabel master `projects`, `units`, `workers`, dan `price_proposals`.

---

### 3.4. User Experience & Interface (UI/UX)

1. **Notifikasi Visual Diskon / Margin di Bawah HPP**:
   Saat Marketing mengajukan harga di bawah HPP, tampilkan kalkulator visual langsung yang memperlihatkan **Persentase Kerugian Margin** (misal: `-5.2% dari HPP`) dan kolom mandatory input alasan promo agar Pengesah mendapatkan informasi menyeluruh sebelum membuat keputusan.
2. **Integrasi Canvas Signature Pad pada Mobile Browser**:
   Pastikan library E-Signature yang digunakan di `resources/views/livewire/profile/signature.blade.php` mendukung event `touchmove` & `touchend` agar Pengawas dan Founder dapat membubuhkan TTD digital langsung melalui smartphone/tablet di lapangan.

---

### 3.5. Automated Testing (Pengujian Kode)

- **Temuan**: Folder `tests/Feature` saat ini hanya berisi `ExampleTest.php`. Seluruh alur kritis seperti kalkulasi kelebihan tanah, pengajuan harga < HPP, approval ganda, dan potongan piutang worker belum terlindungi oleh pengujian otomatis.
- **Rekomendasi Perbaikan**: Buat pengujian otomatis menggunakan Pest / PHPUnit:
  - `test_excess_land_cost_and_hpp_recalculation()`
  - `test_proposal_below_hpp_requires_discount_reason()`
  - `test_dual_approval_logic_for_founder_and_supervisor_or_pengawas()`
  - `test_official_document_issuance_changes_unit_status_to_sold()`

---

## 4. Matriks Prioritas Rencana Aksi (Action Plan Matrix)

| No | Modul / Area | Item Masalah | Tingkat Risiko | File Terkait | Solusi / Action Required |
|:--:|---|---|:---:|---|---|
| 1 | Auth / Security | Celah rute `/switch-role/{role}` publik | 🚨 CRITICAL | `routes/web.php` | Batasi hanya untuk environment `local` / `testing`. |
| 2 | Proposal & Approval | Logic `isFullyApproved()` mengabaikan `pengawas_project` | 🐛 CRITICAL | `app/Models/PriceProposal.php` | Perbarui query agar mendukung `supervisor` ATAU `pengawas_project`. |
| 3 | Official Document | Nomor surat menggunakan `rand(1, 999)` acak | 💡 CRITICAL | `app/Livewire/Proposals/Index.php` | Ganti dengan nomor urut bulanan autoincrement. |
| 4 | Security RBAC | Rute Livewire keuangan belum memiliki middleware role | ⚠️ HIGH | `routes/web.php` | Pasang middleware `role:founder\|finance`. |
| 5 | DB Consistency | Transaksi multi-tabel tanpa `DB::transaction()` | 🚨 HIGH | `Proposals/Index.php`, `Loans.php` | Bungkus kode update multi-tabel dalam `DB::transaction`. |
| 6 | Master Data | Belum ada `SoftDeletes` pada Master Data | 💡 HIGH | `Unit.php`, `Project.php`, `Worker.php` | Tambahkan Trait `SoftDeletes` dan migrasi database. |
| 7 | Log Barang | Flag `is_deducted_from_loan` belum auto-create loan | ⚠️ MEDIUM | `app/Livewire/Materials/WeeklyLog.php` | Buat event trigger pemotongan piutang otomatis. |
| 8 | Quality Assurance | Cakupan Automated Test masih 0% | 🧪 MEDIUM | `tests/Feature/` | Tulis Feature Test untuk kalkulasi HPP, approval, dan kas. |

---

*Dokumen Activity Diagram dan Review Evaluasi Sistem ini disusun sebagai panduan teknis pengembangan dan perbaikan Sistem Informasi Manajemen Proyek Properti.*
