apt install make
apt install docker.io
apt install zip
usermod -aG docker $USER
newgrp docker

make up

#ln -sr ./src/administrator/components/com_sdajem ./joomla_data/administrator/components/com_sdajem
#ln -sr ./src/components/com_sdajem ./joomla_data/components/com_sdajem
#ln -sr ./src/media/com_sdajem ./joomla_data/media/com_sdajem