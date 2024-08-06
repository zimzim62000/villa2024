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

import 'quill/dist/quill.snow.css'; // Import CSS
import Quill from 'quill';

/*
Navbar
 */

document.addEventListener('DOMContentLoaded', () => {
    const editor = document.querySelector('#editor');
    if (editor) {
        const quill = new Quill(editor, {
            theme: 'snow', // ou 'bubble' pour un autre thème
            modules: {
                toolbar: [
                    [{ 'size': ['small', false, 'large', 'huge'] }],
                    ['bold', 'italic', 'underline'],
                    [{ 'color': [] }, { 'background': [] }],
                    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                    ['link']
                ]
            }
        });
        // Synchroniser le contenu du Quill avec le champ caché
        const hiddenContent = document.querySelector('#article_content');
        quill.on('text-change', () => {
            hiddenContent.value = quill.root.innerHTML;
        });

        // Pré-charger le contenu du champ caché dans Quill au chargement de la page
        if (hiddenContent.value) {
            quill.root.innerHTML = hiddenContent.value;
        }
    } else {
        console.error('Quill: No element was found with the ID #editor.');
    }
});

window.addEventListener("load", function() {
    var navbar = document.querySelector(".navbar");
    navbar.classList.remove("navbar-transparent");
});

window.addEventListener("scroll", function() {
    var navbar = document.querySelector(".navbar");
    navbar.classList.toggle("navbar-transparent", window.scrollY > 0);
});



