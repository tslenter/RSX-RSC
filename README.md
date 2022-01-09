[![published](https://static.production.devnetcloud.com/codeexchange/assets/images/devnet-published.svg)](https://developer.cisco.com/codeexchange/github/repo/tslenter/RSX-RSC)
[![Website remotesyslog.com](https://img.shields.io/website-up-down-green-red/http/shields.io.svg)](https://www.remotesyslog.com/)
[![GitHub issues](https://img.shields.io/github/issues/Naereen/StrapDown.js.svg)](https://github.com/tslenter/RSX-RSC/issues)
[![GPLv3 license](https://img.shields.io/badge/License-GPLv3-blue.svg)](http://perso.crans.org/besson/LICENSE.html)
## News:
09-01-2022: https://www.github.com/tslenter/RS has RSCCORE for the Remote Syslog Classic ready for test download.

08-01-2022: https://www.github.com/tslenter/RS has RSX for the RSE core ready for test download.

06-01-2022: Repository has been updated. If run in any trouble of running updates then update the /etc/apt/sources.list.d/syslog-ng.list with:
```
For Ubuntu 20.04: deb https://cloud.remotesyslog.com/xUbuntu_20.04 ./
And run: wget -qO - https://cloud.remotesyslog.com/xUbuntu_20.04/Release.key | /usr/bin/apt-key add -
For ubuntu 18.04: deb https://cloud.remotesyslog.com/xUbuntu_18.04 ./
And run: wget -qO - https://cloud.remotesyslog.com/xUbuntu_18.04/Release.key | /usr/bin/apt-key add -
```

28-12-2021: 4logj instruction for mitigation: https://github.com/tslenter/RS4LOGJ-CVE-2021-44228/

11-08-2021: New version RSE in testing phase. More information: https://github.com/tslenter/RS or https://www.remotesyslog.com/en/

29-07-2021: New CLI syslog viewer: More information: https://github.com/tslenter/rseview. Usable with elasticsearch and the Remote Syslog concept. Currently in BETA.

05-07-2021: Chinese webpage on-line: https://www.remotesyslog.com/ch/ / English can be found here: https://www.remotesyslog.com/en/

For more news check our linkedin page: https://www.linkedin.com/company/remote-syslog/

## 1. License

"Remote Syslog" is a free application that can be used to view syslog messages.

Copyright (C) 2021 Tom Slenter

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

## 2. Versions

RSX is a syslog-ng - elasticsearch - kibana driven syslog server. This
combination allows you to dump and store a lot of syslog messages with almost
no performance decrease in searches. RSX has multiple enterprise grade options.

RSC is a syslog-ng - CLI - PHP GUI driven syslog server. This combination is for 
low powered dives like a Rapspberry Pi and for small environments. Depending on the
functionality RSC will run fine with more then 1000 devices, but tuning is required.

RS Core is a syslog-ng - CLI driven syslog server. This environment can be used in
small/lab/test environments. It is very small and compact. The setup can be done within 
within minutes.

## 3. Config files
Default locations configuration/files:
```
Syslog-ng global config:       /etc/syslog-ng/syslog-ng.conf
Syslog-ng additional configs:  /etc/syslog-ng/conf.d/99*
Kibana global config:          /etc/kibana/kibana.yml
Elasticsearch global config:   /etc/elasticsearch/elasticsearch.yml
Logstash global config:        /etc/logstash/logstash.yml
Logstash additional configs:   /etc/logstash/conf.d/99*
Logrotate:                     /etc/logrotate.d/remotelog
Syslog-ng logrotate:           /etc/logrotate.d/syslog-ng
Colortail global:              /opt/remotesyslog/colortail
Filebeat global:               /etc/filebeat/filebeat.yml
Filebeat Cisco:                /etc/filebeat/modules.d/cisco.yml
Filebeat netflow:              /etc/filebeat/modules.d/netflow.yml
```

## 4. RSE Development version
RSE is a new version of Remote Syslog which has the same functionality of RSC but the backend is running Elasticsearch. It can be tested within the following setup:

Confirmed setups:
```
Tested for Ubuntu 20.04 LTS virtual machine.
Tested for Ubuntu 21.04 Raspberry Pi 4 (4GB RAM)
```

Quick installation:
```
git clone https://www.github.com/tslenter/RS
cd RS
chmod +x rseinstaller
sudo ./rseinstaller
Option 1 => RSE Core installation
Option 1 => Core installation

sudo ./rseinstaller
Optional webinsterface:
Option 2 => RSE webinterface installation
Option 2 => Install RSE WEB
```

Use SSH to run the CLI commands. Updated commands:
```
rseview
rseinstaller
rseuser
```

Webinterface is running @ port 443 (SSL)

## 5. Security
All external connections are encrypted with TLS/SSL, this includes the API on port 8080, SSH and HTTP for user login. Authentication is run by the PAM modules, so all users with a account can login. To restrict user login use the apache2 configuration and add all the users that are allowed to login. 

To update the certificates, copy the new certificates to the following directory:
```
/etc/cert/
```

After you installed the new certificates, update the apache2 configuration. File location:
```
/etc/apache2/sites-enabled/
```

## 6. Installation
### 6.1 Quick start
a. Install a clean debian 9.x or Ubuntu 20.04.2 LTS distro.

b. Run the following commands:

```bash
git clone https://github.com/tslenter/RSX-RSC.git
cd RSX-RSC
chmod +x rsinstaller
./rsinstaller
Choose option 1 to install the core
Choose option 10 to install the RSC version (Remote Syslog Classic)
Choose option 12 to install the RSX version
```
c.  RSX is only supported on Ubuntu 18.04.2 or higher and Debian 10.x or higher

### 6.2 Local user management
The "rsuser" command is used to add or remove users from Remote Syslog X/C.

Add a user without cli access:
```bash
rsuser <username> add web-only 
```

Add a user with cli access:
```bash
rsuser <username> add
```

Remove a user:
```bash
rsuser <username> rm
```

### 6.3 RSX Cluster
With build 52 of RSX 0.1 clustering is supported. RSX will load the default configuration. Feel free to add some best practice option, found here:
```
https://logz.io/blog/elasticsearch-cluster-tutorial/
```
Check the cluster health by running the following command:
```bash
curl -XGET -H "Content-Type: application/json" http://localhost:9200/_cluster/health?pretty=true
```
Expected output:
```
{
  "cluster_name" : "rsx",
  "status" : "green",
  "timed_out" : false,
  "number_of_nodes" : 3,
  "number_of_data_nodes" : 3,
  "active_primary_shards" : 10,
  "active_shards" : 20,
  "relocating_shards" : 0,
  "initializing_shards" : 0,
  "unassigned_shards" : 0,
  "delayed_unassigned_shards" : 0,
  "number_of_pending_tasks" : 0,
  "number_of_in_flight_fetch" : 0,
  "task_max_waiting_in_queue_millis" : 0,
  "active_shards_percent_as_number" : 100.0
}
```

## 7. Optional configuration
### 7.1 Integrate Active Directory LDAP authentication for Apache 2

Activate LDAP module apache:
```bash
a2enmod ldap authnz_ldap
```

Configure /etc/apache2/apache2.conf as following:
```bash
<Directory /var/www/html>
AuthType Basic
AuthName "Remote Syslog Login"
Options Indexes FollowSymLinks
AllowOverride None
AuthBasicProvider ldap
AuthLDAPGroupAttributeIsDN On
AuthLDAPURL "ldap://<myadhost>:389/dc=DC01,dc=local?sAMAccountName?sub?(objectClass=*)"
AuthLDAPBindDN "CN=,OU=Accounts,DC=DC01,DC=local"
AuthLDAPBindPassword
AuthLDAPGroupAttribute member
require ldap-group cn=,ou=Groups,dc=DC01,dc=local
</Directory>
```

### 7.2 Basic authentication for Apache 2

Install apache2-utils:
```bash
apt-get install apache2-utils
```

Create .htpasswd file:
```bash
htpasswd -c /etc/apache2/.htpasswd <myuser>
```

Configure /etc/apache2/apache2.conf as following:
```bash
<Directory /var/www/html>
AuthType Basic
AuthName "Remote Syslog Login"
AuthBasicProvider file
AuthUserFile "/etc/apache2/.htpasswd"
Require user
Options Indexes FollowSymLinks
AllowOverride None
Require valid-user
Order allow,deny
Allow from all
</Directory>
```

### 7.3 Active Directory integration via PAM
Run commands as root:
```bash
su -
```
Upgrade distro:
```bash
apt-get update && apt upgrade -y
```

Install packages:
```bash
apt-get install realmd packagekit sssd-tools sssd libnss-sss libpam-sss adcli oddjob oddjob-mkhomedir adcli samba-common ntpdate ntp unzip resolvconf git -y
```

Enable DNS service:
```bash
systemctl start resolvconf.service
systemctl enable resolvconf.service
systemctl status resolvconf.service
```

Configure DNS service:
```bash
nano /etc/resolvconf/resolv.conf.d/head
```

Add:
```
nameserver <ip dnsserver domeincontroller>
```

Reload DNS service:
```bash
systemctl restart resolvconf.service
```

Check if domain controller connection:
```bash
ping dom001.lan.local
```

Join controller:
```bash
realm join --user=administrator lan.local --verbose
```

Expected output:
```
* Successfully enrolled machine in realm
```

Edit sssd deamon:
```bash
nano /etc/sssd/sssd.conf
```

Edit configuration:
```
[sssd]
domains = LAN.LOCAL
config_file_version = 2
services = nss, pam, sudo
default_domain_suffix = lan.local
full_name_format = %1$s

[domain/lan.local]
ad_domain = lan.local
krb5_realm = LAN.LOCAL
realmd_tags = manages-system joined-with-adcli
cache_credentials = True
id_provider = ad
krb5_store_password_if_offline = True
default_shell = /bin/bash
ldap_id_mapping = True
use_fully_qualified_names = True
fallback_homedir = /home/%u@%d
#Restict AD search:
#ldap_search_base = DC=lan,DC=local
#ldap_user_search_base OU=Power Users,OU=Accounts,DC=lan,DC=local
#ldap_group_search_base OU=Groups,DC=lan,DC=local
access_provider = simple
simple_allow_groups = <ad group 1>, <ad group 2>
manage-system = yes
automatic-id-mapping = yes
```

Reload sssd deamon:
```bash
service sssd restart
```

Configure PAM to auto create home folder:
```bash
nano /etc/pam.d/common-session
```

Add:
```
session    required    pam_mkhomedir.so skel=/etc/skel/ umask=0022
```

Grant root rights (only ubuntu):
```bash
nano /etc/sudoers
```

Add:
```
%<add ad group here> ALL=(ALL:ALL) ALL
```

To add a additional group use the following command:
```bash
realm permit -g <groepnaam>@lan.local
```

Secure apache2 login:
```bash
nano /etc/apache2/sites-enabled/rsx-apache.conf
```

Change the following configuration:
```
Change in all 3 location blocks:
                Require valid-user
                #Require user user1 user2 user3
#To:
                #Require valid-user
                Require user test01 <<-- username
```

Reload apache2 services:
```bash
service apache2 restart
```

Login or continue the RSX/RSC installation.
Default logout link: 
```
https://<ip or dns>/logout
```

## 8. Search strings CLI

### 8.1 Search multiple strings of text within the per_host logging directory
```bash
grep -h "switch1\|switch2\|switch3" /var/log/remote_syslog/* | more
```

### 8.2 Search for the top 15 messages
```bash
egrep -o "%.+?: "/var/log/remote_syslog/remote_syslog.log | sort | uniq -c | sort -nr | head -n 15
```

## 9. Generate an email from an event
### 9.1 Install netsend
```bash
sudo apt install sendmail
```

Edit:
```bash
/etc/mail/sendmail.cf
```

Search for => #"Smart" relay host (may be null)
Change after DS => DSsmtp.lan.corp

### 9.2 Use the following script and save it to /opt/mailrs

Create array:
```bash
#!/bin/bash
#Array of words:
declare -a data=(Trace module)
```

Check if error messages exist:
```bash
for word in "${data[@]}"; do
    mesg=$(cat /var/log/remote_syslog/remote_syslog.log | grep "^$(date +'%b %d')" | grep $word)
    if [ -z "$mesg" ]
    then
        echo "No variable!"
    else
        echo "Variable filled, setting variable to continue …"
        mesgall=1
    fi
done
```

Generate email:
```bash
if [ -z "$mesgall" ]
then
    echo "Nothing to do, abort"
    exit
else
    echo "Subject: Syslog critical errors" > /opt/rs.txt
    echo "" >> /opt/rs.txt
    echo "Hello <user>," >> /opt/rs.txt
    echo "" >> /opt/rs.txt
    echo "The following message is generated by Remote Syslog." >> /opt/rs.txt
    echo "" >> /opt/rs.txt
    for word in "${data[@]}"; do
        cat /var/log/remote_syslog/remote_syslog.log | grep "^$(date +'%b %d')" | grep $word >> /opt/rs.txt
    done
    echo "" >> /opt/rs.txt
    echo "The messages above are generated by the <hostname>!" >> /opt/rs.txt
    echo "" >> /opt/rs.txt
    echo "Thank you for using Remote Syslog … ;-)" >> /opt/rs.txt
    cat /opt/rs.txt
    /usr/sbin/sendmail -v -F "T.Slenter" -f "info@mydomain.com" ticketsystem@domain.com < /opt/rs.txt
fi
```

Make file executable:
```bash
chmod +x /opt/mailrs
```

### 9.3 Install with cron
Command:
```bash
crontab -e
```

Edit:
```bash
0 * * * * /opt/mailrs
```

## 10. Known issues

### 10.1 Disk full by Geo2
Message in logging:
```bash
Jan 27 10:24:50 plisk002.prd.corp syslog-ng[1793]: geoip2(): getaddrinfo failed; gai_error='Name or service not known', ip='', location='/etc/syslog-ng/conf.d/99X-Checkpoint.conf:32:25'
Jan 27 10:24:50 plisk002.prd.corp syslog-ng[1793]: geoip2(): maxminddb error; error='Unknown error code', ip='', location='/etc/syslog-ng/conf.d/99X-Checkpoint.conf:32:25'
```

Components needed for fix:

File: /etc/syslog-ng/syslog-ng.conf

File destinations: 
- d_syslog
- d_error

Log rules:
```bash
- log { source(s_src); filter(f_syslog3); destination(d_syslog); };
- log { source(s_src); filter(f_error); destination(d_error); };
```

Fix:
Edit:
```bash
vi /etc/syslog-ng/syslog-ng.conf
```

Add rules:
```bash
filter geoip_messages_1 { not match("Name or service not known"); };
filter geoip_messages_2 { not match("Unknown error code"); };
```

Change rules:
```bash
-log { source(s_src); filter(f_syslog3); destination(d_syslog); };
-log { source(s_src); filter(f_error); destination(d_error); };
+log { source(s_src); filter(f_syslog3); filter(geoip_messages_1); filter(geoip_messages_2); destination(d_syslog); };
+log { source(s_src); filter(f_error); filter(geoip_messages_1); filter(geoip_messages_2); destination(d_error); };
```

### 10.2 Kibana not loaded after upgrade
Restarting the server will solve this problem. Some report that a restart of the Kibana or Elasticsearch will fix the issue.
```bash
service elasticsearch restart
service kibana restart
```

### 10.3 Data too large, data for [<http_request>] (JVM heap size)
Error message:
```bash
tom@plisk002:~$ curl -X GET 'http://localhost:9200/_cat/health?v'
{"error":{"root_cause":[{"type":"circuit_breaking_exception","reason":"[parent] Data too large, data for [<http_request>] would be [1014538592/967.5mb], which is larger than the limit of [986061209/940.3mb], real usage: [1014538592/967.5mb], new bytes reserved: [0/0b], usages [request=0/0b, fielddata=3057213/2.9mb, in_flight_requests=0/0b, accounting=261018719/248.9mb]","bytes_wanted":1014538592,"bytes_limit":986061209,"durability":"PERMANENT"}],"type":"circuit_breaking_exception","reason":"[parent] Data too large, data for [<http_request>] would be [1014538592/967.5mb], which is larger than the limit of [986061209/940.3mb], real usage: [1014538592/967.5mb], new bytes reserved: [0/0b], usages [request=0/0b, fielddata=3057213/2.9mb, in_flight_requests=0/0b, accounting=261018719/248.9mb]","bytes_wanted":1014538592,"bytes_limit":986061209,"durability":"PERMANENT"},"status":429}
```

Increase memory fix:
```bash
nano /etc/elasticsearch/jvm.options
```

Edit:
```bash
--Xms1g
--Xmx1g
+-Xms6g
+-Xmx6g
```

### 10.4 Syslog-NG 3.27.1 breaks with new upgrade on Ubuntu 18.04 and 20.04
Error message:
```bash
dpkg: error processing package syslog-ng-mod-sql (--configure):
 dependency problems - leaving unconfigured
dpkg: dependency problems prevent configuration of syslog-ng-mod-redis:
 syslog-ng-mod-redis depends on syslog-ng-core (>= 3.27.1-2); however:
  Package syslog-ng-core is not configured yet.
 syslog-ng-mod-redis depends on syslog-ng-core (<< 3.27.1-2.1~); however:
  Package syslog-ng-core is not configured yet.
```
Fix:

Backup configuration
```bash
mkdir ~/syslog-ng_backup/
cp -rf /etc/syslog-ng/* ~/syslog-ng_backup/
```
Verify configuration
```bash
ls ~/syslog-ng_backup/
```
Purge syslog-ng and remove everything
```bash
sudo apt purge syslog-ng-core
```
If some files remain, delete them all
```bash
rm -rf /etc/syslog-ng
```
Reinstall syslog-ng-core
```bash
sudo apt install syslog-ng-core
```
Reinstall syslog-ng
```bash
sudo apt install syslog-ng
```
Cleanup some packages
```bash
sudo apt auto-remove
```
Restore RS configuration files
```bash
cp ~/syslog-ng_backup/conf.d/99* /etc/syslog-ng/conf.d/
```
If you edited the /etc/syslog-ng/syslog-ng.conf file, check the difference and restore your custom configuration.

This issue should be fixed in version 3.27.1-2.1.

### 10.5 My elasticsearch does not recieve any logging, but everything is fine
You probably should check the date. If the date is not correct run in the CLI as root:
dpkg-reconfigure tzdata

This allows you to configure the timezone.

The next thing to check is within the Kibana console
Management => Advanced Settings => Timezone for date formatting => setup the right timezone.

## 11. Default API queries for Elasticsearch
Find all indexes:
```bash
curl -XGET 'localhost:9200/_cat/indices'
```
Find cluster disk space:
```bash
curl -XGET 'localhost:9200/_cat/allocation?v&pretty'
```

## 12. Configuration checks
Logstash test new config: 
```bash
/usr/share/logstash/bin/logstash --config.test_and_exit -f /etc/logstash/conf.d/97-rsmdefault.conf --path.settings /etc/logstash/
```

## 13. Upgrades

### 13.1 Upgrade from Remote Syslog 1.x
Manual remove Remote Syslog 1.x with the following bash script:
```bash
echo "File is only present if local syslog is activated"
rm -rf /etc/syslog-ng/conf.d/99-remote-local.conf
echo "Remove configuration files"
rm -rf /etc/syslog-ng/conf.d/99-remote.conf
rm -rf /etc/logrotate.d/remotelog
rm -rf /etc/colortail/conf.colortail
rm -rf /opt/remotesyslog
echo "Remove binary files"
rm -rf /usr/bin/rsview
rm -rf /usr/bin/rsinstaller
echo "Removing legacy GUI website …"
rm -rf /var/www/html/favicon.ico
rm -rf /var/www/html/index.php
rm -rf /var/www/html/indexs.php
rm -rf /var/www/html/jquery-latest.js
rm -rf /var/www/html/loaddata.php
echo "Remove packages …"
apt -y purge apache2 apache2-utils php libapache2-mod-php syslog-ng colortail
apt -y autoremove
echo "Reinstall rsyslog"
apt -y install rsyslog
```
After the removal of Remote Syslog 1.x, install the new RSX or RSC. The old syslog data is still available through RSC or RSX but only in plain text.

More information over Remote Syslog 1.x: https://github.com/tslenter/Remote_Syslog

### 13.2 Upgrade from Ubuntu 18.04 to 20.04
First update rsinstaller:
```bash
rsinstaller
Select option: 3
```
Build 56 or higher is recommended.

Upgrade commands:
```bash
apt update && sudo apt upgrade
#You probably run in a syslog-ng rdkafka error. This will stop the installation. Therefore we added "apt install -f".
#This only effects version 3.27.1 and was fixed in 3.27.1-2.
apt install -f
reboot
apt install update-manager-core
do-release-upgrade -d
```
It appears that the package "syslog-ng-mod-rdkafka" has some conflics with the core configuration, If you run in this error, try to uninstall this package:
```bash
#This only effects version 3.27.1 and was fixed in 3.27.1-2.
apt remove syslog-ng-mod-rdkafka
```
After the upgrade there is a issue with the Apache2 configuration:
Edit the following file: /etc/apache2/mods-enabled/php7.2.load and change:
```
-LoadModule php7_module /usr/lib/apache2/modules/libphp7.2.so
+LoadModule php7_module /usr/lib/apache2/modules/libphp7.4.so
```
Check to /var/log/syslog for errors. We found 2 errors and this depends on which platform you run the RSX server.
Error 1 || DNS message:
```
Apr 30 20:56:22 lusysl003 systemd-resolved[923]: Server returned error NXDOMAIN, mitigating potential DNS violation DVE-2018-0001, retrying transaction with reduced feature level UDP
```
Recreate symlink will fix this issue:
```
ln -sfn /run/systemd/resolve/resolv.conf /etc/resolv.conf

or

rm /etc/resolv.conf
ln -s /run/systemd/resolve/resolv.conf /etc/resolv.conf
```
Error 2 || If you run the server on ESXi you get the following error:
```
Apr 30 12:47:53 plisk001.prd.corp multipathd[856]: sdb: add missing path
Apr 30 12:47:53 plisk001.prd.corp multipathd[856]: sdb: failed to get udev uid: Invalid argument
Apr 30 12:47:53 plisk001.prd.corp multipathd[856]: sdb: failed to get sysfs uid: Invalid argument
Apr 30 12:47:53 plisk001.prd.corp multipathd[856]: sdb: failed to get sgio uid: No such file or directory
```
Edit the following file /etc/multipath.conf to fix this issue:
```
+blacklist {
+    device {
+        vendor "VMware"
+        product "Virtual disk"
+    }
+}
```
After that restart the deamon:
```
systemctl restart multipath-tools
```
Reactivate repo:
```
wget -qO - https://artifacts.elastic.co/GPG-KEY-elasticsearch | apt-key add -
apt-get install apt-transport-https -y
echo "deb https://artifacts.elastic.co/packages/7.x/apt stable main" | tee -a /etc/apt/sources.list.d/elastic-7.x.list
echo "deb https://artifacts.elastic.co/packages/oss-7.x/apt stable main" | tee -a /etc/apt/sources.list.d/elastic-7.x.list

wget -qO - https://download.opensuse.org/repositories/home:/laszlo_budai:/syslog-ng/xUbuntu_20.04/Release.key | /usr/bin/apt-key add -
echo deb http://download.opensuse.org/repositories/home:/laszlo_budai:/syslog-ng/xUbuntu_20.04 ./ > /etc/apt/sources.list.d/syslog-ng.list
apt update
apt install syslog-ng-mod-snmp syslog-ng-mod-freetds syslog-ng-mod-json syslog-ng-mod-mysql syslog-ng-mod-pacctformat syslog-ng-mod-pgsql syslog-ng-mod-snmptrapd-parser syslog-ng-mod-sqlite3
sudo apt autoremove
```

### 13.3 Ubuntu upgrade policy
For Ubuntu we only test the latest LTS version. At the time of writing this is 20.04 LTS. The next release will be 22.04 LTS.

## 14. Information and external links

More information: https://www.remotesyslog.com/

Find more plugins: https://github.com/syslog-ng/syslog-ng/tree/master/scl

## 15. Donation and help

### 15.1 Donation

Crypto:

```
XRP/Ripple: rHdkpJr3qYqBYY3y3S9ZMr4cFGpgP1eM6B
BTC/Bitcoin: 1JVmexqGBQyGv9fVkSynHapi2U6ZCyjTUJ
LTC/Litecoin Segwit: MAH8ATCK6X7biiTQrW7jUZ6L9eg1YBo5qS
ETH/Ethereum: 0xd617391076F9bEa628f657606DEAB7a189199AF5
```
PayPal:

[![paypal](https://www.paypalobjects.com/en_US/NL/i/btn/btn_donateCC_LG.gif)](https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=KQKRPDQYHYR7W&currency_code=EUR&source=url)

### 15.2 Help

To improve the code and functions we like to have you help. Send your idea or code to: info@remotesyslog.com or create a pull request. We will review it and add it to this project.

### 15.3 What is a RSCX token?
RSCX is created to reward developers for their work and to support the project. It is a tradable token by the Waves exchange. It comes with no warranty and the price indication is based on the live market. Sending funds to the wrong address will result in a loss of those funds. We do not refund RSCX tokens. We suggest that you use a hardware token to secure the RSCX tokens. Good luck trading and have fun!

### 15.4 RSCX token
We have a reward system in place, Remote Syslog has it own token available called RSCX. How to get RSCX?

Send usable code/patterns to info@remotesyslog.com or create a pull request. We will review the code or pattern, this may take some time.

Expected payout RSCX:
 - Patterns: minimum of 20 RSCX
 - Code fixes: minimum of 20 RSCX
 - Security fixes: minimum of 40 RSCX
 - New functionality like a plugin: minimum of 45 RSCX
 - Setup a marketing campagne: minimum of 45 RSCX
 - Advisor payout: minimum of 45 RSCX
 - Test functionality: minimum of 5 RSCX
 - Rewards can be higher depending on the quality of the delivered work
 - Bounty programs may be available in future
 
Note 1: code that is useless will not have a reward.

RSCX distribution:

10 million RSCX tokens are created. Distribution:

 - 4 million RSCX is available for developers (40%)
 - 0.2 million RSCX is available for testers (2%)
 - 2 million RSCX is available for marketing (20%)
 - 0.2 million RSCX is available for advisors (2%)
 - 1 million RSCX is available for investors (10%)
 - 2.6 million RSCX is reserved for future use/insurance/burn (26%)

Where to trade RSCX:

https://waves.exchange/dex-demo?assetId2=CqWLkpZ47CQLjtojz8S14Ao1xsv7i3zue2aWLcH6RJoG&assetId1=8LQW8f7P5d5PZM7GtZEBgaqRPGSzS3DfPuiXrURJ4AJS

Trading pairs:
```
RSCX / WAVES
RSCX / USDN
RSCX / USDT
RSCX / BTC
RSCX / BCH
RSCX / BSV
RSCX / ETH
RSCX / TRY
RSCX / LTC
RSCX / ZEC
RSCX / XMR
RSCX / DASH
BNT / RSCX
RSCX / ERGO
RSCX / WEST
WCT / RSCX
RSCX / WNET
RSCX / EFYT
RSCX / MRT
RSCX / LIQUID
```

A waves account is needed to recieve RSCX tokens.

### 15.5 Funds
All donations and other funds will be used to cover cost of this project and to improve tests/plugins/core scripts. The roadmap will display new functions or products. Check https://www.remotesyslog.com for more information.

### 16 Tips for RSX
- Make a good lifecycle policy to prevent a full disk (Just monitor it for some time).
- By default text file storage is enabled. When using the elastic-based stack you can disable this by editing the syslog-ng config. (If this is disabled the "rsview" command gives no new output). Text-based storage use can be lowered by the RSX installer.
- Everything is built in a block structure so you can disable default services and add your own. This block structure allows you to disable the RSX concept and go an elastic stack setup, or something else.
- By default everything Syslog can be received at port 514 UDP/TCP, we do recommend 514/UDP and no 514/TCP (UDP is faster and we recommend that for all services).
- RSX is capable of running a cluster setup, we recommend a cluster of 3 for full redundancy. When running a cluster make sure you create a good update plan.
- By default we use Syslog-ng as core, this parses all syslog data. If you like to create fields for smart searches within the Kibana interface this is required.
- Check out the active directory (AD) setup. The RSX authentication page works with Linux PAM. PAM can beconfigured to use a AD.
- The default login name for RSX is the created with the Debian/Ubuntu installation. Do NOT use a root user! If you only created a root user, create a noraml user account for the login.
- We do have some patterns preconfigured. (CheckPoint, Cisco, Microsoft, F5,  and more) You probably need to edit them to match the infrastructure.
- Because we use open source software everything is free and patterns can be found with a good google search as well.
