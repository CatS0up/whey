<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Actions\Muscle\UpsertMuscleSmallThumbnailAction;
use App\Actions\Muscle\UpsertMuscleThumbnailAction;
use App\Models\Muscle;
use Illuminate\Database\Seeder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class MuscleThumbnailSeeder extends Seeder
{
    public function __construct(
        private UpsertMuscleThumbnailAction $upsertMuscleThumbnailAction,
        private UpsertMuscleSmallThumbnailAction $upsertMuscleSmallThumbnailAction,
    ) {
    }

    public static function dataTable(): array
    {
        return [
            // Deltoid
            [
                'muscle' => 'Anterior deltoids',
                'original_name' => 'anterior_deltoids.jpg',
                'path' => public_path('images/muscles/deltoid/anterior_deltoids.jpg'),
                'mime_type' => 'image/jpg',
            ],
            [
                'muscle' => 'Lateral deltoids',
                'original_name' => 'lateral_deltoids.jpg',
                'path' => public_path('images/muscles/deltoid/lateral_deltoids.jpg'),
                'mime_type' => 'image/jpg',
            ],
            [
                'muscle' => 'Posterior deltoids',
                'original_name' => 'posterior_deltoids.jpg',
                'path' => public_path('images/muscles/deltoid/posterior_deltoids.jpg'),
                'mime_type' => 'image/jpg',
            ],

            // Chest
            [
                'muscle' => 'Greater pectoral - lower and middle part',
                'original_name' => 'greater_pectoral_lower_and_middle_part.jpg',
                'path' => public_path('images/muscles/chest/greater_pectoral_lower_and_middle_part.jpg'),
                'mime_type' => 'image/jpg',
            ],
            [
                'muscle' => 'Greater pectoral - upper part',
                'original_name' => 'greater_pectoral_upper_part.jpg',
                'path' => public_path('images/muscles/chest/greater_pectoral_upper_part.jpg'),
                'mime_type' => 'image/jpg',
            ],
            [
                'muscle' => 'Serratus anterior muscle',
                'original_name' => 'serratus_anterior_muscle.jpg',
                'path' => public_path('images/muscles/chest/serratus_anterior_muscle.jpg'),
                'mime_type' => 'image/jpg',
            ],
            [
                'muscle' => 'Pectoralis minor',
                'original_name' => 'pectoralis_minor.jpg',
                'path' => public_path('images/muscles/chest/pectoralis_minor.jpg'),
                'mime_type' => 'image/jpg',
            ],

            // Biceps
            [
                'muscle' => 'Biceps - long head',
                'original_name' => 'biceps_long_head.jpg',
                'path' => public_path('images/muscles/biceps/biceps_long_head.jpg'),
                'mime_type' => 'image/jpg',
            ],
            [
                'muscle' => 'Biceps - short head',
                'original_name' => 'biceps_short_head.jpg',
                'path' => public_path('images/muscles/biceps/biceps_short_head.jpg'),
                'mime_type' => 'image/jpg',
            ],

            // Brachialis
            [
                'muscle' => 'Brachialis',
                'original_name' => 'brachialis.jpg',
                'path' => public_path('images/muscles/brachialis/brachialis.jpg'),
                'mime_type' => 'image/jpg',
            ],

            // Triceps
            [
                'muscle' => 'Triceps - long head',
                'original_name' => 'triceps_long_head.jpg',
                'path' => public_path('images/muscles/triceps/triceps_long_head.jpg'),
                'mime_type' => 'image/jpg',
            ],
            [
                'muscle' => 'Triceps - lateral head',
                'original_name' => 'triceps_lateral_head.jpg',
                'path' => public_path('images/muscles/triceps/triceps_lateral_head.jpg'),
                'mime_type' => 'image/jpg',
            ],
            [
                'muscle' => 'Triceps - medial head',
                'original_name' => 'triceps_medial_head.jpg',
                'path' => public_path('images/muscles/triceps/triceps_medial_head.jpg'),
                'mime_type' => 'image/jpg',
            ],

            // Forearm
            [
                'muscle' => 'Extensor carpi',
                'original_name' => 'extensor_carpi.jpg',
                'path' => public_path('images/muscles/forearm/extensor_carpi.jpg'),
                'mime_type' => 'image/jpg',
            ],
            [
                'muscle' => 'Flexor carpi',
                'original_name' => 'triceps_medial_head.jpg',
                'path' => public_path('images/muscles/forearm/flexor_carpi.jpg'),
                'mime_type' => 'image/jpg',
            ],

            // Back
            [
                'muscle' => 'Erector spinae',
                'original_name' => 'erector_spinae.jpg',
                'path' => public_path('images/muscles/back/erector_spinae.jpg'),
                'mime_type' => 'image/jpg',
            ],
            [
                'muscle' => 'Latissimus dorsi',
                'original_name' => 'latissimus_dorsi.jpg',
                'path' => public_path('images/muscles/back/latissimus_dorsi.jpg'),
                'mime_type' => 'image/jpg',
            ],
            [
                'muscle' => 'Teres major',
                'original_name' => 'teres_major.jpg',
                'path' => public_path('images/muscles/back/teres_major.jpg'),
                'mime_type' => 'image/jpg',
            ],
            [
                'muscle' => 'Teres minor',
                'original_name' => 'teres_minor.jpg',
                'path' => public_path('images/muscles/back/teres_minor.jpg'),
                'mime_type' => 'image/jpg',
            ],
            [
                'muscle' => 'Trapezius - upper part',
                'original_name' => 'trapezius_upper_part.jpg',
                'path' => public_path('images/muscles/back/trapezius_upper_part.jpg'),
                'mime_type' => 'image/jpg',
            ],
            [
                'muscle' => 'Trapezius - middle part',
                'original_name' => 'trapezius_middle_part.jpg',
                'path' => public_path('images/muscles/back/trapezius_middle_part.jpg'),
                'mime_type' => 'image/jpg',
            ],
            [
                'muscle' => 'Trapezius - down part',
                'original_name' => 'trapezius_down_part.jpg',
                'path' => public_path('images/muscles/back/trapezius_down_part.jpg'),
                'mime_type' => 'image/jpg',
            ],
            [
                'muscle' => 'Rhomboid',
                'original_name' => 'rhomboid.jpg',
                'path' => public_path('images/muscles/back/rhomboid.jpg'),
                'mime_type' => 'image/jpg',
            ],
            [
                'muscle' => 'Levator scapulae',
                'original_name' => 'levator_scapulae.jpg',
                'path' => public_path('images/muscles/back/levator_scapulae.jpg'),
                'mime_type' => 'image/jpg',
            ],
            [
                'muscle' => 'Infraspinatus',
                'original_name' => 'infraspinatus.jpg',
                'path' => public_path('images/muscles/back/infraspinatus.jpg'),
                'mime_type' => 'image/jpg',
            ],

            // Abs
            [
                'muscle' => 'Rectus abdominis',
                'original_name' => 'rectus_abdominis.jpg',
                'path' => public_path('images/muscles/abs/rectus_abdominis.jpg'),
                'mime_type' => 'image/jpg',
            ],
            [
                'muscle' => 'Oblique',
                'original_name' => 'oblique.jpg',
                'path' => public_path('images/muscles/abs/oblique.jpg'),
                'mime_type' => 'image/jpg',
            ],

            // Glutes
            [
                'muscle' => 'Gluteus maximus',
                'original_name' => 'gluteus_maximus.jpg',
                'path' => public_path('images/muscles/glutes/gluteus_maximus.jpg'),
                'mime_type' => 'image/jpg',
            ],
            [
                'muscle' => 'Gluteus medius',
                'original_name' => 'gluteus_medius.jpg',
                'path' => public_path('images/muscles/glutes/gluteus_medius.jpg'),
                'mime_type' => 'image/jpg',
            ],
            [
                'muscle' => 'Gluteus minimus',
                'original_name' => 'gluteus_minimus.jpg',
                'path' => public_path('images/muscles/glutes/gluteus_minimus.jpg'),
                'mime_type' => 'image/jpg',
            ],

            // Legs
            [
                'muscle' => 'Vastus medialis',
                'original_name' => 'vastus_medialis.jpg',
                'path' => public_path('images/muscles/legs/vastus_medialis.jpg'),
                'mime_type' => 'image/jpg',
            ],
            [
                'muscle' => 'Vastus lateralis',
                'original_name' => 'vastus_lateralis.jpg',
                'path' => public_path('images/muscles/legs/vastus_lateralis.jpg'),
                'mime_type' => 'image/jpg',
            ],
            [
                'muscle' => 'Vastus intermedius',
                'original_name' => 'vastus_intermedius.jpg',
                'path' => public_path('images/muscles/legs/vastus_intermedius.jpg'),
                'mime_type' => 'image/jpg',
            ],
            [
                'muscle' => 'Sartorius',
                'original_name' => 'sartorius.jpg',
                'path' => public_path('images/muscles/legs/sartorius.jpg'),
                'mime_type' => 'image/jpg',
            ],
            [
                'muscle' => 'Adductor longus',
                'original_name' => 'adductor_longus.jpg',
                'path' => public_path('images/muscles/legs/adductor_longus.jpg'),
                'mime_type' => 'image/jpg',
            ],
            [
                'muscle' => 'Pubis',
                'original_name' => 'pubis.jpg',
                'path' => public_path('images/muscles/legs/pubis.jpg'),
                'mime_type' => 'image/jpg',
            ],
            [
                'muscle' => 'Iliacus',
                'original_name' => 'iliacus.jpg',
                'path' => public_path('images/muscles/legs/iliacus.jpg'),
                'mime_type' => 'image/jpg',
            ],
            [
                'muscle' => 'Iliopsoas',
                'original_name' => 'iliopsoas.jpg',
                'path' => public_path('images/muscles/legs/iliopsoas.jpg'),
                'mime_type' => 'image/jpg',
            ],
            [
                'muscle' => 'Tensor fasciae latae',
                'original_name' => 'tensor_fasciae_latae.jpg',
                'path' => public_path('images/muscles/legs/tensor_fasciae_latae.jpg'),
                'mime_type' => 'image/jpg',
            ],
            [
                'muscle' => 'Gracilis',
                'original_name' => 'gracilis.jpg',
                'path' => public_path('images/muscles/legs/gracilis.jpg'),
                'mime_type' => 'image/jpg',
            ],
            [
                'muscle' => 'Biceps femoris',
                'original_name' => 'biceps_femoris.jpg',
                'path' => public_path('images/muscles/legs/biceps_femoris.jpg'),
                'mime_type' => 'image/jpg',
            ],
            [
                'muscle' => 'Semitendinosus',
                'original_name' => 'semitendinosus.jpg',
                'path' => public_path('images/muscles/legs/semitendinosus.jpg'),
                'mime_type' => 'image/jpg',
            ],
            [
                'muscle' => 'Semimembranosus',
                'original_name' => 'semimembranosus.jpg',
                'path' => public_path('images/muscles/legs/semimembranosus.jpg'),
                'mime_type' => 'image/jpg',
            ],

            // Drumsticks
            [
                'muscle' => 'Tibial',
                'original_name' => 'tibial.jpg',
                'path' => public_path('images/muscles/drumsticks/tibial.jpg'),
                'mime_type' => 'image/jpg',
            ],
            [
                'muscle' => 'Soleus',
                'original_name' => 'soleus.jpg',
                'path' => public_path('images/muscles/drumsticks/soleus.jpg'),
                'mime_type' => 'image/jpg',
            ],
            [
                'muscle' => 'Gastrocnemius - front head',
                'original_name' => 'gastrocnemius_front_head.jpg',
                'path' => public_path('images/muscles/drumsticks/gastrocnemius_front_head.jpg'),
                'mime_type' => 'image/jpg',
            ],
            [
                'muscle' => 'Gastrocnemius - lateral head',
                'original_name' => 'gastrocnemius_lateral_head.jpg',
                'path' => public_path('images/muscles/drumsticks/gastrocnemius_lateral_head.jpg'),
                'mime_type' => 'image/jpg',
            ],
        ];
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $muscles = Muscle::all();
        $thumbnails = self::dataTable();

        Storage::disk(config('app.uploads.disk'))->deleteDirectory('thumbnails');
        Storage::disk(config('app.uploads.disk'))->deleteDirectory('small_thumbnails');

        foreach ($thumbnails as $thumbnail) {
            /** @var Muscle $muscle */
            $muscle = $muscles->firstWhere('name', data_get($thumbnail, 'muscle'));

            $file = $this->createUploadedFile(
                path: data_get($thumbnail, 'path'),
                originalName: data_get($thumbnail, 'original_name'),
                mimeType: data_get($thumbnail, 'mime_type'),
            );

            $this->upsertMuscleThumbnailAction->execute($muscle->id, $file);
            $this->upsertMuscleSmallThumbnailAction->execute($muscle->id, $file);
        }
    }

    private function createUploadedFile(
        string $path,
        string $originalName,
        string $mimeType,
    ): UploadedFile {
        return new UploadedFile(
            $path,
            $originalName,
            $mimeType,
        );
    }
}
