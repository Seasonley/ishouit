from selenium import webdriver 
from selenium.webdriver.common.keys import Keys
from selenium.webdriver.common.action_chains import ActionChains
import random,time,os,webbrowser,json,pymysql

def info(str):
	print(time.strftime('%Y-%m-%d %H:%M:%S',time.localtime(time.time()))+" | "+str)
	
def azrwsql(method='get',data=None):
	if method=='set':
		conn = pymysql.connect(host="101.37.25.91",port=3307,user="lingchen",passwd="xSR8sjMjxqeA5ChxA",db="erplz",charset="utf8")
		cur=conn.cursor()
		cur.execute("REPLACE INTO `vanish_azrw_tmp` (`user_id`, `azrwtmp`) VALUES ('azrwtmp', '"+json.dumps(data, ensure_ascii=False)+"')")
		conn.commit()
		conn.close
	else:
		conn = pymysql.connect(host="101.37.25.91",port=3307,user="lingchen",passwd="xSR8sjMjxqeA5ChxA",db="erplz",charset="utf8")
		cur=conn.cursor()
		cur.execute("SELECT azrwtmp from vanish_azrw_tmp where user_id='azrwtmp'")
		res=cur.fetchall()
		conn.close
		return json.loads(res[0][0])

def main(browser):
	azrwlist=azrwsql()
	if len(azrwlist)>0:
		info("本地数据库安装任务单据:"+str(len(azrwlist)))
		for i in range(0,len(azrwlist),1):
			if azrwlist[i]['customer_tel1']!='':
				continue
			browser.find_element_by_xpath("//input[@id='ordernomanage']").send_keys(azrwlist[i]['az_code'])
			browser.find_element_by_xpath("//button[@id='btn_search']").click()
			time.sleep(2)
			browser.switch_to.frame(browser.find_element_by_xpath("//iframe[@id='innerframe']"))
			azrwlist[i]['customer_tel1']=browser.find_element_by_xpath("//input[@name='customerPhone']").get_attribute("value")
			info('安装单：'+azrwlist[i]['az_code']+' 电话：'+azrwlist[i]['customer_tel1'])
			browser.switch_to.default_content()
		azrwsql('set',azrwlist)
	info("——————sleep 0.5 min——————")
	time.sleep(20)
	main(browser)

info('菱辰京东爬虫')
browser = webdriver.Chrome('chromedriver.exe')	
browser.maximize_window()
browser.implicitly_wait(1)
browser.get('https://jdfw.jd.com/')
browser.find_element_by_xpath("//a[contains(text(),'账户登录')]").click()
browser.find_element_by_xpath("//input[@id='loginname']").send_keys("shlcdq")
browser.find_element_by_xpath("//input[@id='nloginpwd']").send_keys("2016xyz62570868")
browser.find_element_by_xpath("//a[@id='loginsubmit']").click()
time.sleep(2)
browser.switch_to.frame(browser.find_element_by_xpath("//frame[@name='main']"))
browser.find_element_by_xpath("//td[contains(text(),'未接收')]/..//a").click()
browser.find_element_by_xpath("//a[@id='morebtn']").click()
time.sleep(2)
browser.find_element_by_xpath("//button[@id='search']").click()
time.sleep(2)
browser.find_element_by_xpath("//td[@field='orderno']/div/a").click()
browser.switch_to.window(browser.window_handles[1])
main(browser)











