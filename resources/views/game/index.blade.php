<x-app-layout>
<div class="max-w-6xl mx-auto px-4 py-12 text-white audiowide-regular">

@foreach($races as $race)
<div class="race-block relative opacity-0 translate-y-6 transition-all duration-700 ease-out mb-20">

    <!-- Background image behind everything -->
    <img src="{{ asset('images/formula.png') }}"
         alt="F1 outline background"
         class="absolute inset-0 w-full h-full object-contain opacity-20 pointer-events-none" />

    <!-- All content sits above -->
    <div class="relative z-10">

        <!-- Header -->
        <div class="mb-16">
            <!-- Desktop layout -->
            <div class="hidden sm:flex justify-center items-start relative">
                <span class="rotate-180 [writing-mode:vertical-rl] text-sm tracking-widest text-gray-400 -mr-1 mt-1">
                    {{ $race['round'] ?? '?' }} ROUND
                </span>
                <div class="inline-block text-center relative">
                    <h2 class="text-5xl font-bold tracking-widest underline decoration-white underline-offset-8">
                        {{ strtoupper($race['raceName']) }}
                    </h2>
                    <div class="absolute right-0 w-full font-bold text-right mt-2 text-base uppercase text-white">
                        Next Round In
                    </div>
                </div>
            </div>

            <!-- Mobile layout -->
            <div class="block sm:hidden text-center">
                <h2 class="text-3xl font-bold tracking-widest underline decoration-white underline-offset-8">
                    {{ strtoupper($race['raceName']) }}
                </h2>
                <div class="mt-3 flex flex-col items-center gap-1 text-xs uppercase">
                    <span class="tracking-widest text-gray-400">
                        {{ $race['round'] ?? '?' }} ROUND
                    </span>
                    <span class="font-bold text-white">
                        Next Round In
                    </span>
                </div>
            </div>
        </div>

        <!-- Countdown -->
        <div class="w-full mb-12">
            <div class="flex flex-col sm:flex-row justify-center items-center gap-6 sm:gap-12">
                <!-- Days -->
                <div class="flex flex-col items-center">
                    <span id="days-{{ $loop->index }}" 
                        class="text-5xl sm:text-8xl font-extrabold text-white 
                                bg-transparent backdrop-blur-sm 
                                w-28 h-28 sm:w-48 sm:h-48 flex items-center justify-center 
                                border-2 border-white rounded-lg 
                                shadow-[0_0_10px_rgba(255,255,255,0.8),0_0_25px_rgba(0,0,0,1)]">
                        0
                    </span>
                    <span class="text-xs sm:text-sm uppercase text-gray-300 mt-2">Days</span>
                </div>

                <!-- Hours -->
                <div class="flex flex-col items-center">
                    <span id="hours-{{ $loop->index }}" 
                        class="text-5xl sm:text-8xl font-extrabold text-white 
                                bg-transparent backdrop-blur-sm 
                                w-28 h-28 sm:w-48 sm:h-48 flex items-center justify-center 
                                border-2 border-white rounded-lg 
                                shadow-[0_0_10px_rgba(255,255,255,0.8),0_0_25px_rgba(0,0,0,1)]">
                        0
                    </span>
                    <span class="text-xs sm:text-sm uppercase text-gray-300 mt-2">Hours</span>
                </div>

                <!-- Minutes -->
                <div class="flex flex-col items-center">
                    <span id="minutes-{{ $loop->index }}" 
                        class="text-5xl sm:text-8xl font-extrabold text-white 
                                bg-transparent backdrop-blur-sm 
                                w-28 h-28 sm:w-48 sm:h-48 flex items-center justify-center 
                                border-2 border-white rounded-lg 
                                shadow-[0_0_10px_rgba(255,255,255,0.8),0_0_25px_rgba(0,0,0,1)]">
                        0
                    </span>
                    <span class="text-xs sm:text-sm uppercase text-gray-300 mt-2">Minutes</span>
                </div>

                <!-- Seconds -->
                <div class="flex flex-col items-center">
                    <span id="seconds-{{ $loop->index }}" 
                        class="text-5xl sm:text-8xl font-extrabold text-white 
                                bg-transparent backdrop-blur-sm 
                                w-28 h-28 sm:w-48 sm:h-48 flex items-center justify-center 
                                border-2 border-white rounded-lg 
                                shadow-[0_0_10px_rgba(255,255,255,0.8),0_0_25px_rgba(0,0,0,1)]">
                        0
                    </span>
                    <span class="text-xs sm:text-sm uppercase text-gray-300 mt-2">Seconds</span>
                </div>
            </div>
        </div>


        <!-- Prediction Button -->
        <a href="{{ route('game.play', ['season' => $race['season'], 'round' => $race['round']]) }}"
           class="block w-full px-6 py-4 sm:py-6 rounded-lg font-bold text-white text-center
                  bg-transparent backdrop-blur-md border-2 border-white
                  shadow-[0_0_10px_rgba(255,255,255,0.8),0_0_25px_rgba(0,0,0,1)]
                  transition transform hover:scale-105 hover:shadow-[0_0_25px_rgba(255,255,255,1),0_0_50px_rgba(0,0,0,1)] mb-6 sm:mb-8">
            Make Your Prediction
        </a>

        <!-- Footer Buttons -->
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('game.myPredictions') }}"
               class="w-full sm:flex-1 text-center px-6 py-4 rounded-lg font-bold text-white
                      bg-transparent backdrop-blur-md border-2 border-white
                      shadow-[0_0_10px_rgba(255,255,255,0.8),0_0_25px_rgba(0,0,0,1)]
                      transition transform hover:scale-105 hover:shadow-[0_0_25px_rgba(255,255,255,1),0_0_50px_rgba(0,0,0,1)]">
                My Predictions
            </a>
            <a href="{{ route('game.predictionResults') }}"
               class="w-full sm:flex-1 text-center px-6 py-4 rounded-lg font-bold text-white
                      bg-transparent backdrop-blur-md border-2 border-white
                      shadow-[0_0_10px_rgba(255,255,255,0.8),0_0_25px_rgba(0,0,0,1)]
                      transition transform hover:scale-105 hover:shadow-[0_0_25px_rgba(255,255,255,1),0_0_50px_rgba(0,0,0,1)]">
                My Results
            </a>
        </div>
    </div>
