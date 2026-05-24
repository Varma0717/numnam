<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class GenerateResponsiveAssets extends Command
{
    protected $signature = 'images:generate-responsive {--path=public/assets/images : Base path to scan}';

    protected $description = 'Generate responsive WebP variants for existing public assets';

    /**
     * @var int[]
     */
    private array $widths = [480, 768, 1200, 1600];

    public function handle(): int
    {
        $scanBase = base_path($this->option('path'));

        if (!is_dir($scanBase)) {
            $this->error('Path not found: ' . $scanBase);
            return self::FAILURE;
        }

        if (!function_exists('imagewebp')) {
            $this->error('GD WebP support is required (imagewebp not available).');
            return self::FAILURE;
        }

        $files = $this->collectImageFiles($scanBase);
        if (empty($files)) {
            $this->warn('No matching image files found.');
            return self::SUCCESS;
        }

        $generated = 0;
        $skipped = 0;

        foreach ($files as $filePath) {
            $result = $this->generateForFile($filePath, $scanBase);
            $generated += $result['generated'];
            $skipped += $result['skipped'];
        }

        $this->info('Responsive generation complete.');
        $this->line('Variants generated: ' . $generated);
        $this->line('Variants skipped: ' . $skipped);

        return self::SUCCESS;
    }

    /**
     * @return string[]
     */
    private function collectImageFiles(string $scanBase): array
    {
        $results = [];

        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($scanBase, \FilesystemIterator::SKIP_DOTS)
        );

        foreach ($iterator as $file) {
            if (!$file->isFile()) {
                continue;
            }

            $path = $file->getPathname();
            if (str_contains(str_replace('\\', '/', $path), '/responsive/')) {
                continue;
            }

            $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
            if (!in_array($ext, ['jpg', 'jpeg', 'png', 'webp'], true)) {
                continue;
            }

            $results[] = $path;
        }

        return $results;
    }

    /**
     * @return array{generated:int,skipped:int}
     */
    private function generateForFile(string $filePath, string $scanBase): array
    {
        $mime = mime_content_type($filePath) ?: '';
        $sourceImage = $this->createImageResource($filePath, $mime);

        if (!$sourceImage) {
            return ['generated' => 0, 'skipped' => 1];
        }

        $size = @getimagesize($filePath);
        if (!$size || empty($size[0]) || empty($size[1])) {
            imagedestroy($sourceImage);
            return ['generated' => 0, 'skipped' => 1];
        }

        [$sourceWidth, $sourceHeight] = $size;

        $relative = ltrim(str_replace(str_replace('\\', '/', $scanBase), '', str_replace('\\', '/', $filePath)), '/');
        $relativeDir = trim(dirname($relative), '.\\/');
        $baseName = pathinfo($relative, PATHINFO_FILENAME);

        $outputDir = $scanBase . DIRECTORY_SEPARATOR . 'responsive' . DIRECTORY_SEPARATOR . ($relativeDir !== '' ? str_replace('/', DIRECTORY_SEPARATOR, $relativeDir) : '');
        if (!is_dir($outputDir)) {
            @mkdir($outputDir, 0755, true);
        }

        $generated = 0;
        $skipped = 0;

        foreach ($this->widths as $targetWidth) {
            if ($targetWidth > $sourceWidth) {
                $skipped++;
                continue;
            }

            $targetHeight = (int) round(($sourceHeight / $sourceWidth) * $targetWidth);
            $canvas = imagecreatetruecolor($targetWidth, $targetHeight);
            if (!$canvas) {
                $skipped++;
                continue;
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

            $outPath = $outputDir . DIRECTORY_SEPARATOR . $baseName . '-' . $targetWidth . 'w.webp';
            $ok = imagewebp($canvas, $outPath, 75);
            imagedestroy($canvas);

            if ($ok) {
                $generated++;
            } else {
                $skipped++;
            }
        }

        imagedestroy($sourceImage);

        return ['generated' => $generated, 'skipped' => $skipped];
    }

    private function createImageResource(string $filePath, string $mime)
    {
        return match ($mime) {
            'image/jpeg' => @imagecreatefromjpeg($filePath),
            'image/png' => @imagecreatefrompng($filePath),
            'image/webp' => function_exists('imagecreatefromwebp') ? @imagecreatefromwebp($filePath) : null,
            default => null,
        };
    }
}
