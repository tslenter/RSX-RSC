<?php
/*
License:
"Remote Syslog" is a free application what can be used to view syslog messages.
Copyright (C) 2020 Tom Slenter

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program. If not, see <http://www.gnu.org/licenses/>.

For more information contact the author:
Name author: Tom Slenter
E-mail: info@remotesyslog.com
*/
?>

<!-- Generate variable -->
<?php
        $url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
?>

<!-- Header info -->
<html>
        <head>
                <meta charset="utf-8">
                <title>Login</title>
                <link rel="stylesheet" href='<?php echo ($url . "/fontawesome/css/all.css"); ?>'>
                <link href='<?php echo ($url . "/style.css"); ?>' rel="stylesheet" type="text/css">
        </head>
<!-- Site main -->
<body VLINK= "#000000" ALINK="#000000">
<center>
        <br></br>
        <img src='<?php echo ($url . "/logo.png"); ?>' width="280" height="280" title="Logo" alt="Logo of RSX" />
</center>
<div class="login">
<?php
if (isset($_POST['user']) && isset($_POST['pwd'])) {
    $username = $_POST['user'];
    $password = $_POST['pwd'];
    if (pam_auth($username, $password , $error, $checkacctmgmt = true )) {
        $_SESSION['name'] = $username;
        session_start();
        $r=session_id();
        $un=uniqid();
        echo "the session id: ".$r;
        echo " and the session has been registered for: ".$_SESSION['name'];
        echo "\r\nUnigid is: ".$un;
        $_SESSION['id'] = $r;
        $_SESSION['un'] = $un;
        header( "refresh:5;url=profile.php" );
    } else {
        #echo "<h4>Error: {$error}</h4>";
        echo "<h4>Login Failed ...</h4>";
                renderForm();
    }
} else {
                renderForm();
}

function renderForm()
{
$form = <<<EOT
        <h1>Login</h1>
        <form action="index.php" method="POST">
        <label for="username">
        <i class="fas fa-user"></i>
        </label>
        \t<input name="user" type="text" placeholder="Username" required/>
        <label for="password">
        <i class="fas fa-lock"></i>
        </label>
        \t<input name="pwd"  type="password" placeholder="Password" required/>
        \t<input type="submit" value="Login"/>
</form>
EOT;
    echo $form;
}
?>
</div>
<center>
<?php
echo "RSX 0.1 - T.Slenter - https://www.remotesyslog.com/";
echo "<br />\n";
echo "<br />\n";
echo "<a href='https://github.com/tslenter/RSX-RSC/blob/master/README.md' target='_blank'>Donate and help</a>";
?>
</center>
</body>
</html>
