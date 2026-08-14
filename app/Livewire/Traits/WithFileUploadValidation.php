<?php

namespace App\Livewire\Traits;

/**
 * Trait WithFileUploadValidation
 * 
 * Trait terpusat yang secara otomatis mengatasi validasi real-time berkas / foto bukti transfer
 * di seluruh komponen Livewire tanpa harus menuliskan fungsi validasi berulang di setiap controller.
 */
trait WithFileUploadValidation
{
    /**
     * Global rules map untuk seluruh properti file di sistem.
     */
    protected array $globalFileRulesMap = [
        'receipt_photo' => 'nullable|file|mimes:jpg,jpeg,png,webp,heic,heif,pdf|max:10240',
        'settle_receipt_photo' => 'nullable|file|mimes:jpg,jpeg,png,webp,heic,heif,pdf|max:10240',
        'new_receipt_photo' => 'nullable|file|mimes:jpg,jpeg,png,webp,heic,heif,pdf|max:10240',
        'pay_rec_photo' => 'nullable|file|mimes:jpg,jpeg,png,webp,heic,heif,pdf|max:10240',
        'settle_comm_photo' => 'nullable|file|mimes:jpg,jpeg,png,webp,heic,heif,pdf|max:10240',
        'unit_pay_comm_photo' => 'nullable|file|mimes:jpg,jpeg,png,webp,heic,heif,pdf|max:10240',
        'payroll_receipt_photo' => 'nullable|file|mimes:jpg,jpeg,png,webp,heic,heif,pdf|max:10240',
        'installment_payment_receipt_photo' => 'nullable|file|mimes:jpg,jpeg,png,webp,heic,heif,pdf|max:10240',
    ];

    /**
     * Livewire 3 Trait Lifecycle Hook.
     * Dipanggil secara otomatis ketika ada properti file yang di-update di Livewire.
     * Menggunakan $this->validate([$propertyName => ...]) agar tidak memicu "No property found for validation" Exception.
     */
    public function updatedWithFileUploadValidation(string $propertyName): void
    {
        if (array_key_exists($propertyName, $this->globalFileRulesMap)) {
            $this->validate([
                $propertyName => $this->globalFileRulesMap[$propertyName],
            ]);
        }
    }

    /**
     * Helper terpusat untuk memvalidasi & mengompres/menyimpan berkas upload secara aman.
     */
    protected function validateAndStoreFile(string $propertyName, string $directory = 'receipts'): ?string
    {
        if (!property_exists($this, $propertyName) || !$this->{$propertyName}) {
            return null;
        }

        $rule = $this->globalFileRulesMap[$propertyName] ?? 'nullable|file|mimes:jpg,jpeg,png,webp,heic,heif,pdf|max:10240';
        $this->validate([
            $propertyName => $rule,
        ]);

        return \App\Services\ImageCompressor::compressAndStore($this->{$propertyName}, $directory);
    }
}
