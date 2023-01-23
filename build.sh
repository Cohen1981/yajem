# create the joomla directory
mkdir www/joomla
# start docker containers
docker-compose up -d
# set priviliges
chmod -Rf 755 *
#download and unzip joomla
cd www/joomla || exit
curl -sSL https://downloads.joomla.org/cms/joomla4/4-2-6/Joomla_4-2-6-Stable-Full_Package.zip?format=zip > joomla.zip
sudo apt install unzip
unzip joomla.zip
chmod -Rf 777 *
#create symbolic links for own code
ln -sr /home/joomla/development/yajem/www/src/administrator/components/com_sdajem /home/joomla/development/yajem/www/joomla/administrator/components/com_sdajem
ln -sr /home/joomla/development/yajem/www/src/components/com_sdajem /home/joomla/development/yajem/www/joomla/components/com_sdajem
ln -sr /home/joomla/development/yajem/www/src/media/com_sdajem /home/joomla/development/yajem/www/joomla/media/com_sdajem

cd ../..