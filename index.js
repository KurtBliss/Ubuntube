/*

    ABUTUBE

*/
var feedsObj = feeds();

function executeSearch() {
  window.location.href =
    "/results?q=" + document.getElementById("searchInput").value;
}

function onSearch(event) {
  if (event.key === "Enter") {
    executeSearch();
  } else {
    return false;
  }
}

function homeLoadFeeds(feed_id = "feeds-list") {
  var feedsObj = feeds();
  for (const feed in feedsObj) {
    appendToHomeFeeds(feedsObj, feed, feed_id);
  }
}

// function appendToHomeFeeds(feedsObj, feed, feed_id) {
//   console.log("appendToHomeFeeds > feedsObj", feedsObj);
//   console.log("appendToHomeFeeds > feed", feed);
//   console.log("appendToHomeFeeds > feed_id", feed_id);
//   appendContainer(
//     '<a class="feed-home" target=_self href="/feed/' +
//       feed +
//       '">' +
//       feedsObj[feed]["name"] +
//       "</a>",
//     feed_id
//   );
// }

function appendToHomeFeeds(feedsObj, feed, feed_id = "feeds-list") {
  appendContainer(
    '<a class="feed-home" target=_self href="/feed/' +
      feed +
      '">' +
      feedsObj[feed]["name"] +
      "</a>",
    feed_id
  );
}

/*

    Menu

*/
var menu_visible = false;

function menuButton() {
  var element_main = document.getElementsByTagName("main")[0];
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
    NewFeedButton();
  } else {
    return false;
  }
}

function NewFeedButton() {
  feedsObj = feeds();
  id = document.getElementById("newFeedInput").value;
  feedsObj[id] = { id: id, name: id, sections: [] };
  appendToHomeFeeds(feedsObj, id);
  feeds(feedsObj);
}

function onFeedName(event, feedId, update = false) {
  if (event.key === "Enter") {
    feedsObj = feeds();

    feedsObj[feedId].name =
      document.getElementById("feedNameInput").value || feedId;

    feeds(feedsObj);

    if (update) {
      renderFeed(feeds(), feedId, true);
    }
  } else {
    return false;
  }
}

// Makes new playlist
// function feed_add_single_playlist(getFeed, playlistId, sectionName) {
//   var feedObj = feeds();

//   feed = document.getElementById("select-" + getFeed).value;

//   feedObj[feed]["sections"].push({
//     name: sectionName,
//     playlistId: playlistId,
//     type: "singlePlaylist"
//   });

//   feeds(feedObj);
// }

function feed_add_playlist(getId, playlistId, sectionName) {
  var feedsObj = feeds();
  feed = document.getElementById("select-feed-" + getId).value;
  section = document.getElementById("select-section-" + getId).value;
  if (section == "-1") {
    feedsObj[feed]["sections"].push({
      name: sectionName,
      playlists: [playlistId],
    });
  } else {
    console.log("playlist added", feed, section);
    feedsObj[feed]["sections"][section].playlists.push(playlistId);
  }
  feeds(feedsObj);
}

function feed_add_playlists(getId, playlistIds, sectionName) {
  console.log("feed_add_playlists", getId, playlistIds, sectionName);

  var feedsObj = feeds();
  var feed = document.getElementById("select-feed-" + getId).value;
  var section = document.getElementById("select-section-" + getId).value;

  playlistIds.forEach((elem) => {
    console.log("elem", elem);
    if (section == "-1") {
      console.log("section is -1");
      feedsObj[feed]["sections"].push({
        name: sectionName,
        playlists: [elem],
      });
      section = feedsObj[feed]["sections"].length - 1;
      // section = sectionName;
      console.log("Section -1 complete");
    } else {
      // console.log("feed", feed);
      // console.log("section", section);
      // console.log("feedsObj", feedsObj);
      console.log('feedsObj[feed]["sections"]', feedsObj[feed]["sections"]);
      // console.log(
      //   'feedsObj[feed]["sections"].playlists',
      //   feedsObj[feed]["sections"].playlists
      // );
      feedsObj[feed]["sections"][section].playlists.push(elem);
      console.log("Else  complete");

      // console.log("playlist added", feed, section);
    }
  });

  feeds(feedsObj);
}

function feed_que_button(channelId, buttonId, buttonIcon, channelName) {
  var button = document.getElementById(buttonId);
  var icon = document.getElementById(buttonIcon);
  if (icon.style.color == "blue") {
    icon.style.color = "black";

    var index = this.que.indexOf(channelId);
    if (index !== -1) {
      this.que.splice(index, 1);
    }
    if (que.length == 0) {
      que_name = "";
    }
  } else {
    icon.style.color = "blue";
    que[que.length] = channelId;
    if (que_name == "") {
      que_name = channelName + " Uploads";
    }
  }
}

function feed_add_channel(channelId, container) {
  var xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
      // document.getElementById("feed-container").innerHTML =
      // console.log("response feed_add_channel status 200", this.responseText);

      document.getElementById(container).innerHTML = this.responseText;

      var playlists = [];

      var res = json_decode(this.responseText);

      print("res", res);

      res.forEach((element) => {
        playlists.push(
          element["contentDetails"]["relatedPlaylists"]["uploads"]
        );
      });

      feed_add_playlists("main", playlists, sectionName);

      updateFeedSelect();
      updateSectionSelect(Object.keys(feeds())[0]);

      // href4ios();
    }
  };
  xhttp.open("POST", "/channel-playlist.php", true);
  xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xhttp.send("channelId=" + channelId);
}

