<?php

declare(strict_types=1);

namespace Tests\Unit\Support;

use App\Support\HtmlPurifier;
use PHPUnit\Framework\TestCase;

class HtmlPurifierTest extends TestCase
{
    /** @test */
    public function it_should_cleanse_the_provided_html_string_by_eliminating_undesirable_html_tags(): void
    {
        $dirtyHtml = <<<HTML
        <body>
            <h1>Hello, world!</h1>
            <p>This is a sample paragraph.</p>
            <script>alert('This is a potentially dangerous JavaScript script!');</script>
            <a href="javascript:alert('Dangerous!')">Click me</a>
            <img src="x" onerror="alert('Dangerous script!')">
        </body>
        HTML;

        $cleanHtml = <<<HTML
            <h1>Hello, world!</h1>
            <p>This is a sample paragraph.</p>
            <a>Click me</a>
            <img src="x" alt="x" />
        HTML;

        $actual = HtmlPurifier::purify($dirtyHtml);

        $actual = preg_replace('/\s+/', ' ', trim($actual));
        $cleanHtml = preg_replace('/\s+/', ' ', trim($cleanHtml));

        $this->assertSame($cleanHtml, $actual);
    }

    /** @test */
    public function it_should_strip_all_html_tags_and_unnecessary_white_spaces_from_the_provided_html_string(): void
    {
        $html = <<<HTML
            <div class="section">
                <h2 class="title"><span style="color:blue">Welcome to my site!</span></h2>
                <p class="description"><strong>Here is some text about my site.</strong></p>
                <div class="links">
                    <h3 class="subtitle">Useful links</h3>
                    <ul class="link-list">
                        <li><a href="https://www.example.com">Example</a></li>
                        <li><a href="https://www.test.com">Test</a></li>
                    </ul>
                </div>
                <div class="comments">
                    <h3 class="subtitle">Comments</h3>
                    <div class="comment">
                        <p class="comment-text">This is a sample comment.</p>
                        <p class="comment-author">- Author</p>
                    </div>
                </div>
            </div>
        HTML;

        $rawText = 'Welcome to my site! Here is some text about my site. Useful links Example Test Comments This is a sample comment. - Author';

        $actual = HtmlPurifier::convertToRawText($html);

        $this->assertSame($rawText, $actual);
    }
}
