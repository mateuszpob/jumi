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
from requests import Session
import re


class Novoterm(threading.Thread):
	url_login = 'http://zam.novoterm.kei.pl/pl/login'
	url_categories = 'http://zam.novoterm.kei.pl/pl/show/products'
	category_counter = 0
	running = True
	category_links = None
	conn = None
	s = None

	def __init__(self, id):
		self.id = str(id)
		threading.Thread.__init__(self)

	# Ładowanie linków kategorii (category_link['href']) i ich nazw (category_link.text) do tablicy 'category_links'
	@staticmethod
	def getCategoryLinks(self):
		self.dbConnection(self)
		self.s = requests.Session()
		r = self.s.get(self.url_login)
		userdata = {'data[email]':'jumi@interia.pl', 'data[password]':'jumi1'}
		r = self.s.post(self.url_login+'/go', data = userdata)
		r = self.s.get(self.url_categories)
		soup = BeautifulSoup(r.text)
		menu_1 = soup.find('ul', {'class', 'menu'})
		menu_2 = menu_1.findAll('li', recursive=False)
		# self.category_links = menu
		# pprint.pprint(menu_1.select('ul > li > a'))
		# pprint.pprint(menu_2)
		for z1 in menu_2:
			pprint.pprint(z1.findAll('a', recursive=False)[0].text)
			# for z2 in z1.findAll('ul', recursive=False):
			# 	pprint.pprint(z2.select('ul > li > a'))
			# for z2 in z1.select('li:first-child > a'):
			# 	pprint.pprint(z2)


	# Z linków do kategorii 'category_links' pobieram wszystkie itemyzÅ
	def getItemsDataFromNovoterm(self):
		if len(self.category_links) > 0:
			cat_link = self.category_links.pop()
			try:
				r = self.s.get(cat_link['href'])
				soup = BeautifulSoup(r.text)
				# pobieram wszystkie rowy z tabeli z itemami
				item_table = soup.find('table', {'class', 'tablesorter'})
				item_rows = item_table.findAll('tr')
				for item in item_rows:
					item_page_link = re.sub('"{1}.*$', '', str(item.select('td:nth-of-type(4) a')).lstrip('[<a class="e" href="http://'))
					ref_number = str(item.select('td:nth-of-type(2)')).lstrip('[<td align="RIGHT">').rstrip('</td>]')
					ean = str(item.select('td:nth-of-type(3)')).lstrip('[<td align="RIGHT">').rstrip('</td>]')
					item_name = re.sub('^.*(">){1}', '', str(item.select('td:nth-of-type(4) a')).rstrip('</a>]'))
					price = str(item.select('td:nth-of-type(5)')).lstrip('[<td align="right">').rstrip('zÅ</td>]')
					# pobiera obrazek na dysk i zapisuje pod zwracaną nazwą
					image_filename = self.getItemBigImage(item_page_link)
					# zapisz itema do bazy
					if item_page_link != ']':
						print('========================> '+price)
						self.dbInsert(item_name, 'desc', image_filename, price,37)
				

				# pprint.pprint(item_rows)
				self.category_counter += 1
			except urllib.error.HTTPError:
				print('error in get item')
		else:
			print('Nie ma kategorii')
			# exit() # bo ubujesz watek

	# dostaje link do strony produktu i ciagne dane, potem zapis
	def getItemBigImage(self, item_link):
		if item_link != ']':
			try:
			
				item_link = 'http://'+item_link
				r = self.s.get(item_link)
				soup = BeautifulSoup(r.text)
				image_link = 'http://zam.novoterm.kei.pl'+str(soup.find('img')['src'])
				print(image_link)
				tl = len(image_link)
				image_name = hashlib.md5(str(time.time()).replace('.', '').encode('utf-8')).hexdigest()+"."+image_link[tl-1]
				urllib.request.urlretrieve(image_link, '/home/error/kod/hiveware/storage/shop/items/novoterm/'+image_name)
				return 'novoterm/'+image_name
			except:
				print('Nie ma obrazka: '+item_link)
		return 'x'

	def dbConnection(self):
		conn_string = "host='localhost' dbname='jumi' user='postgres' password='123123'"
		#conn_string = "host='148.251.156.146' dbname='qwer34_test' user='qwer34_test' password='aWXkNlaDJk'"
          #conn_string = "host='148.251.156.146' dbname='qwer34_test' user='qwer34_test' password='aWXkNlaDJk'"
		self.conn = psycopg2.connect(conn_string)

	def dbInsert(self, name, desc, img, price, cat_id):
		
		cur = self.conn.cursor()
		sql = "INSERT INTO items (name, description, image_path, price_producer, weight, count, created_at, updated_at) VALUES ("
		sql += "'"+name +"', '"+desc+"', '"+img+"', "+price+", 1, 1, now(), now())"
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













Novoterm.getCategoryLinks(Novoterm)

# for category_link in Novoterm.category_links:
# 			# print(category_link.text + '  ' + category_link['href'])
# 			pprint.pprint(category_link)

# i = 0
# threads = [Novoterm(i) for i in range(0, 8)]
# for t in threads:
# 	try:
# 		t.start()
# 	except:
# 		exit()