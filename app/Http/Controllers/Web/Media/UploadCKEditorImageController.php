<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web\Media;

use App\Actions\Media\CreateCKEditorImageAction;
use App\DataObjects\FileData;
use App\Http\Controllers\Controller;
use App\Http\Requests\Media\UploadCKEditorImageRequest;
use Illuminate\Http\JsonResponse;

class UploadCKEditorImageController extends Controller
{
    public function __invoke(UploadCKEditorImageRequest $request, CreateCKEditorImageAction $action): JsonResponse
    {
        /** @var FileData $created */
        $created = $action->execute($request->upload)->getData();

        return response()->json([
            'url' => $created->url,
        ]);
    }
}
