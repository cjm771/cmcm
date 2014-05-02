#!/bin/bash

for i in *
do
[ -d "$i" ] && zip -r -q -9  "$i.zip" "$i"
done