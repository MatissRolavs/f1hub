<x-app-layout>
    <div class="max-w-3xl mx-auto p-6 text-white">
        <h1 class="text-2xl font-bold mb-4">Edit Race: {{ $race->name }}</h1>

        <form method="POST" action="{{ route('admin.races.update', $race) }}">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="block mb-1">Track Image URL</label>
                <input type="url" name="track_image" value="{{ old('track_image', $race->track_image) }}"
                       class="w-full p-2 rounded bg-gray-800 border border-gray-600">
            </div>

            <div class="mb-4">
                <label class="block mb-1">Track Length</label>
                <input type="text" name="track_length" value="{{ old('track_length', $race->track_length) }}"
                       placeholder="e.g. 5.793 km"
                       class="w-full p-2 rounded bg-gray-800 border border-gray-600">
            </div>

            <div class="mb-4">
                <label class="block mb-1">Number of Turns</label>
                <input type="number" name="turns" value="{{ old('turns', $race->turns) }}"
                       class="w-full p-2 rounded bg-gray-800 border border-gray-600">
            </div>

            <div class="mb-4">
                <label class="block mb-1">Lap Record</label>
                <input type="text" name="lap_record" value="{{ old('lap_record', $race->lap_record) }}"
                       placeholder="e.g. 1:18.149 - Lewis Hamilton (2020)"
                       class="w-full p-2 rounded bg-gray-800 border border-gray-600">
            </div>

            <div class="mb-4">
                <label class="block mb-1">Description / Interesting Facts</label>
                <textarea name="description" rows="4"
                          class="w-full p-2 rounded bg-gray-800 border border-gray-600">{{ old('description', $race->description) }}</textarea>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="bg-green-600 hover:bg-green-500 px-4 py-2 rounded">Save</button>
            </div>
        </form>
    </div>
</x-app-layout>
