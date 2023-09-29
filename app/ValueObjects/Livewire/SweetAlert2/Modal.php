<?php

declare(strict_types=1);

namespace App\ValueObjects\Livewire\SweetAlert2;

use App\Enums\SweetAlertModalType;
use Illuminate\Contracts\Support\Arrayable;

final class Modal implements Arrayable
{
    /** @var string */
    public const EMPTY_TEXT = '';
    /** @var bool */
    public const HIDDEN_DENY_BUTTON = false;
    /** @var bool */
    public const SHOW_DENY_BUTTON = false;
    /** @var bool */
    public const HIDDEN_CANCEL_BUTTON = false;
    /** @var string */
    public const CONFIRM_BUTTON_DEFAULT_TEXT = 'Ok';
    /** @var string */
    public const DENY_BUTTON_DEFAULT_TEXT = 'Cancel';

    private function __construct(
        public readonly SweetAlertModalType $type,
        public readonly string $title,
        public readonly string $text = self::EMPTY_TEXT,
        public readonly bool $showDenyButton = self::HIDDEN_DENY_BUTTON,
        public readonly bool $showCancelButton = self::HIDDEN_DENY_BUTTON,
        public readonly string $confirmButtonText = self::CONFIRM_BUTTON_DEFAULT_TEXT,
        public readonly string $denyButtonText = self::DENY_BUTTON_DEFAULT_TEXT,
        public readonly ?ModalEvent $confirmEvent = null,
        public readonly ?ModalEvent $denyEvent = null,
    ) {
    }

    public static function createSimple(
        string $title,
        string $text = '',
        SweetAlertModalType $type = SweetAlertModalType::Success,
    ): self {
        return new self(
            type: $type,
            title: $title,
            text: $text,
        );
    }

    public static function createConfirmable(
        string $title,
        ModalEvent $confirmEvent,
        SweetAlertModalType $type = SweetAlertModalType::Question,
        string $text = self::EMPTY_TEXT,
        bool $showCancelButton = self::HIDDEN_DENY_BUTTON,
        string $confirmButtonText = self::CONFIRM_BUTTON_DEFAULT_TEXT,
        string $denyButtonText = self::DENY_BUTTON_DEFAULT_TEXT,
        ?ModalEvent $denyEvent = null,
    ): self {
        return new self(
            type: $type,
            title: $title,
            text: $text,
            showDenyButton: self::SHOW_DENY_BUTTON,
            showCancelButton: $showCancelButton,
            confirmButtonText: $confirmButtonText,
            denyButtonText: $denyButtonText,
            confirmEvent: $confirmEvent,
            denyEvent: $denyEvent,
        );
    }

    public function toArray(): array
    {
        return [
            'icon' => $this->type->icon(),
            'title' => $this->title,
            'text' => $this->text,
            'show_deny_button' => $this->showDenyButton,
            'show_cancel_button' => $this->showCancelButton,
            'confirm_button_text' => $this->confirmButtonText,
            'deny_button_text' => $this->denyButtonText,
            'confirm_event' => $this->confirmEvent,
            'deny_event' => $this->denyEvent,
        ];
    }
}
