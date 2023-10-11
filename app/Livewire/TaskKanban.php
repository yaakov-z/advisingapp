<?php

namespace App\Livewire;

use Exception;
use Livewire\Component;
use Assist\Task\Models\Task;
use Filament\Actions\EditAction;
use Assist\Task\Enums\TaskStatus;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use Filament\Forms\Contracts\HasForms;
use Filament\Actions\Contracts\HasActions;
use Assist\Task\Filament\Concerns\TaskEditForm;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Bvtterfly\ModelStateMachine\Exceptions\InvalidTransition;
use Assist\Task\Filament\Pages\Components\TaskKanbanViewAction;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Assist\Task\Filament\Resources\TaskResource\Pages\ListTasks;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

class TaskKanban extends Component implements HasForms, HasActions
{
    use InteractsWithActions;
    use InteractsWithForms;
    use TaskEditForm;
    use InteractsWithPageTable;

    protected static string $view = 'livewire.task-kanban';

    public array $statuses = [];

    public EloquentCollection|Collection $tasks;

    public ?Task $currentTask = null;

    public function mount(): void
    {
        $this->statuses = TaskStatus::cases();

        $pageTasks = $this->getPageTableQuery()->get()->groupBy('status');

        $this->tasks = collect($this->statuses)
            ->mapWithKeys(fn ($status) => [$status->value => $pageTasks[$status->value] ?? collect()]);
    }

    public function movedTask(string $taskId, string $fromStatusString, string $toStatusString): JsonResponse
    {
        $fromStatus = TaskStatus::from($fromStatusString);
        $toStatus = TaskStatus::from($toStatusString);

        $task = $this->tasks[$fromStatusString]->firstWhere('id', $taskId);

        try {
            $task->getStateMachine('status')->transitionTo($toStatus);
        } catch (InvalidTransition $e) {
            return response()->json([
                'success' => false,
                'message' => "Cannot transition from \"{$fromStatus->displayName()}\" to \"{$toStatus->displayName()}\".",
            ], ResponseAlias::HTTP_BAD_REQUEST);
        } catch (Exception $e) {
            report($e);

            return response()->json([
                'success' => false,
                'message' => 'Task could not be moved. Something went wrong, if this continues please contact support.',
            ], ResponseAlias::HTTP_BAD_REQUEST);
        }

        $this->tasks[$fromStatusString] = $this->tasks[$fromStatusString]->filter(fn ($task) => $task->id !== $taskId);
        $this->tasks[$toStatusString]->push($task);

        return response()->json([
            'success' => true,
            'message' => 'Task moved successfully.',
        ], ResponseAlias::HTTP_OK);
    }

    public function viewTask(Task $task)
    {
        $this->currentTask = $task;

        $this->mountAction('view');
    }

    public function viewAction()
    {
        return TaskKanbanViewAction::make()->record($this->currentTask)
            ->extraModalFooterActions(
                [
                    EditAction::make('edit')
                        ->record($this->currentTask)
                        ->form($this->editFormFields()),
                ]
            );
    }

    protected function getTablePage(): string
    {
        return ListTasks::class;
    }
}
