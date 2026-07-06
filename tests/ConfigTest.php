<?php

declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\Attributes\Test;

class ConfigTest extends TestCase
{
    protected function getEnvironmentSetUp($app): void
    {
        $app['config']->set('blade-material-symbols.class', 'default-set-class');
        $app['config']->set('blade-material-symbols.attributes', ['data-test' => 'material']);
        $app['config']->set('blade-material-symbols.fallback', 'o-home');
    }

    #[Test]
    public function it_applies_the_default_class_from_config(): void
    {
        $result = svg('gmsi-o-home')->toHtml();

        $this->assertStringContainsString('class="default-set-class"', $result);
    }

    #[Test]
    public function it_applies_the_default_attributes_from_config(): void
    {
        $result = svg('gmsi-o-home')->toHtml();

        $this->assertStringContainsString('data-test="material"', $result);
    }

    #[Test]
    public function it_falls_back_to_the_configured_icon_for_unknown_names(): void
    {
        $result = svg('gmsi-o-this_icon_does_not_exist')->toHtml();

        // The configured fallback (o-home) glyph must be rendered.
        $this->assertStringContainsString('M220-180h150v-250h220v250h150v-390', $result);
    }
}
