<x-app-layout>
<div class="min-h-screen flex flex-col justify-center items-center gap-8 px-4 sm:px-6 lg:px-8 py-8 bg-grey-900 font-mono text-white tracking-[2px] leading-[1.8]">
    
    <!-- Welcome Box -->
    <div class="bg-white/5 border border-white/20 rounded-xl p-6 sm:p-8 max-w-full sm:max-w-2xl lg:max-w-[800px] w-full text-center shadow-[0_0_20px_rgba(255,0,0,0.3)] transition-all duration-300 hover:-translate-y-[5px] hover:shadow-[0_0_30px_rgba(255,0,0,0.6)]">
        <img src="https://copilot.microsoft.com/th/id/BCO.1853a237-4c9c-4232-84b3-e8ae2bb8df46.png"
             alt="F1 Logo"
             class="max-w-[100px] sm:max-w-[120px] md:max-w-[150px] h-auto mx-auto mb-4 block">
        <h1 class="audiowide-regular font-bold uppercase mb-4 text-lg sm:text-xl md:text-2xl">🏎️ Welcome to F1 Hub</h1>
        <p class="text-sm sm:text-base md:text-lg">
            Your ultimate destination for everything Formula&nbsp;1 — race schedules, live standings, driver profiles, and team stats.
        </p>
    </div>

    <!-- About Box -->
    <div class="bg-white/5 border border-white/20 rounded-xl p-6 sm:p-8 max-w-full sm:max-w-2xl lg:max-w-[800px] w-full text-center shadow-[0_0_20px_rgba(255,0,0,0.3)] transition-all duration-300 hover:-translate-y-[5px] hover:shadow-[0_0_30px_rgba(255,0,0,0.6)]">
        <h2 class="audiowide-regular font-bold uppercase mb-4 text-lg sm:text-xl md:text-2xl">About F1 Hub</h2>
        <p class="mb-4 text-sm sm:text-base md:text-lg">
            F1 Hub is built for fans who live and breathe the thrill of Formula&nbsp;1. 
            Here you’ll find up‑to‑date race results, championship standings, driver stats, and exclusive insights — all in one place.
        </p>
        <p class="mb-4 text-sm sm:text-base md:text-lg">
            Whether you’re following your favourite driver, tracking your team’s progress, or just exploring the world of F1, 
            our goal is to make the experience fast, easy, and exciting.
        </p>
        <a href="{{ route('drivers.index') }}" 
           class="text-[#007BFF] underline font-bold transition-colors duration-200 hover:text-[#0056b3] text-sm sm:text-base">
            View Current Drivers
        </a>
    </div>

</div>
</x-app-layout>
