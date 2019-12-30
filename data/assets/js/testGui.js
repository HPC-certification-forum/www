var gTestGui = {};
var initTestGui = function() {
	gTestGui.containerTag = $("#testContainer");
	gTestGui.container = $("#testContainer")[0];

	gTestGui.selectAnswer = function(questionId, answerId, value) {
		if(value == "wrong") {
			gTestGui.activeTest.questions[questionId].answers[answerId].result = false;
		} else if(value == "right") {
			gTestGui.activeTest.questions[questionId].answers[answerId].result = true;
		} else {
			delete gTestGui.activeTest.questions[questionId].answers[answerId].result;
		}
	};

	gTestGui.makeElement = function(elementTag, elementClass, content) {
		var result = document.createElement(elementTag);
		result.setAttribute("class", elementClass);
		if(content) result.innerHTML = content;
		return result;
	};

	gTestGui.makeRadio = function(questionId, answerId, value, checked) {
		var input = gTestGui.makeElement("input", "answer-radio-button");
		input.setAttribute("name", "question" + questionId + "answer" + answerId);
		input.setAttribute("type", "radio");
		if(checked) input.setAttribute("checked", "checked");
		input.setAttribute("onclick", "gTestGui.selectAnswer(" + questionId + ", " + answerId + ", '" + value + "')");
		var result = gTestGui.makeElement("label", "label-" + value);
		result.appendChild(input);
		result.innerHTML += value + " ";
		return result;
	};

	gTestGui.submitTest = function(test) {
		test.metadata = JSON.parse($("#testMetadata")[0].innerHTML);
		var request = {
			url: gTestGui.containerTag.attr("data-submission-url"),
			type: "POST",
			//FIXME: JQuery will parse the response assuming the same format as what was sent, which leads to an error with the test servers' non-JSON response.
			//Using "text" works because it does not perform any transformation in either direction.
			//Once we are using a server that returns a valid JSON text, this should be changed back to "json".
			dataType: "text",
			data: JSON.stringify(test),
			success: function(data) {
				try {
					var data = JSON.parse(data);
					if ("return" in data &&
					data["return"] == 0){
						var element = document.getElementById("inner");
						element.innerHTML = "<h1>Test was successfully submitted</h1><p>Now you must wait for the manual assessment. The results will be sent to you via email.</p>";
					}else{
						alert("Error submitting the test!");
					}
				}catch(err){
						alert(data);
				}
			},
			error: function(jqXHR, textStatus, error) {
				alert("Unexpected error while submitting test! Please retry!");
				console.log("jqXHR: ", jqXHR);
				console.log("status: ", textStatus);
				console.log("error: ", error);
			}
		};
		$.ajax(request);
	};

	//Create a UI for the given test to let the user take it.
	gTestGui.startTest = function(test) {
		//Give gTestGui access to the test data.
		gTestGui.activeTest = test;

		//Clear the container.
		gTestGui.container.innerHTML = "";

		//Build the contents of the container.
		for(var i in test.questions) {
			var questionContainer = gTestGui.makeElement("div", "question-container");

			var questionTitle = gTestGui.makeElement("h3", "question-title", "Question " + (+i + 1));
			questionContainer.appendChild(questionTitle);

			var questionText = gTestGui.makeElement("p", "question-text", test.questions[i].question);
			questionContainer.appendChild(questionText);

			var answersContainer = gTestGui.makeElement("div", "answer-container");
			if(test.questions[i].type == "select multiple") {
				var answersTitle = gTestGui.makeElement("h4", "answers-title", "Mark answers as right or wrong.<br>Zero, one, several, or all of the answers may be correct.");
				answersContainer.appendChild(answersTitle);

				var answersForm = gTestGui.makeElement("form", "answers-form");
				for(var j in test.questions[i].answers) {
					var curFieldset = gTestGui.makeElement("fieldset", "answer-fieldset", "<legend>Answer " + (+j + 1) + ": <b>" + test.questions[i].answers[j].text + "</b></legend>");
					curFieldset.appendChild(gTestGui.makeRadio(i, j, "wrong", false, test.questions[i].answers[j]));
					curFieldset.appendChild(gTestGui.makeRadio(i, j, "unknown", true, test.questions[i].answers[j]));
					curFieldset.appendChild(gTestGui.makeRadio(i, j, "right", false, test.questions[i].answers[j]));
					answersForm.appendChild(curFieldset);
				}
				answersContainer.appendChild(answersForm);
			}
			questionContainer.appendChild(answersContainer);

			gTestGui.container.appendChild(questionContainer);
			gTestGui.container.appendChild(document.createElement("hr"));
		}

		//Create the block with submission details.
		var metadata = JSON.parse($("#testMetadata")[0].innerHTML);
		var submissionFields = gTestGui.makeElement("fieldset", "submission-fieldset", "<legend>Submitter information</legend>");
		submissionFields.appendChild(gTestGui.makeElement("p", "submiter-info",
			"Name: <b>" + metadata.name + "</b><br>" +
			"Email: <b>" + metadata.email + "</b><br>" +
			"Affiliation: <b>" + metadata.affiliation + "</b>"));
		submissionFields.appendChild(gTestGui.makeElement("p", "submission-warning",
			"Please check the correctness of the Name, Email address, and Affiliation.<br>" +
			"<b>Test submissions cannot be undone, the values above will be used to create your certificate</b>."));
		var submitButton = gTestGui.makeElement("input", "submit-button");
		submitButton.setAttribute("type", "button");
		submitButton.setAttribute("value", "submit test");
		submissionFields.appendChild(submitButton);
		gTestGui.container.appendChild(submissionFields);
		submitButton.onclick = function(){ gTestGui.submitTest(test); };
	};

	//Signal to the user that an error occurred.
	gTestGui.handleError = function(jqXHR, textStatus, error) {
		gTestGui.container.innerHTML = "error while loading file from '" + gTestGui.containerTag.attr("data-test-url") + "':<br>status = " + textStatus + "<br>error = " + error;
	}
};

$().ready(function() {
	initTestGui();

	//Load the individualized test.
	$.ajax({
		url: gTestGui.containerTag.attr("data-test-url"),
		dataType: "json",
		mimeType: "application/json",
		success: gTestGui.startTest,
		error: function(jqXHR, textStatus, error) {
			console.log("jqXHR: '", jqXHR, "'");
			console.log("textStatus: '", textStatus, "'");
			console.log("error: '", error, "'");
		}
	});
});
