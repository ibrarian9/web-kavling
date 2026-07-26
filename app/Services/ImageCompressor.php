<?php

namespace App\Services;

use Illuminate\Support\Str;

class ImageCompressor
{
    /**
     * Compress and store uploaded image with max dimensions and quality optimization.
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @param string $folder
     * @param int $maxDimension
     * @param int $quality
     * @return string Relative path stored in public disk
     */
    public static function compressAndStore($file, string $folder = 'receipts', int $maxDimension = 1200, int $quality = 75): string
    {
        if (!$file) {
            return '';
        }

        $tempPath = $file->getRealPath();
        $extension = strtolower($file->getClientOriginalExtension());

        $image = null;
        if (in_array($extension, ['jpg', 'jpeg'])) {
            $image = @imagecreatefromjpeg($tempPath);
        } elseif ($extension === 'png') {
            $image = @imagecreatefrompng($tempPath);
        } elseif ($extension === 'webp') {
            $image = @imagecreatefromwebp($tempPath);
        }

        if ($image) {
            $width = imagesx($image);
            $height = imagesy($image);

            if ($width > $maxDimension || $height > $maxDimension) {
                $ratio = min($maxDimension / $width, $maxDimension / $height);
                $newWidth = (int) round($width * $ratio);
                $newHeight = (int) round($height * $ratio);

                $newImage = imagecreatetruecolor($newWidth, $newHeight);
                if (in_array($extension, ['png', 'webp'])) {
                    imagealphablending($newImage, false);
                    imagesavealpha($newImage, true);
                }
                imagecopyresampled($newImage, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
                imagedestroy($image);
                $image = $newImage;
            }

            $filename = (string) Str::uuid() . '.jpg';
            $relativeDir = 'public/' . $folder;
            $targetDir = storage_path('app/' . $relativeDir);

            if (!file_exists($targetDir)) {
                mkdir($targetDir, 0755, true);
            }

            $targetPath = $targetDir . '/' . $filename;
            imagejpeg($image, $targetPath, $quality);
            imagedestroy($image);

            return $folder . '/' . $filename;
        }

        // Fallback to standard Livewire/Laravel store
        return $file->store($folder, 'public');
    }
}
