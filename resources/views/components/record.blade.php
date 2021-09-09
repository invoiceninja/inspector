<form 
    method="POST"
    action="{{ $updateRouteName ? route($updateRouteName, ['table' => $table->getName(), 'id' => $record->id]) : '#' }}">

    @csrf
    @method('put')

    @foreach ($columns as $column)
        <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4">
            <dt class="text-sm font-medium text-gray-500">
                {{ $column->getName() }}
            </dt>

            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                <x-inspector-input :column="$column" value="{{ $record->{$column->getName()} }}" />

                @error($column->getName())
                    <span class="ml-2">{{ $message }}</span>
                @enderror
            </dd>
        </div>
    @endforeach

    <div class="flex justify-end">
        <button class="bg-gray-100 py-2 px-4 rounded">Save</button>
    </div>
</form>
