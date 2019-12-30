#!/bin/bash

(
cd /home/www/hpccertification/
git pull -q
cd data
jekyll build
) > /dev/null

