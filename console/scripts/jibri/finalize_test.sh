#!/bin/bash

WD="/config"
#RECORDINGS_DIR=$1
RECORDINGS_DIR="/config/recordings/wgoarroeyfgujrls"
RECORDINGS=`ls $RECORDINGS_DIR/*.mp4`
RECORDINGS=`basename $RECORDINGS`
FOLDER_NAME=$(echo $RECORDINGS | tr "_" ",")
FILE_NAME=$(basename $(ls $RECORDINGS_DIR/*.mp4))
COLLECT="$WD/collect_result"

#echo "$RECORDINGS_DIR" >> $WD/txt.txt
#echo "\n" >> $WD/txt.txt

array=(`echo $FOLDER_NAME | sed 's/,/\n/g'`)

#echo "${array[0]}" >> txt.txt
#echo "\n" >> $WD/txt.txt

TARGET="$COLLECT/${array[0]}"

#echo "$TARGET" >> txt.txt
#echo "\n" >> $WD/txt.txt

mkdir -p "$TARGET"

for var in $(ls $RECORDINGS_DIR/*.mp4)
do
NEW_NAME_FILE=`basename $var | sed "s/${array[0]}_//"`
echo $NEW_NAME_FILE
mv $var "$TARGET/$NEW_NAME_FILE"
done

#mv $RECORDINGS_DIR/*.mp4 $TARGET


