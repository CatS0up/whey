<?php

namespace Tests\Feature\Shared;

use Tests\TestCase;

class LanguageTest extends TestCase
{
    /** @test */
    public function it_should_change_locale_to_fallback_when_passed_locale_does_not_supported(): void
    {
        $this->assertEquals(config('app.locale'), 'pl');

        $this->get('lang/xx');

        $this->assertEquals(config('app.fallback_locale'), 'pl');
    }

    /** @test */
    public function it_should_be_able_to_change_to_all_availbale_locales(): void
    {
        $availableLocales = config('app.available_locales');

        foreach ($availableLocales as $code) {
            $response = $this->get("lang/{$code}");

            $response->assertSessionHas('locale', $code);
            $this->assertEquals($code, app()->getLocale());
        }
    }
}
