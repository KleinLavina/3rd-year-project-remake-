<style>
    /* Header Styling */
    header {
        background-color:rgb(5, 42, 14);/* Original dark background color */
        color: #e0fbe7;
        padding: 15px 0; /* Compact padding */
        text-align: center;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; /* Modern font */
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3); /* Shadow for depth */
        position: relative; /* For positioning dynamic elements */
        overflow: hidden; /* Ensures clean animations */
    }

    /* Title Styling */
    h1 {
        font-size: 32px; /* Medium font size for elegance */
        font-weight: bold;
        letter-spacing: 2px; /* Subtle spacing for a futuristic look */
        margin: 0;
        text-transform: uppercase;
        text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.5); /* Strong shadow for depth */
        animation: glow 3s infinite alternate; /* Glowing effect */
    }

    /* Subtitle Styling */
    .subtitle {
        font-size: 16px; /* Smaller subtitle font */
        color: #b5b5b5; /* Softer gray for contrast */
        letter-spacing: 1px;
        margin-top: 5px;
        animation: fadeIn 2s ease-in-out;
    }

    /* Animated Glow Effect */
    @keyframes glow {
    0% {
        color: #ffffff;
        text-shadow: 0 0 3px #ffffff, 0 0 6px #00ffcc, 0 0 9px #00ffcc, 0 0 12px #00ffcc; /* Reduced glow intensity */
    }
    100% {
        color: #00ffcc;
        text-shadow: 0 0 5px #ffffff, 0 0 10px #00ffcc, 0 0 15px #00ffcc, 0 0 20px #00ffcc; /* Subtle glow */
    }
}


    /* Particle Effect */
    .particle {
        position: absolute;
        background-color:rgb(22, 195, 201); /* Futuristic green color */
        width: 4px;
        height: 4px;
        border-radius: 50%;
        opacity: 0.7;
        animation: float 6s linear infinite;
    }

    @keyframes float {
        0% {
            transform: translateY(100%) translateX(calc(-50px + 100 * random()));
            opacity: 0.1;
        }
        50% {
            transform: translateY(50%) translateX(calc(50px - 100 * random()));
            opacity: 0.8;
        }
        100% {
            transform: translateY(-10%) translateX(calc(-50px + 100 * random()));
            opacity: 0.1;
        }
    }

    /* Utility to create random values for particles */
    :root {
        --particle-count: 20; /* Number of particles */
    }
</style>

<header>
    <h1>Cracken Tech </h1>
    <div class="subtitle">Bringing Innovation to the Future</div>
    <!-- Particles -->
    <script>
        // Add floating particles to the header
        const header = document.querySelector('header');
        for (let i = 0; i < getComputedStyle(document.documentElement).getPropertyValue('--particle-count'); i++) {
            const particle = document.createElement('div');
            particle.className = 'particle';
            particle.style.left = Math.random() * 100 + '%';
            particle.style.animationDelay = Math.random() * 6 + 's';
            header.appendChild(particle);
        }
    </script>
</header>
