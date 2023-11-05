<?php

declare(strict_types=1);

namespace App\Http\Livewire\Concerns;

trait CKEditor
{
    public const CKEDITOR_IS_READY = true;
    public const CKEDITOR_IS_NOT_READY = false;

    protected bool $ckeditorIsReady = self::CKEDITOR_IS_NOT_READY;

    public function handleCkeditorReady(): void
    {
        $this->ckeditorIsReady = self::CKEDITOR_IS_READY;
    }

    public function isNotCkeditorReady(): bool
    {
        return self::CKEDITOR_IS_NOT_READY === $this->ckeditorIsReady;
    }

    public function boot(): void
    {
        data_set($this->listeners, 'ckeditorReady', 'handleCkeditorReady');
    }
}
