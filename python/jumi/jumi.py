from bs4 import BeautifulSoup
from inspect import getmembers
import urllib.request
import urllib.error
import urllib.parse
import threading
import requests
import sys
import pprint
import string
import time
import threading
import hashlib
import psycopg2

class Novoterm(threading.Thread):
	item_links = list()
	category_links = []
	i = 0
	running = True
	conn = None
	user_agent = 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/535.19 (KHTML, like Gecko) Ubuntu/12.04 Chromium/18.0.1025.168 Chrome/18.0.1025.168 Safari/535.19'
	
	def __init__(self, id):
		self.id = str(id)
		threading.Thread.__init__(self)
	# (1) 
	@staticmethod
	def getCategoryLinks(self):
		self.dbConnection(self)
		try:
			url = "http://novoterm.pl/kerra/"
			#url = "http://novoterm.pl/loge/"
			response = urllib.request.urlopen(urllib.request.Request(url, headers={'User-Agent': self.user_agent}))
			soup = BeautifulSoup(response)
			x = soup.find('div', {"id":"nasze-produkty"})
			cat_name = x.select('div.bottommargin-sm h3')
			cat_link = x.select('div.bottommargin-sm > a')
			i = 0
			for cn in cat_name:
				cname = str(cn).lstrip('<h3>').rstrip('</h3>')
				
				# insert category name to db
				cur = self.conn.cursor()
				sql = "INSERT INTO categories (name, description, created_at, updated_at) VALUES ("
				sql += "'"+cname +"', 'Desc', now(), now())"
				cur.execute(sql)
				self.conn.commit()

				# get inserted row id
				cur.execute("SELECT currval('categories_id_seq')")

				response = urllib.request.urlopen(urllib.request.Request(cat_link[i]['href'], headers={'User-Agent': self.user_agent}))
				soup = BeautifulSoup(response)
				x = soup.find('div', {"id":"isotope-container"}).select('div > a')
				i = i + 1
				cat_id = cur.fetchone()[0]
				j = 0
				for link in x:
					self.item_links.append((link['href'], int(cat_id)))
					j += 1

				print(cname+": "+str(j))				

		except urllib.error.HTTPError:
			print('err')

	@staticmethod
	def getItemLinks(self):
		try:
			url = 'http://novoterm.pl/kerra/kategoria-produktu/stelaze-podtynkowe-pl-pl/'
			items_stelaze_podtynkowe = list()
			#response = urllib.request.urlopen(url).read()
			response = urllib.request.urlopen(urllib.request.Request(url, headers={'User-Agent': self.user_agent}))
			soup = BeautifulSoup(response)
			x = soup.find('div', {"id":"isotope-container"}).select('div > a')
			for link in x:
				self.item_links.append(link['href'])

		except urllib.error.HTTPError:
			print('err')

		


	def getItemsDataFromNovoterm(self):
		if self.item_links:
			items = self.item_links.pop()
			item_url = items[0]
			try:
				response = urllib.request.urlopen(urllib.request.Request(item_url, headers={'User-Agent': self.user_agent}))
				soup = BeautifulSoup(response)
				# pobieranie obrazka głównego
				item = soup.find('a', {"class":"fancybox"})
				t = item['href'].split('.');
				tl = len(t)
				image_name = hashlib.md5(str(time.time()).replace('.', '').encode('utf-8')).hexdigest()+"."+t[tl-1]
				urllib.request.urlretrieve(item['href'], '/home/error/kod/hiveware/storage/shop/items/'+image_name)

				# pobieranie schematu
				# schema_src = soup.find('div', {"class":"product-content"}).select('div.topmargin-sm > div > img')[0]['src']
				# t = schema_src.split('.');
				# tl = len(t)
				# urllib.request.urlretrieve(schema_src, "schema/"+image_name+"."+t[tl-1])

				# pobiera name
				item_name = str(soup.find('div', {"class":"product-head-info"}).select('h2')[0]).lstrip('<h2>').rstrip('</h2>')
				# pobieranie opisu (razem z html - strongi)
				item_desc = str(soup.find('div', {"class":"product-content"}).select('div.topmargin-sm > div > p')[0])

				self.dbInsert(item_name, item_desc, "items/"+image_name, items[1])

			except urllib.error.HTTPError:
				print('error in get item')
		else:
			self.running = False

	def dbConnection(self):
		conn_string = "host='localhost' dbname='jumi' user='postgres' password='123123'"
		#conn_string = "host='148.251.156.146' dbname='qwer34_test' user='qwer34_test' password='aWXkNlaDJk'"
          #conn_string = "host='148.251.156.146' dbname='qwer34_test' user='qwer34_test' password='aWXkNlaDJk'"
		self.conn = psycopg2.connect(conn_string)

	def dbInsert(self, name, desc, img, cat_id):
		cur = self.conn.cursor()
		sql = "INSERT INTO items (name, description, image_path, price, weight, count, created_at, updated_at) VALUES ("
		sql += "'"+name +"', '"+desc+"', '"+img+"', 1, 1, 1, now(), now())"
		cur.execute(sql)
		self.conn.commit()

		cur.execute("SELECT currval('items_id_seq')")
		item_id = cur.fetchone()[0]
		sql = "INSERT INTO category_item (item_id, category_id) VALUES ("
		sql += str(item_id) + ", " + str(cat_id) + ")"
		cur.execute(sql)
		self.conn.commit()

	def run(self):
		while self.running:
			self.getItemsDataFromNovoterm()

# ========================================================================================================= #




# ========================================================================================================= #
# pobieram linki z itemami zeby poetm wielowatkowo pobierac z tych stron dane i jeb do bazy
Novoterm.getCategoryLinks(Novoterm)

# for x in Novoterm.category_links:
# 	print(x)

i = 0
threads = [Novoterm(i) for i in range(0, 8)]
for t in threads:
	try:
		t.start()
	except:
		exit()
