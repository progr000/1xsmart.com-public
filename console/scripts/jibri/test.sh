#!/bin/bash

RECORDINGS_DIR="/config/recordings/wgoarroeyfgujrls"

for var in $(ls $RECORDINGS_DIR/*.mp4)
do
echo "$var" | sed 's/_//'
done
