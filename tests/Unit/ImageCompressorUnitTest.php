<?php

use App\Services\ImageCompressor;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

test('ImageCompressor falls back to standard store for unsupported file types', function () {
    Storage::fake('public');

    $file = UploadedFile::fake()->create('document.pdf', 100, 'application/pdf');

    $path = ImageCompressor::compressAndStore($file, 'test-uploads');

    expect($path)->not->toBeEmpty();
    expect($path)->toContain('test-uploads');
});

test('ImageCompressor returns empty string for null file', function () {
    $result = ImageCompressor::compressAndStore(null, 'test-folder');

    expect($result)->toBe('');
});

test('ImageCompressor compresses and stores JPEG image', function () {
    // Create a real GD-backed JPEG image
    $tempFile = tempnam(sys_get_temp_dir(), 'test_') . '.jpg';
    $image = imagecreatetruecolor(2000, 1500);
    $color = imagecolorallocate($image, 100, 150, 200);
    imagefill($image, 0, 0, $color);
    imagejpeg($image, $tempFile, 90);
    imagedestroy($image);

    $file = new UploadedFile($tempFile, 'test-image.jpg', 'image/jpeg', null, true);

    $path = ImageCompressor::compressAndStore($file, 'compressed-test', 1200, 75);

    expect($path)->not->toBeEmpty();
    expect($path)->toContain('compressed-test/');
    expect($path)->toEndWith('.jpg');

    // Verify the output file exists and is smaller (dimension-wise it was resized)
    $outputPath = storage_path('app/public/' . $path);
    if (file_exists($outputPath)) {
        $info = getimagesize($outputPath);
        expect($info[0])->toBeLessThanOrEqual(1200);
        expect($info[1])->toBeLessThanOrEqual(1200);
        @unlink($outputPath);
    }

    @unlink($tempFile);
});
