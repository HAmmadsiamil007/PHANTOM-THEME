/**
 * @deprecated 2.0.0 — Legacy jQuery cart handler. Phantom REST cart in phantom-data.js handles all cart operations.
 * @see phantom-data.js
 */

/**
 * @deprecated 2.0.0 No longer loaded in HTML templates. Product quantity
 *             logic handled by phantom-data.js via REST API.
 */
// Product Detail Page counter

function increaseValue(button, limit) {
    const numberInput = button.parentElement.querySelector('.number');
    var value = parseInt(numberInput.innerHTML, 10);
    if (isNaN(value)) value = 0;
    if (limit && value >= limit) return;
    numberInput.innerHTML = value + 1;
}

function decreaseValue(button) {
    const numberInput = button.parentElement.querySelector('.number');
    var value = parseInt(numberInput.innerHTML, 10);
    if (isNaN(value)) value = 0;
    if (value < 1) return;
    numberInput.innerHTML = value - 1;
}

// Tabs next previous button

const tabs = document.querySelectorAll('.tab-pane1');
let currentIndex = 0;

function showTab(index) {
    tabs.forEach(tab => tab.classList.remove('active', 'show'));
    tabs[index].classList.add('active', 'show');
}

document.getElementById('nextBtn').onclick = function () {
    currentIndex = (currentIndex + 1) % tabs.length;
    showTab(currentIndex);
};

document.getElementById('prevBtn').onclick = function () {
    currentIndex = (currentIndex - 1 + tabs.length) % tabs.length;
    showTab(currentIndex);
};
