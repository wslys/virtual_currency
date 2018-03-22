# -*- coding:utf-8 -*-
import scrapy #导入scrapy包

from scrapy.http import Request ##一个单独的request的模块，需要跟进URL的时候，需要用它
from tutorial.items import DmozItem, CurrencyItem
import json, MySQLdb, time, os 

class DmozSpider(scrapy.Spider):
    name = 'markets'
    bash_url = 'https://coinmarketcap.com/'

    def start_requests(self):
        for i in range(1, 17):
            url = self.bash_url + str(i)
            yield Request(url, self.parseOne)

    def parseOne(self, response):
        items = []

        for sel in response.xpath('//tbody/tr'):
            item = CurrencyItem()

            Url               = sel.xpath('td[2]/a/@href').extract()[0].strip().encode('unicode-escape').decode('string_escape')
            Index             = sel.xpath('td[1]/text()').extract()[0].strip().encode('unicode-escape').decode('string_escape')
            Name              = sel.xpath('td[2]/@data-sort').extract()[0].strip().encode('unicode-escape').decode('string_escape')
            MarketCap         = sel.xpath('td[3]/@data-sort').extract()[0].strip().encode('unicode-escape').decode('string_escape')
            Price             = sel.xpath('td[4]/@data-sort').extract()[0].strip().encode('unicode-escape').decode('string_escape')
            Volume_24Hh       = sel.xpath('td[5]/@data-sort').extract()[0].strip().encode('unicode-escape').decode('string_escape')
            CirculatingSupply = sel.xpath('td[6]/@data-sort').extract()[0].strip().encode('unicode-escape').decode('string_escape')
            Change_24h        = sel.xpath('td[7]/@data-sort').extract()[0].strip().encode('unicode-escape').decode('string_escape')

            item['url']                = Url 
            item['index']              = Index
            item['name']               = Name
            item['market_cap']         = MarketCap
            item['price']              = Price
            item['volume_24h']         = Volume_24Hh
            item['circulating_supply'] = CirculatingSupply
            item['change_24h']         = Change_24h  
            # item['priceGraph_7d'] = priceGraph_7d  
            # item['max_supply'] = max_supply # TODO  
            items.append(item)

        # 清空数据表 
        self.execute_sql("truncate table `currencys`;")
        self.execute_sql("truncate table `historical_data`;")
        self.execute_sql("truncate table `markets`;")

        for item in items:
            url  = self.bash_url + str(item['url'])
            request = Request(url, callback=self.coin)
            request.meta['item'] = item
            yield request

        for item in items:
            url  = self.bash_url + str(item['url']) + "/#markets"
            request = Request(url, callback=self.singleMarkets)
            request.meta['type_name'] = str(item['name'])
            request.meta['index'] = str(item['index'])
            yield request

        for item in items:
            url  = self.bash_url + str(item['url']) + "/historical-data/"
            request = Request(url, callback=self.historicalData)
            request.meta['type_name'] = item['name']
            request.meta['index'] = str(item['index'])
            yield request

    def coin(self, response):
        _item = response.meta['item']
        _max_supply = response.xpath('//body/div[4]/div[1]/div[1]/div[4]/div[1]/div[5]/div[2]/span/@data-format-value').extract()
        
        print DmozSpider.objToStr(_item)
        max_supply = ""
        if len(_max_supply):
            max_supply = _max_supply[0].strip().encode('unicode-escape').decode('string_escape')
        

        _item['max_supply'] = max_supply
        sql = """INSERT INTO currencys(c_index, name, market_cap, price, volume_24h, circulating_supply, change_24h, max_supply, create_at) VALUES """
        sql += "(" + _item['index'] + ", '" + _item['name'] + "', '" + _item['market_cap'] + "', '" + _item['price'] + "', " + _item['volume_24h'] + ", '" + _item['circulating_supply'] + "', '" + _item['change_24h'] + "', '" + _item['max_supply'] + "', '" + time.strftime("%Y-%m-%d %H:%M:%S", time.localtime()) + "')"
        
        self.execute_sql(sql)

    def singleMarkets(self, response):
        type_name = str(response.meta['type_name'])
        _index = str(response.meta['index'])

        sql = """INSERT INTO markets(c_index, currency_name, source, pair, volume, price, volumes, create_at) VALUES """
        i = 1
        for sel in response.xpath('//tbody/tr'):
            Index      = sel.xpath('td[1]/text()').extract()[0].strip().encode('unicode-escape').decode('string_escape')
            Source     = sel.xpath('td[2]/@data-sort').extract()[0].strip().encode('unicode-escape').decode('string_escape')
            Pair       = sel.xpath('td[3]/@data-sort').extract()[0].strip().encode('unicode-escape').decode('string_escape')
            Volume_24H = sel.xpath('td[4]/@data-sort').extract()[0].strip().encode('unicode-escape').decode('string_escape')
            Price      = sel.xpath('td[5]/@data-sort').extract()[0].strip().encode('unicode-escape').decode('string_escape')
            Volume_S   = sel.xpath('td[6]/@data-sort').extract()[0].strip().encode('unicode-escape').decode('string_escape')

            if i>100:break
            sql += "('" + _index + "', '" + type_name + "', '" + Source + "', '" + Pair + "', '" + Volume_24H + "', " + Price + ", '" + Volume_S + "', '" + time.strftime("%Y-%m-%d %H:%M:%S", time.localtime()) + "'),"
            i += 1  

        self.execute_sql(sql[:-1])

    def historicalData(self, response):
        type_name = str(response.meta['type_name'])
        _index = str(response.meta['index'])
        
        sql = """INSERT INTO historical_data(c_index, currency_name, old_date, open, hight, low, close, volume, market_cap, create_at) VALUES """
        for sel in response.xpath('//tbody/tr'):
            Old_date  = sel.xpath('td[1]/text()').extract()[0].strip().encode('unicode-escape').decode('string_escape')
            Open      = sel.xpath('td[2]/@data-format-value').extract()[0].strip().encode('unicode-escape').decode('string_escape')
            High      = sel.xpath('td[3]/@data-format-value').extract()[0].strip().encode('unicode-escape').decode('string_escape')
            Low       = sel.xpath('td[4]/@data-format-value').extract()[0].strip().encode('unicode-escape').decode('string_escape')
            Close     = sel.xpath('td[5]/@data-format-value').extract()[0].strip().encode('unicode-escape').decode('string_escape')
            Volume    = sel.xpath('td[6]/@data-format-value').extract()[0].strip().encode('unicode-escape').decode('string_escape')
            MarketCap = sel.xpath('td[7]/@data-format-value').extract()[0].strip().encode('unicode-escape').decode('string_escape')
            
            timeStamp = int(time.mktime(time.strptime(Old_date, "%b %d, %Y")))
            localTime = time.localtime(timeStamp) 
            strTime   = time.strftime("%Y-%m-%d %H:%M:%S", localTime) 
            
            sql += "('" + _index + "', '" + type_name + "', '" + strTime + "', '" + Open + "', '" + High + "', " + Low + ", '" + Close + "', '" + Volume + "', '" + MarketCap + "', '" + time.strftime("%Y-%m-%d %H:%M:%S", time.localtime()) + "'),"
            
        self.execute_sql(sql[:-1])

    @staticmethod
    def objToStr(obj):
        return json.dumps(obj, default=lambda obj: obj.__dict__, sort_keys=True, indent=4)

    @staticmethod
    def execute_sql(sql):
        db = MySQLdb.connect("localhost", "root", "123456", "tutorial")
        cursor = db.cursor()
        try:
            cursor.execute(sql)
            db.commit()
        except:
            db.rollback()

        db.close()
