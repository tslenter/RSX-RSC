## 1. License

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

## 2. Versions

RSX is a syslog-ng - elasticsearch - kibana driven syslog server. This
combination allows you to dump and store a lot syslog messages with almost
no performance decrease in searches. RSX has multiple enterprise grade options.

RSC is a syslog-ng - CLI - PHP GUI driven syslog server. This combination is for 
low powered dives like a Rapspberry Pi and for small environments. Depending on the
functionality RSC will run fine with more then 1000 devices, but tuning is required.

RS Core is a syslog-ng - CLI driven syslog server. This environment can be used to
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
```

## 4. RS version 2.0 Premium
This version was announced but did not pass quality standards. Therefor
it is postponed.

## 5. Security
All external connections are encrypted with TLS/SSL, this includes the API on port 8080, SSH and HTTP for user login. Authentication is run by the PAM modules, so all users with a account can login. To restrict user login use the apache2 configuration and add the all allowed users for login. 

To update the certificates, copy the new certificates to the following directory:
```
/etc/cert/
```

After you installed the new certificates, update the apache2 configuration. File location:
```
/etc/apache2/sites-enabled/
```

## 6. Installation
a. Install a clean debian 9.x or Ubuntu 18.04.2 distro

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

## 8. Search multiple strings of text within the per host logging directory
```bash
grep -h "switch1\|switch2\|switch3" /var/log/remote_syslog/* | more
```

## 9. Generate a mail from a event
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

Generate mail:
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

## 11. Default API query's for Elasticsearch
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
## 13. Upgrade from Remote Syslog 1.x

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

## 13. Information and external links

More information: https://www.remotesyslog.com/

Find more plugins: https://github.com/syslog-ng/syslog-ng/tree/master/scl

## 14. Donation and help

### 14.1 Donation

Crypto:

```
XRP/Ripple: rHdkpJr3qYqBYY3y3S9ZMr4cFGpgP1eM6B
BTC/Bitcoin: 1JVmexqGBQyGv9fVkSynHapi2U6ZCyjTUJ
LTC/Litecoin Segwit: MAH8ATCK6X7biiTQrW7jUZ6L9eg1YBo5qS
ETH/Ethereum: 0xd617391076F9bEa628f657606DEAB7a189199AF5
```
PayPal:

[![paypal](https://www.paypalobjects.com/en_US/NL/i/btn/btn_donateCC_LG.gif)](https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=KQKRPDQYHYR7W&currency_code=EUR&source=url)

### 14.2 Help

To improve the code and functions we like to have you help. Send you idea or code to: info@remotesyslog.com. We will review it and add it to this project.

### 14.3 What is a RSXC token?
RSCX is created to reward developers for there work and to support the project. It is a tradable token by the waves exchange. It comes with no warranty and the price indication is based on the live market. Sending funds to the wrong adres will result in a loss of those funds. We do not refund RSCX tokens. We suggest that you use a hardware token to secure the RSCX tokens. Good luck trading and have fun.

### 14.4 RSCX token
We have a reward system in place, Remote Syslog has it own token available called RSCX. How to get RSCX?

Send usable code/patterns to info@remotesyslog.com. We will review the code or pattern, this may take some time. This token can only be used to trade.

Expected payout RSCX:
 - patterns: minimum of 10 RSCX
 - Code fixes: minimum of 10 RSCX
 - Security fixes: minimum of 20 RSCX
 - New functionality like a plugin: minimum of 20 RSCX
 - Rewards can be higher depending on the code quality
 - Bounty programs may be available in future
 
Note 1: code what is useless will not have a reward

RSCX distribution:

10 million RSCX tokens are created. 

 - Only 1 million RSCX will be up for sale
   -  Every 3 months a maximum of 1000 RSCX will be sold (Only if tradingvolume is sustainable)
 - 4 million RSCX is available for developers
 - 5 million is reserved

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

### 14.5 Funds
All donations and other funds will be used to cover cost of this project and to improve tests/plugins/core scripts. The roadmap will display new functions or products. Check https://www.remotesyslog.com for more information.
