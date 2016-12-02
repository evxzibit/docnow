#!/bin/sh
# Passes the STDIN into a script

DIR="doc_root/live"
SCR="bounce.php"

cd $DIR
/usr/local/zend/bin/php -q -c /usr/local/zend/etc/ -d max_execution_time=0 $SCR
# End of script