function feed_add_channels(getId, channelIds, sectionName) {
  var xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
      var playlists = [];
      var res = JSON.parse(this.responseText);
      res.forEach((element) => {
        playlists.push(element);
      });

      feed_add_playlists(getId, playlists, sectionName);
    }
  };
  xhttp.open("POST", "/channel-playlist.php", true);
  xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  var id_param = "";
  console.log(channelIds);
  channelIds.forEach((elem) => {
    console.log(elem);
    id_param += elem + ",";
  });
  console.log("id_param", id_param);
  xhttp.send("channelIds=" + id_param);
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
  xhttp.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
      document.getElementById("feed-container").innerHTML = this.responseText;
      // href4ios();
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
  // console.log("id", id);
  // console.log("append", append);
  // console.log("document.getElementById(id)", document.getElementById(id));
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

// function href4ios() {
//   var a = document.getElementsByTagName("a");
//   for (var i = 0; i < a.length; i++) {
//     if (a[i].onclick == null)
//       a[i].onclick = function () {
//         var href = this.getAttribute("href");
//         if (href != null) {
//           console.log(href);
//           if (!href.includes("http")) {
//             window.location = this.getAttribute("href");
//             return false;
//           }
//         }
//       };
//   }
// }

/*
  google auth       
  
  Create form to request access token from Google's OAuth 2.0 server.
*/
function oauthSignIn() {
  // Google's OAuth 2.0 endpoint for requesting an access token
  var oauth2Endpoint = "https://accounts.google.com/o/oauth2/v2/auth";

  // Create <form> element to submit parameters to OAuth 2.0 endpoint.
  var form = document.createElement("form");
  form.setAttribute("method", "GET"); // Send as a GET request.
  form.setAttribute("action", oauth2Endpoint);

  // Parameters to pass to OAuth 2.0 endpoint.
  var params = {
    client_id:
      "201589520141-fuqoimont1hli3po8qfo90qc3vto970t.apps.googleusercontent.com",
    redirect_uri: "https://ubuntube.herokuapp.com/process_token",
    response_type: "token",
    scope: "https://www.googleapis.com/auth/youtube.force-ssl",
    include_granted_scopes: "true",
    state: "",
  };

  // Add form parameters as hidden input values.
  for (var p in params) {
    var input = document.createElement("input");
    input.setAttribute("type", "hidden");
    input.setAttribute("name", p);
    input.setAttribute("value", params[p]);
    form.appendChild(input);
  }

  // Add form to page and submit it to open the OAuth 2.0 endpoint.
  document.body.appendChild(form);
  form.submit();
}

// function submit_token(t) {
//   form_get({
//     token: t,
//   });
// }

function form_get(params, endpoint = "/") {
  // Create <form> element to submit parameters to OAuth 2.0 endpoint.
  var form = document.createElement("form");
  form.setAttribute("method", "GET"); // Send as a GET request.
  form.setAttribute("action", endpoint);

  // Add form parameters as hidden input values.
  for (var p in params) {
    var input = document.createElement("input");
    input.setAttribute("type", "hidden");
    input.setAttribute("name", p);
    input.setAttribute("value", params[p]);
    form.appendChild(input);
  }

  // Add form to page and submit it to open the OAuth 2.0 endpoint.
  document.body.appendChild(form);
  form.submit();
}

// Feeds

function updateFeedSelect() {
  var feedsObj = feeds();
  var pickFeed = "";
  for (const feed in feedsObj) {
    pickFeed += "<option value='" + feed + "'>";
    pickFeed += feedsObj[feed]["name"];
    pickFeed += "</option>";
  }

  var elements = document.getElementsByClassName("addToFeed");

  for (var i = 0; i < elements.length; i++) {
    elements[i].innerHTML = pickFeed;
  }
}

function updateSectionSelect(feed) {
  var feedsObj = feeds();
  console.log("updateSectionSelect-> ", feed);
  var sections = feedsObj[feed]["sections"];
  // console.log("got", sections);
  var pickSection = '<option value="-1">As New Section</option>';
  for (const section in sections) {
    // console.log(section);
    pickSection += "<option value='" + section + "'>";
    pickSection += sections[section]["name"];
    pickSection += "</option>";
  }

  // Updated elements
  var elements = document.getElementsByClassName("addToSection");
  for (var i = 0; i < elements.length; i++) {
    elements[i].innerHTML = pickSection;
  }
}

function reloadCss() {
  var links = document.getElementsByTagName("link");
  for (var cl in links) {
    var link = links[cl];
    if (link.rel === "stylesheet") link.href += "";
  }
}

function slide(elem_id = "hor-list") {
  this.sideScroll(document.getElementById(elem_id), "right", 7, 100, 15);
}

function slideBack(elem_id = "hor-list") {
  this.sideScroll(document.getElementById(elem_id), "left", 7, 100, 15);
}

function sideScroll(element, direction, speed, distance, step) {
  let scrollAmount = 0;
  var slideTimer = setInterval(function () {
    if (direction === "left") {
      element.scrollLeft -= step;
    } else {
      element.scrollLeft += step;
    }
    scrollAmount += step;
    if (scrollAmount >= distance) {
      window.clearInterval(slideTimer);
    }
  }, speed);
}
