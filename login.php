<?php

require_once ("include/initialize.php"); 

if (@$_GET['page'] <= 2 or @$_GET['page'] > 5) {

 # code...

   // unset($_SESSION['PRODUCTID']);

   // // unset($_SESSION['QTY']);

   // // unset($_SESSION['TOTAL']);

} 







if(isset($_POST['sidebarLogin'])){

 $email = trim($_POST['U_USERNAME']);

 $upass  = trim($_POST['U_PASS']);

 $h_upass = sha1($upass);

 

  if ($email == '' OR $upass == '') {



     message("Invalid Username and Password!", "error");

     redirect(web_root."index.php");

        

   } else {   

       $cus = new Customer();

       $cusres = $cus::cusAuthentication($email,$h_upass);



       if ($cusres==true){





        header('Location:'.$_SERVER['HTTP_REFERER']);

       }else{

            message("Invalid Username and Password! Please contact administrator", "error");

            redirect(web_root."index.php");

       }



}

}



function containsSqlInjection($str) {
    // Allow common email characters
    $str = preg_replace('/[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}/', '', $str);
    
    $sql_regexes = array(
        "/(\s|'|\"|=|<|>|\(|\)|\{|\}|;|--|\^|\/\*|\*\/|!\d+|_|\%|\\\\)/i",
        "/(union\s+select|select\s+from|insert\s+into|delete\s+from|update\s+set|drop\s+table)/i"
    );
    
    foreach ($sql_regexes as $regex) {
        if (preg_match($regex, $str)) {
            return true;
        }
    }
    return false;
}

function logSqlInjectionAttempt($ip_address) {
    global $mydb;
    $mydb->setQuery("INSERT INTO `tbl_sql_injection_logs` (`ip_address`, `attempt_date`) VALUES ('" . $mydb->escape_value($ip_address) . "', NOW())");
    $mydb->executeQuery();
}


header('Content-Type: application/json');

if(isset($_POST['modalLogin'])) {
    $email = trim($_POST['U_USERNAME']);
    $upass = trim($_POST['U_PASS']);
    $ip_address = $_SERVER['REMOTE_ADDR'];

    if (containsSqlInjection($email) || containsSqlInjection($upass)) {
        logSqlInjectionAttempt($ip_address);
        echo json_encode([
            'status' => 'error',
            'message' => 'Security Breach Detected',
            'sqlInjection' => true
        ]);
        exit;
    }
   
   if ($email == '' OR $upass == '') {
       echo json_encode([
           'status' => 'error',
           'message' => 'Invalid Username and Password!'
       ]);
       exit;
   } else {
       $h_upass = sha1($upass);
       
       global $mydb;
       $mydb->setQuery("SELECT * FROM `tblcustomer` WHERE `CUSUNAME` = '" . $mydb->escape_value($email) . "' AND `CUSPASS` = '" . $mydb->escape_value($h_upass) . "'");
       $cur = $mydb->executeQuery();
       
       if($mydb->num_rows($cur) > 0) {
           $customer_data = $mydb->loadSingleResult();
           
           if (!empty($customer_data->code)) {
               echo json_encode([
                   'status' => 'error',
                   'message' => 'Please verify your email address first.'
               ]);
               exit;
           }
           
           $_SESSION['CUSID'] = $customer_data->CUSTOMERID;
           $_SESSION['CUSNAME'] = $customer_data->FNAME . ' ' . $customer_data->LNAME;
           
           if(empty($_POST['proid'])) {
               echo json_encode([
                   'status' => 'success',
                   'message' => 'Login successful!',
                   'redirect' => web_root . "index.php"
               ]);
           } else {
               $proid = $_POST['proid'];
               $cusid = $_SESSION['CUSID'];
               $mydb->setQuery("INSERT INTO `tblwishlist` (`PROID`, `CUSID`, `WISHDATE`, `WISHSTATS`)
                                VALUES ('$proid', '$cusid', NOW(), 0)");
               $mydb->executeQuery();
               
               echo json_encode([
                   'status' => 'success',
                   'message' => 'Login successful!',
                   'redirect' => web_root . "index.php?q=profile"
               ]);
           }
       } else {
           echo json_encode([
               'status' => 'error',
               'message' => 'Invalid Username and Password!'
           ]);
       }
   }
} else {
   echo json_encode([
       'status' => 'error',
       'message' => 'Invalid request'
   ]);
}
?>