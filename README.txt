1. License
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

2. Version 2 basic is free and is a duplicate of version 1.x rewritten 
in bash. The premium version contains a API and a new GUI but is currently
work in progress. We expect a release before 2020.

3. Config files
To change the core configuration use the following files:
/opt/remotesyslog/colortail => for a new color regex
/opt/remotesyslog/syslog-ng => syslog configuration
/opt/remotesyslog/logrotate => change file rotation

4. Information
The basic version is community driven. For the moment it allows you to 
deploy the old version 1.x written in bash. It has some bug fixes.
It allows you to download a lightweight and response syslog
server. The basic edition only contains a TUI and the free GUI.

5. RS version 2.0 Premium
There is a Premium version in development. Currently testers are allowed
to login and download the premium. The premium version is currently in BETA.
The premium is NOT avialable within this installation.

6. Security
RSX and RSC have SSL/TLS encryption by default enabled and authentication is 
enabled over PAM

7. Installation
a. Install a clean debian 9.x or Ubuntu 18.04.2 distro
b. Run the following commands:
    - git clone https://github.com/tslenter/RSX-RSC.git
    - cd RSX-RSC
    - chmod +x rsinstaller
    - ./rsinstaller
    - Choose option 1 to install the core
    - Choose option 10 to install the RSC version (Remote Syslog Classic)
    - Choose option 12 to install the RSX version
c.  RSX is only supported on Ubuntu 18.04.2 or higher

8. More information: https://www.remotesyslog.com/
