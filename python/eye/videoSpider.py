#coding=utf-8
import re,urllib2,urllib,json,time,os

def getHtmlCode(url):
    return urllib2.urlopen(url).read()

def getContent():
    strTime = time.strftime('%Y%m%d',time.localtime(time.time()))
    # 1.daily
    print "1.daily:"
    url = 'http://baobab.wandoujia.com/api/v2/feed'
    htmlCode = getHtmlCode(url)
    decodeJson = json.loads(htmlCode)
    itemList = decodeJson["issueList"][0]["itemList"]
    for i in range(1,len(itemList) - 1):
        data = itemList[i]["data"]
        if data.has_key('title'):
            title =  itemList[i]["data"]["title"]
            playUrl = itemList[i]["data"]["playUrl"]
            try:
                print playUrl
                # urllib.urlretrieve(playUrl,"%s.mp4" %("daily/" + strTime + title))
                # print (title + 'done')
            except:
                print "error"
                print (title + 'error')
    # # 2.weekly
    # print "2.weekly:"
    # url = 'http://baobab.wandoujia.com/api/v3/ranklist?strategy=weekly'
    # htmlCode = getHtmlCode(url)
    # decodeJson = json.loads(htmlCode)
    # itemList = decodeJson["itemList"]
    # for i in range(0,len(itemList)):
    #     title =  itemList[i]["data"]["title"]
    #     playUrl = itemList[i]["data"]["playUrl"]
    #     fileName = title + ".mp4"
    #     if os.path.isfile(fileName) is None:
    #         urllib.urlretrieve(playUrl,"%s.mp4" %fileName)
    #         print (title + 'done')

    # # 3.monthly
    # print "3.monthly:"
    # url = 'http://baobab.wandoujia.com/api/v3/ranklist?strategy=monthly'
    # htmlCode = getHtmlCode(url)
    # decodeJson = json.loads(htmlCode)
    # itemList = decodeJson["itemList"]
    # for i in range(0,len(itemList)):
    #     title =  itemList[i]["data"]["title"]
    #     playUrl = itemList[i]["data"]["playUrl"]
    #     fileName = title + ".mp4"
    #     if os.path.isfile(fileName) is None:
    #         urllib.urlretrieve(playUrl,"%s.mp4" %fileName)
    #         print (title + 'done')

    # # 4.historical
    # print "4.historical:"
    # url = 'http://baobab.wandoujia.com/api/v3/ranklist?strategy=historical'
    # htmlCode = getHtmlCode(url)
    # decodeJson = json.loads(htmlCode)
    # itemList = decodeJson["itemList"]
    # for i in range(0,len(itemList)):
    #     title =  itemList[i]["data"]["title"]
    #     playUrl = itemList[i]["data"]["playUrl"]
    #     fileName = title + ".mp4"
    #     if os.path.isfile(fileName) is None:
    #         urllib.urlretrieve(playUrl,"%s.mp4" %fileName)
    #         print (title + 'done')

getContent()
