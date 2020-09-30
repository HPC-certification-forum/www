#!/bin/bash

(
cd /home/www/hpccertification/
git pull -q
git push -q
cd data
jekyll build
) > /dev/null

