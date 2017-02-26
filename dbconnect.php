<?php

	 $DBhost = "localhost";
	 $DBuser = "root";
	 $DBpass = "eminem2020";
	 $DBname = "ryanProject";
	 
	 $DBcon = new MySQLi($DBhost,$DBuser,$DBpass,$DBname);
    
     if ($DBcon->connect_errno) {
         die("ERROR : -> ".$DBcon->connect_error);
     }
