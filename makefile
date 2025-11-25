.EXPORT_ALL_VARIABLES:
-include .env

config:
	@UID=$$(id -u) GID=$$(id -g) docker compose config

down: stop
	-@UID=$$(id -u) GID=$$(id -g) docker compose down --remove-orphans

log:
	-@UID=$$(id -u) GID=$$(id -g) docker compose logs

reset: down
	-@rm -rf db_data joomla_data
	-@docker system prune --volumes

start: up
	@clear
	@printf "\033[1;33m%s\033[0m\n\n" "To start your site, please jump to http://127.0.0.1:${WEB_PORT}"
	@printf "\033[1;33m%s\033[0m\n\n" "Go to http://127.0.0.1:${WEB_PORT}/administrator to open your backend."

	@printf "\033[1;104m%s\033[0m\n\n" "Below a summary of your current installation:"

	@printf "\033[1;34m%s\033[0m\n\n" "JOOMLA"
	@printf "\033[1;34m%-30s\033[0m\033[1;104m%s\033[0m\n" "  * Project name" "${PROJECT_NAME}"
	@printf "\033[1;34m%-30s\033[0m\033[1;104m%s\033[0m\n" "  * Version" "${JOOMLA_VERSION}"
	@printf "\033[1;34m%-30s\033[0m\033[1;104m%s\033[0m\n" "  * Port" "${WEB_PORT}"

	@printf "\n\033[1;34m%s\033[0m\n\n" "  Administration"
	@printf "\033[1;34m%-30s\033[0m\033[1;104m%s\033[0m\n" "  * Site name" "${JOOMLA_SITE_NAME}"
	@printf "\033[1;34m%-30s\033[0m\033[1;104m%s\033[0m\n" "  * Admin friendly username" "${JOOMLA_ADMIN_USER}"
	@printf "\033[1;34m%-30s\033[0m\033[1;104m%s\033[0m\n" "  * Admin username" "${JOOMLA_ADMIN_USERNAME}"
	@printf "\033[1;34m%-30s\033[0m\033[1;104m%s\033[0m\n" "  * Admin password" "${JOOMLA_ADMIN_PASSWORD}"
	@printf "\033[1;34m%-30s\033[0m\033[1;104m%s\033[0m\n\n" "  * Admin email" "${JOOMLA_ADMIN_EMAIL}"

	@printf "\033[1;34m%s\033[0m\n\n" "DATABASE"
	@printf "\033[1;34m%-30s\033[0m\033[1;104m%s\033[0m\n" "  * Host" "joomla-db"
	@printf "\033[1;34m%-30s\033[0m\033[1;104m%s\033[0m\n" "  * User name" "${DB_USER}"
	@printf "\033[1;34m%-30s\033[0m\033[1;104m%s\033[0m\n" "  * Password" "${DB_PASSWORD}"
	@printf "\033[1;34m%-30s\033[0m\033[1;104m%s\033[0m\n" "  * Database name" "${DB_NAME}"
	@printf "\033[1;34m%-30s\033[0m\033[1;104m%s\033[0m\n" "  * Version" "${MYSQL_VERSION}"
	@printf "\033[1;34m%-30s\033[0m\033[1;104m%s\033[0m\n\n" "  * Port" "${MYSQL_PORT}"

stop:
	-@UID=$$(id -u) GID=$$(id -g) docker compose stop

