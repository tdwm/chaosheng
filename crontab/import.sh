#!/bin/bash
cd /home/wwwroot/chaosheng/protected/;
import(){
    /usr/local/php/bin/php yiic crawler import --pid=$1 & 1>/dev/null 2>&1;
}
for i in 1 2 3 4 5 6 7 8 10
do
 import $i;
 sleep 2;
done;
