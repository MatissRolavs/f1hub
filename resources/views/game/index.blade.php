<x-app-layout>
<style>
    .race-card {
        background: linear-gradient(145deg, #1f1f1f, #2a2a2a);
        border: 1px solid rgba(255,255,255,0.1);
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 4px 15px rgba(0,0,0,0.4);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .race-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 6px 20px rgba(255,0,0,0.4);
    }
    .race-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 0.5rem;
    }
    .race-name {
        font-size: 1.25rem;
        font-weight: bold;
    }
    .race-date {
        font-size: 0.9rem;
        color: #bbb;
    }
    .btn {
        padding: 0.4rem 0.8rem;
        border-radius: 6px;
        font-weight: bold;
        transition: background 0.2s ease;
        text-align: center;
        display: inline-block;
    }
    .btn-green {
        background: #16a34a;
        color: white;
    }
    .btn-green:hover {
        background: #15803d;
    }
    .btn-blue {
        background: #2563eb;
        color: white;
    }
    .btn-blue:hover {
        background: #1d4ed8;
    }
    .countdown {
        font-family: 'Courier New', monospace;
        font-size: 1.1rem;
        color: #facc15;
        margin-top: 0.5rem;
    }
    @media (max-width: 640px) {
        .race-header {
            flex-direction: column;
            align-items: flex-start;
        }
    }
</style>

<div class="max-w-4xl mx-auto px-4 py-6 text-white">
    <a href="{{ route('game.leaderboard') }}" class="btn btn-blue mb-4">🏆 Leaderboard</a>
    <h2 class="text-2xl font-bold mb-6 audiowide-regular">Next Race</h2>

    @foreach($races as $race)
        <div class="race-card">
            <div class="race-header">
                <div>
                    <div class="race-name">{{ $race['raceName'] }}</div>
                    <div class="race-date">{{ $race['date'] }}</div>
                </div>
                <a href="{{ route('game.play', ['season' => $race['season'], 'round' => $race['round']]) }}" class="btn btn-green">
                    Make Prediction
                </a>
            </div>
            <div class="mt-3">
                <span class="font-bold">Countdown:</span>
                <span id="countdown-{{ $loop->index }}" class="countdown"></span>
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
        <a href="{{ route('game.myPredictions') }}" class="btn btn-blue">📋 My Predictions</a>
        <a href="{{ route('game.predictionResults') }}" class="btn btn-blue">📊 My Results</a>
    </div>
</div>
</x-app-layout>
