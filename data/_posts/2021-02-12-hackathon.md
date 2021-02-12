---
layout: post
title:  "Lessons learned from the 1st Certification Hackathon"
date:   2021-02-12
description: The HPC Certification forum organized a <a href="/2021/01/20/hackathon.html">hackathon</a> in order to collaboratively review and expand the question pool for the first certificate to come. In this blog post, we share our conclusions from the meeting and its implications on the mission of the HPC CF.
nolink: False
---

The HPC Certification forum organized a [hackathon](/2021/01/20/hackathon.html) in order to collaboratively review and expand the question pool for the first certificate to come.

In this blog post, we share our conclusions from the meeting and its implications on the mission of the HPC CF.
In total 15 attendees from different data centers and academic institutions from Europe, UK and USA attended the session, they discussed and amended the current question pool  for the skill USE1.1-B Command Line Interface. This specific skill already consists of 22 fine-grained learning objectives and various questions have been submitted to it.
For this skill, we aim for a question pool with about 100 questions, which means about 5 questions per learning objective.
It was pointed out that while the HPC CF aims to certify all flavours of competences  relevant in HPC, we need to start the certification process of even a very limited subset to gain further traction.
Note that HPC CF has already identified more than 200 competences and for each learning objective of a competence, several questions would be necessary to provide a question pool suitable for the random selection of questions per learning objective.


# Question pool
First, everyone creating questions for a HPC CF skill needs to understand how questions are selected for a certificate that requires a certain skill.
For each competence, a selected number of questions is chosen (e.g., 2 questions), and for each question a random set of potential answers is selected (typically 4 answers). In the exam, an examinee needs to select for each answer individually if the particular statement is “correct” or “wrong”.  Any wrong answer deducts points from the obtained score.
A question in the pool may have originally up to 10 answers.

# Lessons learned
As expected a clear specification of the skills is essential to allow everyone to share the same idea about a competence and be able to contribute high-quality questions.
This includes a description of the background to clarify the concepts behind the skill, the goal and the learning objectives.
Moreover, an understanding of the inter-relationship between skills supports the creation of questions as it is likewise important to identify questions that do not fit to a specific skill or learning objective. In our sessions, it was discussed whether or not to include certain commands such as “less” or specific editors. We converged to clarify the meaning of the skill, in particular, the skill aims to be for newcomers and shall cover basics to overcome typical misconceptions that make them stuck when interacting with the terminal. Therefore, conception-wise these skills will be covered in other skills, for example, related to file-system specifics or editors.
We must be agile and willing to adjust a skill slightly as during the formulation of questions the full scope becomes clear. Naturally, that means we cannot finalize a specific competence standard without having a sufficient question pool at hand.

The formulation of proper questions is not trivial and expertise to formulate high-level questions that fit into the concepts must be acquired by contributors.
Several guidelines for the formulation of a question were discussed during the meeting:
  * A question and each answer must be absolutely precise.
  * It must only use learning objectives covered by the specific skill and must follow them closely. However, it is possible to embed further knowledge in the question itself. For example, for a learning objective about the program sort and the piping of input/output: Assume the program “ls” shows all files. How can you obtain a sorted list of all the files?
  * It may cover multiple learning objectives of the same skill but should be submitted/listed under the most fitting learning objective.
  * It should be positively formulated such that “true”/”false” statements of individual answers do not have to be negated, e.g., don’t ask: “which of the following is *not* something” but “which is something”
  * Write answers individually, i.e., there shall be no reference between answers. E.g., don’t write something like: “all the above are correct” as the presented set of answers are randomly picked out of all possible answers and ordered randomly
  * Multiple answers should be correct and wrong as the examinee shall think about every answer individually from all other answers. A question should have at least 5 answers. Do not ask “which of the following answers is the only right way to do sth” or “all the above are correct”.
  * Reviews of the question pool by at least two additional readers with a diverse background are mandatory.

# Conclusions
We conclude that the format is fruitful to establish understanding in the community and to expand the question pool.
It seems vital for any potential contributor to participate once in such a session to clarify misconceptions and to ensure that contributed questions fit into the joint framework. Therefore, we aim to organize hackathon sessions more frequently.
Additional suggestions where to consider the gamification of the process; by providing a leaderboard contributors might be encouraged to submit more questions. We could also consider asking anyone that succeeds with completing a certificate to contribute some questions to the skills covered in the certificate - as the HPC certification is free for participants, it would ensure participation.
We will also explore other question types and interactive scenarios as the nature of multiple choice limits the scope. Nevertheless, multiple choice will remain an important pillar in the overall process.

Finally, we know the HPC CF and all its processes is not yet perfect but overall the attendees agreed that it is a huge step forward for the community. Therefore, we encourage everyone to participate to ensure it serves its purpose for the community.
