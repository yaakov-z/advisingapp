<?php

/*
<COPYRIGHT>

    Copyright © 2016-2024, Canyon GBS LLC. All rights reserved.

    Advising App™ is licensed under the Elastic License 2.0. For more details,
    see https://github.com/canyongbs/advisingapp/blob/main/LICENSE.

    Notice:

    - You may not provide the software to third parties as a hosted or managed
      service, where the service provides users with access to any substantial set of
      the features or functionality of the software.
    - You may not move, change, disable, or circumvent the license key functionality
      in the software, and you may not remove or obscure any functionality in the
      software that is protected by the license key.
    - You may not alter, remove, or obscure any licensing, copyright, or other notices
      of the licensor in the software. Any use of the licensor’s trademarks is subject
      to applicable law.
    - Canyon GBS LLC respects the intellectual property rights of others and expects the
      same in return. Canyon GBS™ and Advising App™ are registered trademarks of
      Canyon GBS LLC, and we are committed to enforcing and protecting our trademarks
      vigorously.
    - The software solution, including services, infrastructure, and code, is offered as a
      Software as a Service (SaaS) by Canyon GBS LLC.
    - Use of this software implies agreement to the license terms and conditions as stated
      in the Elastic License 2.0.

    For more information or inquiries please visit our website at
    https://www.canyongbs.com or contact us via email at legal@canyongbs.com.

</COPYRIGHT>
*/

namespace AdvisingApp\IntegrationOpenAi\Services;

use Closure;
use Generator;
use AdvisingApp\Ai\Models\AiThread;
use AdvisingApp\Ai\Models\AiMessage;
use Illuminate\Support\Facades\Http;
use OpenAI\Contracts\ClientContract;
use AdvisingApp\Ai\Models\AiAssistant;
use AdvisingApp\Ai\Settings\AiSettings;
use OpenAI\Responses\Threads\ThreadResponse;
use AdvisingApp\Ai\Services\Contracts\AiService;
use OpenAI\Responses\Threads\Runs\ThreadRunResponse;
use AdvisingApp\Ai\Exceptions\MessageResponseException;
use AdvisingApp\Ai\Services\Concerns\HasAiServiceHelpers;
use AdvisingApp\Ai\Exceptions\MessageResponseTimeoutException;
use AdvisingApp\IntegrationOpenAi\Services\Concerns\UploadsFiles;
use AdvisingApp\IntegrationOpenAi\Exceptions\FileUploadsCannotBeEnabled;
use AdvisingApp\IntegrationOpenAi\Exceptions\FileUploadsCannotBeDisabled;
use AdvisingApp\IntegrationOpenAi\DataTransferObjects\Threads\ThreadsDataTransferObject;
use AdvisingApp\IntegrationOpenAi\DataTransferObjects\Assistants\AssistantsDataTransferObject;
use AdvisingApp\IntegrationOpenAi\DataTransferObjects\Assistants\FileSearchDataTransferObject;
use AdvisingApp\IntegrationOpenAi\DataTransferObjects\Assistants\ToolResourcesDataTransferObject;

abstract class BaseOpenAiService implements AiService
{
    use HasAiServiceHelpers;
    use UploadsFiles;

    public const FORMATTING_INSTRUCTIONS = 'When you answer, it is crucial that you format your response using rich text in markdown format. Do not ever mention in your response that the answer is being formatted/rendered in markdown.';

    protected ClientContract $client;

    abstract public function getApiKey(): string;

    abstract public function getApiVersion(): string;

    abstract public function getModel(): string;

    public function getClient(): ClientContract
    {
        return $this->client;
    }

