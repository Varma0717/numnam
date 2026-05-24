<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;

class ResponsiveImageService
{
    /**
     * Width breakpoints for generated responsive variants.
     *
     * @var int[]
     */
    private array $widths = [480, 768, 1200, 1600];

    /**
     * Generate WebP variants for an image stored on the public disk.
     *
     * @return array<int, array{width:int,path:string,url:string}>
     */
    public function generateForPublicDisk(string $diskPath, string $mimeType): array
    {
        if (!$this->isResizableMime($mimeType)) {
            return [];
        }

        $sourcePath = storage_path('app/public/' . ltrim($diskPath, '/'));
        if (!is_file($sourcePath)) {
            return [];
        }

        $size = @getimagesize($sourcePath);
        if (!$size || empty($size[0]) || empty($size[1])) {
            return [];
        }

        [$sourceWidth, $sourceHeight] = $size;
        $driver = $this->resolveDriver($sourcePath, $mimeType);
        if ($driver === 'none') {
            return [];
        }

        $directory = trim(dirname($diskPath), '.\\/');
        $baseName = pathinfo($diskPath, PATHINFO_FILENAME);

        $variants = [];

        foreach ($this->widths as $targetWidth) {
            if ($targetWidth > $sourceWidth) {
                continue;
            }

            $targetHeight = (int) round(($sourceHeight / $sourceWidth) * $targetWidth);
            $webpBytes = $driver === 'gd'
                ? $this->renderWithGd($sourcePath, $mimeType, $sourceWidth, $sourceHeight, $targetWidth, $targetHeight)
                : $this->renderWithImagick($sourcePath, $targetWidth, $targetHeight);

            if ($webpBytes === false || $webpBytes === '') {
                continue;
            }

            $relativeVariantPath = 'responsive/'
                . ($directory !== '' ? $directory . '/' : '')
                . $baseName
                . '-' . $targetWidth . 'w.webp';

            Storage::disk('public')->put($relativeVariantPath, $webpBytes);

            $variants[] = [
                'width' => $targetWidth,
                'path' => $relativeVariantPath,
                'url' => url('storage/' . $relativeVariantPath),
            ];
        }

        return $variants;
    }

    private function isResizableMime(string $mimeType): bool
    {
        return in_array($mimeType, ['image/jpeg', 'image/png', 'image/webp'], true);
    }

    private function resolveDriver(string $sourcePath, string $mimeType): string
    {
        if (function_exists('imagewebp')) {
            $resource = $this->createGdImageResource($sourcePath, $mimeType);
            if ($resource) {
                imagedestroy($resource);
                return 'gd';
            }
        }

        if (class_exists('Imagick')) {
            return 'imagick';
        }

        return 'none';
    }

    private function createGdImageResource(string $sourcePath, string $mimeType)
    {
        return match ($mimeType) {
            'image/jpeg' => @imagecreatefromjpeg($sourcePath),
            'image/png' => @imagecreatefrompng($sourcePath),
            'image/webp' => function_exists('imagecreatefromwebp') ? @imagecreatefromwebp($sourcePath) : null,
            default => null,
        };
    }

    private function renderWithGd(
        string $sourcePath,
        string $mimeType,
        int $sourceWidth,
        int $sourceHeight,
        int $targetWidth,
        int $targetHeight
    ) {
        $sourceImage = $this->createGdImageResource($sourcePath, $mimeType);
        if (!$sourceImage) {
            return false;
        }

        $canvas = imagecreatetruecolor($targetWidth, $targetHeight);
        if (!$canvas) {
            imagedestroy($sourceImage);
            return false;
        }

        imagealphablending($canvas, true);
        imagesavealpha($canvas, true);

        imagecopyresampled(
            $canvas,
            $sourceImage,
            0,
            0,
            0,
            0,
            $targetWidth,
            $targetHeight,
            $sourceWidth,
            $sourceHeight
        );

        ob_start();
        imagewebp($canvas, null, 75);
        $bytes = ob_get_clean();

        imagedestroy($canvas);
        imagedestroy($sourceImage);

        return $bytes;
    }

    private function renderWithImagick(string $sourcePath, int $targetWidth, int $targetHeight): string|false
    {
        try {
            $imagickClass = 'Imagick';
            $image = new $imagickClass($sourcePath);
            $image->setIteratorIndex(0);
            $filter = defined('Imagick::FILTER_LANCZOS') ? constant('Imagick::FILTER_LANCZOS') : 22;
            $image->resizeImage($targetWidth, $targetHeight, $filter, 1, true);
            $image->setImageFormat('webp');
            $image->setImageCompressionQuality(75);
            $bytes = $image->getImageBlob();
            $image->clear();
            $image->destroy();

            return $bytes;
        } catch (\Throwable $e) {
            return false;
        }
    }
}
