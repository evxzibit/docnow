#!/bin/sh
#

if [[ $(/usr/bin/id -u) -ne 0 ]]; then
    echo "Please run this script as root"
    exit
fi
echo 'Enter a unique name for the service: '
read name
echo 'Enter path to your CLI installation: '
read path
cp setup-install-linux-scheduler-template.txt scheduler.sh
sed -i "s/@name@/${name//\//\\\/}/g;s/@pid_file@/\"${PWD//\//\\/}\/scheduler.pid\"/g;s/@bin_file@/\"${path//\//\\/} ${PWD//\//\\/}\/scheduler.php --clean-start\"/g" scheduler.sh
chmod ugo+x scheduler.sh
mv scheduler.sh /etc/rc.d/init.d/$name
ln -s /etc/rc.d/init.d/$name /etc/rc.d/rc3.d/S99$name

echo 'All done!'
