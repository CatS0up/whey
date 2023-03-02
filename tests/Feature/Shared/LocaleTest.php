<?php

declare(strict_types=1);

namespace Tests\Feature\Shared;

use Tests\TestCase;

class LocaleTest extends TestCase
{
    /** @test */
    public function it_should_switch_locale_to_fallback_locale_when_given_locale_is_not_supported(): void
    {
        $this->get('locale/xx');

        $this->assertEquals(config('app.fallback_locale'), app()->getLocale());
    }

    /** @test */
    public function it_should_switch_locale_to_all_locales_defined_in_app_config_file(): void
    {
        $availableLocales = config('app.available_locales');

        foreach ($availableLocales as $locale) {
            $response = $this->get("locale/{$locale}");

            $response->assertSessionHas('locale', $locale);
            $this->assertEquals(app()->getLocale(), $locale);
        }
    }

    // TODO: Add redirection back test when routing will be ready
}
