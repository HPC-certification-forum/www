import re
import sys

def addAnswer(goodAnswers, badAnswers, answerResult, answerText):
	if answerResult == "right":
		goodAnswers.append(answerText.strip())
	elif answerResult == "wrong":
		badAnswers.append(answerText.strip())
	else:
		sys.exit("assertion failed: invalid answer result '" + answerResult + "'")

def parseFile(path: str) -> list:
	with open(path, 'r') as catalogue:
		questionHeaderRegex = re.compile(r"^#([^#].*)$")
		questionTypeRegex = re.compile(r"^##([^#].*)$")
		selectMultipleItemRegex = re.compile(r"^  +\* *_(right|wrong)_ *(.*)")
		emptyLineRegex = re.compile(r"^[ 	]*$")

		questions = []
		status = "start"
		for line in catalogue:
			if status == "start":
				match = re.match(questionHeaderRegex, line)
				if match:
					skillId = match.group(1)
					questionText = ""
					status = "question"

			elif status == "question":
				match = re.match(questionTypeRegex, line)
				if not match:
					questionText = questionText + line
				else:
					questionType = match.group(1)
					if questionType == "select multiple":
						goodAnswers = []
						badAnswers = []
						status = "answer list"
					elif questionType == "free form text":
						questions.append({
							'skill': skillId,
							'question': questionText.strip(),
							'type': questionType})
						status = "start"

			elif status == "answer list":
				match = re.match(selectMultipleItemRegex, line)
				if match:
					answerResult = match.group(1)
					answerText = match.group(2)
					status = "answer"
				elif re.match(emptyLineRegex, line):
					pass
				else:
					sys.exit("error: expected answer list item, got: '" + line + "'")

			elif status == "answer":
				answerMatch = re.match(selectMultipleItemRegex, line)
				questionMatch = re.match(questionHeaderRegex, line)
				if answerMatch:
					addAnswer(goodAnswers, badAnswers, answerResult, answerText)

					answerResult = answerMatch.group(1)
					answerText = answerMatch.group(2)
				elif questionMatch:
					addAnswer(goodAnswers, badAnswers, answerResult, answerText)
					questions.append({
						'skill': skillId,
						'question': questionText.strip(),
						'type': questionType,
						'good': goodAnswers,
						'bad': badAnswers})

					skillId = questionMatch.group(1)
					questionText = ""
					status = "question"
				else:
					answerText = answerText + line

			else:
				sys.exit("assertion failed: unexpected status")

		if status == "start" or status == "question" or status == "answer list":
			sys.exit("preliminary end of file, status = " + status)
		elif status == "answer":
			addAnswer(goodAnswers, badAnswers, answerResult, answerText)
			questions.append({
				'skill': skillId,
				'question': questionText.strip(),
				'type': questionType,
				'good': goodAnswers,
				'bad': badAnswers})
		else:
			sys.exit("assertion failed: unexpected status '" + status + "'")

		return questions
	sys.exit("error opening file '" + path + "'")
