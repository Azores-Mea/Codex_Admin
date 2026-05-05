// Bar Chart (This is usually okay, but we'll add maintenance to be safe)
const ctxBar = document.getElementById('barChart').getContext('2d');
new Chart(ctxBar, {
    type: 'bar',
    data: {
        labels: ['Bgnr', 'Int', 'Adv'],
        datasets: [{
            data: [65, 45, 30],
            backgroundColor: ['#E3AF64', '#4398F2', '#A666F4']
        }]
    },
    options: { 
        responsive: true, 
        maintainAspectRatio: false, /* Let CSS control the Bar chart box height */
        plugins: { legend: { display: false } } 
    }
});

// Pie Chart (The broken one)
const ctxPie = document.getElementById('pieChart').getContext('2d');
new Chart(ctxPie, {
    type: 'pie',
    data: {
        labels: ['Beginner', 'Intermediate', 'Advanced'],
        datasets: [{
            data: [60, 20, 20],
            backgroundColor: ['#E3AF64', '#4398F2', '#A666F4'],
            borderWidth: 2,
            borderColor: '#FFFFFF'
        }]
    },
    options: { 
        responsive: true, 
        maintainAspectRatio: true, /* Pinaka-importante: Sinisiguro nitong 1:1 circle ang pie */
        aspectRatio: 1, /* explicit 1:1 ratio */
        plugins: { 
            legend: { 
                position: 'right', /* Move legend to the side gaya ng target */
                labels: {
                    usePointStyle: true,
                    pointStyle: 'circle'
                }
            } 
        } 
    }
});
/* ================================================================
   COURSE MANAGEMENT LOGIC
   ================================================================ */

document.addEventListener('DOMContentLoaded', function() {
    const triggers = document.querySelectorAll('.cm-dropdown-trigger');

    triggers.forEach(trigger => {
        trigger.addEventListener('click', function(e) {
            // STOP logic: Huwag mag-dropdown kung ang clinick ay buttons
            if (e.target.closest('.cm-actions')) return;

            const card = this.closest('.cm-module-card');
            const lessonList = card.querySelector('.cm-lesson-list');
            const caret = this.querySelector('.caret-icon');

            // Toggle lessons
            if (lessonList.style.display === 'none' || lessonList.style.display === '') {
                lessonList.style.display = 'block';
                if (caret) caret.classList.add('rotate-down');
            } else {
                lessonList.style.display = 'none';
                if (caret) caret.classList.remove('rotate-down');
            }
        });
    });
});