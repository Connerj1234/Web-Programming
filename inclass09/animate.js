let timerId = null;

window.addEventListener("DOMContentLoaded", function () {
    document.addEventListener("click", startAnimation);
});

function startAnimation(e) {
    let clickX = e.clientX;
    let clickY = e.clientY;

    // If a timer is already running, stop it BEFORE starting a new one
    if (timerId !== null) {
        clearInterval(timerId);
        timerId = null;
    }

    // Start a new timer that repeatedly moves the image
    timerId = setInterval(function () {
        moveImage(clickX, clickY);
    }, 10);
}

function moveImage(x, y) {
    const img = document.querySelector("img");

    let imgX = parseInt(img.style.left);
    let imgY = parseInt(img.style.top);

    const centerX = Math.round(x - (img.width / 2));
    const centerY = Math.round(y - (img.height / 2));

    // If the heart has reached the target position, stop animation
    if (imgX === centerX && imgY === centerY) {
        clearInterval(timerId);
        timerId = null;
        return;
    }

    // Move one pixel horizontally
    if (imgX < centerX) {
        imgX++;
    } else if (imgX > centerX) {
        imgX--;
    }

    // Move one pixel vertically
    if (imgY < centerY) {
        imgY++;
    } else if (imgY > centerY) {
        imgY--;
    }

    img.style.left = imgX + "px";
    img.style.top = imgY + "px";
}
