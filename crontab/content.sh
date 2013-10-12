#!/bin/bash
cd /home/wwwroot/chaosheng/protected/;
content(){
    num=$(/usr/local/php/bin/php yiic crawler getcount --id=$1 & 1>/dev/null 2>&1);
    if [ $num -eq 0 ];then 
        return;
    fi
    if [ $num -ge 10 ];then
        max=10;
    else
        max=$num;
    fi
    for((j=1;j<=$max;j++)); do
        /usr/local/php/bin/php yiic crawler colcontent --id=$1 & 1>/dev/null 2>&1;
        sleep 2;
    done;
}
## 循环采集nodeid列表 ##
for i in 1 2 4 5 6 7 8 9 10
do
    content $i;
    sleep 1;
done;
