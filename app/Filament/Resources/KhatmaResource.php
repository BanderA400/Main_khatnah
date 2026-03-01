<?php

namespace App\Filament\Resources;

use App\Enums\KhatmaDirection;
use App\Enums\KhatmaScope;
use App\Enums\KhatmaStatus;
use App\Enums\KhatmaType;
use App\Enums\PlanningMethod;
use App\Filament\Resources\KhatmaResource\Pages;
use App\Models\Khatma;
use App\Models\Surah;
use Carbon\Carbon;
use Filament\Notifications\Notification;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class KhatmaResource extends Resource
{
    protected static ?string $model = Khatma::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-book-open';

    protected static ?string $navigationLabel = 'ختماتي';

    protected static ?string $modelLabel = 'ختمة';

    protected static ?string $pluralModelLabel = 'ختماتي';

    protected static string|\UnitEnum|null $navigationGroup = 'القرآن الكريم';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('معلومات الختمة')
                    ->description('حدد اسم الختمة ونوعها')
                    ->icon('heroicon-o-book-open')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('اسم الختمة')
                            ->placeholder('مثال: ختمة رمضان')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\Select::make('type')
                            ->label('نوع الختمة')
                            ->options(KhatmaType::class)
                            ->required()
                            ->native(false),

                        Forms\Components\Select::make('status')
                            ->label('الحالة')
                            ->options(KhatmaStatus::class)
                            ->default(KhatmaStatus::Active->value)
                            ->required()
                            ->native(false)
                            ->visibleOn('edit'),
                    ])
                    ->columns(2),

                Section::make('نطاق الختمة')
                    ->description('حدد النطاق والاتجاه')
                    ->icon('heroicon-o-document-text')
                    ->schema([
                        Forms\Components\Select::make('scope')
                            ->label('النطاق')
                            ->options(KhatmaScope::class)
                            ->required()
                            ->native(false)
                            ->live()
                            ->afterStateUpdated(function ($get, $set, $state) {
                                if (static::stateValue($state) === KhatmaScope::Full->value) {
                                    $set('start_page', 1);
                                    $set('end_page', 604);
                                    $set('total_pages', 604);
                                    static::recalculate($get, $set);
                                }
                            }),

                        Forms\Components\Select::make('direction')
                            ->label('الاتجاه')
                            ->options(KhatmaDirection::class)
                            ->default(KhatmaDirection::Forward->value)
                            ->required()
                            ->disabled(fn (?Khatma $record): bool => (int) ($record?->completed_pages ?? 0) > 0)
                            ->native(false),

                        Forms\Components\Select::make('start_surah')
                            ->label('من سورة')
                            ->options(fn () => Surah::orderBy('number')->pluck('name_arabic', 'number')->toArray())
                            ->searchable()
                            ->native(false)
                            ->visible(fn ($get) => static::stateValue($get('scope')) === KhatmaScope::Custom->value)
                            ->live()
                            ->afterStateUpdated(function ($get, $set, $state) {
                                if ($state) {
                                    $surah = Surah::where('number', $state)->first();
                                    if ($surah) {
                                        $set('start_page', $surah->start_page);
                                        static::calculateTotalPages($get, $set);
                                    }
                                }
                            }),

                        Forms\Components\Select::make('end_surah')
                            ->label('إلى سورة')
                            ->options(fn () => Surah::orderBy('number')->pluck('name_arabic', 'number')->toArray())
                            ->searchable()
                            ->native(false)
                            ->visible(fn ($get) => static::stateValue($get('scope')) === KhatmaScope::Custom->value)
                            ->live()
                            ->afterStateUpdated(function ($get, $set, $state) {
                                if ($state) {
                                    $surah = Surah::where('number', $state)->first();
                                    if ($surah) {
                                        $set('end_page', $surah->end_page);
                                        static::calculateTotalPages($get, $set);
                                    }
                                }
                            }),

                        Forms\Components\TextInput::make('start_page')
                            ->label('صفحة البداية')
                            ->numeric()
                            ->default(1)
                            ->minValue(1)
                            ->maxValue(604)
                            ->required()
                            ->lte('end_page')
                            ->live(debounce: 500)
                            ->afterStateUpdated(fn ($get, $set) => static::calculateTotalPages($get, $set))
                            ->disabled(fn ($get) => static::stateValue($get('scope')) === KhatmaScope::Full->value),

                        Forms\Components\TextInput::make('end_page')
                            ->label('صفحة النهاية')
                            ->numeric()
                            ->default(604)
                            ->minValue(1)
                            ->maxValue(604)
                            ->required()
                            ->gte('start_page')
                            ->live(debounce: 500)
                            ->afterStateUpdated(fn ($get, $set) => static::calculateTotalPages($get, $set))
                            ->disabled(fn ($get) => static::stateValue($get('scope')) === KhatmaScope::Full->value),

                        Forms\Components\TextInput::make('total_pages')
                            ->label('إجمالي الصفحات')
                            ->numeric()
                            ->default(604)
                            ->required()
                            ->minValue(1)
                            ->disabled()
                            ->dehydrated(),
                    ])
                    ->columns(2),

                Section::make('التخطيط')
                    ->description('حدد طريقة التخطيط والجدول الزمني')
                    ->icon('heroicon-o-calendar-days')
                    ->schema([
                        Forms\Components\DatePicker::make('start_date')
                            ->label('تاريخ البداية')
                            ->default(now())
                            ->required()
                            ->native(false)
                            ->live()
                            ->afterStateUpdated(fn ($get, $set) => static::recalculate($get, $set)),

                        Forms\Components\Select::make('planning_method')
                            ->label('طريقة التخطيط')
                            ->options(PlanningMethod::class)
                            ->required()
                            ->native(false)
                            ->live()
                            ->afterStateUpdated(function ($get, $set, $state) {
                                // مسح القيم عند تغيير الطريقة
                                if (static::stateValue($state) === PlanningMethod::ByDuration->value) {
                                    $set('daily_pages', null);
                                } else {
                                    $set('expected_end_date', null);
                                }
                            }),

                        Forms\Components\Toggle::make('auto_compensate_missed_days')
                            ->label('تعويض تلقائي عند فوات الأيام')
                            ->helperText('عند التفعيل: يعاد توزيع المتبقي تلقائيًا على الأيام القادمة.')
                            ->default(false)
                            ->inline(false),

                        // === بالمدة: المستخدم يحدد تاريخ الختم ===
                        Forms\Components\DatePicker::make('expected_end_date')
                            ->label('تاريخ الختم المتوقع')
                            ->native(false)
                            ->minDate(fn ($get) => $get('start_date'))
                            ->required(fn ($get) => static::stateValue($get('planning_method')) === PlanningMethod::ByDuration->value)
                            ->visible(fn ($get) => static::stateValue($get('planning_method')) === PlanningMethod::ByDuration->value)
                            ->live()
                            ->afterStateUpdated(function ($get, $set) {
                                if (static::stateValue($get('planning_method')) !== PlanningMethod::ByDuration->value) {
                                    return;
                                }

                                // حساب الورد اليومي = الصفحات ÷ الأيام
                                $startDate = $get('start_date');
                                $endDate = $get('expected_end_date');
                                $totalPages = (int) $get('total_pages');

                                if ($startDate && $endDate && $totalPages > 0) {
                                    $days = static::calculateInclusiveDays($startDate, $endDate);

                                    if ($days !== null) {
                                        $set('daily_pages', (int) ceil($totalPages / $days));
                                    }
                                }
                            }),

                        // === بالورد: المستخدم يحدد عدد الصفحات يومياً ===
                        Forms\Components\TextInput::make('daily_pages')
                            ->label('الورد اليومي (عدد الصفحات)')
                            ->numeric()
                            ->minValue(1)
                            ->maxValue(604)
                            ->required(fn ($get) => static::stateValue($get('planning_method')) === PlanningMethod::ByWird->value)
                            ->visible(fn ($get) => static::stateValue($get('planning_method')) === PlanningMethod::ByWird->value)
                            ->live(debounce: 500)
                            ->afterStateUpdated(function ($get, $set) {
                                if (static::stateValue($get('planning_method')) !== PlanningMethod::ByWird->value) {
                                    return;
                                }

                                // حساب تاريخ الختم = البداية + (الصفحات ÷ الورد)
                                $startDate = $get('start_date');
                                $dailyPages = (int) $get('daily_pages');
                                $totalPages = (int) $get('total_pages');

                                if ($startDate && $dailyPages > 0 && $totalPages > 0) {
                                    $days = (int) ceil($totalPages / $dailyPages);
                                    $set('expected_end_date', Carbon::parse($startDate)->addDays(max($days - 1, 0))->format('Y-m-d'));
                                }
                            }),

                        // === الحقول المحسوبة (للعرض) ===
                        Forms\Components\Placeholder::make('calculated_daily')
                            ->label('📖 الورد اليومي المحسوب')
                            ->content(function ($get) {
                                $daily = $get('daily_pages');
                                return $daily ? "{$daily} صفحة يومياً" : '—';
                            })
                            ->visible(fn ($get) => static::stateValue($get('planning_method')) === PlanningMethod::ByDuration->value),

                        Forms\Components\Placeholder::make('calculated_end')
                            ->label('📅 تاريخ الختم المحسوب')
                            ->content(function ($get) {
                                $date = $get('expected_end_date');
                                return $date ? \Carbon\Carbon::parse($date)->translatedFormat('j F Y') : '—';
                            })
                            ->visible(fn ($get) => static::stateValue($get('planning_method')) === PlanningMethod::ByWird->value),
                    ])
                    ->columns(2),

                // حقول مخفية
                Forms\Components\Hidden::make('user_id')
                    ->default(fn () => auth()->id()),

                Forms\Components\Hidden::make('current_page')
                    ->default(fn ($get) => static::resolveCurrentPageFromDirection(
                        $get('direction'),
                        (int) ($get('start_page') ?? 1),
                        (int) ($get('end_page') ?? 604),
                    )),

                Forms\Components\Hidden::make('completed_pages')
                    ->default(0),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('اسم الختمة')
                    ->searchable()
                    ->weight('bold')
                    ->icon(fn (Khatma $record): string => $record->type->getIcon())
                    ->iconColor(fn (Khatma $record): string => $record->type->getColor()),

                Tables\Columns\TextColumn::make('type')
                    ->label('النوع')
                    ->badge()
                    ->color(fn (Khatma $record): string => $record->type->getColor()),

                Tables\Columns\TextColumn::make('status')
                    ->label('الحالة')
                    ->badge(),

                Tables\Columns\TextColumn::make('daily_pages')
                    ->label('الورد اليومي')
                    ->suffix(' صفحة')
                    ->alignCenter(),

                Tables\Columns\ViewColumn::make('progress')
                    ->label('التقدم')
                    ->view('filament.tables.columns.khatma-progress')
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('completed_pages')
                    ->label('المنجز')
                    ->formatStateUsing(fn (Khatma $record): string => "{$record->completed_pages} / {$record->total_pages}")
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('expected_end_date')
                    ->label('تاريخ الختم')
                    ->date('j M Y')
                    ->color('gray'),

                Tables\Columns\TextColumn::make('start_date')
                    ->label('تاريخ البداية')
                    ->date('j M Y')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->label('النوع')
                    ->options(KhatmaType::class),

                Tables\Filters\SelectFilter::make('status')
                    ->label('الحالة')
                    ->options(KhatmaStatus::class),
            ])
            ->actions([
                \Filament\Actions\Action::make('toggle_status')
                    ->label(fn (Khatma $record): string => $record->status === KhatmaStatus::Active ? 'إيقاف' : 'استئناف')
                    ->icon(fn (Khatma $record): string => $record->status === KhatmaStatus::Active ? 'heroicon-o-pause' : 'heroicon-o-play')
                    ->color(fn (Khatma $record): string => $record->status === KhatmaStatus::Active ? 'gray' : 'success')
                    ->visible(fn (Khatma $record): bool => in_array($record->status, [KhatmaStatus::Active, KhatmaStatus::Paused], true))
                    ->action(function (Khatma $record): void {
                        $newStatus = $record->status === KhatmaStatus::Active
                            ? KhatmaStatus::Paused
                            : KhatmaStatus::Active;

                        $record->update(['status' => $newStatus]);

                        Notification::make()
                            ->title($newStatus === KhatmaStatus::Paused ? 'تم الإيقاف' : 'تم الاستئناف')
                            ->body("تم تحديث حالة ختمة \"{$record->name}\"")
                            ->success()
                            ->send();
                    }),
                \Filament\Actions\EditAction::make()
                    ->label('تعديل'),
                \Filament\Actions\DeleteAction::make()
                    ->label('حذف'),
            ])
            ->bulkActions([
                \Filament\Actions\BulkActionGroup::make([
                    \Filament\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateHeading('لا توجد ختمات')
            ->emptyStateDescription('ابدأ رحلتك مع القرآن بإنشاء ختمة جديدة')
            ->emptyStateIcon('heroicon-o-book-open');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListKhatmas::route('/'),
            'create' => Pages\CreateKhatma::route('/create'),
            'edit' => Pages\EditKhatma::route('/{record}/edit'),
        ];
    }

    /**
     * تصفية الختمات حسب المستخدم الحالي
     */
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('user_id', auth()->id());
    }

    // ==================
    // دوال الحساب
    // ==================

    protected static function calculateTotalPages($get, $set): void
    {
        $startPage = (int) $get('start_page');
        $endPage = (int) $get('end_page');

        if ($startPage <= 0 || $endPage <= 0) {
            $set('total_pages', null);

            return;
        }

        if ($endPage < $startPage) {
            $set('total_pages', null);

            return;
        }

        $set('total_pages', $endPage - $startPage + 1);
        static::recalculate($get, $set);
    }

    protected static function recalculate($get, $set): void
    {
        $method = static::stateValue($get('planning_method'));
        $totalPages = (int) $get('total_pages');
        $startDate = $get('start_date');

        if (! $method || $totalPages <= 0 || blank($startDate)) {
            return;
        }

        if ($method === PlanningMethod::ByDuration->value) {
            $endDate = $get('expected_end_date');

            if (blank($endDate)) {
                return;
            }

            $days = static::calculateInclusiveDays($startDate, $endDate);

            if ($days !== null) {
                $set('daily_pages', (int) ceil($totalPages / $days));
            }
        } elseif ($method === PlanningMethod::ByWird->value) {
            $dailyPages = (int) $get('daily_pages');
            if ($dailyPages > 0) {
                $days = (int) ceil($totalPages / $dailyPages);
                $set('expected_end_date', Carbon::parse($startDate)->addDays(max($days - 1, 0))->format('Y-m-d'));
            }
        }
    }

    protected static function calculateInclusiveDays(string $startDate, string $endDate): ?int
    {
        $start = Carbon::parse($startDate)->startOfDay();
        $end = Carbon::parse($endDate)->startOfDay();

        if ($end->lt($start)) {
            return null;
        }

        return $start->diffInDays($end) + 1;
    }

    protected static function resolveCurrentPageFromDirection(mixed $direction, int $startPage, int $endPage): int
    {
        return static::stateValue($direction) === KhatmaDirection::Backward->value ? $endPage : $startPage;
    }

    protected static function stateValue(mixed $state): mixed
    {
        return $state instanceof \BackedEnum ? $state->value : $state;
    }
}
