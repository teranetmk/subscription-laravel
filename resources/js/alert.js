hideAlert = function (el) {
    setTimeout( function() {
        el.classList.add('absolute', 'opacity-0', 'transform', '-translate-x-32');
    }, 2000 );
};
document.addEventListener('DOMContentLoaded', () => {
    let alerts = document.querySelectorAll('.basis-alert');
    if(alerts) {
       alerts.forEach(hideAlert);
    }
});
