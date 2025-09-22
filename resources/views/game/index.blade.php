<x-app-layout>
<div class="max-w-4xl mx-auto px-4 py-6 text-white">
    <a href="{{ route('game.leaderboard') }}"
       class="inline-block px-3 py-1.5 rounded-md font-bold text-white bg-blue-600 hover:bg-blue-700 mb-4 transition-colors">
        🏆 Leaderboard
    </a>

    <h2 class="text-2xl font-bold mb-6 audiowide-regular">Next Race</h2>

    @foreach($races as $race)
        <div class="bg-gradient-to-br from-[#1f1f1f] to-[#2a2a2a] border border-white/10 rounded-xl p-6 mb-6 shadow-lg transition-all duration-200 hover:-translate-y-1 hover:shadow-[0_6px_20px_rgba(255,0,0,0.4)]">
            <div class="flex flex-wrap items-center justify-between gap-2 sm:flex-row">
                <div>
                    <div class="text-xl font-bold">{{ $race['raceName'] }}</div>
                    <div class="text-sm text-gray-400">{{ $race['date'] }}</div>
                </div>
                <a href="{{ route('game.play', ['season' => $race['season'], 'round' => $race['round']]) }}"
                   class="inline-block px-3 py-1.5 rounded-md font-bold text-white bg-green-600 hover:bg-green-700 transition-colors">
                    Make Prediction
                </a>
            </div>
            <div class="mt-3">
                <span class="font-bold">Countdown:</span>
                <span id="countdown-{{ $loop->index }}" class="font-mono text-lg text-yellow-400 mt-1 inline-block"></span>
            </div>
        </div>

        <script>
            (function(){
                const raceDate = new Date("{{ $race['date'] }}T{{ $race['time'] ?? '00:00:00Z' }}").getTime();
                const countdownEl = document.getElementById("countdown-{{ $loop->index }}");

                function updateCountdown() {
                    const now = new Date().getTime();
                    const distance = raceDate - now;

                    if (distance <= 0) {
                        countdownEl.innerHTML = "Race has started!";
                        clearInterval(timer);
                        setTimeout(() => window.location.reload(), 2000);
                        return;
                    }

                    const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                    const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                    const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                    countdownEl.innerHTML = `${days}d ${hours}h ${minutes}m ${seconds}s`;
                }

                updateCountdown();
                const timer = setInterval(updateCountdown, 1000);
            })();
        </script>
    @endforeach

    <div class="mt-6 flex flex-wrap gap-2">
        <a href="{{ route('game.myPredictions') }}"
           class="inline-block px-3 py-1.5 rounded-md font-bold text-white bg-blue-600 hover:bg-blue-700 transition-colors">
            📋 My Predictions
        </a>
        <a href="{{ route('game.predictionResults') }}"
           class="inline-block px-3 py-1.5 rounded-md font-bold text-white bg-blue-600 hover:bg-blue-700 transition-colors">
            📊 My Results
        </a>
    </div>
</div>
</x-app-layout>
