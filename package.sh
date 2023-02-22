rm -Rf ./target/*

mkdir temp
mkdir temp/template
mkdir temp/template/media
cp -Rf ./www/src/templates/survivants/* ./temp/template
cp -Rf ./www/src/media/templates/site/survivants/* ./temp/template/media
cd temp/template || exit
zip -r ../../target/survivants.zip *
cd ../..

mkdir temp/comp
mkdir temp/comp/media
mkdir temp/comp/components
mkdir temp/comp/administrator
mkdir temp/comp/administrator/components
cp -Rf ./www/src/administrator/components/com_sdajem ./temp/comp/administrator/components
cp -Rf ./www/src/components/com_sdajem ./temp/comp/components
cp -Rf ./www/src/media/com_sdajem ./temp/comp/media
cp -Rf ./temp/comp/administrator/components/com_sdajem/sdajem.xml ./temp/comp
cd temp/comp || exit
zip -r ../../target/sdajem.zip *
cd ../..

rm -Rf temp