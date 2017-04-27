#!/usr/bin/env bash

#mysql root settings
MYSQL_SEVER=localhost
MYSQL_PORT=3306
MYSQL_ADMIN_USER_NAME=root
MYSQL_ADMIN_PASSWORD=root1
MYSQL_DATABASE_NAME=translate
FILE_NAME=dump.sql

wget -O FILE_NAME https://raw.githubusercontent.com/openemr/translations_development_openemr/master/languageTranslations_utf8.sql
sed -i -- 's/lang_constants/community_lang_constants/g' FILE_NAME
sed -i -- 's/lang_definitions/community_lang_definitions/g' FILE_NAME
sed -i -- 's/lang_languages/community_lang_languages/g' FILE_NAME

echo "install db";
mysql -u${MYSQL_ADMIN_USER_NAME} -p${MYSQL_ADMIN_PASSWORD} -h${MYSQL_SEVER} -P${MYSQL_PORT}  ${MYSQL_DATABASE_NAME} < FILE_NAME || error_exit "creating database was failed"
mysql -u${MYSQL_ADMIN_USER_NAME} -p${MYSQL_ADMIN_PASSWORD} -h${MYSQL_SEVER} -P${MYSQL_PORT} -e "DELETE FROM community_lang_languages WHERE lang_id NOT IN(1,7);" ${MYSQL_DATABASE_NAME}
mysql -u${MYSQL_ADMIN_USER_NAME} -p${MYSQL_ADMIN_PASSWORD} -h${MYSQL_SEVER} -P${MYSQL_PORT} -e "DELETE FROM community_lang_definitions WHERE lang_id NOT IN(1,7);" ${MYSQL_DATABASE_NAME}
mysql -u${MYSQL_ADMIN_USER_NAME} -p${MYSQL_ADMIN_PASSWORD} -h${MYSQL_SEVER} -P${MYSQL_PORT} -e "DELETE FROM community_lang_constants WHERE constant_name IN (SELECT constant_name FROM lang_constants);" ${MYSQL_DATABASE_NAME}
mysql -u${MYSQL_ADMIN_USER_NAME} -p${MYSQL_ADMIN_PASSWORD} -h${MYSQL_SEVER} -P${MYSQL_PORT} -e "DELETE FROM community_lang_definitions WHERE cons_id NOT IN (SELECT cons_id FROM community_lang_constants);" ${MYSQL_DATABASE_NAME}
mysql -u${MYSQL_ADMIN_USER_NAME} -p${MYSQL_ADMIN_PASSWORD} -h${MYSQL_SEVER} -P${MYSQL_PORT} -e "DROP TABLE IF EXISTS `lang_custom`;CREATE TABLE `lang_custom` ( `lang_description` varchar(100) NOT NULL DEFAULT '', `lang_code` char(2) NOT NULL DEFAULT '', `constant_name` mediumtext, `definition` mediumtext ) ENGINE=InnoDB DEFAULT CHARSET=utf8;INSERT INTO lang_custom (lang_description,lang_code,constant_name,definition) SELECT languages.lang_description,languages.lang_code,constants.constant_name,definitions.definition FROM community_lang_definitions as definitions JOIN community_lang_constants as constants on definitions.cons_id = constants.cons_id JOIN community_lang_languages as languages on definitions.lang_id = languages.lang_id;" ${MYSQL_DATABASE_NAME}
echo "finish";