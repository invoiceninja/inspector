<table class="w-full rounded-t-xl overflow-hidden p-10">
    <tbody>
        @foreach ($tables as $resource)
            <tr>
                <td class="px-4 py-2 text-gray-900 border">{{ \ucfirst(\str_replace('_', ' ', $resource)) }}</td>
                <td class="px-4 py-2 text-gray-900 border">
                    <a href="{{ route($showRouteName, $resource) }}">View</a>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
