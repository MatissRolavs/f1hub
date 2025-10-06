<x-app-layout>
<h2 class="text-3xl sm:text-4xl font-bold tracking-widest text-white underline decoration-white underline-offset-8 mb-6 pt-6 text-center audiowide-regular">
    MY PREDICTIONS
</h2>

<div class="max-w-xs mx-auto px-4 py-6 text-white audiowide-regular">

    @forelse($predictions as $pred)
        <div class="mb-4 p-4 rounded-lg bg-transparent backdrop-blur-md border-2 border-white 
                    shadow-[0_0_8px_rgba(255,255,255,0.7),0_0_20px_rgba(0,0,0,1)] 
                    max-w-2xl mx-auto text-center"> <!-- added text-center -->
            
            <!-- Race name -->
            <div class="text-lg font-bold mb-1">
                {{ strtoupper($pred->raceName) }}
            </div>

            <!-- Race date -->
            <div class="text-xs text-gray-400 mb-2">
                Race Date: {{ $pred->raceDate }}
            </div>

            <!-- Prediction -->
            <div class="mb-2">
                <span class="font-bold text-red-400 text-sm block">Your Prediction:</span>
                <div class="mt-1 space-y-0.5">
                    @foreach($pred->formatted_order as $driverLine)
                        <div class="text-gray-200 text-sm">{{ $driverLine }}</div>
                    @endforeach
                </div>
            </div>

            <!-- Action -->
            @if($pred->raceDate && \Carbon\Carbon::parse($pred->raceDate)->isFuture())
                <a href="{{ route('game.play', ['season' => $pred->season, 'round' => $pred->round]) }}"
                   class="block w-full sm:w-2/3 mx-auto px-6 py-3 rounded-lg font-bold text-white text-center text-sm
                          bg-transparent backdrop-blur-md border-2 border-white
                          shadow-[0_0_8px_rgba(255,255,255,0.7),0_0_20px_rgba(0,0,0,1)]
                          transition transform hover:scale-105 hover:shadow-[0_0_20px_rgba(255,255,255,1),0_0_40px_rgba(0,0,0,1)]">
                    Edit Prediction
                </a>
            @else
                <span class="text-gray-400 text-xs italic">Race locked</span>
            @endif
        </div>
    @empty
        <p class="text-center text-gray-400 text-sm">You haven't made any predictions yet.</p>
    @endforelse
</div>
</x-app-layout>
