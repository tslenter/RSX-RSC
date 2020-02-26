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
/**
 * Check loggedin
 */
session_start();
if (isset($_SESSION['id']) && $_SESSION['un'] == true) {
/**
 * Just continue if logged in
 */
} else {
    echo "Please log in first to see this page.";
    exit;
}
?>

<?php

class PHPTail {
    /**
     * Location of the log file we're tailing
     * @var string
     */
    private $log = "";
    /**
     * The time between AJAX requests to the server.
     *
     * Setting this value too high with an extremly fast-filling log will cause your PHP application to hang.
     * @var integer
     */
    private $updateTime;
    /**
     * This variable holds the maximum amount of bytes this application can load into memory (in bytes).
     * @var string
     */
    private $maxSizeToLoad;
    /**
     *
     * PHPTail constructor
     * @param string $log the location of the log file
     * @param integer $defaultUpdateTime The time between AJAX requests to the server.
     * @param integer $maxSizeToLoad This variable holds the maximum amount of bytes this application can load into memory (in bytes). Default is 2 Megabyte = 2097152 byte
     */
    public function __construct($log, $defaultUpdateTime = 2000, $maxSizeToLoad = 2097152) {
        $this->log = is_array($log) ? $log : array($log);
        $this->updateTime = $defaultUpdateTime;
        $this->maxSizeToLoad = $maxSizeToLoad;
    }
    /**
     * This function is in charge of retrieving the latest lines from the log file
     * @param string $lastFetchedSize The size of the file when we lasted tailed it.
     * @param string $grepKeyword The grep keyword. This will only return rows that contain this word
     * @return Returns the JSON representation of the latest file size and appended lines.
     */
    public function getNewLines($file, $lastFetchedSize, $grepKeyword, $invert) {

        /**
         * Clear the stat cache to get the latest results
         */
        clearstatcache();
        /**
         * Define how much we should load from the log file
         * @var
         */
        if(empty($file)) {
            $file = key(array_slice($this->log, 0, 1, true));
        }
        $fsize = filesize($this->log[$file]);
        $maxLength = ($fsize - $lastFetchedSize);
        /**
         * Verify that we don't load more data then allowed.
         */
        if($maxLength > $this->maxSizeToLoad) {
            $maxLength = ($this->maxSizeToLoad / 2);
        }
        /**
         * Actually load the data
         */
        $data = array();
        if($maxLength > 0) {

            $fp = fopen($this->log[$file], 'r');
            fseek($fp, -$maxLength , SEEK_END);
            $data = explode("\n", fread($fp, $maxLength));

        }
        /**
         * Run the grep function to return only the lines we're interested in.
         */
        if($invert == 0) {
            $data = preg_grep("/$grepKeyword/",$data);
        }
        else {
            $data = preg_grep("/$grepKeyword/",$data, PREG_GREP_INVERT);
        }
        /**
         * If the last entry in the array is an empty string lets remove it.
         */
        if(end($data) == "") {
            array_pop($data);
        }
        return json_encode(array("size" => $fsize, "file" => $this->log[$file], "data" => $data));
    }
    /**
     * This function will print out the required HTML/CSS/JS
     */
    public function generateGUI() {
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Remote Syslog Classic</title>

<link rel="stylesheet" href="bootstrap.min.css">
<link rel="stylesheet" href="bootstrap-theme.min.css">
<link rel="stylesheet" href="jquery-ui.css" />

<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
    <script src="html5shiv.min.js"></script>
    <script src="respond.min.js"></script>
<![endif]-->

<style type="text/css">

#grepKeyword, #settings {
    font-size: 100%;
}

.float {
    background: white;
    border-bottom: 1px solid black;
    padding: 10px 0 10px 0;
    margin: 0px;
    height: 30px;
    width: 100%;
    text-align: left;
}
.contents {
    margin-top: 30px;
    background: black;
}

.out {
    overflow: scroll;
    scroll-behavior: smooth;
    overflow-y:hidden;
    padding-bottom: 20px;
    font-family: monospace;
    font-size: small;
    white-space: pre;
    background: #212729;
    color: lightgrey;
}

.nav {
    color:grey;
    background-color:silver;
}

.navbar-default {
    background-color:silver;
    border-color:darkgray;
    background-image: none;
    background-repeat: no-repeat;
    color:gray;
}

.navbar-default .navbar-brand {
    color:#2e2e2e;
    background-color:silver;
}

.navbar-default .dropdown-menu {
    color:#2e2e2e;
    background-color:silver;
}

</style>

<script src="jquery.min.js"></script>
<script src="jquery-ui.min.js"></script>

<script type="text/javascript">
    /* <![CDATA[ */
    //Last know size of the file
    lastSize = 0;
    //Grep keyword
    grep = "";
    //Should the Grep be inverted?
    invert = 0;
    //Last known document height
    documentHeight = 0;
    //Last known scroll position
    scrollPosition = 0;
    //Should we scroll to the bottom?
    scroll = true;
    lastFile = window.location.hash != "" ? window.location.hash.substr(1) : "";
    console.log(lastFile);
    $(document).ready(function() {

        // Setup the settings dialog
        $("#settings").dialog({
            modal : true,
            resizable : false,
            draggable : false,
            autoOpen : false,
            width : 590,
            height : 270,
            buttons : {
                Close : function() {
                    $(this).dialog("close");
                }
            },
            open : function(event, ui) {
                scrollToBottom();
            },
            close : function(event, ui) {
                grep = $("#grep").val();
                invert = $('#invert input:radio:checked').val();
                $("#out").text("");
                lastSize = 0;
                $("#grepspan").html("Filter word: \"" + grep + "\"");
                $("#invertspan").html("Inverted: " + (invert == 1 ? 'true' : 'false'));
            }
        });
        //Close the settings dialog after a user hits enter in the textarea
        $('#grep').keyup(function(e) {
            if (e.keyCode == 13) {
                $("#settings").dialog('close');
            }
        });
        //Focus on the textarea
        $("#grep").focus();
        //Settings button into a nice looking button with a theme
        //Settings button opens the settings dialog
        $("#grepKeyword").click(function() {
            $("#settings").dialog('open');
            $("#grepKeyword").removeClass('ui-state-focus');
        });
        $(".file").click(function(e) {
            $("#out").text("");
            lastSize = 0;
            console.log(e);
            lastFile = $(e.target).text();
        });

        //Set up an interval for updating the log. Change updateTime in the PHPTail contstructor to change this
        setInterval("updateLog()", <?php echo $this->updateTime; ?>);
        //Some window scroll event to keep the menu at the top
        $(window).scroll(function(e) {
            if ($(window).scrollTop() > 0) {
                $('.float').css({
                    position : 'fixed',
                    top : '0',
                    left : 'auto'
                });
            } else {
                $('.float').css({
                    position : 'static'
                });
            }
        });
        //If window is resized should we scroll to the bottom?
        $(window).resize(function() {
            if (scroll) {
                scrollToBottom();
            }
        });
        //Handle if the window should be scrolled down or not
        $(window).scroll(function() {
            documentHeight = $(document).height();
            scrollPosition = $(window).height() + $(window).scrollTop();
            if (documentHeight <= scrollPosition) {
                scroll = true;
            } else {
                scroll = false;
            }
        });
        scrollToBottom();

    });
    //This function scrolls to the bottom
    function scrollToBottom() {
        $("html, body").animate({scrollTop: $(document).height()}, "fast");
    }
    //This function queries the server for updates.
    function updateLog() {
        $.getJSON('?ajax=1&file=' + lastFile + '&lastsize=' + lastSize + '&grep=' + grep + '&invert=' + invert, function(data) {
            lastSize = data.size;
            $("#current").text(data.file);
            $.each(data.data, function(key, value) {
                $("#out").append('' + value + '<br/>');
            });
            if (scroll) {
                scrollToBottom();
            }
        });
    }
    /* ]]> */
</script>

</head>
<body style="background-color:#212729;">
    <div class="navbar navbar-default navbar-fixed-top" role="navigation">
        <div class="container">
            <div class="navbar-header">
                <a class="navbar-brand" href="#"><img src="logo_black.png" alt="RS Logo" width="45" height="45" style="margin:-20px 0px; margin-right: 20px;" align="middle"</img>Remote Syslog Classic</a>
            </div>
            <div class="collapse navbar-collapse">
                <ul class="nav navbar-nav">
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Live log<span class="caret"></span></a>
                        <ul class="dropdown-menu" role="menu">
                            <?php foreach ($this->log as $title => $f): ?>
                            <li><a class="file" href="#<?php echo $title;?>"><?php echo $title;?></a></li>
                            <?php endforeach;?>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Options<span class="caret"></span></a>
                        <ul class="dropdown-menu" role="menu">
                           <li><a href="remote_syslog/">Archive</a></li>
                           <li <?php if ($_GET['pg'] == "st") { exec('logger -n 127.0.0.1 -d "This is a UDP test message!"; logger -T -P 514 -n 127.0.0.1 "This is a TCP test message!"'); } ?>><a href="?pg=st">Test message</a></li>
                           <li <?php if ($_GET['pg'] == "ar") { exec('rm -rf /var/log/remote_syslog/remote_syslog.log.*'); } ?>><a href="?pg=ar">Clear live log archive</a></li>
                           <li><a href="https://github.com/tslenter/RSX-RSC/blob/master/LICENSE" target="_blank">License</a></li>
                        </ul>
                    </li>
                    <li><a href="logout.php">Logout</a></li>
                    <li><a href="#" id="grepKeyword">Filter</a></li>
                    <li><span class="navbar-text" id="grepspan"></span></li>
                    <li><span class="navbar-text" id="invertspan"></span></li>
                </ul>
              <p class="navbar-text navbar-right" id="current"></p>
            </div>
        </div>
    </div>
    <br></br>
    <div class="contents">
        <div id="out" class="out"></div>
        <div id="settings" title="RSC Filter">
            <p>Filter word: (return results that contain this keyword)</p>
            <input id="grep" type="text" value="" />
            <p>Should the filter be inverted? (Return results that do NOT contain the keyword)</p>
            <div id="invert">
                <input type="radio" value="1" id="invert1" name="invert" /><label for="invert1">Yes</label>
                <input type="radio" value="0" id="invert2" name="invert" checked="checked" /><label for="invert2">No</label>
            </div>
        </div>
    </div>
    <div align="center">
        <br></br>
        <?php echo "<font color=\"silver\">Remote Syslog Classic v0.1 - <a href='https://github.com/tslenter/RSX-RSC/blob/master/README.md' target='_blank'>Donate and help</a></font><br>"; ?>
        <br></br>
    </div>
<script src="bootstrap.min.js"></script>
</body>
</html>
        <?php
    }
}
