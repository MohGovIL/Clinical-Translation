#!/bin/bash

MYSQLUSERNAME=$1
MYSQLPASSWORD=$2
MYSQLDATABASE=$3

function error_exit
{
	echo "$1" 1>&2
	exit 1
}

# get last openemr translation
rm languageTranslations_utf8.sql
wget https://raw.githubusercontent.com/openemr/translations_development_openemr/master/languageTranslations_utf8.sql

# adding transaction for efficiency
sed -i "1i\\SET autocommit=0; START TRANSACTION;" languageTranslations_utf8.sql
sed -i "$ a\\COMMIT; SET autocommit=1;" languageTranslations_utf8.sql


echo "save last version of openemr translation"
mysql -u${MYSQLUSERNAME} -p${MYSQLPASSWORD} ${MYSQLDATABASE}  < languageTranslations_utf8.sql || error_exit "import translattion failed!"

# create sql file with insert to custom sql statment 
rm customTranslation.sql
echo "convert csv to sql"
php csvToSql.php || error_exit "csv to sql translattion failed!"

#truncate old custom translation
mysql -u${MYSQLUSERNAME} -p${MYSQLPASSWORD} ${MYSQLDATABASE} -e "TRUNCATE table lang_custom;"
# insert custom languge to openemr
mysql -u${MYSQLUSERNAME} -p${MYSQLPASSWORD} ${MYSQLDATABASE}  < customTranslation.sql
# sync custom lang
php sync_lang_definition.php || error_exit "sync translattion failed!"

echo "create clinikal_translation.sql file"
rm clinikal_translation.sql
mysqldump -u${MYSQLUSERNAME} -p${MYSQLPASSWORD} --add-drop-table ${MYSQLDATABASE}  lang_constants lang_custom lang_definitions lang_languages > clinikal_translation.sql || error_exit "create file translattion failed!"

echo "finish succesfully"




	
