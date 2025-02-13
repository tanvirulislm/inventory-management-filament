<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\Unit;
use Filament\Tables;
use App\Models\Product;
use Filament\Forms\Set;
use App\Models\Category;
use App\Models\Purchase;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\PurchaseResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\PurchaseResource\RelationManagers;
use Filament\Facades\Filament;

class PurchaseResource extends Resource
{
    protected static ?string $model = Purchase::class;

    protected static ?string $navigationIcon = 'heroicon-o-bolt';


    protected static ?string $navigationLabel = 'Purchases';

    protected static ?string $navigationGroup = 'Transactions';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Provider Details')->schema([
                    Forms\Components\Select::make('provider_id')
                    ->relationship('provider', 'name')
                    ->required()
                    ->label('Provider')
                    ->createOptionForm(function () {
                        $tenantField = [
                            Forms\Components\Hidden::make('tenant_id')
                            ->default(Filament::getTenant()->id)
                        ];
                        return array_merge(CustomerResource::getCustomerFormSchema(), $tenantField);
                    }),
                    Forms\Components\TextInput::make('invoice_no')
                    ->required()
                    ->label('Invoice No'),
                    Forms\Components\DatePicker::make('purchase_date')
                    ->required()
                    ->native(false)
                    ->label('Purchase Date'),
                ])->columns(3),
                Forms\Components\Section::make()->schema([
                    Forms\Components\Repeater::make('Product Details')
                    ->columns(4)->schema([
                    Forms\Components\Select::make('product_id')
                    ->options(function(){
                        return Product::pluck('name', 'id')->toArray();
                    })
                    ->required()
                    ->searchable()
                    ->label('Product')
                    ->createOptionForm(function () {
                        $tenantField = [
                            Forms\Components\Hidden::make('tenant_id')
                            ->default(Filament::getTenant()->id)
                        ];
                        return array_merge(ProductResource::getProductForm(), $tenantField);
                        })
                        ->createOptionUsing(function (array $data) {
                            $product = Product::create($data);
                            return $product->id;
                        }),
                        Forms\Components\TextInput::make('price')
                        ->required()
                        ->numeric()
                        ->label('Price')
                        ->reactive()
                        ->debounce(500)
                        ->afterStateUpdated(function(callable $get, Set $set) {
                            $price = intval($get('price'));
                            $quantity = intval($get('quantity'));
                            $set('total', $price * $quantity);
                        }),

                    Forms\Components\TextInput::make('quantity')
                        ->required()
                        ->numeric()
                        ->label('Quantity')
                        ->reactive()
                        ->debounce(500)
                        ->afterStateUpdated(function(callable $get, Set $set) {
                            $price = intval($get('price'));
                            $quantity = intval($get('quantity'));
                            $set('total', $price * $quantity);
                        }),

                    Forms\Components\TextInput::make('total')
                        ->required()
                        ->numeric()
                        ->label('Total')
                        ->disabled(),
                    ])

                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPurchases::route('/'),
            'create' => Pages\CreatePurchase::route('/create'),
            'edit' => Pages\EditPurchase::route('/{record}/edit'),
        ];
    }
}
