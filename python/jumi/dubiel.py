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
import json

class Dubiel(threading.Thread):
	inserted_category = dict()
	item_links = list()
	category_links = list()
	i = 0
	running = True
	conn = None
	user_agent = 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/535.19 (KHTML, like Gecko) Ubuntu/12.04 Chromium/18.0.1025.168 Chrome/18.0.1025.168 Safari/535.19'
	
	def __init__(self, id):
		self.id = str(id)
		threading.Thread.__init__(self)

	@staticmethod
	def getCategoryLinks(self):
		self.dbConnection(self)
		try:
			url = "http://dubielvitrum.pl/pl/oferta/lustra/produkty.html"
			response = urllib.request.urlopen(urllib.request.Request(url, headers={'User-Agent': self.user_agent}))
			soup = BeautifulSoup(response)
			x = soup.find('div', {"class":"menu-name-main-menu"})
			for category_a in x.select('a'):
				self.category_links.append({'category_name':category_a.text, 'category_link':category_a['href']})
				print(category_a.text)
				self.getItemUrls(self, category_a.text, category_a['href'])

		except urllib.error.HTTPError:
			print('err')

	@staticmethod
	def getItemUrls(self, category_name, category_url):
		response = urllib.request.urlopen(urllib.request.Request('http://dubielvitrum.pl'+category_url, headers={'User-Agent': self.user_agent}))
		soup = BeautifulSoup(response)
		x = soup.find('a', {"href":category_url, "class":"active-trail active"}).next_element.next_element
		try:
			for item_a in x.select('li > a'):
				self.item_links.append({'category_name':category_name, 'item_name':item_a.text, 'item_url':item_a['href']})
		except:
			print('Err2')	

	@staticmethod		
	def downloadImage(self, image_link):
		if image_link == '':
			return ''
		tl = len(image_link)
		# te pojebane rzeczy przy haszowaniu sa po to zeby wyciagnac rozszerzenie pliku obrazka
		# file_ext = image_link[tl-3]+image_link[tl-2]+image_link[tl-1]
		file_ext = image_link.split('?')[0]
		tl = len(file_ext)
		file_ext = file_ext[tl-3]+file_ext[tl-2]+file_ext[tl-1]
		image_name = hashlib.md5(str(time.time()).replace('.', '').encode('utf-8')).hexdigest()+"."+file_ext
		urllib.request.urlretrieve(image_link, '/home/error/kod/hiveware/storage/shop/items/dubielvitrum/'+image_name)
		#print('items/dubielvitrum/'+image_name)
		return 'items/dubielvitrum/'+image_name

	def getItemsDataFromDubiel(self):
		keys_list = []
		item = self.item_links.pop()
		#print('item_url: '+str(item['item_url']))
		response = urllib.request.urlopen(urllib.request.Request('http://dubielvitrum.pl'+item['item_url'], headers={'User-Agent': self.user_agent}))
		soup = BeautifulSoup(response)
		page_content = soup.find('div', {'class':'blok_right'})
		item_name = page_content.select('#page-title')[0].text.strip(' \t\n\r')
		item_desc = page_content.select('div.field-item.even p')[0].text.strip(' \t\n\r')
		item_image_path = page_content.select('div.field-item.even > a > img')[0]['src']
		
		image_name = self.downloadImage(self, item_image_path)

		if item['category_name'] in self.inserted_category:
			category_id = self.inserted_category[item['category_name']]
		else:
			category_id = self.insertCategory(item['category_name'], 10000)
			self.inserted_category[item['category_name']] = category_id

		new_item_id = self.dbInsertItem(item_name, item_desc, image_name, category_id)

		# teraz warianty !!		
		a1 = page_content.find('div', {'class':'field-item even'})
		a2 = a1.findAll('tr')
		#print(a1)
		size_table_header = page_content.select('div.field-item.even table tr th')
		size_table_header2 = a1.findAll()
		size_table = page_content.select('div.field-item.even table tr td')
		# print(size_table_header)
		keys_count = 0
		ean_position = 0;
		# wyciagam klucze dla wariantow i ich liczbę
		for lh in size_table_header:
			keys_list.append(lh.text.strip())
			keys_count += 1
			if(lh.text.strip() == 'Kod EAN'):
				ean_position = keys_count
		# wyciagam wartosci z kazdego wariantu, ładuje w tablice i JSONem traktuje
		tmp = {}
		i = 0
		for node in size_table:
			pprint.pprint(node)
			if(keys_list[i%keys_count] != 'Kod EAN'):
				tmp[keys_list[i%keys_count]] = ''.join(node.findAll(text=True)).strip()
			else:
				ean_code = ''.join(node.findAll(text=True)).strip();
			i += 1
			if(i==keys_count):
				i = 0
				json_data = json.dumps(tmp) 
				pprint.pprint(json_data)
				self.dbInsertVariant(str(new_item_id), ean_code, json_data, new_item_id);
		
				

	def dbConnection(self):
		print('connection')
		conn_string = "host='148.251.156.146' dbname='qwer34_mojachata' user='qwer34_mojachata' password='qweasd123A'"
		#conn_string = "host='localhost' dbname='postgres' user='postgres' password='123123'"
          	#conn_string = "host='148.251.156.146' dbname='qwer34_test' user='qwer34_test' password='aWXkNlaDJk'"
		self.conn = psycopg2.connect(conn_string)

	def dbInsertItem(self, name, desc, img, cat_id):
		cur = self.conn.cursor()
		sql = "INSERT INTO items (name, description, image_path, price, price_producer, weight, count, created_at, updated_at) VALUES ("
		sql += "'"+name +"', '"+desc+"', '"+img+"', 0, 0, 1, 1, now(), now())"
		cur.execute(sql)
		self.conn.commit()

		cur.execute("SELECT currval('items_id_seq')")
		item_id = cur.fetchone()[0]
		sql = "INSERT INTO category_item (item_id, category_id) VALUES ("
		sql += str(item_id) + ", " + str(cat_id) + ")"
		cur.execute(sql)
		self.conn.commit()
		print('inserted item id: '+str(item_id))
		return item_id

	def dbInsertVariant(self, item_id, ean, data, price):
		cur = self.conn.cursor()
		sql = "INSERT INTO product_variants (item_id, ean, data, price, created_at, updated_at) VALUES ("
		sql += item_id+", '"+ean+"', '"+data+"',0, now(), now())"

		cur.execute(sql)
		self.conn.commit()

	# Wrzucam kategorie do bazy i zwracam id
	def insertCategory(self, name, id_upper):
		cur = self.conn.cursor()
		sql = "INSERT INTO categories (name, id_upper, description, created_at, updated_at, schema_id) VALUES ('"+name+"', "+str(id_upper)+", 'Desc', now(), now(), 1)"
		cur.execute(sql)
		self.conn.commit()
		# pobierz ostatnio wstawione id
		cur.execute("SELECT currval('categories_id_seq')")
		return cur.fetchone()[0]

	def run(self):
		while self.running:
			self.getItemsDataFromDubiel()













# pobieram linki z itemami zeby poetm wielowatkowo pobierac z tych stron dane i jeb do bazy
Dubiel.getCategoryLinks(Dubiel)
#for cats in Dubiel.item_links:
#	print(cats['category_name']+'   '+cats['item_name']+'    '+cats['item_url'])



i = 0
threads = [Dubiel(i) for i in range(0, 1)]
for t in threads:
	try:
		t.start()
	except:
		exit()