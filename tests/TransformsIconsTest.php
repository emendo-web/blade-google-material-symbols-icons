<?php

declare(strict_types=1);

namespace Tests;

use DOMDocument;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;

/**
 * Guards the output contract produced by scripts/UpdateIcons.php:
 * every generated icon must expose `fill="currentColor"`, keep the
 * original viewBox and carry no width/height attributes.
 */
class TransformsIconsTest extends TestCase
{
    private const SVG_DIR = __DIR__ . '/../resources/svg';

    #[Test]
    #[DataProvider('representativeIcons')]
    public function representative_icons_respect_the_output_contract(string $file): void
    {
        $svg = $this->loadSvgElement(self::SVG_DIR . '/' . $file);

        $this->assertNotNull($svg, "No <svg> element in {$file}");
        $this->assertSame('currentColor', $svg->getAttribute('fill'));
        $this->assertSame('0 -960 960 960', $svg->getAttribute('viewBox'));
        $this->assertFalse($svg->hasAttribute('width'), "Unexpected width in {$file}");
        $this->assertFalse($svg->hasAttribute('height'), "Unexpected height in {$file}");
    }

    public static function representativeIcons(): array
    {
        return [
            'outlined' => ['o-home.svg'],
            'rounded' => ['r-home.svg'],
            'sharp' => ['s-home.svg'],
            'outlined filled' => ['o-home-fill.svg'],
            'rounded filled' => ['r-home-fill.svg'],
            'sharp filled' => ['s-home-fill.svg'],
        ];
    }

    #[Test]
    public function a_deterministic_sample_of_the_whole_set_respects_the_contract(): void
    {
        $icons = glob(self::SVG_DIR . '/*.svg') ?: [];
        $this->assertNotEmpty($icons, 'No icon found in the resources directory.');

        // Sample every 60th file to cover the whole alphabet without
        // parsing all ~23k icons on each run.
        for ($i = 0; $i < count($icons); $i += 60) {
            $file = $icons[$i];
            $svg = $this->loadSvgElement($file);

            $name = basename($file);
            $this->assertNotNull($svg, "No <svg> element in {$name}");
            $this->assertSame('currentColor', $svg->getAttribute('fill'), "Missing fill in {$name}");
            $this->assertFalse($svg->hasAttribute('width'), "Unexpected width in {$name}");
            $this->assertFalse($svg->hasAttribute('height'), "Unexpected height in {$name}");
        }
    }

    private function loadSvgElement(string $path): ?\DOMElement
    {
        $dom = new DOMDocument();
        $this->assertTrue($dom->loadXML((string) file_get_contents($path)), 'Invalid SVG: ' . basename($path));

        return $dom->getElementsByTagName('svg')->item(0);
    }
}
