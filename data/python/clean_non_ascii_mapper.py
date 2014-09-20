#!/usr/bin/python

import sys

# input comes from STDIN (standard input)
for line in sys.stdin:
    print ''.join([i if ord(i) < 128 else ' ' for i in line])
