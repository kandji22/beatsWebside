
import './styles/app.css';
import $ from 'jquery';
import 'bootstrap';
import 'bootstrap/dist/css/bootstrap.min.css';
import 'popper.js'
import 'font-awesome/css/font-awesome.min.css'
global.$ = $;


import gsap from "gsap";
global.gsap = gsap;
$(document).ready(function() {
    // Cacher le spinner une fois que tous les éléments de la page sont chargés
    $("#spinner").fadeOut("slow");
});