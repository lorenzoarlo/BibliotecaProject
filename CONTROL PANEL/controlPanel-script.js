function resetDb_onclick() {
    fetch("resetDatabase.php", {
        method: "GET",
    })
        .then((response) => response.text())
        .then((text) => (document.querySelector("#output").innerText = text + "\n"));
}

function fillDb_onclick() {
    fetch("fillDatabase.php", {
        method: "GET",
    })
        .then((response) => response.text())
        .then((text) => (document.querySelector("#output").innerText += text + "\n"));
}