</div>

        <script>
            (function(){
                const raceDate = new Date("{{ $race['date'] }}T{{ $race['time'] ?? '00:00:00Z' }}").getTime();
                const daysEl = document.getElementById("days-{{ $loop->index }}");
                const hoursEl = document.getElementById("hours-{{ $loop->index }}");
                const minutesEl = document.getElementById("minutes-{{ $loop->index }}");
                const secondsEl = document.getElementById("seconds-{{ $loop->index }}");

                function updateCountdown() {
                    const now = new Date().getTime();
                    const distance = raceDate - now;

                    if (distance <= 0) {
                        daysEl.innerHTML = "0";
                        hoursEl.innerHTML = "0";
                        minutesEl.innerHTML = "0";
                        secondsEl.innerHTML = "0";
                        clearInterval(timer);
                        return;
                    }

                    const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                    const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                    const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                    daysEl.innerHTML = days;
                    hoursEl.innerHTML = hours;
                    minutesEl.innerHTML = minutes;
                    secondsEl.innerHTML = seconds;
                }

                updateCountdown();
                const timer = setInterval(updateCountdown, 1000);
            })();
        </script>
    </div>
    @endforeach
</div>
<script>
document.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll(".race-block").forEach((el, i) => {
        // stagger each block slightly for a nice cascading effect
        setTimeout(() => {
            el.classList.remove("opacity-0", "translate-y-6");
            el.classList.add("opacity-100", "translate-y-0");
        }, i * 200); // 200ms delay between each block
    });
});
</script>