    public function complete(string $prompt, string $content): string
    {
        $aiSettings = app(AiSettings::class);

        $completionResponse = Http::asJson()
            ->withHeader('api-key', $this->getApiKey())
            ->post("{$this->getDeployment()}/deployments/{$this->getModel()}/chat/completions?api-version={$this->getApiVersion()}", [
                'messages' => [
                    ['role' => 'system', 'content' => $prompt],
                    ['role' => 'user', 'content' => $content],
                ],
                'temperature' => $aiSettings->temperature,
            ])
            ->json();

        return $completionResponse['choices'][0]['message']['content'] ?? '';
    }

    public function createAssistant(AiAssistant $assistant): void
    {
        $newAssistantResponse = $this->client->assistants()->create([
            'name' => $assistant->name,
            'instructions' => $this->generateAssistantInstructions($assistant),
            'model' => $this->getModel(),
            'metadata' => [
                'last_updated_at' => now(),
            ],
            'tools' => [
                [
                    'type' => 'file_search',
                ],
            ],
        ]);

        $assistant->assistant_id = $newAssistantResponse->id;
    }

    public function updateAssistant(AiAssistant $assistant): void
    {
        $this->client->assistants()->modify($assistant->assistant_id, [
            'instructions' => $this->generateAssistantInstructions($assistant),
            'name' => $assistant->name,
            'model' => $this->getModel(),
        ]);
    }

    public function retrieveAssistant(AiAssistant $assistant): AssistantsDataTransferObject
    {
        $assistantResponse = $this->client->assistants()->retrieve($assistant->assistant_id);

        return AssistantsDataTransferObject::from([
            'id' => $assistantResponse->id,
            'name' => $assistantResponse->name,
            'description' => $assistantResponse->description,
            'model' => $assistantResponse->model,
            'instructions' => $assistantResponse->instructions,
            'tools' => $assistantResponse->tools,
            'toolResources' => ToolResourcesDataTransferObject::from([
                'codeInterpreter' => $assistantResponse->toolResources->codeInterpreter ?? null,
                'fileSearch' => FileSearchDataTransferObject::from([
                    'vectorStoreIds' => $assistantResponse->toolResources->fileSearch->vectorStoreIds ?? [],
                ]),
            ]),
        ]);
    }

    public function updateAssistantTools(AiAssistant $assistant, array $tools): void
    {
        $tools = collect($tools)->map(function ($tool) {
            return [
                'type' => $tool,
            ];
        })->toArray();

        $this->client->assistants()->modify($assistant->assistant_id, [
            'tools' => $tools,
        ]);
    }

    public function enableAssistantFileUploads(AiAssistant $assistant): void
    {
        throw new FileUploadsCannotBeEnabled();
    }

    public function disableAssistantFileUploads(AiAssistant $assistant): void
    {
        throw new FileUploadsCannotBeDisabled();
    }

    public function createThread(AiThread $thread): void
    {
        $existingMessagePopulationLimit = 32;
        $existingMessages = [];
        $existingMessagesOverflow = [];

        if ($thread->exists) {
            $allExistingMessages = $thread->messages()
                ->orderBy('id')
                ->get()
                ->toBase()
                ->map(fn (AiMessage $message): array => [
                    'content' => $message->content,
                    'role' => $message->user_id ? 'user' : 'assistant',
                ]);

            $existingMessages = $allExistingMessages
                ->take($existingMessagePopulationLimit)
                ->values()
                ->all();

            $existingMessagesOverflow = $allExistingMessages
                ->slice($existingMessagePopulationLimit)
                ->values()
                ->all();
        }

        $newThreadResponse = $this->client->threads()->create([
            'messages' => $existingMessages,
        ]);

        $thread->thread_id = $newThreadResponse->id;

        if (count($existingMessagesOverflow)) {
            foreach ($existingMessagesOverflow as $overflowMessage) {
                $this->client->threads()->messages()->create($thread->thread_id, $overflowMessage);
            }
        }
    }

    public function retrieveThread(AiThread $thread): ThreadsDataTransferObject
    {
        $threadResponse = $this->client->threads()->retrieve($thread->thread_id);

        return ThreadsDataTransferObject::from([
            'id' => $thread->thread_id,
            'vectorStoreIds' => $threadResponse->toolResources?->fileSearch?->vectorStoreIds ?? [],
        ]);
    }

