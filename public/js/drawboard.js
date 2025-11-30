// Draw board
$(document).ready(() => {
    $('.draw-board-container').css('display', 'none');
})
const canvas = document.getElementById('drawBoard');  
const clearButton = $('#clearDraw');
const ctx = canvas.getContext('2d'); // canvas context is a 2D drawing surface
let drawing = false; 
let lastX = 0;
let lastY = 0;
let strokes = [];
let currentStroke = [];

canvas.addEventListener('mousedown', e => {
    drawing = true;
    lastX = e.offsetX; 
    lastY = e.offsetY;

    currentStroke = [];
    currentStroke.push([lastX, lastY]); // add the current position to the current stroke
    strokes.push(currentStroke); // add the current stroke to the strokes array
});

canvas.addEventListener("mouseup", () => {
    drawing = false;
    recognizeStrokes();
});
canvas.addEventListener('mouseout', () => drawing = false);

canvas.addEventListener('mousemove', e => {
    if (!drawing) {
        return;
    }
    ctx.strokeStyle = '#d63384';
    ctx.lineWidth = 4;
    ctx.lineCap = 'round'; 

    ctx.beginPath();  // start a new path
    ctx.moveTo(lastX, lastY); // move the pen to the last draw position
    ctx.lineTo(e.offsetX, e.offsetY); // draw from the previous position to the current mouse position
    ctx.stroke(); // draw the line

    lastX = e.offsetX; 
    lastY = e.offsetY; 

    currentStroke.push([lastX, lastY]); // add the current position to the current stroke
});

function convertStrokes() { // convert strokes to ink
    return strokes.map(stroke => {
        const x = stroke.map(p => p[0]); // get the x coordinates
        const y = stroke.map(p => p[1]); // get the y coordinates
        return [x, y];
    });
}

async function recognizeStrokes(){
    if(strokes.length === 0){
        return;
    }
    const ink = convertStrokes(); 
    const body = {
        input_type: 'handwriting',
        requests: [
            {
                ink: ink,
                language: 'ja'
            }
        ]
    };

    try{
        const response = await fetch(
            "https://inputtools.google.com/request?itc=ja-t-i0-handwrit",
            {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                }, 
                body: JSON.stringify(body) 
            }
        );

        const data = await response.json(); 
        const result = data?.[1]?.[0]?.[1] ?? "(no result)";
        const filteredResult = result.slice(0, 5); // get the first 5 characters
        renderCharacterButtons(filteredResult);

    } catch(err){
        console.error(err);
    }
}

function renderCharacterButtons(results) {
    const output = $('#output');
    output.html('');
    results.forEach(result => {
        const button = $('<button>').text(result);
        button.on('click', () => {
           const searchInput = $(`input[name="search"]`);
           searchInput.val(searchInput.val() + result);
           searchInput.trigger('input');
           clearButton.trigger('click');
        });
        output.append(button);
    });
}

clearButton.on('click', () => {
    strokes = [];
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    $('#output').html('');
});

// open canvas
$("#openCanvas").on("click", () => {
    const container = $(".draw-board-container");
    container.css("display", "block");
    const openButton = $("#openCanvas");
    openButton.css("display", "none");
});

// close canvas
$("#closeCanvas").on("click", () => {
    const container = $(".draw-board-container");
    container.css("display", "none");
    const openButton = $("#openCanvas");
    openButton.css("display", "block");
});
