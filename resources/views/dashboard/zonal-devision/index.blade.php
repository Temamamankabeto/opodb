<x-layout>
    <h1 class="text-xl font-bold mb-4">Zonal Devisions List</h1>

    <a href="{{ route('zonal_devisions.create') }}" class="btn btn-primary mb-3">+ Add New</a>

    <table class="table-auto w-full border">
        <thead>
            <tr class="bg-gray-200">
                <th>Zone</th>
                <th>Crop</th>
                <th>Total Land</th>
                <th>Total Production</th>
                <th>Land for Zone</th>
                <th>Production for Zone</th>
                <th>Productivity</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($devisions as $item)
            <tr>
                <td>{{ $item->zone->name ?? 'N/A' }}</td>
                <td>{{ $item->crop->name ?? 'N/A' }}</td>
                <td>{{ $item->total_land }}</td>
                <td>{{ $item->totla_production }}</td>
                <td>{{ $item->{'ammount_of_land_for zone'} }}</td>
                <td>{{ $item->{'ammount_of_prucuction_for'} }}</td>
                <td>{{ $item->productivity }}</td>
                <td>
                    <a href="{{ route('zonal_devisions.edit', $item->id) }}" class="text-blue-500">Edit</a> |
                    <form action="{{ route('zonal_devisions.destroy', $item->id) }}" method="POST" class="inline-block"
                          onsubmit="return confirm('Are you sure?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-red-600">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</x-layout>
