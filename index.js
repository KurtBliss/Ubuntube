function search(event) {
  if (event.key === "Enter") {
    window.location.href =
      "/results?q=" + document.getElementById("searchInput").value;
  } else {
    return false;
  }
}
