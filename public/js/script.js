let leaveBtn = document.querySelector('.event-leave-btn');
let originalText = leaveBtn.innerHTML;

leaveBtn.addEventListener('mouseenter', () => {
    leaveBtn.innerHTML = 'Leave <i class="fa-solid fa-right-from-bracket"></i>'
})

leaveBtn.addEventListener('mouseleave', () => {
    leaveBtn.innerHTML = originalText;
})
