document.addEventListener("DOMContentLoaded", () => {
    function createSakura() {
        const sakuraContainer = document.querySelector('.sakura-container');
        if (!sakuraContainer) return;
        const sakura = document.createElement('div');
        sakura.classList.add('sakura');
        // randomize horizontal start
        sakura.style.left = Math.random() * 100 + 'vw';
        sakura.style.animationDelay = Math.random() * 3 + "s";
        
        // Calculate duration so speed is constant 
        const fallDistance = window.innerHeight + 50; // start above + full screen
        const speed = 100; // px per second
        const duration = fallDistance / speed; // in seconds
        sakura.style.animationDuration = `${duration}s`;

        sakuraContainer.appendChild(sakura);

        // remove when animation ends
        sakura.addEventListener("animationend", () => {
            sakura.remove();
        });
    }
    function generateSakura() {
        // only generate if tab is visible
        if (!document.hidden) {
            createSakura();
        }
    }

    setInterval(generateSakura, 350);
});
