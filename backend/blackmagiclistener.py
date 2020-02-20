#!/usr/bin/python
import sys, re
import telnetlib
import json
import memcache
import os, socket
import time

host = os.environ['VIDEOHUB_IP']
port = 9990
CONFIG = {};
CONFIG['memcache_host'] = os.environ['CACHE_IP']
CONFIG['memcache_port'] = "11211"
CONFIG['memcache_debug'] = "0"

initialStatusOptions = ['ALARM STATUS:', 'SERIAL PORT STATUS:', 'SERIAL PORT DIRECTIONS:', 'SERIAL PORT ROUTING:', 'SERIAL PORT LOCKS:', 'SERIAL PORT LABELS:', 'VIDEO OUTPUT STATUS:', 'VIDEO OUTPUT ROUTING:', 'VIDEO OUTPUT LOCKS:', 'OUTPUT LABELS:', 'VIDEO INPUT STATUS:', 'INPUT LABELS:', 'VIDEOHUB DEVICE:']

def memcached_connection(host, port, debug=0):
	return memcache.Client(['{0}:{1}'.format(host, port)], debug)

def update_routing(client, line):
	ports = line.split(" ")
	port_output = int(ports[0])
	port_input = int(ports[1])
	routings = json.loads(client.get('L'))

	output_object = {
		'Label'  : outputLabels[port_output],
		'Source' : port_input
	}

	# update the output port with the new input port
	routings[port_output] = output_object
	# json encode list of routings
	routings = json.dumps(routings)

	# update cache
	client.delete('L')
	client.set('L', routings)


def memcached_test(client, port_type="TEST_I"):
	memcached.set(port_type, [{0 : 'Flame 1'}, {1 : 'Flame 2'}])
	value = memcached.get(port_type)
	print value

	memcached.set(port_type, [{0 : 'Flame 1'}, {1 : 'Flame 2'}, {2 : 'Flame 3'}])
	value = memcached.get(port_type)
	print value

	sys.exit(0)


def readLabels(data):
	inputs = {}
	lines = data.split('\n')
	for line in data.split('\n'):
		if line != '':
			i = line.split(' ')
			try:
				x = int(i[0])
				inputs[x] = ' '.join(i[1:])
			except:
				pass
	return inputs

if __name__ == "__main__":

	# BEGIN test
	memcached = memcached_connection(CONFIG['memcache_host'], CONFIG['memcache_port'], CONFIG['memcache_debug'])

	tn = telnetlib.Telnet(host, port)

	vorUpdateRegex = re.compile('^VIDEO OUTPUT ROUTING:')

	videohubDeviceData = tn.read_until('INPUT LABELS:\n')
	inputLabelData = tn.read_until('VIDEO INPUT STATUS:\n')
	videoInputStatusData = tn.read_until('OUTPUT LABELS:\n')
	outputLabelData = tn.read_until('VIDEO OUTPUT LOCKS:\n')
	videoOutputLocksData = tn.read_until('VIDEO OUTPUT ROUTING:\n')
	videoOutputRoutingData = tn.read_until('VIDEO OUTPUT STATUS:\n')



	inputLabels = readLabels(inputLabelData)
	outputLabels = readLabels(outputLabelData)
	outputLocks = readLabels(videoOutputLocksData)
	outputRouting = readLabels(videoOutputRoutingData)

	memcached.delete("I")
	memcached.delete("O")

	# new code

	# storing into memory cache

	# caching input labels
	tmpInputs = []
	inputs = []
	for key in inputLabels:
		tmpInputs.append({'Id' : key, 'Label' : inputLabels[key]})

	inputs = json.dumps(tmpInputs)
	memcached.set("I", inputs)
	# print memcached.get("I")

	# caching output labels
	# get output labels
	tmpOutputs = []
	outputs = []
	for key in outputLabels:
		tmpOutputs.append({'Id' : key, 'Label' : outputLabels[key]})

	outputs = json.dumps(tmpOutputs)
	memcached.set("O", outputs)
	# print memcached.get("O")

	routingList = []
	for key in outputRouting:
		currentPort = {
			'Label'  : outputLabels[key],
			'Source' : outputRouting[key],
		}

		routingList.append(currentPort)

	routingList = json.dumps(routingList)
	memcached.set("L", routingList)

	test_routing = None
	while True:

		line = tn.read_until("\n")

		if line == 'VIDEO OUTPUT ROUTING:\n':
			line = tn.read_until("\n")
			print "UPDATE ROUTE %s" % line
			update_routing(memcached, line)

		elif line == 'VIDEO OUTPUT LOCKS:\n':
			line = tn.read_until("\n")
			print " VIDEO OUTPUT LOCKS %s" % line
		else:
			pass

	tn.close()
