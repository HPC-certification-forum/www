#!/usr/bin/env python3

import sqlite3
import time
import sys
import json
import re
import subprocess
import gnupg

from createCertificate import createCertificate

now = time.time()
retry_delay_days = 7

dry_run = True

o = sqlite3.connect('db/statistics/results.db')
o.execute("create table if not exists t (submissionTime INTEGER, exam TEXT,  affiliation TEXT)")
o.execute("create table if not exists exam (json TEXT)")

c = sqlite3.connect('db/examinations.db')
# CREATE TABLE t (deadline INTEGER, exam TEXT, name TEXT PRIMARY KEY, email TEXT, affiliation TEXT, seed INTEGER, score FLOAT);

# Get current data
cursor = c.execute('SELECT * FROM t')
names = list(map(lambda x: x[0], cursor.description))
expected = ['deadline', 'exam', 'name', 'email', 'affiliation', 'seed', 'score']

# positions
SCORE = 6
SEED = 5
AFFILIATION = 4
EMAIL = 3
NAME = 2
EXAM = 1
DEADLINE = 0

# Verify schema
for x in range(0, len(expected)):
  if names[x] != expected[x]:
    print("Error on position %d" % x)
    sys.exit(1)


# purge entries older retry_delay_days, where the exam hasn't been submitted OR it was marked as too low
c.execute('delete from t where deadline < ? AND score < 0', (now + 3600*24*retry_delay_days,))

def cleanString(str):
  return re.sub('[^A-Za-z0-9\-]', '', str)

def loadCertificate(name):
  with open('./exam/%s.json' % name) as json_file:
    return json.load(json_file)
  return None

def submitted_filename(name, seed):
  name = cleanString(name)
  return "./db/submissions/%s-%s.md" % (name, seed)

def update_statistics(db, cert, row):
  file = submitted_filename(row[NAME], row[SEED])
  p = subprocess.Popen(('./examiner -r %d check -v -p -f %s' % (row[SEED], file)).split(" "), stdout=subprocess.PIPE, stderr=subprocess.STDOUT)
  lines = p.stdout.readlines()
  retval = p.wait()

  load = json.loads(b"".join(lines).decode("utf-8"))
  del load["metadata"]
  del load["nonce"]
  del load["checksum"]
  del load["timeout"]
  db.execute("INSERT INTO t VALUES(?, ?, ?)", (row[DEADLINE] + cert["deadline"] * 60, row[EXAM], row[AFFILIATION]))
  db.execute("INSERT INTO exam VALUES(?)", (json.dumps(load),))

def generate_certificate(cert, row):
  timer = time.gmtime(row[DEADLINE] + cert["deadline"] * 60)
  year = timer.tm_year
  month = "{:02d}".format(timer.tm_mon)
  date = "%s/%s" % (month, year)

  data = {}
  data["date"] = date
  data["certName"] = cert["name"]
  data["certID"] = cert["id"]
  data["name"] = row[NAME]
  data["email"] = row[EMAIL]

  # data to encrypt into the URL
  encData = [row[NAME], date, cert["id"]]

  gpg = gnupg.GPG()
  #crypted = gpg.encrypt(json.dumps(encData), recipients="HPC Certification Forum")
  #crypted = str(crypted).replace('\n', '')
  #crypted = re.sub(r"-----BEGIN PGP MESSAGE-----(.*)-----END PGP MESSAGE-----", r"\1", crypted)
  #crypted = crypted.replace('+', '.').replace('/', '_').replace('=', '-')

  data["url"] = "https://hpc-certification.org"
  #data["url"] = "https://hpc-certification.org/verify?d=" + crypted


  outputFile = "certificates/%s" % cleanString(data["name"])
  createCertificate(outputFile + ".pdf", data)

  crypted = gpg.encrypt(json.dumps(encData), recipients="HPC Certification Forum")
  crypted = str(crypted).replace('\n', '')
  crypted = re.sub(r"-----BEGIN PGP MESSAGE-----(.*)-----END PGP MESSAGE-----", r"\1", crypted)
  crypted = crypted.replace('+', '.').replace('/', '_').replace('=', '-')
  URL = "https://hpc-certification.org/verify?d=" + crypted


  # data to encrypt and return as TXT file
  txt = "HPC Certification Forum Certificate\n\nThis text confirms that \"%s\"\nhas successfully obtained the certificate\n\"%s\" (id: %s) at %s.\n\nVerification URL: %s" % (row[NAME], cert["name"], cert["id"], date, URL)
  crypted = gpg.sign(txt, keyid="0x74E894B4CAEB81B5")
  with open(outputFile + ".txt", "w") as fd:
    fd.write(str(crypted))
  email = ""


def check_generate_certificate(cert, row):
  file = submitted_filename(row[NAME], row[SEED])

  with open(file) as json_file:
    load = json.load(json_file)

    metadata = load["metadata"]
    # check metadata for consistency
    if metadata["name"] != row[NAME] or metadata["email"] != row[EMAIL]:
      print("Error, metadata not matching")
      return False
    generate_certificate(cert, row)
    return True
  return False

purge_files = []

# now perform an automatic grading
for row in cursor:
  if row[SCORE] > 0:
    cert = loadCertificate(row[EXAM])
    if row[SCORE] * 100 >= cert["minimum percentage"]:
      print("Success for " + str(row))
      if check_generate_certificate(cert, row):
        update_statistics(o, cert, row)
        purge_files.append(submitted_filename(row[NAME], row[SEED]))
        c.execute('delete from t where name == ?', (row[NAME],))
    else:
      waiting = (now - row[DEADLINE]) / 3600 + 24*retry_delay_days
      if waiting > 0:
        msg = "must wait for %d hours" % waiting
      else:
        msg = "deleting attempt"
        c.execute('delete from t where name == ?', (row[NAME],))
        update_statistics(o, cert, row)
        purge_files.append(submitted_filename(row[NAME], row[SEED]))

      print("Failed the minimum percentage (%s) " % msg + str(row))


if not dry_run:
  c.commit()
c.close()

if not dry_run:
  o.commit()
o.close()

for f in purge_files:
  print("Removing %s" % f)
  if not dry_run:
    os.remove(f)