{{-- Leaderboard Section --}}
<div class="px-4 sm:px-6 lg:px-8 max-w-6xl mx-auto mt-12">
    <div class="bg-[#1a1a1a] rounded-xl shadow-lg text-white audiowide-regular p-8">
        <h2 class="text-4xl font-bold mb-10 text-center underline decoration-white underline-offset-8">
            Leaderboard
        </h2>

        @if($leaderboard->isEmpty())
            <p class="text-center text-gray-400">No scores yet.</p>
        @else
            <!-- Podium -->
            <div class="flex flex-col md:flex-row items-center md:items-end justify-center gap-10 mb-12">
                <!-- 1st Place -->
                @if(isset($leaderboard[0]))
                <div class="flex flex-col items-center order-1 md:order-2">
                    <div class="relative w-48 h-64 flex flex-col justify-end items-center rounded-lg 
                                border-4 border-yellow-400
                                bg-gradient-to-t from-yellow-600 to-yellow-300 text-black
                                shadow-[0_0_25px_rgba(255,255,255,0.7),0_0_50px_rgba(0,0,0,1)] p-4 text-center">
                        
                        <!-- Number badge at top -->
                        <div class="absolute top-2 left-1/2 -translate-x-1/2 
                                    bg-black/70 text-yellow-400 border-2 border-yellow-400 
                                    rounded-full w-12 h-12 flex items-center justify-center 
                                    text-2xl font-extrabold shadow-lg">
                            1
                        </div>

                        <!-- Player info -->
                        <div class="mt-10">
                            <span class="block font-bold break-words">{{ $leaderboard[0]->player_name }}</span>
                            <span class="block text-sm">{{ $leaderboard[0]->total_score }} pts</span>
                            <span class="block text-xs text-gray-800">Races: {{ $leaderboard[0]->races_played }}</span>
                        </div>
                    </div>
                </div>
                @endif

                <!-- 2nd Place -->
                @if(isset($leaderboard[1]))
                <div class="flex flex-col items-center order-2 md:order-1">
                    <div class="relative w-44 h-56 flex flex-col justify-end items-center rounded-lg 
                                border-4 border-gray-300
                                bg-gradient-to-t from-gray-700 to-gray-400
                                shadow-[0_0_20px_rgba(255,255,255,0.6),0_0_40px_rgba(0,0,0,1)] p-4 text-center">
                        
                        <!-- Number badge -->
                        <div class="absolute top-2 left-1/2 -translate-x-1/2 
                                    bg-black/70 text-gray-200 border-2 border-gray-300 
                                    rounded-full w-12 h-12 flex items-center justify-center 
                                    text-2xl font-bold shadow-lg">
                            2
                        </div>

                        <!-- Player info -->
                        <div class="mt-10">
                            <span class="block font-semibold break-words">{{ $leaderboard[1]->player_name }}</span>
                            <span class="block text-sm">{{ $leaderboard[1]->total_score }} pts</span>
                            <span class="block text-xs text-gray-200">Races: {{ $leaderboard[1]->races_played }}</span>
                        </div>
                    </div>
                </div>
                @endif

                <!-- 3rd Place -->
                @if(isset($leaderboard[2]))
                <div class="flex flex-col items-center order-3 md:order-3">
                    <div class="relative w-40 h-48 flex flex-col justify-end items-center rounded-lg 
                                border-4 border-orange-400
                                bg-gradient-to-t from-orange-700 to-orange-400
                                shadow-[0_0_20px_rgba(255,255,255,0.6),0_0_40px_rgba(0,0,0,1)] p-4 text-center">
                        
                        <!-- Number badge -->
                        <div class="absolute top-2 left-1/2 -translate-x-1/2 
                                    bg-black/70 text-orange-300 border-2 border-orange-400 
                                    rounded-full w-12 h-12 flex items-center justify-center 
                                    text-2xl font-bold shadow-lg">
                            3
                        </div>

                        <!-- Player info -->
                        <div class="mt-10">
                            <span class="block font-semibold break-words">{{ $leaderboard[2]->player_name }}</span>
                            <span class="block text-sm">{{ $leaderboard[2]->total_score }} pts</span>
                            <span class="block text-xs text-gray-200">Races: {{ $leaderboard[2]->races_played }}</span>
                        </div>
                    </div>
                </div>
                @endif
            </div>



            <!-- Remaining Players -->
            @if(count($leaderboard) > 3)
            <table class="w-full border-collapse rounded-lg overflow-hidden">
                <thead>
                    <tr class="bg-[#222] text-gray-400 uppercase text-sm">
                        <th class="p-3 text-left">Rank</th>
                        <th class="p-3 text-left">Player</th>
                        <th class="p-3 text-left">Total Score</th>
                        <th class="p-3 text-left">Races Played</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($leaderboard->slice(3) as $index => $player)
                        <tr class="even:bg-[#2a2a2a] hover:bg-[#333] transition">
                            <td class="p-3">{{ $index + 1 }}</td>
                            <td class="p-3 font-bold">{{ $player->player_name }}</td>
                            <td class="p-3">{{ $player->total_score }}</td>
                            <td class="p-3">{{ $player->races_played }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            @endif
        @endif
    </div>
</div>


</x-app-layout>