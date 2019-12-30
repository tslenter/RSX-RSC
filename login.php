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
<body>
<center>
<br></br>
<img src='<?php echo ($url . "/logo.png"); ?>' width="280" height="280" title="Logo" alt="Logo of RSX" />
</center>
<div class="login">
<h1>Login</h1>
<form method="POST" action="">
        <label for="username">
                <i class="fas fa-user"></i>
        </label>
        <input type="text" name="httpd_username" value="" placeholder="Username" required/>
        <label for="password">
                <i class="fas fa-lock"></i>
        </label>
        <input type="password" name="httpd_password" value="" placeholder="Password" required/>
        <input type="submit" name="login" value="Login" />
</form>
</div>
<center>
echo "RSX 0.1 - T.Slenter - https://www.remotesyslog.com/";
echo "Donate XRP: rHdkpJr3qYqBYY3y3S9ZMr4cFGpgP1eM6B";
</center>
</body>
</html>
