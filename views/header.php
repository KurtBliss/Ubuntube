<?php

global $header;

$header = <<<HTML
<header>
  <div class="headerTopBar">
    <!--   Logo   -->
    <label>
      <a id="nav-toggle" onclick="menuButton(menu_visible)">&#9776;</a>
      <a href="/" class="header-title">
        BLISSFULTUBE
      </a>
    </label>
    
    <!-- Search -->
    <div class="searchWrapper">
      <input id="searchInput" class="searchInput" type="text" placeholder="Search.." onkeypress="onSearch(event)">
      <a onclick="executeSearch()">&#128269;</a>
    </div>
  </div>
</header>
HTML;