up:
	-@mkdir -p db_data joomla_data vendor
	@UID=$$(id -u) GID=$$(id -g) docker compose up --detach --build --remove-orphans
	-@mkdir -p ./joomla_data/administrator/components/com_${COMPONENT_NAME}
	-@mkdir -p ./joomla_data/administrator/components/com_${COMPONENT_NAME}/src

	-@if [ -L ./joomla_data/administrator/components/com_${COMPONENT_NAME}/sdajem.xml ] ; then echo "already linked";else sleep 10;ln -sr ./src/Sda/Component/${CAP_COMPONENT_NAME}/Administrator/sdajem.xml ./joomla_data/administrator/components/com_${COMPONENT_NAME}/sdajem.xml;echo "admin now linked";fi
	-@if [ -L ./joomla_data/administrator/components/com_${COMPONENT_NAME}/script.php ] ; then echo "already linked";else ln -sr ./src/Sda/Component/${CAP_COMPONENT_NAME}/Administrator/script.php ./joomla_data/administrator/components/com_${COMPONENT_NAME}/script.php;echo "admin now linked";fi
	-@if [ -L ./joomla_data/administrator/components/com_${COMPONENT_NAME}/config.xml ] ; then echo "already linked";else ln -sr ./src/Sda/Component/${CAP_COMPONENT_NAME}/Administrator/config.xml ./joomla_data/administrator/components/com_${COMPONENT_NAME}/config.xml;echo "admin now linked";fi
	-@if [ -L ./joomla_data/administrator/components/com_${COMPONENT_NAME}/access.xml ] ; then echo "already linked";else ln -sr ./src/Sda/Component/${CAP_COMPONENT_NAME}/Administrator/access.xml ./joomla_data/administrator/components/com_${COMPONENT_NAME}/access.xml;echo "admin now linked";fi
	-@if [ -L ./joomla_data/administrator/components/com_${COMPONENT_NAME}/src/Controller ] ; then echo "already linked";else ln -sr ./src/Sda/Component/${CAP_COMPONENT_NAME}/Administrator/Controller ./joomla_data/administrator/components/com_${COMPONENT_NAME}/src/Controller;echo "admin now linked";fi
	-@if [ -L ./joomla_data/administrator/components/com_${COMPONENT_NAME}/src/Extension ] ; then echo "already linked";else ln -sr ./src/Sda/Component/${CAP_COMPONENT_NAME}/Administrator/Extension ./joomla_data/administrator/components/com_${COMPONENT_NAME}/src/Extension;echo "admin now linked";fi
	-@if [ -L ./joomla_data/administrator/components/com_${COMPONENT_NAME}/src/Field ] ; then echo "already linked";else ln -sr ./src/Sda/Component/${CAP_COMPONENT_NAME}/Administrator/Field ./joomla_data/administrator/components/com_${COMPONENT_NAME}/src/Field;echo "admin now linked";fi
	-@if [ -L ./joomla_data/administrator/components/com_${COMPONENT_NAME}/forms ] ; then echo "already linked";else ln -sr ./src/Sda/Component/${CAP_COMPONENT_NAME}/Administrator/forms ./joomla_data/administrator/components/com_${COMPONENT_NAME}/forms;echo "admin now linked";fi
	-@if [ -L ./joomla_data/administrator/components/com_${COMPONENT_NAME}/language ] ; then echo "already linked";else ln -sr ./src/Sda/Component/${CAP_COMPONENT_NAME}/Administrator/language ./joomla_data/administrator/components/com_${COMPONENT_NAME}/language;echo "admin now linked";fi
	-@if [ -L ./joomla_data/administrator/components/com_${COMPONENT_NAME}/src/Library ] ; then echo "already linked";else ln -sr ./src/Sda/Component/${CAP_COMPONENT_NAME}/Administrator/Library ./joomla_data/administrator/components/com_${COMPONENT_NAME}/src/Library;echo "admin now linked";fi
	-@if [ -L ./joomla_data/administrator/components/com_${COMPONENT_NAME}/src/Model ] ; then echo "already linked";else ln -sr ./src/Sda/Component/${CAP_COMPONENT_NAME}/Administrator/Model ./joomla_data/administrator/components/com_${COMPONENT_NAME}/src/Model;echo "admin now linked";fi
	-@if [ -L ./joomla_data/administrator/components/com_${COMPONENT_NAME}/src/Service ] ; then echo "already linked";else ln -sr ./src/Sda/Component/${CAP_COMPONENT_NAME}/Administrator/Service ./joomla_data/administrator/components/com_${COMPONENT_NAME}/src/Service;echo "admin now linked";fi
	-@if [ -L ./joomla_data/administrator/components/com_${COMPONENT_NAME}/services ] ; then echo "already linked";else ln -sr ./src/Sda/Component/${CAP_COMPONENT_NAME}/Administrator/services ./joomla_data/administrator/components/com_${COMPONENT_NAME}/services;echo "admin now linked";fi
	-@if [ -L ./joomla_data/administrator/components/com_${COMPONENT_NAME}/sql ] ; then echo "already linked";else ln -sr ./src/Sda/Component/${CAP_COMPONENT_NAME}/Administrator/sql ./joomla_data/administrator/components/com_${COMPONENT_NAME}/sql;echo "admin now linked";fi
	-@if [ -L ./joomla_data/administrator/components/com_${COMPONENT_NAME}/src/Table ] ; then echo "already linked";else ln -sr ./src/Sda/Component/${CAP_COMPONENT_NAME}/Administrator/Table ./joomla_data/administrator/components/com_${COMPONENT_NAME}/src/Table;echo "admin now linked";fi
	-@if [ -L ./joomla_data/administrator/components/com_${COMPONENT_NAME}/tmpl ] ; then echo "already linked";else ln -sr ./src/Sda/Component/${CAP_COMPONENT_NAME}/Administrator/tmpl ./joomla_data/administrator/components/com_${COMPONENT_NAME}/tmpl;echo "admin now linked";fi
	-@if [ -L ./joomla_data/administrator/components/com_${COMPONENT_NAME}/src/View ] ; then echo "already linked";else ln -sr ./src/Sda/Component/${CAP_COMPONENT_NAME}/Administrator/View ./joomla_data/administrator/components/com_${COMPONENT_NAME}/src/View;echo "admin now linked";fi

	-@if [ -L ./joomla_data/components/com_${COMPONENT_NAME} ] ; then echo "already linked";else ln -sr ./src/Sda/Component/${CAP_COMPONENT_NAME}/Site ./joomla_data/components/com_${COMPONENT_NAME};echo "component now linked";fi
	-@if [ -L ./joomla_data/media/com_${COMPONENT_NAME} ] ; then echo "already linked";else ln -sr ./src/media/com_${COMPONENT_NAME} ./joomla_data/media/com_${COMPONENT_NAME};echo "component media now linked";fi
	-@if [ -L ./joomla_data/templates/${TEMPLATE_NAME} ] ; then echo "already linked";else ln -sr ./src/templates/${TEMPLATE_NAME} ./joomla_data/templates/${TEMPLATE_NAME};echo "template now linked";fi
	-@if [ -L ./joomla_data/media/templates/site/${TEMPLATE_NAME} ] ; then echo "already linked";else ln -sr ./src/media/templates/site/${TEMPLATE_NAME} ./joomla_data/media/templates/site/${TEMPLATE_NAME};echo "template media now linked";fi

build:
	-@rm -Rf ./target/*

	@mkdir -p target temp

	@mkdir -p temp/template temp/template/media
	@cp -Rf ./src/templates/${TEMPLATE_NAME}/* ./temp/template
	@cp -Rf ./src/media/templates/site/${TEMPLATE_NAME}/* ./temp/template/media
	@cd temp/template; zip -r ../../target/${TEMPLATE_NAME}.zip *

	@mkdir -p temp/comp temp/comp/media temp/comp/components temp/comp/administrator temp/comp/administrator/components
	@cp -Rf ./src/administrator/components/com_${COMPONENT_NAME} ./temp/comp/administrator/components
	@cp -Rf ./src/components/com_${COMPONENT_NAME} ./temp/comp/components
	@cp -Rf ./src/media/com_${COMPONENT_NAME} ./temp/comp/media
	@cp -f ./temp/comp/administrator/components/com_${COMPONENT_NAME}/${COMPONENT_NAME}.xml ./temp/comp/${COMPONENT_NAME}.xml

	@cd temp/comp; zip -r ../../target/${COMPONENT_NAME}.zip *

	@rm -rf ./temp
