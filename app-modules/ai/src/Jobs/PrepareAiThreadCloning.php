<?php

namespace AdvisingApp\Ai\Jobs;

use App\Models\User;
use Illuminate\Bus\Queueable;
use AdvisingApp\Team\Models\Team;
use AdvisingApp\Ai\Models\AiThread;
use Illuminate\Support\Facades\Bus;
use Illuminate\Queue\SerializesModels;
use Filament\Notifications\Notification;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use AdvisingApp\Ai\Enums\AiThreadShareTarget;
use Filament\Notifications\Notification as FilamentNotification;

class PrepareAiThreadCloning implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(
        protected AiThread $thread,
        protected string $targetType,
        protected array $targetIds,
        protected User $sender,
    ) {}

    public function handle(): void
    {
        if ($this->targetType === AiThreadShareTarget::User->value) {
            User::query()
                ->whereKey($this->targetIds)
                ->get()
                ->each(function (User $recipient) {
                    dispatch(new CloneAiThread($this->thread, $this->sender, $recipient));

                    $recipientName = $this->sender->is($recipient) ? 'yourself' : $recipient->name;

                    Notification::make()
                        ->success()
                        ->title("You cloned an AI chat to {$recipientName}.")
                        ->sendToDatabase($this->sender);
                });

            return;
        }

        if ($this->targetType === AiThreadShareTarget::Team->value) {
            $sender = $this->sender;

            Team::query()
                ->whereKey($this->targetIds)
                ->with('users')
                ->get()
                ->each(function (Team $team) use ($sender) {
                    Bus::batch(
                        $team->users()->whereKeyNot($this->sender)->get()
                            ->map(fn (User $recipient) => new CloneAiThread($this->thread, $this->sender, $recipient))
                            ->all(),
                    )
                        ->name("PrepareAiThreadCloning for team {$team->id}")
                        ->then(function () use ($sender, $team) {
                            FilamentNotification::make()
                                ->success()
                                ->title("You cloned an AI chat to users in team {$team->name}.")
                                ->sendToDatabase($sender);
                        })
                        ->dispatch();
                });
        }
    }
}
