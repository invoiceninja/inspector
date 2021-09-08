<div class="my-6">
    <span class="uppercase bg-gray-100 rounded py-1 px-2 text-xs">Columns</span>

    <table class="w-full mt-4">
        <thead>
            <td class="px-4 py-2 text-gray-900 border">Column</td>
            <td class="px-4 py-2 text-gray-900 border">Type</td>
        </thead>
        <tbody>
            @foreach ($columns as $column => $properties)
                <tr>
                    <td class="px-4 py-2 text-gray-900 border">{{ $column }}</td>
                    <td class="px-4 py-2 text-gray-900 border">{{ $properties->getType() }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
