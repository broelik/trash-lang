<?php
if(\php\lang\System::getProperty('environment') == 'dev'){
    $argv = ['', 'data/large.tsh'];
}
else{
    global $argv;
}
$launcher = new \trash\debug\DebugLauncher($argv);
$launcher->launch();