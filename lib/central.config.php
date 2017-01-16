<?php

   

        //Database Connectivity
        	define('DB_HOST','localhost');
     		  define('DB_USER',''); #Please set User
          define('DB_PASS', '');  #Please Set Password
        	define('DB_NAME','tripseats');
        
        //Make connection to DB
        
       $dbcon = mysql_pconnect(DB_HOST,DB_USER,DB_PASS)
        or die("Could not connect:".mysql_error());
        
        //echo "DBCON:".$dbcon."<br>";
        
        if ($dbcon)
        {
          mysql_select_db(DB_NAME,$dbcon)
          or die("Could not find:".mysql_error());
        }
?>