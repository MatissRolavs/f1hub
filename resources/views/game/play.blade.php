<x-app-layout>
<div class="max-w-4xl mx-auto px-4 py-6 text-white audiowide-regular">

    <h2 class="text-5xl sm:text-4xl font-bold tracking-widest underline decoration-white underline-offset-8 mb-8 text-center">
        PREDICTION FOR {{ strtoupper($raceName) }}
    </h2>

    <form method="POST" action="{{ route('game.storePrediction') }}" id="guess-form" class="space-y-6">
        @csrf
        <input type="hidden" name="season" value="{{ $season }}">
        <input type="hidden" name="round" value="{{ $round }}">
        <input type="hidden" name="player_name" value="{{ Auth::user()->name }}">

        <div id="error-message" class="text-red-400 font-bold hidden text-center"></div>

        @for($pos = 1; $pos <= $drivers->count(); $pos++)
        <div class="flex items-center gap-4">
            <!-- Position number -->
            <span class="w-10 text-center font-bold text-yellow-400 text-lg">
                {{ $pos }}
            </span>

            <!-- Styled select -->
            <select name="positions[{{ $pos }}]" 
                    class="flex-1 px-4 py-3 rounded-lg bg-transparent text-white 
                           border-2 border-white backdrop-blur-md
                           shadow-[0_0_10px_rgba(255,255,255,0.8),0_0_25px_rgba(0,0,0,1)]
                           focus:outline-none focus:ring-2 focus:ring-red-500">
                <option value="" class="text-black">-- Select Driver --</option>
                @foreach($drivers as $driver)
                    <option value="{{ $driver->id }}" class="text-black"
                        {{ isset($savedOrder[$pos-1]) && $savedOrder[$pos-1] == $driver->id ? 'selected' : '' }}>
                        {{ $driver->given_name }} {{ $driver->family_name }}
                    </option>
                @endforeach
            </select>
        </div>
        @endfor

        <!-- Save button styled like timer buttons -->
        <button type="submit" 
                class="w-full px-6 py-5 rounded-lg font-bold text-white text-center
                       bg-transparent backdrop-blur-md border-2 border-white
                       shadow-[0_0_10px_rgba(255,255,255,0.8),0_0_25px_rgba(0,0,0,1)]
                       transition transform hover:scale-105 hover:shadow-[0_0_25px_rgba(255,255,255,1),0_0_50px_rgba(0,0,0,1)]">
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
