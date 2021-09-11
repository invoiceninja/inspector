<table class="{{ $attributes['table-class'] ?? '' }}">
    <thead>
        <td class="{{ $attributes['td-class'] ?? '' }}">Column</td>
        <td class="{{ $attributes['td-class'] ?? '' }}">Type</td>
    </thead>
    <tbody>
        @foreach ($columns as $column => $properties)
            <tr>
                <td class="{{ $attributes['td-class'] ?? '' }}">{{ $column }}</td>
                <td class="{{ $attributes['td-class'] ?? '' }}">{{ $properties->getType()->getName() }} </td>
            </tr>
        @endforeach
    </tbody>
</table>
