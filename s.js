let currentSlide = 0;
const slides = document.querySelectorAll('.slider-image');
const totalSlides = slides.length;

// Funksioni për të kaluar në slajdin tjetër ose më të kaluar
function moveSlide(step) {
    currentSlide += step;

    // Kontrollo nëse duam të kalojmë përtej fundin ose fillimin
    if (currentSlide >= totalSlides) {
        currentSlide = 0; // Kthehet te slajdi i parë
    } else if (currentSlide < 0) {
        currentSlide = totalSlides - 1; // Kthehet te slajdi i fundit
    }

    updateSliderPosition();
}

// Funksioni që përditëson pozicionin e slider-it
function updateSliderPosition() {
    const newTransformValue = -currentSlide * 100;
    document.querySelector('.slider-container').style.transform = `translateX(${newTransformValue}%)`;
}

// Fillimisht, shfaq slajdin e parë
updateSliderPosition();