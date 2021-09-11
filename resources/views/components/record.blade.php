<form method="POST"
    action="{{ $updateRouteName ? route($updateRouteName, ['table' => $table->getName(), 'id' => $record->id]) : '#' }}">

    @csrf
    @method('put')

    @foreach ($columns as $column)
        <div class="{{ $attributes['input-wrapper-class'] ?? '' }}">
            <dt class="{{ $attributes['input-label-class'] ?? '' }}">
                {{ $column->getName() }}
            </dt>

            <dd class="{{ $attributes['input-field-wrapper-class'] ?? '' }}">
                <x-inspector-input :column="$column" value="{{ $record->{$column->getName()} }}" />

                @error($column->getName())
                    <span class="{{ $attributes['validation-class'] ?? '' }}">{{ $message }}</span>
                @enderror
            </dd>
        </div>
    @endforeach

    <div class="{{ $attributes['form-button-wrapper-class'] ?? '' }}">
        <button class="{{ $attributes['form-button-class'] ?? '' }}">Save</button>
    </div>
</form>
