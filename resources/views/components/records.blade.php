<div class="my-6">
    <span class="uppercase bg-gray-100 rounded py-1 px-2 text-xs">Records</span>
</div>

<div class="overflow-x-auto">
    <table class="w-full">
        <thead>
            <td class="px-4 py-2 text-gray-900 border">#</td>
            @foreach ($columns as $column => $properties)
                <td class="px-4 py-2 text-gray-900 border">{{ $column }}</td>
            @endforeach
        </thead>
        <tbody>
            @foreach ($records as $row)
                <tr>
                    <td class="px-4 py-2 text-gray-900 border">
                        @if ($showRouteName)
                            <a
                                href="{{ route($showRouteName, ['table' => $table->getName(), 'id' => $row->id]) }}">View</a>
                        @endif
                    </td>
                    @foreach ($columns as $column => $properties)
                        <td class="px-4 py-2 text-gray-900 border">{{ $row->{$column} }}</td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>

    @if ($records instanceof \Illuminate\Pagination\LengthAwarePaginator)
        <div class="mt-4">
            {{ $records->links() }}
        </div>
    @endif
</div>
