<?php

declare(strict_types=1);

namespace Tests\Feature\Web\Media;

use App\Models\Media;
use App\Models\Permission;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\Authentication;
use Tests\Concerns\Media as HasMedia;
use Tests\TestCase;

class CKEditorImageUploadTest extends TestCase
{
    use Authentication;
    use HasMedia;
    use RefreshDatabase;

    /** @test */
    public function guest_user_can_not_upload_a_Ckeditor_image_and_should_be_redirected_to_the_login_page(): void
    {
        $response = $this->post(
            uri: 'media/ckeditor-image/upload',
            data: [
                'upload' => $this->createTestImage(),
            ],
        )
            ->assertRedirect('/login');

        $this->followRedirects($response)
            ->assertSee('Logowanie');
    }

    /** @test */
    public function it_should_abort_request_with_a_403_status_code_when_the_auth_user_without_upload_ckeditor_images_permission_try_to_upload_a_Ckeditor_image(): void
    {
        $this->authenticated()
            ->post(
                uri: 'media/ckeditor-image/upload',
                data: [
                    'upload' => $this->createTestImage(),
                ],
            )
            ->assertForbidden();
    }

    /** @test */
    public function auth_user_can_upload_a_Ckeditor_image_when_their_has_assigned_upload_ckeditor_images_permission(): void
    {
        $this->assignPermissionToUser(Permission::factory()->create(['name' => 'upload-ckeditor-images']));

        $this->authenticated()
            ->post(
                uri: 'media/ckeditor-image/upload',
                data: [
                    'upload' => $this->createTestImage(),
                ],
            )
            ->assertOk();
    }

    /** @test */
    public function it_should_return_file_full_path_when_auth_user_upload_the_Ckeditor_image_succeed(): void
    {
        $this->assignPermissionToUser(Permission::factory()->create(['name' => 'upload-ckeditor-images']));
        $file = $this->createTestImage();

        $response = $this->authenticated()
            ->post(
                uri: 'media/ckeditor-image/upload',
                data: [
                    'upload' => $file,
                ],
            )
            ->assertOk();

        $createdImageData = Media::query()->firstWhere('name', $file->hashName())->getData();

        $response->assertExactJson([
            'url' => $createdImageData->url,
        ]);
    }
}
