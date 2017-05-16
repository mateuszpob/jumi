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
	category_links = []
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
			l_1 = z1.findAll('a', recursive=False)[0]
			cname_1 = l_1.text
			clink_1 = l_1['href']
			id_upper = self.insertCategory(self, cname_1, 'null')
			self.category_links.append({'cname':cname_1, 'clink':clink_1, 'id':id_upper, 'producer_id':re.search('/{1}[0-9]{1,3}$', clink_1).group(0).lstrip('/')})
			for z2 in z1.findAll('ul', recursive=False):
				for z3 in z2.findAll('li', recursive=False):
					l_2 = z3.findAll('a', recursive=False)[0]
					cname_2 = l_2.text
					clink_2 = l_2['href']
					id_upper_2 = self.insertCategory(self, cname_2, id_upper)
					self.category_links.append({'cname':cname_2, 'clink':clink_2, 'id':id_upper_2, 'parrent_id':id_upper, 'producer_id':re.search('/{1}[0-9]{1,3}$', clink_2).group(0).lstrip('/')})
					for z4 in z3.findAll('ul', recursive=False):
						for z5 in z4.findAll('li', recursive=False):
							l_3 = z5.findAll('a', recursive=False)[0]
							cname_3 = l_3.text
							clink_3 = l_3['href']
							# id_upper_3 = self.insertCategory(self, cname_3, 'null')
							self.category_links.append({'cname':cname_3, 'clink':clink_3, 'id':0, 'parrent_id':id_upper_2, 'producer_id':re.search('/{1}[0-9]{1,3}$', clink_3).group(0).lstrip('/')})

		# pprint.pprint(self.category_links)

			# for z2 in z1.select('li:first-child > a'):
			# 	pprint.pprint(z2)

	# Wrzucam kategorie do bazy i zwracam id
	def insertCategory(self, name, id_upper):
		cur = self.conn.cursor()
		sql = "INSERT INTO categories (name, id_upper, description, created_at, updated_at, schema_id) VALUES ('"+name+"', "+str(id_upper)+", 'Desc', now(), now(), 1)"
		cur.execute(sql)
		self.conn.commit()
		# pobierz ostatnio wstawione id
		cur.execute("SELECT currval('categories_id_seq')")
		return cur.fetchone()[0]


	# Z linków do kategorii 'category_links' pobieram wszystkie itemyzÅ
	def getItemsDataFromNovoterm(self):
		if len(self.category_links) > 0:
			category_row = self.category_links.pop()
			
			try:
				r = self.s.get(category_row.get('clink'))
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
						if category_row.get('id') == 0:
							category_id = category_row.get('parrent_id')
						else:
							category_id = category_row.get('id')
						self.dbInsert(item_name, 'desc', image_filename, price, category_id, ean, ref_number)
				

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
				image_name = hashlib.md5(str(time.time()).replace('.', '').encode('utf-8')).hexdigest()+"."+image_link[tl-3]+image_link[tl-2]+image_link[tl-1]
				
				urllib.request.urlretrieve(image_link, '/home/error/kod/hiveware/storage/shop/items/novoterm/'+image_name)
				# urllib.request.urlretrieve(image_link, '/home/error/tmp/novoterm/'+image_name)
				return 'novoterm/'+image_name
			except:
				print('Nie ma obrazka: '+item_link)
		return None

	def dbConnection(self):
		conn_string = "host='localhost' dbname='jumi' user='postgres' password='123123'"
		# conn_string = "host='148.251.156.146' dbname='qwer34_mojachata' user='qwer34_mojachata' password='qweasd123A'"
		#conn_string = "host='148.251.156.146' dbname='qwer34_test' user='qwer34_test' password='aWXkNlaDJk'"
          #conn_string = "host='148.251.156.146' dbname='qwer34_test' user='qwer34_test' password='aWXkNlaDJk'"
		self.conn = psycopg2.connect(conn_string)

	def dbInsert(self, name, desc, img, price, cat_id, ean, ref_number):
		if img == None:
			img = "null"
		else:
			img = "'"+img+"'"

		cur = self.conn.cursor()
		sql = "INSERT INTO items (name, description, image_path, price_producer, price, weight, count, created_at, updated_at, ean, code, schema_id) VALUES ("
		sql += "'"+name +"', '"+desc+"', "+img+", "+price+", "+price+", 1, 1, now(), now(), '"+ean+"', '"+ref_number+"', 1)"
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

i = 0
threads = [Novoterm(i) for i in range(0, 1)]
for t in threads:
	try:
		t.start()
	except:
		exit()