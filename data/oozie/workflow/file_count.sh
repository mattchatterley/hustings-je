#!/bin/bash

echo "NumberOfFiles=`hadoop fs -ls /user/flume/tweets/test1/* | grep -o -e '/user/flume/tweets/test1/FlumeData.[0-9]\{1,\}$' | wc -l`"