    public function modifyThread(AiThread $thread, array $parameters): ThreadsDataTransferObject
    {
        /** @var ThreadResponse $updatedThreadResponse */
        $updatedThreadResponse = $this->client->threads()->modify($thread->thread_id, $parameters);

        return ThreadsDataTransferObject::from([
            'id' => $updatedThreadResponse->id,
            'vectorStoreIds' => $updatedThreadResponse->toolResources?->fileSearch?->vectorStoreIds ?? [],
        ]);
    }

    public function deleteThread(AiThread $thread): void
    {
        $this->client->threads()->delete($thread->thread_id);

        $thread->thread_id = null;
    }

    public function sendMessage(AiMessage $message, array $files, Closure $saveResponse): Closure
    {
        $latestRun = $this->client->threads()->runs()->list($message->thread->thread_id, [
            'order' => 'desc',
            'limit' => 1,
        ])->data[0] ?? null;

        // An existing run might be in progress, so we need to wait for it to complete first.
        if ($latestRun && (! in_array($latestRun?->status, ['completed', 'failed', 'expired', 'cancelled', 'incomplete']))) {
            $this->awaitThreadRunCompletion($latestRun);
        }

        $createdFiles = [];

        if (method_exists($this, 'createFiles') && ! empty($files)) {
            $createdFiles = $this->createFiles($message, $files);
        }

        $data = [
            'role' => 'user',
            'content' => $message->content,
        ];

        if (! empty($createdFiles)) {
            $data['attachments'] = collect($createdFiles)->map(function ($createdFile) {
                return [
                    'file_id' => $createdFile->file_id,
                    'tools' => [
                        [
                            'type' => 'file_search',
                        ],
                    ],
                ];
            })->toArray();
        }

        $newMessageResponse = $this->client->threads()->messages()->create($message->thread->thread_id, $data);

        $instructions = $this->generateAssistantInstructions($message->thread->assistant, withDynamicContext: true);

        $message->context = $instructions;
        $message->message_id = $newMessageResponse->id;
        $message->save();

        if (! empty($createdFiles)) {
            foreach ($createdFiles as $file) {
                $file->message()->associate($message);
                $file->save();
            }
        }

        $aiSettings = app(AiSettings::class);

        $runData = [
            'assistant_id' => $message->thread->assistant->assistant_id,
            'instructions' => $instructions,
            'max_completion_tokens' => $aiSettings->max_tokens,
            'temperature' => $aiSettings->temperature,
        ];

        if ($message->query()->has('thread.messages.files')) {
            $runData['tools'] = [
                ['type' => 'file_search'],
            ];
        }

        $stream = $this->client->threads()->runs()->createStreamed($message->thread->thread_id, $runData);

        return function () use ($saveResponse, $stream): Generator {
            $response = new AiMessage();

            foreach ($stream as $streamResponse) {
                if ($streamResponse->event === 'thread.message.delta') {
                    foreach ($streamResponse->response->delta->content as $content) {
                        yield json_encode(['type' => 'content', 'content' => base64_encode($content->text->value)]);
                        $response->content .= $content->text->value;
                    }

                    $response->message_id = $streamResponse->response->id;
                } elseif ($streamResponse->event === 'thread.message.incomplete') {
                    yield json_encode(['type' => 'content', 'content' => base64_encode('...'), 'incomplete' => true]);
                    $response->content .= '...';
                } elseif ($streamResponse->event === 'thread.message.completed') {
                    $response->content = $streamResponse->response->content[0]?->text->value;
                    $response->message_id = $streamResponse->response->id;
                } elseif ($streamResponse->event === 'thread.run.step.completed') {
                    $saveResponse($response);
                } elseif (in_array($streamResponse->event, [
                    'thread.run.expired',
                    'thread.run.step.expired',
                ])) {
                    yield json_encode(['type' => 'timeout', 'message' => 'The AI took too long to respond to your message.']);

                    report(new MessageResponseTimeoutException());

                    return;
                } elseif (in_array($streamResponse->event, [
                    'thread.run.failed',
                    'thread.run.cancelling',
                    'thread.run.cancelled',
                    'thread.run.step.failed',
                    'thread.run.step.cancelled',
                ])) {
                    yield json_encode(['type' => 'failed', 'message' => 'An error happened when sending your message.']);

                    report(new MessageResponseException('Thread run not successful: [' . json_encode($streamResponse->response->toArray()) . '].'));

                    return;
                }
            }
        };
    }

