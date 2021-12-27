function resetDb_onclick() {
    fetch("PHP_QUERIES/resetDatabase.php", {
        method: "GET",
    })
        .then((response) => response.text())
        .then((text) => (document.querySelector("#output").innerText = text + "\n"));
}

function fillDb_onclick() {
    fetch("PHP_QUERIES/fillDatabase.php", {
        method: "GET",
    })
        .then((response) => response.text())
        .then((text) => (document.querySelector("#output").innerText += text + "\n"));
}
