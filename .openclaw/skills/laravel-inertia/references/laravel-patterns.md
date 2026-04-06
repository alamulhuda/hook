# Laravel Patterns Reference

## Controller Pattern

```php
public function store(Request $request): RedirectResponse
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
    ]);

    $item = Model::create($validated);

    return redirect()->route('items.index')
        ->with('success', 'Item created.');
}
```

## Eloquent with Relationships

```php
class Penjualan extends Model
{
    public function items(): HasMany
    {
        return $this->hasMany(PenjualanItem::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Member::class, 'member_id');
    }
}
```

## Form Request Validation

```php
class StorePenjualanRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'member_id' => 'nullable|exists:members,id',
            'items' => 'required|array|min:1',
            'items.*.produk_id' => 'required|exists:produks,id',
            'items.*.qty' => 'required|integer|min:1',
        ];
    }
}
```

## Route Model Binding

```php
Route::put('/items/{item}', [ItemController::class, 'update'])
    ->can('update', 'item'); // Uses policy
```

## Inertia Render with Flash

```php
return redirect()->route('items.index')
    ->with('flash', ['type' => 'success', 'message' => 'Done!']);
```

## Accessing Flash in Vue

```vue
<script setup>
import { usePage } from '@inertiajs/vue3'
const flash = usePage().props.flash
</script>
```
