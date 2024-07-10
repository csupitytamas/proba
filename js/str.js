const moveImg = document.getElementById("move-img");
const moveSelect = document.getElementById("move-select");

moveImg.addEventListener("click", () => {
    moveSelect.style.display = "block";

});
moveSelect.addEventListener("change", () => {
    moveSelect.style.display = "none";
});