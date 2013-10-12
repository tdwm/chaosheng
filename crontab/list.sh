#!/bin/bash
cd /home/wwwroot/chaosheng/protected/;
list(){
    /usr/local/php/bin/php yiic crawler colurllist --id=$1 & 1>/dev/null 2>&1;
}
for i in 1 2 4 5 6 7 8 9 10
do
    list $i;
    sleep 1;
done;
