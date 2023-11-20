<?php

declare(strict_types=1);

namespace Tests\Feature\Observers;

use App\Models\Exercise;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\Concerns\Authentication;
use Tests\Concerns\Authorization;
use Tests\TestCase;

class ExerciseObserverTest extends TestCase
{
    use Authentication;
    use Authorization;
    use RefreshDatabase;

    private string $dirtyHtml;
    private string $cleanHtml;
    private string $cleanHtmlRawText;

    protected function setUp(): void
    {
        parent::setUp();

        Mockery::globalHelpers();

        $this->dirtyHtml = <<<HTML
        <body>
            <h1>Hello, world!</h1>
            <p>This is a sample paragraph.</p>
            <script>alert('This is a potentially dangerous JavaScript script!');</script>
            <a href="javascript:alert('Dangerous!')">Click me</a>
            <img src="x" onerror="alert('Dangerous script!')">
        </body>
        HTML;

        $this->cleanHtml = <<<HTML
            <h1>Hello, world!</h1>
            <p>This is a sample paragraph.</p>
            <a>Click me</a>
            <img src="x" alt="x" />
        HTML;

        $this->cleanHtmlRawText = 'Hello, world! This is a sample paragraph. Click me';
    }

    /** @test */
    public function it_should_cleanse_the_provided_instructions_html_property_by_eliminating_undesirable_html_tags_during_creation(): void
    {
        $this->createMockToMuteSendVerificationEmailAction();

        $exercise = Exercise::factory()->create([
            'instructions_html' => $this->dirtyHtml,
        ]);

        $this->assertHtmlSame($this->cleanHtml, $exercise->instructions_html);
    }

    /** @test */
    public function it_should_cleanse_the_provided_instructions_html_property_by_eliminating_undesirable_html_tags_during_updating(): void
    {
        $this->createMockToMuteSendVerificationEmailAction();

        $exercise = Exercise::factory()->create();
        $prevInstructionsHtml = $exercise->instructions_html;

        $exercise->update([
            'instructions_html' => $this->dirtyHtml,
        ]);

        $this->assertHtmlNotSame($prevInstructionsHtml, $exercise->instructions_html);
        $this->assertHtmlSame($this->cleanHtml, $exercise->instructions_html);
    }

    /** @test */
    public function it_should_strip_all_white_spaces_and_html_tags_when_the_instructions_html_property_has_been_assigned_and_assign_that_stripped_string_to_the_instructions_raw_property(): void
    {
        $this->createMockToMuteSendVerificationEmailAction();

        $exercise = Exercise::factory()->create([
            'instructions_html' => $this->dirtyHtml,
        ]);

        $this->assertSame($this->cleanHtmlRawText, $exercise->instructions_raw);
    }

    /** @test */
    public function it_should_strip_all_white_spaces_and_html_tags_when_the_instructions_html_property_has_been_updated_and_assign_that_stripped_string_to_the_instructions_raw_property(): void
    {
        $this->createMockToMuteSendVerificationEmailAction();

        $exercise = Exercise::factory()->create();
        $prevInstructionsRaw = $exercise->instructions_raw;

        $exercise->update([
            'instructions_html' => $this->dirtyHtml,
        ]);

        $this->assertNotSame($prevInstructionsRaw, $exercise->instructions_raw);
        $this->assertSame($this->cleanHtmlRawText, $exercise->instructions_raw);
    }
}
