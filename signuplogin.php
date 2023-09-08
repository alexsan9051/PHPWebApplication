<?php 

    require_once("DB.class.php");

    $db = new DB();

    if (isset($_POST['signup'])) {
        //proccess sign up submission
        $name = $_POST['name'];
        $password = $_POST['pwd'];
        $db->insertUser($name, $password);
    }

    if (isset($_POST['login'])) {
        //process login
        session_start();

        $name = isset($_POST['name']) ? $_POST['name'] : $_SESSION['name'];
        $pwd = isset($_POST['pwd']) ? $_POST['pwd'] : $_SESSION['pwd'];
        
        if(!isset($name)) {
            ?>
            <html>
            <head>
            <title> Please Log In for Access </title>
            <meta http-equiv="Content-Type"
            content="text/html; charset=iso-8859-1" />
            </head>
            <body>
            <h1> Login Required </h1>
            <p>You must log in to access this area of the site. If you are
            not a registered user, <a href="?page=signup">click here</a>
            to sign up for instant access!</p>
            <p><form method="post" action="">
            Name: <input type="text" name="name" size="8" /><br />
            Password: <input type="password" name="pwd" SIZE="8" /><br />
            <input type="submit" name="login" value="Log in" />
            </form></p>
            </body>
            </html>
            <?php
            exit;
            }
        
        $_SESSION['name'] = $name;
        $_SESSION['pwd'] = $pwd;

        $db->checkLogin($name, $pwd);
    }