    public function completeResponse(AiMessage $response, array $files, Closure $saveResponse): Closure
    {
        $latestRun = $this->client->threads()->runs()->list($response->thread->thread_id, [
            'order' => 'desc',
            'limit' => 1,
        ])->data[0] ?? null;

        // An existing run might be in progress, so we need to wait for it to complete first.
        if ($latestRun && (! in_array($latestRun?->status, ['completed', 'failed', 'expired', 'cancelled', 'incomplete']))) {
            $this->awaitThreadRunCompletion($latestRun);
        }

        $createdFiles = [];

        if (method_exists($this, 'createFiles') && ! empty($files)) {
            $createdFiles = $this->createFiles($response, $files);
        }

        $data = [
            'role' => 'user',
            'content' => 'Continue generating the response, do not mention that I told you as I will paste it directly after the last message.',
        ];

        if (! empty($createdFiles)) {
            $data['attachments'] = collect($createdFiles)->map(function ($createdFile) {
                return [
                    'file_id' => $createdFile->file_id,
                    'tools' => [
                        [
                            'type' => 'file_search',
                        ],
                    ],
                ];
            })->toArray();
        }

        $this->client->threads()->messages()->create($response->thread->thread_id, $data);

        $instructions = $this->generateAssistantInstructions($response->thread->assistant, withDynamicContext: true);

        if (! empty($createdFiles)) {
            foreach ($createdFiles as $file) {
                $file->message()->associate($response);
                $file->save();
            }
        }

        $aiSettings = app(AiSettings::class);

        $runData = [
            'assistant_id' => $response->thread->assistant->assistant_id,
            'instructions' => $instructions,
            'max_completion_tokens' => $aiSettings->max_tokens,
            'temperature' => $aiSettings->temperature,
        ];

        if ($response->query()->has('thread.messages.files')) {
            $runData['tools'] = [
                ['type' => 'file_search'],
            ];
        }

        $stream = $this->client->threads()->runs()->createStreamed($response->thread->thread_id, $runData);

        return function () use ($response, $saveResponse, $stream): Generator {
            foreach ($stream as $streamResponse) {
                if ($streamResponse->event === 'thread.message.delta') {
                    foreach ($streamResponse->response->delta->content as $content) {
                        yield json_encode(['type' => 'content', 'content' => base64_encode($content->text->value)]);
                        $response->content .= $content->text->value;
                    }

                    $response->message_id = $streamResponse->response->id;
                } elseif ($streamResponse->event === 'thread.message.incomplete') {
                    yield json_encode(['type' => 'content', 'content' => base64_encode('...'), 'incomplete' => true]);
                    $response->content .= '...';
                } elseif ($streamResponse->event === 'thread.message.completed') {
                    $response->content = $streamResponse->response->content[0]?->text->value;
                    $response->message_id = $streamResponse->response->id;
                } elseif ($streamResponse->event === 'thread.run.step.completed') {
                    $saveResponse($response);
                } elseif (in_array($streamResponse->event, [
                    'thread.run.expired',
                    'thread.run.step.expired',
                ])) {
                    yield json_encode(['type' => 'timeout', 'message' => 'The AI took too long to respond to your message.']);

                    report(new MessageResponseTimeoutException());

                    return;
                } elseif (in_array($streamResponse->event, [
                    'thread.run.failed',
                    'thread.run.cancelling',
                    'thread.run.cancelled',
                    'thread.run.step.failed',
                    'thread.run.step.cancelled',
                ])) {
                    yield json_encode(['type' => 'failed', 'message' => 'An error happened when sending your message.']);

                    report(new MessageResponseException('Thread run not successful: [' . json_encode($streamResponse->response->toArray()) . '].'));

                    return;
                }
            }
        };
    }

