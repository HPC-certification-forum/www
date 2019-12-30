#! /usr/bin/env python3

import hashlib
import os
import random

class PseudoRandom:
	def __init__(self, seed:bytes = None, seedInt: int = 0):
		if seedInt != 0:
			random.seed(seedInt)
			txt = ""
			for i in range(1, 32):
				txt = txt + str(random.randint(0,9))
			self.seed = txt.encode("utf-8")
		else:
			if seed:
				self.seed = seed
			else:
				self.seed = os.urandom(32)

		self.hasher = hashlib.sha256(self.seed)
		firstBits = self.hasher.digest()
		self.hasher.update(firstBits)
		self.bits = firstBits

	def getSeed(self) -> bytes:
		return self.seed

	def randBytes(self, count: int) -> bytes:
		while len(self.bits) < count:
			newBits = self.hasher.digest()
			self.hasher.update(newBits)
			self.bits = self.bits + newBits
		result = self.bits[:count]
		self.bits = self.bits[count:]
		return result

	def randInRange(self, minVal: int, boundVal: int) -> int:
		rangeSize = boundVal - minVal
		if rangeSize <= 0: return None

		bitCount = 0
		bound = 1
		while bound < rangeSize:
			bitCount += 1
			bound *= 2
		byteCount = (bitCount + 7)//8

		while True:
			bits = self.randBytes(byteCount)
			randVal = 0;
			for i in range(0, byteCount):
				randVal = 256*randVal + bits[i]
			randVal &= 2**bitCount - 1
			if randVal < rangeSize:
				return randVal + minVal

	def popRandItem(self, items: list):
		if len(items) == 0: return None

		return items.pop(self.randInRange(0, len(items)))

#execute test code if run as a script
if __name__ == '__main__':
	import sys

	prng = PseudoRandom()
	storedSeed = prng.getSeed()
	storedSequence = [prng.randInRange(0, 1000000) for i in range(0, 1000)]

	for i in range(1, 1024):
		value = prng.randInRange(42, 42 + i)
		if value < 42 or value >= 42 + i:
			sys.exit("randInRange() returned value outside of range")

	counts = [0]*10
	for i in range(0, 1024):
		value = prng.randInRange(0, 10)
		counts[value] += 1
	if min(counts) == 0:
		sys.exit("randInRange() does not seem to be able to return all values in range")

	prng = PseudoRandom(storedSeed)
	for i in range(0, 1000):
		if storedSequence[i] != prng.randInRange(0, 1000000):
			sys.exit("failure to recreate the sequence with stored seed")
