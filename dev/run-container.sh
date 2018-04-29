#!/bin/bash
docker run -p 127.0.0.1:8888:80 -h hps -it --rm  -v $PWD/../:/data/ kunkel/hps
