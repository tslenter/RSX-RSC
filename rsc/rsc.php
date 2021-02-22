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

Enhanced with PHPTail
Found @ https://github.com/taktos/php-tail
Forked from: http://code.google.com/p/php-tail/
*/
?>

<?php
session_start();
if (isset($_SESSION['id']) && $_SESSION['un'] == true) {
    /**
     * Require the library
     */
    require 'PHPTail.php';
    /**
     * Initilize a new instance of PHPTail
     * @var PHPTail
     */

    $tail = new PHPTail(array(
        "Remote Syslog" => "/var/log/remote_syslog/remote_syslog.log",
    ));

    /**
     * We're getting an AJAX call
     */
    if(isset($_GET['ajax']))  {
        echo $tail->getNewLines($_GET['file'], $_GET['lastsize'], $_GET['grep'], $_GET['invert']);
        die();
    }

    /**
     * Regular GET/POST call, print out the GUI
     */
     $tail->generateGUI();
} else {
    echo "<center>Please log in first to see this page.</center>";
    echo "<br>";
    echo '<center><a href="/rsc">Return to login</a></center>';
    echo '<meta http-equiv="refresh" content="10.01;/rsc"/>';
}
?>
