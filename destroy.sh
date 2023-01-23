#stop docker
docker-compose down
#delete symbolic links for own code
rm /home/joomla/development/yajem/www/joomla/administrator/components/com_sdajem
rm /home/joomla/development/yajem/www/joomla/components/com_sdajem
rm /home/joomla/development/yajem/www/joomla/media/com_sdajem

rm /home/joomla/development/yajem/www/joomla/administrator/components/com_sdaprofile
rm /home/joomla/development/yajem/www/joomla/components/com_sdaprofile
rm /home/joomla/development/yajem/www/joomla/media/com_sdaprofile

rm /home/joomla/development/yajem/www/joomla/plugins/user/sdaprofile

sudo rm -Rf /home/joomla/development/yajem/www/joomla/*
sudo rm -Rf /home/joomla/development/yajem/mysql/data/*