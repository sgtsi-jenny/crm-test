<?php
  require_once("support/config.php");
  if(!isLoggedIn()){
    toLogin();
    die();
  }

    if(!AllowUser(array(1,2))){
        redirect("index.php");
    }

  $employees=$con->myQuery("SELECT id,CONCAT(last_name,', ',first_name,' ',middle_name,' (',code,')') as employee_name FROM employees WHERE is_deleted=0 ORDER BY last_name")->fetchAll(PDO::FETCH_ASSOC);
  
  ?>