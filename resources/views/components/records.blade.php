<div class="{{ $attributes['records-wrapper-class'] ?? '' }}">
    <table class="{{ $attributes['table-class'] ?? '' }}">
        <thead>
            <td class="{{ $attributes['td-class'] ?? '' }}">#</td>
            @foreach ($columns as $column => $properties)
                <td class="{{ $attributes['td-class'] ?? '' }}">{{ $column }}</td>
            @endforeach
        </thead>
        <tbody>
            @foreach ($records as $row)
                <tr>
                    <td class="{{ $attributes['td-class'] ?? '' }}">
                        @if ($showRouteName)
                            <a
                                href="{{ route($showRouteName, ['table' => $table->getName(), 'id' => $row->id]) }}">{{ $attributes['link-label'] ?? 'View' }}</a>
                        @endif
                    </td>
                    @foreach ($columns as $column => $properties)
                        <td class="{{ $attributes['td-class'] ?? '' }}">{{ $row->{$column} }}</td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>

    @if ($records instanceof \Illuminate\Pagination\LengthAwarePaginator)
        <div class="{{ $attributes['pagination-wrapper-class'] ?? '' }}">
            {{ $records->links() }}
        </div>
    @endif
</div>
