<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\MuscleGroup;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MuscleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('muscles')->insert([
            // Deltoid
            ['name' => 'Anterior deltoids', 'muscle_group' => MuscleGroup::Deltoid->value],
            ['name' => 'Lateral deltoids', 'muscle_group' => MuscleGroup::Deltoid->value],
            ['name' => 'Posterior deltoids', 'muscle_group' => MuscleGroup::Deltoid->value],

            // Chest
            ['name' => 'Greater pectoral - lower and middle part', 'muscle_group' => MuscleGroup::Chest->value],
            ['name' => 'Greater pectoral - upper part', 'muscle_group' => MuscleGroup::Chest->value],
            ['name' => 'Serratus anterior muscle', 'muscle_group' => MuscleGroup::Chest->value],
            ['name' => 'Pectoralis minor', 'muscle_group' => MuscleGroup::Chest->value],

            // Biceps
            ['name' => 'Biceps - long head', 'muscle_group' => MuscleGroup::Biceps->value],
            ['name' => 'Biceps - short head', 'muscle_group' => MuscleGroup::Biceps->value],

            // Brachialis
            ['name' => 'Brachialis', 'muscle_group' => MuscleGroup::Brachialis->value],

            // Triceps
            ['name' => 'Triceps - long head', 'muscle_group' => MuscleGroup::Triceps->value],
            ['name' => 'Triceps - lateral head', 'muscle_group' => MuscleGroup::Triceps->value],
            ['name' => 'Triceps - medial head', 'muscle_group' => MuscleGroup::Triceps->value],

            // Forearm
            ['name' => 'Extensor carpi', 'muscle_group' => MuscleGroup::Forearm->value],
            ['name' => 'Flexor carpi', 'muscle_group' => MuscleGroup::Forearm->value],

            // Back
            ['name' => 'Erector spinae', 'muscle_group' => MuscleGroup::Back->value],
            ['name' => 'Latissimus dorsi', 'muscle_group' => MuscleGroup::Back->value],
            ['name' => 'Teres major', 'muscle_group' => MuscleGroup::Back->value],
            ['name' => 'Teres minor', 'muscle_group' => MuscleGroup::Back->value],
            ['name' => 'Trapezius - upper part', 'muscle_group' => MuscleGroup::Back->value],
            ['name' => 'Trapezius - middle part', 'muscle_group' => MuscleGroup::Back->value],
            ['name' => 'Trapezius - down part', 'muscle_group' => MuscleGroup::Back->value],
            ['name' => 'Rhomboid', 'muscle_group' => MuscleGroup::Back->value],
            ['name' => 'Levator scapulae', 'muscle_group' => MuscleGroup::Back->value],
            ['name' => 'Infraspinatus', 'muscle_group' => MuscleGroup::Back->value],

            // Abs
            ['name' => 'Rectus abdominis', 'muscle_group' => MuscleGroup::Abdominal->value],
            ['name' => 'Oblique', 'muscle_group' => MuscleGroup::Abdominal->value],

            // Glutes
            ['name' => 'Gluteus maximus', 'muscle_group' => MuscleGroup::Glutes->value],
            ['name' => 'Gluteus medius', 'muscle_group' => MuscleGroup::Glutes->value],
            ['name' => 'Gluteus minimus', 'muscle_group' => MuscleGroup::Glutes->value],

            // Legs
            ['name' => 'Vastus medialis', 'muscle_group' => MuscleGroup::Legs->value],
            ['name' => 'Vastus lateralis', 'muscle_group' => MuscleGroup::Legs->value],
            ['name' => 'Vastus intermedius', 'muscle_group' => MuscleGroup::Legs->value],
            ['name' => 'Sartorius', 'muscle_group' => MuscleGroup::Legs->value],
            ['name' => 'Adductor longus', 'muscle_group' => MuscleGroup::Legs->value],
            ['name' => 'Pubis', 'muscle_group' => MuscleGroup::Legs->value],
            ['name' => 'Iliacus', 'muscle_group' => MuscleGroup::Legs->value],
            ['name' => 'Iliopsoas', 'muscle_group' => MuscleGroup::Legs->value],
            ['name' => 'Tensor fasciae latae', 'muscle_group' => MuscleGroup::Legs->value],
            ['name' => 'Gracilis', 'muscle_group' => MuscleGroup::Legs->value],
            ['name' => 'Biceps femoris', 'muscle_group' => MuscleGroup::Legs->value],
            ['name' => 'Semitendinosus', 'muscle_group' => MuscleGroup::Legs->value],
            ['name' => 'Semimembranosus', 'muscle_group' => MuscleGroup::Legs->value],

            // Drumsticks
            ['name' => 'Tibial', 'muscle_group' => MuscleGroup::Drumsticks->value],
            ['name' => 'Soleus', 'muscle_group' => MuscleGroup::Drumsticks->value],
            ['name' => 'Gastrocnemius - front head', 'muscle_group' => MuscleGroup::Drumsticks->value],
            ['name' => 'Gastrocnemius - lateral head', 'muscle_group' => MuscleGroup::Drumsticks->value],
        ]);
    }
}
