<table class="{{ $attributes['table-class'] ?? '' }}">
    <tbody>
        @foreach ($tables as $resource)
            <tr>
                <td class="{{ $attributes['td-class'] ?? '' }}">
                    {{ \ucfirst(\str_replace('_', ' ', $resource)) }}
                </td>
                @if ($showRouteName)
                    <td class="{{ $attributes['td-class'] ?? '' }}">
                        <a href="{{ route($showRouteName, $resource) }}">
                            {{ $attributes['link-label'] ?? 'View' }}
                        </a>
                    </td>
                @endif
            </tr>
        @endforeach
    </tbody>
</table>
