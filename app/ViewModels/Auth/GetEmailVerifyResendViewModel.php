<?php

declare(strict_types=1);

namespace App\ViewModels\Auth;

use App\DataObjects\Auth\EmailVerifyData;
use App\Exceptions\Auth\UserHasEmailVeirfyActiveToken;
use App\Exceptions\Auth\UserHasVerifiedEmail;
use App\Models\EmailVerify;
use App\ViewModels\ViewModel;

class GetEmailVerifyResendViewModel extends ViewModel
{
    public function __construct(private readonly EmailVerify $token)
    {
        /** @var User $user */
        $user = $this->token->user;
        $userHasActiveToken = $this->token->query()
            ->whereIsActiveForUser($user->id)
            ->exists();


        if ($user->hasVerifiedEmail()) {
            throw UserHasVerifiedEmail::because('Given user has verified email');
        }

        if ($userHasActiveToken) {
            throw UserHasEmailVeirfyActiveToken::because('Given user has active token');
        }
    }

    public function token(): EmailVerifyData
    {
        return $this->token->getData();
    }
}
