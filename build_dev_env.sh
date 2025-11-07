# Update the package lists.
apt update
apt install make
apt install docker.io
apt install zip
apt install npm
# Install PHP.
apt install -y php
usermod -aG docker $USER
newgrp docker

make start

#ln -sr ./src/administrator/components/com_sdajem ./joomla_data/administrator/components/com_sdajem
#ln -sr ./src/components/com_sdajem ./joomla_data/components/com_sdajem
#ln -sr ./src/media/com_sdajem ./joomla_data/media/com_sdajem