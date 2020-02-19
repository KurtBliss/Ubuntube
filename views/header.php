<?php

global $header;

$header = <<<HTML
<header>
  <!--   Logo   -->
  <label>
    <p class="header-title">
      BLISSFULTUBE
    </p>
  </label>
  
  <!--  Navigation  -->
  <nav class="header-nav">
    <a>home</a> | <a>about</a> | <a>contact</a> | <input id="searchInput" type="text" placeholder="Search.." onkeypress="search(event)">
  </nav>
  <hr> 
</header>
HTML;