    public function retryMessage(AiMessage $message, array $files, Closure $saveResponse): Closure
    {
        $latestRun = $this->client->threads()->runs()->list($message->thread->thread_id, [
            'order' => 'desc',
            'limit' => 1,
        ])->data[0] ?? null;

        if (in_array($latestRun?->status, ['failed', 'expired', 'cancelled', 'incomplete'])) {
            report(new MessageResponseException('Thread run was not successful: [' . json_encode($latestRun->toArray()) . '].'));
        }

        if (
            $latestRun &&
            (! in_array($latestRun?->status, ['completed', 'failed', 'expired', 'cancelled', 'incomplete'])) &&
            filled($message->message_id)
        ) {
            $this->awaitThreadRunCompletion($latestRun);

            $latestMessageResponse = $this->client->threads()->messages()->list($message->thread->thread_id, [
                'order' => 'desc',
                'limit' => 1,
            ])->data[0];

            return function () use ($latestMessageResponse, $saveResponse): Generator {
                $response = new AiMessage();

                yield $latestMessageResponse->content[0]->text->value;

                $response->content = $latestMessageResponse->content[0]->text->value;
                $response->message_id = $latestMessageResponse->id;

                $saveResponse($response);
            };
        }

        $instructions = $this->generateAssistantInstructions($message->thread->assistant, withDynamicContext: true);

        if (blank($message->message_id)) {
            $data = [
                'role' => 'user',
                'content' => $message->content,
            ];

            $createdFiles = [];

            if (method_exists($this, 'createFiles') && ! empty($files)) {
                $createdFiles = $this->createFiles($message, $files);
            }

            if (! empty($createdFiles)) {
                $data['attachments'] = collect($createdFiles)->map(function ($createdFile) {
                    return [
                        'file_id' => $createdFile->file_id,
                        'tools' => [
                            [
                                'type' => 'file_search',
                            ],
                        ],
                    ];
                })->toArray();
            }

            $newMessageResponse = $this->client->threads()->messages()->create($message->thread->thread_id, $data);

            $message->context = $instructions;
            $message->message_id = $newMessageResponse->id;
            $message->save();
        }

        $aiSettings = app(AiSettings::class);

        $runData = [
            'assistant_id' => $message->thread->assistant->assistant_id,
            'instructions' => $instructions,
            'max_completion_tokens' => $aiSettings->max_tokens,
            'temperature' => $aiSettings->temperature,
        ];

        if ($message->query()->has('thread.messages.files')) {
            $runData['tools'] = [
                ['type' => 'file_search'],
            ];
        }

        $stream = $this->client->threads()->runs()->createStreamed($message->thread->thread_id, $runData);

        return function () use ($saveResponse, $stream): Generator {
            $response = new AiMessage();

            foreach ($stream as $streamResponse) {
                if ($streamResponse->event === 'thread.message.delta') {
                    foreach ($streamResponse->response->delta->content as $content) {
                        yield json_encode(['type' => 'content', 'content' => base64_encode($content->text->value)]);
                        $response->content .= $content->text->value;
                    }

                    $response->message_id = $streamResponse->response->id;
                } elseif ($streamResponse->event === 'thread.message.incomplete') {
                    yield json_encode(['type' => 'content', 'content' => base64_encode('...'), 'incomplete' => true]);
                    $response->content .= '...';
                } elseif ($streamResponse->event === 'thread.message.completed') {
                    $response->content = $streamResponse->response->content[0]?->text->value;
                    $response->message_id = $streamResponse->response->id;
                } elseif ($streamResponse->event === 'thread.run.step.completed') {
                    $saveResponse($response);
                } elseif (in_array($streamResponse->event, [
                    'thread.run.expired',
                    'thread.run.step.expired',
                ])) {
                    yield json_encode(['type' => 'timeout', 'message' => 'The AI took too long to respond to your message.']);

                    report(new MessageResponseTimeoutException());

                    return;
                } elseif (in_array($streamResponse->event, [
                    'thread.run.failed',
                    'thread.run.cancelling',
                    'thread.run.cancelled',
                    'thread.run.step.failed',
                    'thread.run.step.cancelled',
                ])) {
                    yield json_encode(['type' => 'failed', 'message' => 'An error happened when sending your message.']);

                    report(new MessageResponseException('Thread run not successful: [' . json_encode($streamResponse->response->toArray()) . '].'));

                    return;
                }
            }
        };
    }

