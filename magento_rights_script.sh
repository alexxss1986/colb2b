#!/bin/bash

GRUPPO=apache
 
if [ ! -f ./app/etc/local.xml ]; then
    echo "-- ERROR"
    echo "-- This doesn't look like a Magento install.  Please make sure"
    echo "-- that you are running this from the Magento main doc root dir"
    exit
fi
 
if [ `id -u` != 0 ]; then
    echo "-- ERROR"
    echo "-- This script should be run as root so that file ownership"
    echo "-- changes can be set correctly"
    exit
fi
 
echo -n "Enter the UNIX user which the Magento scripts should run as: "
read user
 
if [ ! `id -u $user` ]; then
    echo "-- ERROR"
    echo "-- No such user: $user"
    exit
fi

 echo -n "Installazione [P]lugins o Messa in [S]icurezza? "
 echo "[P/S]"
 read risposta
 if [ $risposta = "P" ]; then
	echo "Opening..."
	find . \-exec chown ${user}.${GRUPPO} {} \;
	find . -type d \-exec chmod 775 {} \;
	find . -type f \-exec chmod 664 {} \;
 else
	echo "Securing..."
	find . \-exec chown ${user}.${GRUPPO} {} \;
	find . -type f \-exec chmod 644 {} \;
	find . -type d \-exec chmod 755 {} \;
	find ./var -type d \-exec chmod 775 {} \;
	find ./var -type f \-exec chmod 664 {} \;
	find ./media -type d \-exec chmod 775 {} \;
	find ./media -type f \-exec chmod 664 {} \;
### 	find . -type f -name "*.php" \-exec chmod 640 {} \;
	find . -type f -name "mage" \-exec chmod 550 {} \;
	find . -type f -wholename "./app/etc/local.xml" \-exec chmod 640 {} \;
	exit
 fi

## Starting rights
## find ./${cartella} \-exec chown $user.apache {} \;
## find ./${cartella} -type f \-exec chmod 644 {} \;
## find ./${cartella} -type d \-exec chmod 711 {} \;
## # find ./${cartella} -type f -name "*.php" \-exec chmod 600 {} \;
## find . -type f -wholename "${cartella}/mage" \-exec chmod 550 {} \;
## find . -type f -wholename "${cartella}/app/etc/local.xml" \-exec chmod 600 {} \;

