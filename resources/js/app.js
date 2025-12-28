import "./bootstrap";
import "../css/app.css";

import Alpine from "alpinejs";

window.Alpine = Alpine;

Alpine.start();

const rakContainer = document.getElementById("rakContainer");

let isDown = false;
let startX;
let scrollLeft;

rakContainer.addEventListener("mousedown", (e) => {
    isDown = true;
    rakContainer.classList.add("cursor-grabbing");
    startX = e.pageX - rakContainer.offsetLeft;
    scrollLeft = rakContainer.scrollLeft;
});
rakContainer.addEventListener("mouseleave", () => {
    isDown = false;
    rakContainer.classList.remove("cursor-grabbing");
});
rakContainer.addEventListener("mouseup", () => {
    isDown = false;
    rakContainer.classList.remove("cursor-grabbing");
});
rakContainer.addEventListener("mousemove", (e) => {
    if (!isDown) return;
    e.preventDefault();
    const x = e.pageX - rakContainer.offsetLeft;
    const walk = (x - startX) * 2; // scroll-fast
    rakContainer.scrollLeft = scrollLeft - walk;
});
