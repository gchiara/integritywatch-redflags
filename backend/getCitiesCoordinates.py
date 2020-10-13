from pathlib import Path
import os
import mimetypes
import glob
import json
import csv
import requests
import re
import urllib.parse
from geopy.geocoders import Nominatim

geolocator = Nominatim(user_agent="IntegrityWatch EU")

cities = []

with open('cities.json', encoding='utf8') as json_file:
	cities = json.load(json_file)
	i = 0
	nonParsedCount = 0;
	parsedWithFullAddress = 0;
	for city in cities:
		location = geolocator.geocode(city["city"])
		if location:
			print("found at first attempt")
			city["coordinates"] = {}
			city["coordinates"]["lat"] = location.latitude
			city["coordinates"]["lon"] = location.longitude
			print(location)
			print((location.latitude, location.longitude))
			print("---")
		else:
			print("no location found")
			print("---")
			nonParsedCount += 1
		print(i)
		i += 1

	with open('cities_geocoded.json', 'w', encoding='utf8') as outfile:
		json.dump(cities, outfile, indent=4, sort_keys=True, ensure_ascii=False)
	print("Not found: ", nonParsedCount)