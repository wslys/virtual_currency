import scrapy

class CurrencyItem(scrapy.Item):
    url = scrapy.Field()
    index = scrapy.Field()
    name = scrapy.Field()
    market_cap = scrapy.Field()
    price = scrapy.Field()
    volume_24h = scrapy.Field()
    circulating_supply = scrapy.Field()
    change_24h = scrapy.Field()
    priceGraph_7d = scrapy.Field()
    max_supply = scrapy.Field()

class DmozItem(scrapy.Item):
    Name   = scrapy.Field()
    Symbol = scrapy.Field()
    Price  = scrapy.Field()
    Volume = scrapy.Field()
    hour1  = scrapy.Field()
    hour24 = scrapy.Field()
    day7   = scrapy.Field()
    MarketCap = scrapy.Field()
    CirculatingSupply = scrapy.Field()

