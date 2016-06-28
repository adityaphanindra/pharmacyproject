$(document).ready(function() {
    // Navbar mobile hamburger menu
    $('.right.menu.open').click(function(e) {
        e.preventDefault();
        $('.ui.vertical.menu').toggle();
    });
    $('.ui.dropdown').dropdown();

    $('.ui.accordion').accordion();

    $('.menu .item').tab();

    $('.login_button').click(function() {
        $('.login_modal').modal('show');
    });    
}); 