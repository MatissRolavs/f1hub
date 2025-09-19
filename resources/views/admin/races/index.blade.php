<x-app-layout>
    <div class="max-w-4xl mx-auto p-6 text-white">
        <h1 class="text-2xl font-bold mb-4">Select a Race to Edit</h1>

        <table class="w-full border border-gray-700">
            <thead>
                <tr class="bg-gray-800">
                    <th class="p-2 text-left">Season</th>
                    <th class="p-2 text-left">Round</th>
                    <th class="p-2 text-left">Name</th>
                    <th class="p-2 text-left">Date</th>
                    <th class="p-2 text-left">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($races as $race)
                    <tr class="border-t border-gray-700 hover:bg-gray-800">
                        <td class="p-2">{{ $race->season }}</td>
                        <td class="p-2">{{ $race->round }}</td>
                        <td class="p-2">{{ $race->name }}</td>
                        <td class="p-2">{{ \Carbon\Carbon::parse($race->date)->format('d M Y') }}</td>
                        <td class="p-2">
                            <a href="{{ route('admin.races.edit', $race) }}"
                               class="bg-blue-600 hover:bg-blue-500 text-white px-3 py-1 rounded">
                                Edit
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-app-layout>
