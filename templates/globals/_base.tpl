<html>
<head>
  <meta http-equiv ="Content-Type" content="text/html; charset=utf-8">
  <meta name="keywords" content="{META_KEYWORDS}" />
  <meta name="description" content="{META_DESCRIPTION}" />
  <script type="text/javascript" src="js/jquery-1.7.2.min.js"></script>
  <script type="text/javascript" async="" src="js/ga.js"></script>
  <script type="text/javascript" src="js/jquery.cycle.all.min.js"></script>
  <script type="text/javascript" src="js/jquery.galleriffic.js"></script>
  <script type="text/javascript" src="js/jquery.opacityrollover.js"></script>
  <link rel="stylesheet" href="css/galleriffic.css" type="text/css">
  <link rel="stylesheet" type="text/css" href="/css/main.css">
  <title>{PAGE_TITLE}</title>
</head>
<body>
  <div class="container">
    <div class="header">
      <div class="logo">
	      <a href="{SITE_URL}"><img src="/img/logo.png"></a>
      </div>
      <div class="menu-division">
	     <ul class="menu">
          {MENU}
          <li><a href="/{ALIAS}" class="menu-item-{ID} {ACTIVE}">{TITLE}</a></li>
          {MENU}
        </ul>
      </div>
    </div>
    {PAGE_CONTENT}
    <div class="footer">
      <div class="separate-prefoot">
	      <img src="/img/hr.png">
      </div>
      <div class="menu-division">
      	<ul class="menu">
          {MENU_FOOTER}
      	  <li><a href="/{ALIAS}" class="menu-item-{ID} {ACTIVE}">{TITLE}</a></li>
          {MENU_FOOTER}
      	</ul>
      </div>
    </div>
    <div class="debug" style="color:white">
      {DEBUG}[Time: {TOTAL_TIME} | PHP: {PHP_PERCENTS}%, SQL: {SQL_PERCENTS}% | SQL запросов: {QUERY_AMOUNT} | SQL time: {SQL_TIME} ]{DEBUG}
    </div>
  <!-- Container End -->
  </div>
</body>
</html>
