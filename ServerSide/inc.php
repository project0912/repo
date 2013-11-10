<?

date_default_timezone_set('GMT');
require_once('config/mongo.php');
require_once('lib/MDB.php');
require_once('lib/SimpleImage.php');

require_once('classes/Hash.php');
require_once('classes/Helper.php');

require_once('classes/Location.php');
require_once('classes/Statistics.php');
require_once('classes/Image.php');
require_once('classes/Video.php');
require_once('classes/StatisticsBackend.php');
require_once('classes/Cover.php');
require_once('classes/Tag.php');
require_once('classes/History.php');
require_once('classes/Flag.php');
require_once('classes/User.php');
require_once('classes/UserCurrent.php');
require_once('classes/UserUnregistered.php');
require_once('classes/Comment.php');
require_once('classes/Crisis.php');
require_once('classes/Claim.php');
require_once('classes/Evidence.php');
require_once('classes/Observer.php');
require_once('classes/Share.php');


// Active assert and make it quiet
assert_options(ASSERT_ACTIVE, 1);
assert_options(ASSERT_WARNING, 0);
assert_options(ASSERT_QUIET_EVAL, 1);

// Create a handler function
function my_assert_handler($file, $line, $code){
    echo "<hr><b>Assertion Failed:</b><br /> File ". $file ."<br />Line ". $line ."<br />Code ". $code ."<br /><hr />";
}

// Set up the callback
assert_options(ASSERT_CALLBACK, 'my_assert_handler');