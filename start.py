# -*- coding:utf-8 -*-
import scrapy

import time, os 

while True:
	os.system("scrapy crawl dmoz")
	time.sleep(300)  #每5分钟运行一次 24*60*60=86400s
