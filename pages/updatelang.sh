#!/usr/bin/env bash
#mysql root settings
MYSQL_SEVER=localhost
MYSQL_PORT=3306
MYSQL_ADMIN_USER_NAME=root
MYSQL_ADMIN_PASSWORD=root1
MYSQL_DATABASE_NAME=translate
FILE_NAME=dump/dump.sql

rm $FILE_NAME
wget -O $FILE_NAME https://raw.githubusercontent.com/openemr/translations_development_openemr/master/languageTranslations_utf8.sql
chmod 755 $FILE_NAME

sed -i -- 's/lang_constants/community_lang_constants/g' $FILE_NAME
sed -i -- 's/lang_definitions/community_lang_definitions/g' $FILE_NAME
sed -i -- 's/lang_languages/community_lang_languages/g' $FILE_NAME
sed -i "1i\\SET autocommit=0; START TRANSACTION;" $FILE_NAME
sed -i "$ a\\COMMIT; SET autocommit=1;" $FILE_NAME

echo "save last version of openemr translation"
mysql -u${MYSQL_ADMIN_USER_NAME} -p${MYSQL_ADMIN_PASSWORD} ${MYSQL_DATABASE_NAME}  < $FILE_NAME || error_exit "import translattion failed!"
mysql -u${MYSQL_ADMIN_USER_NAME} -p${MYSQL_ADMIN_PASSWORD} -h${MYSQL_SEVER} -P${MYSQL_PORT} -e "DELETE FROM community_lang_languages WHERE lang_id NOT IN(1,7);" ${MYSQL_DATABASE_NAME}
mysql -u${MYSQL_ADMIN_USER_NAME} -p${MYSQL_ADMIN_PASSWORD} -h${MYSQL_SEVER} -P${MYSQL_PORT} -e "DELETE FROM community_lang_definitions WHERE lang_id NOT IN(1,7);" ${MYSQL_DATABASE_NAME}
mysql -u${MYSQL_ADMIN_USER_NAME} -p${MYSQL_ADMIN_PASSWORD} -h${MYSQL_SEVER} -P${MYSQL_PORT} -e "DELETE FROM community_lang_constants WHERE constant_name IN (SELECT constant_name FROM lang_constants);" ${MYSQL_DATABASE_NAME}
mysql -u${MYSQL_ADMIN_USER_NAME} -p${MYSQL_ADMIN_PASSWORD} -h${MYSQL_SEVER} -P${MYSQL_PORT} -e "DELETE FROM community_lang_definitions WHERE cons_id NOT IN (SELECT cons_id FROM community_lang_constants);" ${MYSQL_DATABASE_NAME}
mysql -u${MYSQL_ADMIN_USER_NAME} -p${MYSQL_ADMIN_PASSWORD} -h${MYSQL_SEVER} -P${MYSQL_PORT} -e "DROP TABLE IF EXISTS lang_custom;CREATE TABLE lang_custom ( lang_description varchar(100) NOT NULL DEFAULT '', lang_code char(2) NOT NULL DEFAULT '', constant_name mediumtext, definition mediumtext ) ENGINE=InnoDB DEFAULT CHARSET=utf8;INSERT INTO lang_custom (lang_description,lang_code,constant_name,definition) SELECT languages.lang_description,languages.lang_code,constants.constant_name,definitions.definition FROM community_lang_definitions as definitions JOIN community_lang_constants as constants on definitions.cons_id = constants.cons_id JOIN community_lang_languages as languages on definitions.lang_id = languages.lang_id;" ${MYSQL_DATABASE_NAME}
mysql -u${MYSQL_ADMIN_USER_NAME} -p${MYSQL_ADMIN_PASSWORD} -h${MYSQL_SEVER} -P${MYSQL_PORT} -e "INSERT INTO lang_constants (constant_name) SELECT custom.constant_name as constant_name FROM lang_custom AS custom WHERE NOT EXISTS (SELECT cons_id FROM lang_constants AS constants WHERE constants.constant_name = custom.constant_name) GROUP BY custom.constant_name; INSERT INTO lang_definitions(cons_id, lang_id, definition) SELECT temp.cons_id,temp.lang_id,temp.definition FROM (SELECT constants.cons_id,languages.lang_id,custom.definition FROM lang_custom AS custom JOIN lang_constants AS constants ON constants.constant_name = custom.constant_name JOIN lang_languages AS languages ON languages.lang_code = custom.lang_code AND languages.lang_description = custom.lang_description) AS temp WHERE NOT EXISTS (SELECT def.cons_id FROM lang_definitions AS def WHERE def.cons_id = temp.cons_id AND def.lang_id = temp.lang_id); UPDATE lang_definitions as def JOIN lang_constants AS constants ON constants.cons_id = def.cons_id JOIN lang_languages AS languages ON languages.lang_id = def.lang_id JOIN lang_custom AS custom ON custom.lang_code = languages.lang_code AND custom.lang_description = languages.lang_description AND custom.constant_name = constants.constant_name SET def.cons_id = constants.cons_id, def.lang_id = languages.lang_id, def.definition = custom.definition WHERE custom.definition <> def.definition;" ${MYSQL_DATABASE_NAME}
mysql -u${MYSQL_ADMIN_USER_NAME} -p${MYSQL_ADMIN_PASSWORD} -h${MYSQL_SEVER} -P${MYSQL_PORT} -e "DROP TABLE IF EXISTS lang_custom; DROP TABLE IF EXISTS community_lang_constants; DROP TABLE IF EXISTS community_lang_definitions; DROP TABLE IF EXISTS community_lang_languages;" ${MYSQL_DATABASE_NAME}
echo "finish";
rm $FILE_NAME