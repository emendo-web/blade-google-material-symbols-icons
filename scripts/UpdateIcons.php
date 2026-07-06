<?php

class UpdateIcons
{
    private const VARIANTS = [
        'outlined' => 'o-',
        'rounded' => 'r-',
        'sharp' => 's-'
    ];

    private const SVG_DIR = __DIR__ . '/../resources/svg';
    private const SOURCE_DIR = __DIR__ . '/../node_modules/@material-symbols/svg-400';

    public function __construct()
    {
        $this->log('-- Updating icons...');

        $this->check_sources();
        $this->remove_old_icons();
        $this->copy_new_icons();
        $this->adapt_new_icons();

        $this->log('-- Done.');
    }

    private function check_sources()
    {
        $this->log('-- -- Checking sources...');

        foreach (self::VARIANTS as $variant => $prefix) {
            $dir = self::SOURCE_DIR . "/{$variant}";
            if (!is_dir($dir)) {
                $this->abort(
                    "Source directory not found: {$dir}" . PHP_EOL .
                    "Did you run `npm install` first?"
                );
            }
        }

        // Ensure the destination directory exists before we start.
        if (!is_dir(self::SVG_DIR) && !mkdir(self::SVG_DIR, 0755, true)) {
            $this->abort('Unable to create destination directory: ' . self::SVG_DIR);
        }
    }

    private function remove_old_icons()
    {
        $this->log('-- -- Removing old icons...');

        $icons = glob(self::SVG_DIR . '/*.svg') ?: [];
        foreach ($icons as $icon) {
            unlink($icon);
        }
    }

    private function copy_new_icons()
    {
        $this->log('-- -- Copying new icons...');

        $copied = 0;
        foreach (self::VARIANTS as $variant => $prefix) {
            $icons = glob(self::SOURCE_DIR . "/{$variant}/*.svg") ?: [];
            foreach ($icons as $icon) {
                $newName = $prefix . basename($icon);
                if (copy($icon, self::SVG_DIR . '/' . $newName)) {
                    $copied++;
                }
            }
        }

        if ($copied === 0) {
            $this->abort('No icon was copied. Aborting to avoid ending up with an empty icon set.');
        }

        $this->log("-- -- {$copied} icons copied.");
    }

    private function adapt_new_icons()
    {
        $this->log('-- -- Adapt new icons...');

        $useInternalErrors = libxml_use_internal_errors(true);

        $adapted = 0;
        $icons = glob(self::SVG_DIR . '/*.svg') ?: [];
        foreach ($icons as $icon) {
            // Get icon content
            $svgString = file_get_contents($icon);

            // Parse icon content
            $dom = new DOMDocument;
            if (!$dom->loadXML($svgString)) {
                libxml_clear_errors();
                $this->log('-- -- -- Skipping invalid SVG: ' . basename($icon));
                continue;
            }

            // Get svg element
            $svg = $dom->getElementsByTagName('svg')->item(0);
            if ($svg === null) {
                $this->log('-- -- -- No <svg> element in: ' . basename($icon));
                continue;
            }

            $svg->setAttribute('fill', 'currentColor');
            // Supprimer les attributs width, height
            $svg->removeAttribute('width');
            $svg->removeAttribute('height');

            // Save icon svg without namespace
            $newSvgString = $dom->saveXML($svg, LIBXML_NOXMLDECL);

            // Save icon
            file_put_contents($icon, $newSvgString);
            $adapted++;
        }

        libxml_use_internal_errors($useInternalErrors);

        $this->log("-- -- {$adapted} icons adapted.");
    }

    private function log($message)
    {
        echo $message . PHP_EOL;
    }

    private function abort($message)
    {
        fwrite(STDERR, $message . PHP_EOL);
        exit(1);
    }
}

(new UpdateIcons);
