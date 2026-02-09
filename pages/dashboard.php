<?php
    if(isset($_SESSION['user_id'])){
    echo $_SESSION['user_id'];
    }
        
    echo 'Level: ' . (isAdmin() ? "Admin" : "Normal User");

    
?>

<h1>Dashboard</h1>