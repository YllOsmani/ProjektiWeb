
function showPage(page) {
    
    var sections = document.querySelectorAll('section');
    sections.forEach(function(section) {
        section.style.display = 'none';
    });

    
    var selectedPage = document.querySelector('.' + page);
    if (selectedPage) {
        selectedPage.style.display = 'block';
    }
}


document.addEventListener('DOMContentLoaded', function() {
    
    document.querySelector('.home-link').addEventListener('click', function(e) {
        e.preventDefault(); 
        showPage('home');
    });

    
    document.querySelector('.news-link').addEventListener('click', function(e) {
        e.preventDefault(); 
        showPage('news');
    });

    
    showPage('home'); 
});