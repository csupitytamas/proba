const langImg = document.getElementById("lang-img");
const langSelect = document.getElementById("language-select");

langImg.addEventListener("click", () => {
    langSelect.style.display = "block";

});
langSelect.addEventListener("change", () => {
    langSelect.style.display = "none";
});
