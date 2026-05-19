<?php

namespace App\Seo;

use GdImage;

class SharingImageGenerator
{
    public const IMAGE_WIDTH = 1200;
    public const IMAGE_HEIGHT = 630;
    public const PADDING = 70;
    public const TITLE_FONT_SIZE = 72;
    public const TITLE_FONT_SIZE_SMALL = 56;
    public const LABEL_FONT_SIZE = 22;

    protected const FONT_TITLE = __DIR__.'/../../../assets/fonts/HostGrotesk-Bold.ttf';
    protected const FONT_MONO  = __DIR__.'/../../../assets/fonts/JetBrainsMono-Regular.ttf';

    protected string $title;
    protected ?string $author = null;
    protected ?\DateTimeInterface $date = null;
    protected ?string $tag = null;

    public function setTitle($title): self
    {
        $this->title = $title;
        return $this;
    }

    public function setAuthor($author): self
    {
        $this->author = $author;
        return $this;
    }

    public function setDate(?\DateTimeInterface $date): self
    {
        $this->date = $date;
        return $this;
    }

    public function setTag(?string $tag): self
    {
        $this->tag = $tag;
        return $this;
    }

    public function output(): void
    {
        $image = $this->prepare();
        header('Content-type: image/jpeg');
        imagejpeg($image, null, 92);
    }

    public function save($path): void
    {
        $image = $this->prepare();
        imagejpeg($image, $path, 92);
    }

    protected function prepare(): GdImage
    {
        $img = imagecreatetruecolor(self::IMAGE_WIDTH, self::IMAGE_HEIGHT);

        $bg     = imagecolorallocate($img, 23, 20, 15);     // #17140f
        $ink    = imagecolorallocate($img, 245, 242, 236);  // #f5f2ec
        $muted  = imagecolorallocate($img, 138, 132, 124);  // #8a847c
        $accent = imagecolorallocate($img, 255, 45, 143);   // #ff2d8f

        imagefilledrectangle($img, 0, 0, self::IMAGE_WIDTH, self::IMAGE_HEIGHT, $bg);

        // top label row: accent dot + mono date · tag
        $labelParts = [];
        if ($this->date) {
            $labelParts[] = $this->date->format('Y-m-d');
        }
        if ($this->tag) {
            $labelParts[] = strtoupper($this->tag);
        }
        $label = implode('  ·  ', $labelParts);

        $dotCx = self::PADDING + 8;
        $dotCy = self::PADDING + 10;
        imagefilledellipse($img, $dotCx, $dotCy, 14, 14, $accent);

        if ($label !== '') {
            imagettftext(
                $img,
                self::LABEL_FONT_SIZE,
                0,
                $dotCx + 18,
                $dotCy + 8,
                $muted,
                self::FONT_MONO,
                $label
            );
        }

        // title — pixel-aware wrap
        $maxWidth = self::IMAGE_WIDTH - (self::PADDING * 2);
        [$lines, $titleSize] = $this->wrapTitle($this->title, $maxWidth);

        $lineHeight = (int) round($titleSize * 1.18);
        $titleBlockHeight = $lineHeight * count($lines);
        $titleStartY = (int) ((self::IMAGE_HEIGHT - $titleBlockHeight) / 2) + (int) round($titleSize * 0.9);

        foreach ($lines as $i => $line) {
            imagettftext(
                $img,
                $titleSize,
                0,
                self::PADDING,
                $titleStartY + ($i * $lineHeight),
                $ink,
                self::FONT_TITLE,
                $line
            );
        }

        // bottom row: left "by Author", right "pronskiy.com"
        $bottomY = self::IMAGE_HEIGHT - self::PADDING + 8;

        if ($this->author) {
            imagettftext(
                $img,
                self::LABEL_FONT_SIZE,
                0,
                self::PADDING,
                $bottomY,
                $muted,
                self::FONT_MONO,
                $this->author
            );
        }

        return $img;
    }

    /**
     * Pixel-aware word wrap. Returns [lines[], fontSize].
     * Tries TITLE_FONT_SIZE first, falls back to TITLE_FONT_SIZE_SMALL if >3 lines.
     */
    protected function wrapTitle(string $title, int $maxWidth): array
    {
        foreach ([self::TITLE_FONT_SIZE, self::TITLE_FONT_SIZE_SMALL] as $size) {
            $lines = $this->wrapAtSize($title, $maxWidth, $size);
            if (count($lines) <= 3) {
                return [$lines, $size];
            }
        }
        // last resort: smallest size, take first 3 lines + ellipsis
        $lines = $this->wrapAtSize($title, $maxWidth, self::TITLE_FONT_SIZE_SMALL);
        if (count($lines) > 3) {
            $lines = array_slice($lines, 0, 3);
            $lines[2] = rtrim($lines[2]) . '…';
        }
        return [$lines, self::TITLE_FONT_SIZE_SMALL];
    }

    protected function wrapAtSize(string $text, int $maxWidth, int $size): array
    {
        $words = preg_split('/\s+/', trim($text));
        $lines = [];
        $current = '';

        foreach ($words as $word) {
            $candidate = $current === '' ? $word : $current . ' ' . $word;
            $bbox = imagettfbbox($size, 0, self::FONT_TITLE, $candidate);
            $width = $bbox[2] - $bbox[0];

            if ($width <= $maxWidth || $current === '') {
                $current = $candidate;
            } else {
                $lines[] = $current;
                $current = $word;
            }
        }
        if ($current !== '') {
            $lines[] = $current;
        }
        return $lines;
    }
}
