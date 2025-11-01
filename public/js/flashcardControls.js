let currentCard = 0;
const wrapper = $('.flashcard-wrapper');

function showCard(index) {
    wrapper.each((i, card) => {
        if(index === 0){
            prevBtn.css('display', 'none');
        }
        else{
            prevBtn.css('display', 'inline-block');
        }
        $(card).toggleClass('active', i === index);
        const flashcard = $(card).find('.flashcard');
        flashcard.removeClass('flipped'); // reset flip state when showing a new card
    });
}

function newCard() {
    currentCard = (currentCard + 1) % wrapper.length;
    showCard(currentCard);
}
function prevCard() {
    currentCard = (currentCard - 1 + wrapper.length) % wrapper.length;
    showCard(currentCard);
}

function reshuffle() {
}

const nextBtn = $('.btn.btn-next, .btn-next');
const prevBtn = $('.btn.btn-prev, .btn-prev');
const reshuffleBtn = $('.btn.btn-reshuffle, .btn-reshuffle');

if (nextBtn) {
    nextBtn.on('click', (e) => {
    e.preventDefault();
    currentCard = (currentCard + 1) % wrapper.length;
    showCard(currentCard);
});
}

if (prevBtn) {
prevBtn.on('click', (e) => {
    e.preventDefault();
    currentCard = (currentCard - 1 + wrapper.length) % wrapper.length;
    showCard(currentCard);
});
}

$(document).on('keydown', function(event) {
    if (event.key === 'ArrowRight') {
        nextBtn.click();
    }
    else if (event.key === 'ArrowLeft') {
        prevBtn.click();
    }
    else if (event.key === ' ') {
        event.preventDefault();
        const activeCard = $(wrapper[currentCard]).find('.flashcard');
        activeCard.toggleClass('flipped');
    }
});

wrapper.each((i, card) => {
    $(card).find('.flashcard').on('click', function() {
        $(this).toggleClass('flipped');
    });
});

showCard(currentCard);
