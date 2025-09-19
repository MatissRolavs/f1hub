<x-app-layout>
<style>
    body {
        background-color: #000;
        font-family: monospace;
        color: #fff;
        letter-spacing: 2px;
        line-height: 1.8;
        margin: 0;
    }
    nav, nav a {
    font-family: inherit;
    letter-spacing: normal;
    line-height: normal;
}

    .full-height {
        min-height: 100vh;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        gap: 2rem;
        padding: 2rem;
    }
    .content-box {
        background-color: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 12px;
        padding: 2rem;
        max-width: 800px;
        width: 100%;
        text-align: center;
        box-shadow: 0 0 20px rgba(255, 0, 0, 0.3);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .content-box:hover {
        transform: translateY(-5px);
        box-shadow: 0 0 30px rgba(255, 0, 0, 0.6);
    }
    h1, h2 {
        font-weight: bold;
        text-transform: uppercase;
        margin-bottom: 1rem;
    }
    .f1-image {
        max-width: 150px;
        height: auto;
        margin: 0 auto 1rem auto; /* Centers horizontally */
        display: block;           /* Ensures margin auto works */
    }
    .link-style {
    color: #007BFF; /* Bootstrap link blue */
    text-decoration: underline;
    font-weight: bold;
    transition: color 0.2s ease;
    }
    .link-style:hover {
        color: #0056b3; /* Darker blue on hover */
    }

</style>

<div class="full-height">
    <!-- Welcome Box -->
    <div class="content-box">
        <img src="https://copilot.microsoft.com/th/id/BCO.1853a237-4c9c-4232-84b3-e8ae2bb8df46.png"
             alt="F1 Logo"
             class="f1-image">
        <h1 class="audiowide-regular">🏎️ Welcome to F1 Hub</h1>
        <p class="lead ">
            Your ultimate destination for everything Formula&nbsp;1 — race schedules, live standings, driver profiles, and team stats.
        </p>
    </div>

    <!-- About Box -->
    <div class="content-box">
    <h2 class="audiowide-regular">About F1 Hub</h2>
    <p class="">
        F1 Hub is built for fans who live and breathe the thrill of Formula&nbsp;1. 
        Here you’ll find up‑to‑date race results, championship standings, driver stats, and exclusive insights — all in one place.
    </p>
    <p class="">
        Whether you’re following your favourite driver, tracking your team’s progress, or just exploring the world of F1, 
        our goal is to make the experience fast, easy, and exciting.
    </p>
    <a href="{{ route('drivers.index') }}" class="link-style ">
        View Current Drivers
    </a>
</div>
</div>
</x-app-layout>
