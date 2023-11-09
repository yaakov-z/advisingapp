<?php

namespace Assist\Form\Filament\Resources\FormResource\Pages\Concerns;

use App\TiptapBlocks\BatmanBlock;
use FilamentTiptapEditor\Enums\TiptapOutput;
use FilamentTiptapEditor\TiptapEditor;
use Filament\Forms\Get;
use Illuminate\Support\Arr;
use Assist\Form\Models\Form;
use Assist\Form\Rules\IsDomain;
use Assist\Form\Models\FormStep;
use Assist\Form\Models\FormField;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Builder;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Assist\Form\Filament\Blocks\FormFieldBlockRegistry;

trait HasSharedFormConfiguration
{
    public function fields(): array
    {
        return [
            TextInput::make('name')
                ->required()
                ->string()
                ->maxLength(255)
                ->autocomplete(false)
                ->columnSpanFull(),
            Textarea::make('description')
                ->string()
                ->columnSpanFull(),
            Toggle::make('embed_enabled')
                ->label('Embed Enabled')
                ->live()
                ->helperText('If enabled, this form can be embedded on other websites.'),
            TagsInput::make('allowed_domains')
                ->label('Allowed Domains')
                ->helperText('Only these domains will be allowed to embed this form.')
                ->placeholder('example.com')
                ->hidden(fn (Get $get) => ! $get('embed_enabled'))
                ->disabled(fn (Get $get) => ! $get('embed_enabled'))
                ->nestedRecursiveRules(
                    [
                        'string',
                        new IsDomain(),
                    ]
                ),
            Toggle::make('is_wizard')
                ->label('Multi-step form')
                ->live()
                ->columnSpanFull(),
            Section::make('Fields')
                ->schema([
                    $this->fieldBuilder(),
                ])
                ->hidden(fn (Get $get) => $get('is_wizard')),
            Repeater::make('steps')
                ->schema([
                    TextInput::make('label')
                        ->required()
                        ->string()
                        ->maxLength(255)
                        ->autocomplete(false)
                        ->columnSpanFull()
                        ->lazy(),
                    $this->fieldBuilder(),
                ])
                ->addActionLabel('New step')
                ->itemLabel(fn (array $state): ?string => $state['label'] ?? null)
                ->visible(fn (Get $get) => $get('is_wizard'))
                ->relationship()
                ->columnSpanFull(),
        ];
    }

    public function fieldBuilder(): TiptapEditor
    {
        return TiptapEditor::make('content')
            ->output(TiptapOutput::Json)
            ->blocks(FormFieldBlockRegistry::get())
            ->columnSpanFull()
            ->extraInputAttributes(['style' => 'min-height: 12rem;']);
    }
}
