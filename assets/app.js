/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.scss in this case)
import './styles/app.scss';

// start the Stimulus application
import './bootstrap';

/*
Navbar
 */

window.addEventListener("load", function() {
    var navbar = document.querySelector(".navbar");
    navbar.classList.remove("navbar-transparent");
});

window.addEventListener("scroll", function() {
    var navbar = document.querySelector(".navbar");
    navbar.classList.toggle("navbar-transparent", window.scrollY > 0);
});



