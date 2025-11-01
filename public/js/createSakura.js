$(document).ready(() => {
    function createSakura() {
        const sakuraContainer = $('.sakura-container');
        if (sakuraContainer.length === 0) return;
        const sakura = $('<div></div>').addClass('sakura');
        // randomize horizontal start
        sakura.css('left', Math.random() * 100 + 'vw');
        sakura.css('animationDelay', Math.random() * 3 + "s");

        // Calculate duration so speed is constant
        const fallDistance = window.innerHeight + 50; // start above + full screen
        const speed = 100; // px per second
        const duration = fallDistance / speed; // in seconds
        sakura.css('animationDuration', `${duration}s`);

        sakuraContainer.append(sakura);

        // remove when animation ends
        sakura.on("animationend", () => {
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
