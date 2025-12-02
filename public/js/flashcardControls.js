let currentCard = 0;
const wrapper = $('.flashcard-wrapper');

function showCard(index) {
    wrapper.each((i, card) => {
        if(wrapper.length === 1){
            reshuffleBtn.css('display', 'none');
        }
        if(index === 0){
            prevBtn.css('display', 'none');
        }
        else{
            prevBtn.css('display', 'inline-block');
        }
        if(index === wrapper.length - 1){
            nextBtn.css('display', 'none');
        }
        else{
            nextBtn.css('display', 'inline-block');
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
    const container = $('.flashcard-deck');
    const shuffled = wrapper.sort(() => Math.random() - 0.5); 

    // reappend shuffled cards
    shuffled.each(function () {
        container.append(this);
    });

    // Renumber after shuffle
    renumberCards();

    // Reset to first card
    currentCard = 0;
    showCard(currentCard);
}

function renumberCards() {
    $('.flashcard-wrapper').each((i, card) => {
        $(card).find('.card-number').text(i + 1);
    });
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

if (reshuffleBtn) {
    reshuffleBtn.on('click', (e) => {
        e.preventDefault();
        reshuffle();
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
