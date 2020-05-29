#!/bin/bash
argument=$1 #argument NIU com a variable de sessio

#Conversió de mp3 a WAV
ffmpeg -i /var/www/html/audios/audio$argument.mp3 /var/www/html/audios/audio_final$argument.wav