    public function getMaxAssistantInstructionsLength(): int
    {
        $limit = 32768;

        $limit -= strlen(resolve(AiSettings::class)->prompt_system_context);
        $limit -= strlen(static::FORMATTING_INSTRUCTIONS);

        $limit -= 600; // For good measure.
        $limit -= ($limit % 100); // Round down to the nearest 100.

        return $limit;
    }

    public function isAssistantExisting(AiAssistant $assistant): bool
    {
        return filled($assistant->assistant_id);
    }

    public function isThreadExisting(AiThread $thread): bool
    {
        return filled($thread->thread_id);
    }

    public function supportsMessageFileUploads(): bool
    {
        return false;
    }

    public function supportsAssistantFileUploads(): bool
    {
        return true;
    }

    protected function awaitThreadRunCompletion(ThreadRunResponse $threadRunResponse): void
    {
        $runId = $threadRunResponse->id;

        // 60 second total request timeout, with a 10-second buffer.
        $currentTime = time();
        $requestTime = app()->runningUnitTests() ? time() : $_SERVER['REQUEST_TIME'];
        $timeoutInSeconds = 60 - ($currentTime - $requestTime) - 10;
        $expiration = $currentTime + $timeoutInSeconds;

        while ($threadRunResponse->status !== 'completed') {
            if (time() >= $expiration) {
                throw new MessageResponseTimeoutException();
            }

            if (in_array($threadRunResponse->status, ['failed', 'expired', 'cancelled', 'incomplete'])) {
                throw new MessageResponseException('Thread run not successful: [' . json_encode($threadRunResponse->toArray()) . '].');
            }

            usleep(500000);

            $threadRunResponse = $this->client->threads()->runs()->retrieve($threadRunResponse->threadId, $runId);
        }
    }

    protected function generateAssistantInstructions(AiAssistant $assistant, bool $withDynamicContext = false): string
    {
        $assistantInstructions = rtrim($assistant->instructions, '. ');

        $maxAssistantInstructionsLength = $this->getMaxAssistantInstructionsLength();

        if (strlen($assistantInstructions) > $maxAssistantInstructionsLength) {
            $truncationEnd = '... [truncated]';

            $assistantInstructions = (string) str($assistantInstructions)
                ->limit($maxAssistantInstructionsLength - strlen($truncationEnd), $truncationEnd);
        }

        $formattingInstructions = static::FORMATTING_INSTRUCTIONS;

        if (! $withDynamicContext) {
            return "{$assistantInstructions}.\n\n{$formattingInstructions}";
        }

        $dynamicContext = rtrim(auth()->user()->getDynamicContext(), '. ');

        return "{$dynamicContext}.\n\n{$assistantInstructions}.\n\n{$formattingInstructions}";
    }
}
