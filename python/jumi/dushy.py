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

class Duschy(threading.Thread):
	item_links = list()
	category_links = list()
	category_links_2 = list()
	category_links_inserted = list()
	category_links_inserted_wid = list()
	i = 0
	running = True
	conn = None
	user_agent = 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/535.19 (KHTML, like Gecko) Ubuntu/12.04 Chromium/18.0.1025.168 Chrome/18.0.1025.168 Safari/535.19'
	
	def __init__(self, id):
		self.id = str(id)
		threading.Thread.__init__(self)

	@staticmethod
	def getCategoryLinks(self):
		try:
			url = "http://www2.duschy.com/produkty"
			response = urllib.request.urlopen(urllib.request.Request(url, headers={'User-Agent': self.user_agent}))
			soup = BeautifulSoup(response)
			x = soup.find('div', {'id':'webshopMenu'})
			links = x.select('ul > li > a')
			for link in links:
				self.category_links.append('http://www2.duschy.com/' + link['href'])


		except:
			print('dupa')

	@staticmethod
	def getItemsLinks(self):
		self.dbConnection(self)
		category_count = len(self.category_links)
		j = 0
		# lece po wszystkich kategoriach
		for url in self.category_links:
			# zamieniam hujowe znaki na zajebiste
			cat_url = str(url).replace('http://www2.duschy.com/', '').rstrip('/').replace('-322', 'ł').replace('oacute', 'ó').replace('-347', 'ś').replace('-261', 'ą').replace('-281', 'ę').replace('-380', 'ż').replace('-379','ż').replace('-', ' ')
			

			ci = 0
			# jeśli to nie jest jakas podstrona kategorii
			if cat_url.find('&page=') == -1:
				# tu sa podkategorie
				categ_arr = cat_url.split('/')
				# current_category_for_item = reversed(categ_arr)
				for cat_t in categ_arr:
					# jeśli tej jeszcze nie ma na liście, to ją wstaw do db i na liste
					if cat_t not in self.category_links_inserted:
						cur = self.conn.cursor()
						if ci > 0:
							parent_id = str(self.category_links_inserted_wid[int(self.category_links_inserted.index(categ_arr[ci-1]))])
							sql = "INSERT INTO categories (schema_id, name, description, created_at, updated_at, id_upper) VALUES (1, "
							sql += "'"+cat_t +"', 'Desc', now(), now(), "+parent_id+")" 
						else:
							sql = "INSERT INTO categories (schema_id, name, description, created_at, updated_at) VALUES (1,"
							sql += "'"+cat_t +"', 'Desc', now(), now())"
						cur.execute(sql)
						self.conn.commit()
						print ('==== wstawiam do db ====> '+cat_t)
						# tu wstawiam na liste zeby jeszcze raz jej w przyszlosci nie wpierdolic do db
						self.category_links_inserted.append(cat_t)

						#  wstawiam id kategorii do innej listy
						cur.execute("SELECT currval('categories_id_seq')")
						cat_id = cur.fetchone()[0]
						print(cat_id)
						self.category_links_inserted_wid.append(cat_id)
					ci += 1
			# else:
				# current_category_for_item = reversed(cat_url.split('/'))[1]
			try:
				response = urllib.request.urlopen(urllib.request.Request(url, headers={'User-Agent': self.user_agent}))
				soup = BeautifulSoup(response)
				x = soup.find('div', {'id':'productsWrapper'})
				pager = x.select('div#pager > span.text')
				page_count_string = str(pager[1]).lstrip('<span class="text">(Av: ').rstrip(')</span>')
				page_count_int = int(page_count_string)

				# dodaje linki z paginacja do linkow z kategoriami
				if page_count_int > 1 and j < category_count:
					for i in range(2, page_count_int+1):
						self.category_links.append(url + '&page=' + str(i))
					# na stronie kategorii wyszukuje wszystkie itemy
					for item in x.select('div.productBox > div.productBoxImage > a'):
						# print(str(cat_id)+' -append kurwa ' + item['href'])
						self.item_links.append((item['href'], cat_id))
			except:
				print(sys.exc_info())

			j += 1

		for x in self.item_links:
			print('...> '+x[0]+','+str(x[1]))
		

	def dbConnection(self):
		#conn_string = "host='148.251.156.146' dbname='qwer34_mojachata' user='qwer34_mojachata' password='qweasd123A'"
		conn_string = "host='127.0.0.1' dbname='jumi' user='postgres' password='123123'"
		self.conn = psycopg2.connect(conn_string)

	def dbInsert(self, name, desc, img, cat_id, code):
		# cat_id = str(self.category_links_inserted_wid[int(self.category_links_inserted.index(category_name))])

		cur = self.conn.cursor()
		sql = "INSERT INTO items (schema_id, name, description, image_path, category_name, code, price, price_producer, weight, "
		sql += "count, created_at, updated_at) VALUES (1,"
		sql += "'"+name +"', '"+desc+"', '"+img+"', 'cat', '"+code+"', 0, 0, 1, 1, now(), now())"
		cur.execute(sql)
		self.conn.commit()

		cur.execute("SELECT currval('items_id_seq')")
		item_id = cur.fetchone()[0]
		sql = "INSERT INTO category_item (item_id, category_id) VALUES ("
		sql += str(item_id) + ", " + str(cat_id) + ")"
		cur.execute(sql)
		self.conn.commit()

	def getItemsDataFromDuschy(self):
		if self.item_links:
			items = self.item_links.pop()
			item_url = 'http://www2.duschy.com/' + items[0]
			try:
				response = urllib.request.urlopen(urllib.request.Request(item_url, headers={'User-Agent': self.user_agent}))
				soup = BeautifulSoup(response)
				# pobieranie obrazka głównego
				item_cont = soup.find('div', {"id":"viewProduct"})
				imagesrc = item_cont.select('div#productImage > a > img')[0]['src']
				print(imagesrc)
				t = imagesrc.split('.');
				tl = len(t)
				image_name = hashlib.md5(str(time.time()).replace('.', '').encode('utf-8')).hexdigest()+"."+t[tl-1][:-6]
				urllib.request.urlretrieve(imagesrc, '/home/mo/kod/hiveware/storage/shop/tmp/'+image_name)

				item_cont = soup.find('div', {"id":"viewProduct"})
				item_name = str(item_cont.select('span#viewProductHead > h2')[0]).lstrip('<h2>').rstrip('</h2>')
				item_desc = str(item_cont.select('span#viewProductDescription')[0].text)
				code = str(item_cont.select('span#viewProductFoot tr > td')[5]).lstrip('<td>').rstrip('</td>')
				print(item_name+' - '+code)
				self.dbInsert(item_name, item_desc, 'duschi/'+image_name, str(items[1]), code)
			except:
				print(sys.exc_info())


	def run(self):
		while self.running:
			self.getItemsDataFromDuschy()

# ========================================================================================================= #
# pobieram linki z itemami zeby poetm wielowatkowo pobierac z tych stron dane i jeb do bazy
Duschy.getCategoryLinks(Duschy)
print('Zgarniete linki kategorii')
Duschy.getItemsLinks(Duschy)
print('++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++')
i=0
for f in Duschy.item_links:
	print(str(i)+' >>>>>> ' + str(f[1]) + ', ' + str(f[0]))
	i += 1

i = 0
threads = [Duschy(i) for i in range(0, 1)]
for t in threads:
	try:
		t.start()
	except:
		exit()
