<x-app-layout>
<div class="max-w-4xl mx-auto px-4 py-6 text-white">
    <h2 class="text-2xl font-bold mb-4">Prediction for {{ $raceName }}</h2>

    <form method="POST" action="{{ route('game.storePrediction') }}" id="guess-form" class="space-y-4">
        @csrf
        <input type="hidden" name="season" value="{{ $season }}">
        <input type="hidden" name="round" value="{{ $round }}">
        <input type="hidden" name="player_name" value="{{ Auth::user()->name }}">

        <div id="error-message" class="text-red-400 font-bold hidden"></div>

        @for($pos = 1; $pos <= $drivers->count(); $pos++)
        <div class="flex items-center gap-4">
            <span class="w-8 text-center font-bold text-yellow-400">{{ $pos }}</span>
            <select name="positions[{{ $pos }}]" class="flex-1 text-black p-2 rounded">
                <option value="">-- Select Driver --</option>
                @foreach($drivers as $driver)
                    <option value="{{ $driver->id }}"
                        {{ isset($savedOrder[$pos-1]) && $savedOrder[$pos-1] == $driver->id ? 'selected' : '' }}>
                        {{ $driver->given_name }} {{ $driver->family_name }}
                    </option>
                @endforeach
            </select>
        </div>
        @endfor


        <button type="submit" class="bg-green-600 px-4 py-2 rounded font-bold hover:bg-green-500">
            Save Prediction
        </button>
    </form>
</div>

<script>
document.getElementById('guess-form').addEventListener('submit', function(e) {
    const selects = document.querySelectorAll('select[name^="positions"]');
    const chosen = [];
    let error = '';

    selects.forEach(select => {
        const val = select.value;
        if (!val) {
            error = 'Please select a driver for every position.';
        } else if (chosen.includes(val)) {
            error = 'Each driver can only be assigned to one position.';
        }
        chosen.push(val);
    });

    if (error) {
        e.preventDefault();
        const errorBox = document.getElementById('error-message');
        errorBox.textContent = error;
        errorBox.classList.remove('hidden');
    }
});
</script>
</x-app-layout>
