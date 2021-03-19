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

<html>
        <head>
                <meta charset="utf-8">
                <title>Remote Syslog Classic</title>
        </head>
<body>
<?php
    $location = "";
    header('HTTP/1.1 301 Moved Permanently');
    header('Location: ' . $location . "rsc");
?>
</body>
</html>
