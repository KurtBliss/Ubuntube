<?php

global $header;

$header = <<<HTML
<header>
  <!--   Logo   -->
  <label>
    <a href="/" class="header-title">
      BLISSFULTUBE
    </a>
  </label>
  
  <!--  Navigation  -->
  <nav class="header-nav">
    <!-- <a href="/">home</a> | <a>about</a> | <a>contact</a> |  -->
    <input id="searchInput" class="searchInput" type="text" placeholder="Search.." onkeypress="search(event)">
  </nav>
  <hr> 
</header>
HTML;