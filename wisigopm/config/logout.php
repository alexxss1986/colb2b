<?php 
   session_cache_limiter('nocache');
   session_start();
   $_SESSION=array();
   if(isset($_COOKIE[session_name()])){
   	 setcookie(session_name(),'',time()-8760);
   }
   session_destroy();
   echo"<script type=\"text/javascript\">location.replace(\"../index.php\");</script>";

?> 
<noscript>Questo documento contiene codice Javascript che il tuo browser non &egrave; in grado di visualizzare</noscript>