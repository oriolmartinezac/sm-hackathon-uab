#!/bin/bash
argument=$1 #argument NIU com a variable de sessio

#Conversió de base64 a mp3
base64 /var/www/html/audios/audio_base64$argument.txt -d > /var/www/html/audios/audio$argument.mp3
