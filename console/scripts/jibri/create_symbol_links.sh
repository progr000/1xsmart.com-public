#!/bin/bash
RECORD_PATH="/home/config_jitsi-meet-stable-5390-3/jibri/recordings/*/*.mp4"
COLLECT_VIDEOS_PATH="/home/config_jitsi-meet-stable-5390-3/jibri/collect_videos"

mkdir -p $COLLECT_VIDEOS_PATH
for i in $(ls $RECORD_PATH); do split_name=$(echo $i| sed 's@.*/@@') && echo $split_name && ln -s $i $COLLECT_VIDEOS_PATH/$split_name; done
