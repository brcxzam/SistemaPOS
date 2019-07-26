<?php
require __DIR__ . '/composer/vendor/autoload.php';
$serial = new PhpSerial();
//this is the port where my Arduino is. Check from the Arduino IDE to see yours!
$serial->deviceSet("/dev/ttyACM0");
$serial->confBaudRate(9600);
$serial->confParity("none");
$serial->confCharacterLength(8);
$serial->confStopBits(1);
$serial->confFlowControl("none");
$serial->deviceOpen();
// we will send the colors as csv strings
$colors['red']   = '255, 0, 0';
$colors['green'] = '0, 255, 0';
$colors['blue']  = '0, 0, 255';
foreach ($colors as $color => $value) {
        echo "Now sending $color\n";
        $serial->sendMessage($value . "\n");
        sleep(1);
}
echo "DONE\n";