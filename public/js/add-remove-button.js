// Function to add a new answer input group
function addAnswer() {
    const answerIndex = document.querySelectorAll('.answer').length;
    const answerDiv = document.createElement('div');
    answerDiv.classList.add('answer');

    answerDiv.innerHTML = `
        <input type="text" name="answers[${answerIndex}][text]">
        <input type="checkbox" name="answers[${answerIndex}][is_correct]" value="1">
        <button type="button" class="btn-remove-answer">Remove</button>
    `;

    document.getElementById('answers-container').appendChild(answerDiv);
}

// Function to remove an answer input group
function removeAnswer(event) {
    const answerDiv = event.target.closest('.answer');
    if (answerDiv) {
        answerDiv.remove();
    }
}

// Event listener for adding answer inputs
document.getElementById('btn-add-answer').addEventListener('click', addAnswer);

// Event delegation for removing answer inputs
document.addEventListener('click', function(event) {
    if (event.target.classList.contains('btn-remove-answer')) {
        removeAnswer(event);
    }
});
