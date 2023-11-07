<?php

declare(strict_types=1);

namespace App\Support;

use HTMLPurifier as Purifier;
use HTMLPurifier_Config;

final class HtmlPurifier
{
    public static function purify(string $html): string
    {
        $config = HTMLPurifier_Config::createDefault();
        return (new Purifier($config))->purify($html);
    }

    public static function convertToRawText(string $html): string
    {
        $config = HTMLPurifier_Config::createDefault();
        $config->set('HTML.Allowed', '');
        $purified = (new Purifier($config))->purify($html);

        return preg_replace('/\s+/', ' ', trim($purified));
    }
}
