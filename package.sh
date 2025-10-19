rm -Rf ./target/*
rm -Rf ./temp/*

mkdir -p target
mkdir -p temp
mkdir -p temp/template
mkdir -p temp/template/media
cp -Rf ./src/templates/survivants/* ./temp/template
cp -Rf ./src/media/templates/site/survivants/* ./temp/template/media
cd temp/template || exit
zip -r ../../target/survivants.zip *
cd ../..

mkdir -p temp/comp
mkdir -p temp/comp/media
mkdir -p temp/comp/components
mkdir -p temp/comp/administrator
mkdir -p temp/comp/administrator/components
cp -Rf ./src/administrator/components/com_sdajem ./temp/comp/administrator/components
cp -Rf ./src/components/com_sdajem ./temp/comp/components
cp -Rf ./src/media/com_sdajem ./temp/comp/media
cp -Rf ./temp/comp/administrator/components/com_sdajem/sdajem.xml ./temp/comp
cd temp/comp || exit
zip -r ../../target/sdajem.zip *
cd ..

rm -Rf temp