import pseudoRandom
import sys

def selectQuestions(rng: pseudoRandom.PseudoRandom, questions: list, questionCount: int, answersPerQuestion: int, includeAnswers: bool) -> list:
	if len(questions) < questionCount:
		sys.exit("error: cannot generate more test questions than there are questions in the catalogue")

	questionsCopy = questions.copy()
	result = []
	for i in range(0, questionCount):
		curQuestion = rng.popRandItem(questionsCopy)
		if curQuestion["type"] == "free form text":
			result.append(curQuestion)
		elif curQuestion["type"] == "select multiple":
			goodAnswers = curQuestion["good"].copy()
			badAnswers = curQuestion["bad"].copy()
			curAnswerCount = min(len(goodAnswers), len(badAnswers), answersPerQuestion)
			answers = []
			for i in range(0, curAnswerCount):
				if rng.randInRange(0, 2):
					curAnswer = {'text': rng.popRandItem(goodAnswers)}
					if includeAnswers: curAnswer['result'] = True
				else:
					curAnswer = {'text': rng.popRandItem(badAnswers)}
					if includeAnswers: curAnswer['result'] = False
				answers.append(curAnswer)
			result.append({
				'skill': curQuestion["skill"],
				'question': curQuestion["question"],
				'type': curQuestion["type"],
				'answers': answers})
		else:
			sys.exit("error: unexpected question type '" + curQuestion["type"] + "'")
	return result

def compareMember(objectA, objectB, member: str) -> None:
	"""Tristate comparison: Return None if "member" does not exist in either object, False if it exists but differs, and True if it exists and is equal."""
	try:
		return objectA[member] == objectB[member]
	except:
		return None

def gradeTest(reference, submission):
	"""Compare the reference test (which should be generated including answers) with a submission (which should include user-provided answers), and generate a grade.

	If the structure of the reference and submission differ, None is returned to signal an invalid submission.
	Otherwise, the returned grade is a value between 0 and 1, where a grade of 0 is awarded for either no answers at all, or exactly 50% correct answers,
	i.e. just answering randomly will give the same result as not answering at all.
	On the other hand, a grade of 1 is awarded if, and only if all answers are correct."""

	minPoints = 0	#the sum of all the points that would be awarded to a blank submission
	points = 0	#the sum of all the points that are actually awarded
	maxPoints = 0	#the sum of all the awardable points

	if len(reference) != len(submission): return None
	for i in range(0, len(reference)):
		#consistency checking of question in submission
		if not compareMember(reference[i], submission[i], "skill"): return None
		if not compareMember(reference[i], submission[i], "question"): return None
		if not compareMember(reference[i], submission[i], "type"): return None
		if reference[i]["type"] == "free form text": continue
		if reference[i]["type"] != "select multiple": return None

		try:
			if len(reference[i]["answers"]) != len(submission[i]["answers"]): return None
		except:
			return None
		for j in range(0, len(reference[i]["answers"])):
			#consistency checking of answer text in submission
			refAnswer = reference[i]["answers"][j]
			submitAnswer = submission[i]["answers"][j]
			if not compareMember(refAnswer, submitAnswer, "text"): return None

			#the grading comparison
			result = compareMember(refAnswer, submitAnswer, "result")
			submission[i]["answers"][j]["correct"] = result

			#update the points
			minPoints += 0.5
			maxPoints += 1
			if result:
				points += 1
			elif result == None:
				points += 0.5
			else:
				points += 0

	#convert points to grade
	if points >= minPoints:
		return (points - minPoints)/(maxPoints - minPoints)
	else:
		#It's possible to get less than minPoints by answering worse than a random monkey.
		#However, in that case we just grade with 0 instead of returning a negative grade.
		return 0
