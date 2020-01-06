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
		<title>Remote Syslog Classic: v0.1</title>
	</head>
	<body bgcolor="#000000"; style="color:#00FF00">

        </script>
                <div style="width:100%;">
                                <?php echo "<font color=\"white\">Remote Syslog Classic v0.1<br>More info: </font>"; ?><a href="https://www.remotesyslog.com/" target="_blank" style="color: #FFFF00">https://www.remotesyslog.com/</a>
                                <hr width="100%" noshade></hr>
                                <div style="float:left; width:100%; color:#FFFFFF;" >
                                        <?php if (isset($_POST['button4'])) { header('Location: remote_syslog'); } ?>
                                        <?php if (isset($_POST['button1'])) { exec('logger -n 127.0.0.1 -d "This is a UDP test message!"; logger -T -P 514 -n 127.0.0.1 "This is a TCP test message!"'); } ?>
                                        <?php if (isset($_POST['button2'])) { exec('rm -rf /var/log/remote_syslog/remote_syslog.log.*'); } ?>
                                        <?php if (isset($_POST['button3'])) { header('Location: https://github.com/tslenter/RSX-RSC/blob/master/LICENSE'); } ?>
                                        <?php if (isset($_POST['button10'])) { header('Location: index.php'); } ?>
                                                <form action="index.php" method="post">
							Search live logging: <input type="text" style="width:150px; height:24px; margin-right:5;" name="data" />
                                                	<input type="submit" name="submit" formaction="/rs/indexs.php" style="width:150px; height:24px; margin-right:5;" value="Search" />
                                                        <button type="submit" name="button10" style="width:150px; height:24px; margin-right:5;">Live logging</button>
                                                        <button type="submit" name="button4" style="width:150px; height:24px; margin-right:5;">Syslog archive</button>
                                                        <button type="submit" name="button1" style="width:150px; height:24px; margin-right:5;">Send test message</button>
                                                        <button type="submit" name="button2" style="width:150px; height:24px; margin-right:5;">Clear live log archive</button>
                                                        <button type="submit" name="button3" style="width:150px; height:24px;">License</button>
						<hr width="100%" noshade></hr>
                                                </form>
                                </div>
                                <div style="overflow: scroll; height: 507px; width: 100%;" >
				<pre><?php
					$search = $_POST['data'];
					$lines = file('/var/log/remote_syslog/remote_syslog.log');
					$found = false;
					foreach($lines as $line)
					{
  						if(strpos($line, $search) !== false)
  						{
    							$found = true;
    							echo $line;
  						}
					}
					if(!$found)
					{
						echo 'No match found, search is case-sensitive';
					}
				?></pre>
                                </div>
				<hr width="100%" noshade></hr>
				<?php echo "<font color=\"white\"></font>"; ?>
		</div>
		<div align="center">
				<?php echo "<font color=\"white\">Search completed!</font><br>"; ?>
                </div>
 	</body>
</html>
