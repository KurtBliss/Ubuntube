/*

    ABUTUBE

*/
var feedsObj = feeds();

function search(event) {
  if (event.key === "Enter") {
    window.location.href =
      "/results?q=" + document.getElementById("searchInput").value;
  } else {
    return false;
  }
}

/*

    FEEDS

*/
function feeds(set) {
  if (set === undefined) {
    obj = loadObject("feeds");
    if (obj) return loadObject("feeds");
    else return {};
  } else {
    saveObject("feeds", set);
  }
}

/*

    Feeds Edit

*/
function onFeedNew(event) {
  if (event.key === "Enter") {
    feedsObj = feeds();
    id = document.getElementById("newFeedInput").value;
    feedsObj[id] = { name: id };
    feeds(feedsObj);
  } else {
    return false;
  }
}

function onFeedName(event, feedId) {
  if (event.key === "Enter") {
    feedsObj = feeds();
    feedsObj[feedId] = {
      name: document.getElementById("feedNameInput").value || "$feedId"
    };
    feeds(feedsObj);
  } else {
    return false;
  }
}

function feed_add_single_playlist(getFeed, playlistId) {
  var feedObj = feeds();

  feed = document.getElementById("select-" + getFeed).value;

  if (feedObj[feed].section === undefined) {
    feedObj[feed].section = {
      key: 0,
      0: { playlistId: playlistId, type: "singlePlaylist" }
    };
  } else {
    object_key = feedObj[feed].section.key + 1;
    feedObj[feed].section = {
      ...feedObj[feed].section,
      key: object_key,
      [object_key]: { playlistId: playlistId, type: "singlePlaylist" }
    };
  }

  feeds(feedObj);
}

/*

    Render Feeds

*/
function renderFeed(data, feedId) {
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

function appendContainer(append, id) {
  document.getElementById(id).innerHTML += append;
}

/*

    local storage

*/
function saveObject(name, object) {
  window.localStorage.setItem("obj_" + name, JSON.stringify(object));
}

function loadObject(name) {
  return JSON.parse(localStorage.getItem("obj_" + name));
}
