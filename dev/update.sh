#!/bin/bash

(
cd /home/www/hpccertification/
git pull -q
git push -q
pushd data
jekyll build
popd

for x in skill-data-events skill-data-material examination-tools  examination-questions ; do
pushd $x
git pull
popd
done

# Updateables

for x in examination-questions-staging  ; do
pushd $x
git add *
git commit -m "Autocommit"
git pull -q
git push -q
popd
done

) > /dev/null

