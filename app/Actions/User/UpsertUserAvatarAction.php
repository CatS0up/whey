<?php

declare(strict_types=1);

namespace App\Actions\User;

use App\Actions\Media\ImageResizeAction;
use App\Actions\Media\UpsertAvatarAction;
use App\DataObjects\FileData;
use App\Models\Media;
use App\Models\User;
use App\ValueObjects\Media\Dimension;
use Illuminate\Http\UploadedFile;

class UpsertUserAvatarAction
{
    public function __construct(
        private UpsertAvatarAction $upsertAvatarAction,
        private ImageResizeAction $imageResizeAction,
        private User $user,
    ) {
    }

    public function execute(int $id, UploadedFile $file): Media
    {
        /** @var User $target */
        $target = $this->user->query()->findOrFail($id);

        $avatar = $this->upsertAvatarAction->execute($file, $target);

        /** @var FileData $fileData */
        $fileData = $avatar->getData();
        $this->imageResizeAction->execute(
            fileData: $fileData,
            dimension: new Dimension(User::AVATAR_WIDTH, User::AVATAR_WIDTH),
            shouldAspectRatio: ImageResizeAction::SHOULD_ASPECT_RATIO,
        );

        return $avatar;
    }
}
