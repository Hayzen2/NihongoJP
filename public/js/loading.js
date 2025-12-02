$(document).on("click", function (e) {
    const link = e.target.closest("a");
    if (!link) {
        return;
    }
    // Ignore links with `#`, javascript:void(0), or data-bs-toggle (Bootstrap modals)
    const href = link.getAttribute("href");
    if (!href || href.startsWith("#") || href.startsWith("javascript:") || link.dataset.bsToggle) {
        return;
    }
    // Show loading overlay
    const overlay = $("#loadingOverlay");
    overlay.css("display", "flex");
    overlay.removeClass("hidden");
});


$(window).on('pageshow', () => {
    const loadingOverlay = $('#loadingOverlay');
    loadingOverlay.addClass('hidden');
});