document.addEventListener('DOMContentLoaded', () => {
    const config = window.__QUIZ_CONFIG__ || {};
    const timeEl = document.getElementById('floatTime'); 
    const submitBtn = document.getElementById('submitBtn');
    const form = document.getElementById('quizForm');

    let timeLeft = parseInt(config.timeLimit, 10) || 120;
    let timerInterval = null;

    function startTimer() {
        if (config.locked) {
            timeEl.textContent = 0;
            submitBtn.disabled = true;
            return;
        }
        timeEl.textContent = timeLeft;
        timerInterval = setInterval(() => {
            timeLeft--;
            if (timeLeft <= 0) {
                clearInterval(timerInterval);
                submitQuiz();
                return;
            }
            timeEl.textContent = timeLeft;
        }, 1000);
    }

    function collectAnswers() {
        if (config.locked) return {}; // locked, do nothing
        const answers = {};
        const inputs = form.querySelectorAll('input[type="radio"], input[type="checkbox"]');
        const grouped = {};
        inputs.forEach(inp => {
            const name = inp.name;
            if (!name) return;
            grouped[name] = grouped[name] || [];
            if (inp.type === 'radio' && inp.checked) grouped[name] = [inp.value];
            if (inp.type === 'checkbox' && inp.checked) grouped[name].push(inp.value);
        });
        Object.keys(grouped).forEach(name => {
            const id = name.replace(/^qa_/, '');
            const vals = grouped[name];
            if (Array.isArray(vals)) {
                if (vals.length === 0) return;
                const radioExists = !!form.querySelector(`input[type="radio"][name="${name}"]`);
                answers[id] = radioExists && vals.length === 1 ? vals[0] : vals;
            } else answers[id] = vals;
        });
        return answers;
    }

    function highlightAnswers(perQuestion) {
        const cards = document.querySelectorAll('.question-card');
        cards.forEach(card => {
            const qaId = parseInt(card.dataset.qaId, 10);
            const info = perQuestion[qaId] || {};
            const inputs = card.querySelectorAll('input');
            inputs.forEach(inp => {
                const label = inp.closest('label') || inp.parentElement;
                if (!label) return;
                label.classList.remove('choice-correct', 'choice-wrong', 'choice-selected');
                inp.disabled = true; // lock input
                if (info.selected_answers) {
                    const selected = Array.isArray(info.selected_answers) ? info.selected_answers : [info.selected_answers];
                    if (selected.includes(inp.value)) label.classList.add('choice-selected');
                }
                if (info.correct_answer === inp.value) label.classList.add('choice-correct'); // green tick
                else if (label.classList.contains('choice-selected')) label.classList.add('choice-wrong'); // red x
            });
        });
    }

    async function submitQuiz() {
        clearInterval(timerInterval);
        submitBtn.disabled = true;
        if (config.locked) return;

        const answers = collectAnswers();
        const payload = { quiz_id: config.quizId, answers };
        try {
            const res = await fetch('/quizzes/submit', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(payload)
            });
            const json = await res.json();
            if (res.ok) {
                window.location.href = `/quizzes/view/${config.quizId}?result=1`;
            } else {
                alert(json.error || 'Submission failed');
                submitBtn.disabled = false;
            }
        } catch (err) {
            console.error(err);
            alert('Network error');
            submitBtn.disabled = false;
        }
    }

    submitBtn.addEventListener('click', () => {
        if (!confirm('Submit your answers?')) return;
        submitQuiz();
    });

    // If already attempted, highlight previous answers
    if (config.locked && config.prevAnswers) {
        const perQuestion = {};
        Object.keys(config.prevAnswers).forEach(qaId => {
            perQuestion[qaId] = {
                selected_answers: config.prevAnswers[qaId],
                correct_answer: null // will be replaced by server on result page
            };
        });
        highlightAnswers(perQuestion);
    }

    startTimer();
});
