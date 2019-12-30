#!/usr/bin/env python3
# -*- coding: utf-8 -*-

import traceback
import csv
import sys

# This script generates a certificate.
# JK 2018

import sys
import os.path
from PIL import Image
from reportlab.pdfgen import canvas
from reportlab.lib.units import inch, cm
from reportlab.pdfbase import pdfmetrics
from reportlab.pdfbase.ttfonts import TTFont

# We use the Arimo font
pdfmetrics.registerFont(TTFont('Arimo-Bold', 'Arimo-Bold.ttf'))
pdfmetrics.registerFont(TTFont('Arimo', 'Arimo-Regular.ttf'))

class PDFDoc():
  def __init__(self, name, width, height):
    self.dim = (width*cm, height*cm)
    self.c = canvas.Canvas(name, pagesize=self.dim)

  def newpage(self):
    self.c.showPage()

  def save(self):
    self.c.showPage()
    self.c.save()

  def findImg(self, fileimg):
    fileimg = fileimg.replace(" ", "-").lower()
    for typ in ["png", "jpg"]:
      fname = fileimg + "." + typ
      if os.path.isfile(fname):
        return fname
    return None

  def addKeyVal(self, value, posx, posy, size=0.1, font= "Arimo", color=(0,0,0)):
    if value == None or len(value) == 0:
      return
    #relative font size
    self.c.setFont(font, self.dim[1] * size)
    self.c.setStrokeColorRGB(*color)
    self.c.setFillColorRGB(*color)
    value = value.strip().split("\n")
    for l in value:
      self.c.drawCentredString(self.dim[0] * posx, self.dim[1] * posy, l)

def adjSize(name, size, maxlen):
  if len(name) > maxlen:
    return size * maxlen / len(name)
  return size

def createCertificate(outputFile, data):
  dim = (29.7, 21.0)
  doc = PDFDoc("foreground.pdf", dim[0], dim[1])

  name = data["name"]
  size = adjSize(name, 0.09, 20)
  doc.addKeyVal(name, 0.5, 0.51, size, font="Arimo-Bold")

  doc.addKeyVal(data["date"], 0.655, 0.34, size=0.034, font="Arimo-Bold")
  certID = data["certID"]

  size = adjSize(certID, 0.07, 10)
  doc.addKeyVal(certID, 0.484, 0.315, size, font="Arimo-Bold")

  str = data["certName"]
  size = adjSize(str, 0.07, 10)
  doc.addKeyVal(str, 0.484, 0.07, size, font="Arimo-Bold")

  doc.addKeyVal(data["url"], 0.484, 0.01, size, font="Arimo-Bold", color=(0.5,0.5, 0.5))

  doc.save()

  from PyPDF2 import PdfFileWriter, PdfFileReader
  import io
  background = PdfFileReader(open("certificate-empty.pdf", "rb"))
  foreground = PdfFileReader(open("foreground.pdf", "rb"))
  page = background.getPage(0)
  page.mergePage(foreground.getPage(0))
  output = PdfFileWriter()
  output.addPage(page)
  outputStream = open(outputFile, "wb")
  output.write(outputStream)
  outputStream.close()
