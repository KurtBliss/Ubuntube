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

    Menu

*/
var menu_visible = false;

function menuButton() {
  var element_main = document.getElementsByTagName("main")[0];
  var element_menu = document.getElementById("asideMenu");

  if (menu_visible) {
    element_main.style = "margin-left: 0";
    element_menu.style = "display:none";
    menu_visible = false;
  } else {
    element_main.style = "margin-left: 180px";
    element_menu.style = "display:block";
    menu_visible = true;
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
    feedsObj[id] = { id: id, name: id };
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

function feedRemove(feed) {
  var feedObj = feeds();
  delete feedObj[feed];
  feeds(feedObj);
}

/*

    Render Feeds

*/
function renderFeed(data, feedId, edit = false) {
  document.getElementById("feed-container").innerHTML = "<p>rendering...</p>";
  var xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      document.getElementById("feed-container").innerHTML = this.responseText;
      href4ios();
    }
  };
  if (edit) {
    xhttp.open("POST", "/views/feed/render_edit.php", true);
  } else {
    xhttp.open("POST", "/views/feed/render.php", true);
  }
  xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  console.log(data);
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

/*

    Avoid links exiting ios fullscreen mode

*/

function href4ios() {
  var a = document.getElementsByTagName("a");
  for (var i = 0; i < a.length; i++) {
    if (a[i].onclick == null)
      a[i].onclick = function() {
        var href = this.getAttribute("href");
        if (href != null) {
          console.log(href);
          if (!href.includes("http")) {
            window.location = this.getAttribute("href");
            return false;
          }
        }
      };
  }
}
