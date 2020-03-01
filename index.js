function search(event) {
  if (event.key === "Enter") {
    window.location.href =
      "/results?q=" + document.getElementById("searchInput").value;
  } else {
    return false;
  }
}

function onFeedName(event) {
  if (event.key === "Enter") {
    feedsObj = feeds();
    feedsObj["$feedId"] = {
      name: document.getElementById("feedNameInput").value || "$feedId"
    };
    feeds(feedsObj);
  } else {
    return false;
  }
}

function feeds(set) {
  if (set === undefined) {
    obj = loadObject("feeds");
    if (obj) return loadObject("feeds");
    else return {};
  } else {
    saveObject("feeds", set);
  }
}

function feed_add_single_playlist(feed, playlistiId) {}

function saveObject(name, object) {
  window.localStorage.setItem("obj_" + name, JSON.stringify(object));
}

function loadObject(name) {
  return JSON.parse(localStorage.getItem("obj_" + name));
}

function renderFeed(data, feedId) {
  console.log("rendering...");
  document.getElementById("feed-container").innerHTML = "<p>rendering...</p>";
  var xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      document.getElementById("feed-container").innerHTML = this.responseText;
    }
  };
  xhttp.open("POST", "/views/feed/render.php", true);
  xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xhttp.send("data=" + JSON.stringify(data[feedId]));
}
