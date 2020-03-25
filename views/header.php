<?php

global $header;

$header = <<<HTML
<header>
  <!--   Logo   -->
  <label>
    <a id="nav-toggle" onclick="menuButton()">&#9776;</a>
    <a href="/" class="header-title">
      BLISSFULTUBE
    </a>
  </label>
  
  <!-- Search -->
  <!-- <div class="searchWrapper"> -->
    <input id="searchInput" class="searchInput" type="text" placeholder="Search.." onkeypress="search(event)">
  <!-- </div> -->



</header>
HTML;