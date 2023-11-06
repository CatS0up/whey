<?php

declare(strict_types=1);

namespace App\Http\Livewire\Exercise;

use App\Actions\Exercise\ReviewExerciseAction;
use App\Actions\Exercise\SendExerciseVerificationStatusNotificationAction;
use App\DataObjects\Exercise\ExerciseReviewData;
use App\Enums\ExerciseStatus;
use App\Enums\SweetAlertToastType;
use App\Http\Livewire\Concerns\CKEditor;
use App\Models\Exercise;
use App\Validators\Exercise\ExerciseReviewValidator;
use Illuminate\Auth\AuthManager;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Livewire\Component;

class ExerciseReviewForm extends Component
{
    use AuthorizesRequests;
    use CKEditor;

    public int $exerciseId;
    public string $status = ExerciseStatus::Verified->value;
    public ?string $explanation = null;

    public function getIsVerifiedProperty(): bool
    {
        return $this->status === ExerciseStatus::Verified->value;
    }

    public function getIsRejectedProperty(): bool
    {
        return $this->status === ExerciseStatus::Rejected->value;
    }

    public function submit(
        ReviewExerciseAction $action,
        AuthManager $auth,
        SendExerciseVerificationStatusNotificationAction $sendExerciseVerificationStatusNotificationAction,
    ): RedirectResponse {
        $this->authorize('review-exercises');
        $this->validate();

        $exercise = $action->execute(ExerciseReviewData::from([
            'exercise_id' => $this->exerciseId,
            'reviewer_id' => $auth->id(),
            'status' => $this->status,
        ]));

        $sendExerciseVerificationStatusNotificationAction->execute(
            exerciseId: $this->exerciseId,
            explanation: $this->explanation,
        );

        return $this->redirectToExerciseListWithStatusMessage($exercise);
    }

    public function mount(int $exerciseId): void
    {
        $this->exerciseId = $exerciseId;
    }

    public function render()
    {
        return view('livewire.exercise.exercise-review-form');
    }

    protected function rules(): array
    {
        return (new ExerciseReviewValidator())->rulesForExerciseReviewForm($this);
    }

    private function redirectToExerciseListWithStatusMessage(Exercise $exercise): RedirectResponse
    {
        if ($exercise->status->isVerified()) {
            // TODO: Tłumaczenia
            return to_route('web.exercises.index')
                ->with(
                    key: SweetAlertToastType::Success->type(),
                    value: "The exercise named '{$exercise->name}' has been verified",
                );
        }

        // TODO: Tłumaczenia
        return to_route('web.exercises.index')
            ->with(
                key: SweetAlertToastType::Success->type(),
                value: "The exercise named '{$exercise->name}' has been rejected",
            );
    }
}
