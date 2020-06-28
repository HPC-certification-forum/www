---
layout: page
title: Processes
permalink: /processes/
category: "processes"
order: 3
backgroundID: processes
---
{::options parse_block_html="true" /}

This page describes various processes of the HPC Certification Forum.
Note that not all processes are completely implemented, yet.


<div id="accordion">


<div class="card">
<div class="card-header">
<h2 id="skills_"><a class="card-link" data-toggle="collapse" href="#skills">Competence Standard</a></h2></div>
<div id="skills" class="collapse" data-parent="#accordion">
  <div class="card-body">
  <div id="cs-accordion">

  <div class="card">
  <div class="card-header">
  <h3 id="cs-evolving_"><a class="card-link" data-toggle="collapse" href="#cs-evolving">Evolving the Standard</a></h3></div>
  <div id="cs-evolving" class="collapse" data-parent="#cs-accordion">
  <div class="card-body">

  Goal: The skill tree representing the competence standard will be developed and evolved as needed by the HPC community.
  This means that skills may be modified/added and removed to provide the best utility to the community.

#### Requirements for the development process

  * Stability of the skill tree and skill specification
  * Preservation of the history of the competence standard
  * Tools in the ecosystem shall be able to continue to work with any (previous) version of the standard

#### Classification of changes

  Changes to the competence standard are classified to indicate the intrusiveness of the change, a lower category is less intrusive than a higher category.
  This impacts the decision making process and versioning.

  The classifications of the modifications are:

  * On cs-level, i.e., modification involves only a single skill:
    - S0 changes clearly not influencing the semantics of the skill, e.g., fixing typos
    - S1 trivial clarifications of a skill specification or modifications of the background section
    - S2 non-trivial clarifications/extensions in background/learning outcomes
    - S3 adding/removing learning outcomes, changing the aim, ...
  * On tree-level
    - T0 adding a new skill on leaf-level that is specific knowledge/technology or adding a reference to an existing skill
    - T1 renaming/moving a skill from the current place to another
    - T2 splitting a skill that was specified too generic into subskills
    - T3 moving content of one skill to one or multiple other skills
    - T4 deleting a skill

  Note that all skill specifications together with the tree that defines their organization defines a HPC Competence Standard.
  This classification scheme shall be used by contributors in understanding the release and [versioning process](#cs-versions).

  </div>
  </div>
  </div>



  <div class="card">
  <div class="card-header">
  <h3 id="cs-versions_"><a class="card-link" data-toggle="collapse" href="#cs-versions">Versioning</a></h3></div>
  <div id="cs-versions" class="collapse" data-parent="#cs-accordion">
  <div class="card-body">

  A released skill tree together with all skill specifications has the version number: MAJOR.MINOR.
  The latest version of the skill tree is available with the tag “latest”.

  The version number is increased depending on the most disruptive [type of change made](/processes/#cs-evolving):
  * No version change is necessary if only changes of type S0 are made.
  * Minor version number:
    - S1
    - T0, T1
  * Major version number:
    - S2, S3
    - T2-T4


  </div>
  </div>
  </div>


  <div class="card">
  <div class="card-header">
  <h3 id="cs-releases_"><a class="card-link" data-toggle="collapse" href="#cs-releases">Release Management</a></h3></div>
  <div id="cs-releases" class="collapse" data-parent="#cs-accordion">
  <div class="card-body">

  The skills are version controlled in [GitHub](https://github.com/HPC-certification-forum/skill-tree).
  * There exists a current “working/development” version, which is the main branch in GitHub that is also editable using in the Wiki.
  * Each released version is tagged with the respective version tag.

  We aim to not have more than one major release of the competence standard per year: either announced at the Supercomputing conference or the ISC HPC conference.
  * Rationale: At these conferences we typically organize BoFs and we can announce the changes during the conferences.

#### Integration of changes

  Change requests are handled depending on the [severity of the change](/processes/#cs-versions):
  * S0: any HPCCF member can perform these changes and they are automatically accepted
  * S1, S2, T0, T2: the respective topic curator
  * T1: topic curator as long as the skill remains inside the topic
  * S3, T3, T4: Skill tree curator

  To prevent stagnation, if more than two topic curators agree on a change on tree level and no other topic curator disagrees, the skill tree curator must accept these changes except if new evidence is presented to not perform the changes.

  Changes are accepted until one month before a release by which time the standard is frozen.
  When the status is frozen, an initial tag is created with a new version number.
  After that period, only “bugfixes” or serious concerns may lead to a modification
  Changes considered to be non-ready may be withhold for the next release.

#### Conditions to make a release

  * For each leaf-level skill, at least a pool covering 50% of the required exam questions shall be available.
  * The skill tree was frozen at least one month ago and no additional concern was raised by any member within one week.

#### Release process

 The steering board represents the role of release manager, and, thus, owns the release management lifecycle for the competence standard which includes scheduling, coordinating and managing any release.
 The responsibility of the release manager is distributed among the roles of the steering board.

 In particular:
  1. The subtree topic chairs reports the readiness of the respective trees to the tree curator who reports the status of the next release during a board meeting.
  1. The board will decide upon the new version tag.
  1. Typically the tree curator will tag the accepted status with a new revision of the tree followed by "-rc" for release candidates, e.g., "v1.05-rc".
  1. The tree curator will announce the release candidate on Slack and the mailing list.
  1. The board shall checked the correct tagging and that existing tools work correctly with the new revision of the tree.
  1. The final release tag will be set, the "latest" tag will be bumped up to this veri.
  1. The general chair will announce the new revision on Slack and the mailing list.

  </div>
  </div>
  </div>


  <div class="card">
  <div class="card-header">
  <h3 id="cs-contributing_"><a class="card-link" data-toggle="collapse" href="#cs-contributing">Contributing</a></h3></div>
  <div id="cs-contributing" class="collapse" data-parent="#cs-accordion">
  <div class="card-body">

  Contribution to the competence standard are welcome.

  The standard is available in Markdown format and a wiki is available to render them directly online.
  The skills are structured in directories according to the hierarchy in the skill tree.
  The MindMap structures are synchronized with the tree directory to test more invasive changes on the tree level.

  Contributions to the skill definitions can be made by
  1. editing the skill definitions on the Wiki (particularly for S0, S1 and T1 changes)
  1. discussing them on Slack (it is a good idea to announce complex changes to the community in the #skill-tree channel)
  1. adjusting the cs-tree in the MindMap (editable via the FreePlane tool)
  1. directly preparing a GitHub pull request that changes the Markdown files in the community repository. As GitHub allows for commenting on individual lines, this provides means for rapid feedback as well.

#### Integration

  Any suggestion made by a contributor is reviewed by the community and the steering board before it will be included in a release of the standard.

  Ultimately, the curators of the respective skill sub-trees will be in charge of assessing the suggestions and modifications and reacting to change requests.
  At the moment, the process is lightweight, direct change requests are often directly accepted for the phase of building the first release version of the tree.

  </div>
  </div>
  </div>


  <div class="card">
  <div class="card-header">
  <h3 id="cs-guidelines_"><a class="card-link" data-toggle="collapse" href="#cs-guidelines">Guidelines for Contributions</a></h3></div>
  <div id="cs-guidelines" class="collapse" data-parent="#cs-accordion">
  <div class="card-body">

  By contributing to the standard, contributors agree to transfer their copyright to the HPCCF in accordance to the [CC BY licence terms](/processes/#cs-license).
  You **shall not** contribute anything that was developed by others and you do not have the explicit permissions and Copyright to do so.


  * Language should be: American English
  * Title with capital letters
    - Capitalize the first and last word
    - Capitalize verbs and helping verbs
    - Capitalize adjectives and adverbs
    - Do not capitalize short prepositions
    - Do not capitalize articles
    - Do not capitalize short [coordinating conjunctions](https://grammar.yourdictionary.com/capitalization/rules-for-capitalization-in-titles.html)
  * Aim with topics in the format
    - Each sentence is one (independent) aim.
    - To do (something) + sentence
  * Outcomes with topics in the format
    - Each sentence is one learning outcome, ending with “.”.
    - Verb (in the infinite) + sentence
    - Use action verbs from [Bloom’s taxonomy](https://tips.uark.edu/blooms-taxonomy-verb-chart/)
    - Items always start with a capital letter, commands (program invocations) are an exception


  </div>
  </div>
  </div>


  <div class="card">
  <div class="card-header">
  <h3 id="cs-license_"><a class="card-link" data-toggle="collapse" href="#cs-license">License Terms</a></h3></div>
  <div id="cs-license" class="collapse" data-parent="#cs-accordion">
  <div class="card-body">
  The HPCCF competence standard and provided tools are licensed under the CC BY 4.0 [Creative Commons Attribution 4.0 license](https://creativecommons.org/licenses/by/4.0/) if not stated explicitly otherwise in the tools.

  If any artifact (e.g., an excerpt of a competence or tool) is used outside the clear scope of the HPCCF, the license information must be provided:

    CC-BY-4.0 HPC Certification Forum

  </div>
  </div>
  </div>




  </div>
  </div>
</div>
</div>





<div class="card">
<div class="card-header"><h2 id="training_"><a class="card-link" data-toggle="collapse" href="#training">Training Material</a></h2></div>
<div id="training" class="collapse" data-parent="#accordion">
  <div class="card-body">
  <div id="train-accordion">

The HPC Certification Forum is not developing training material directly or competing with providers of training material.
However, we support individuals and institutions by endorsing and promoting their training materials and courses in two ways.

Firstly, an author is allowed to indicate on the training material itself or a related promotional material which skills are covered either fully or partially using our seal of endorsment.

Secondly, we will link from our webpage the endorsed training material covering the individual skills and certificates.
With our tools, we provide various views of the skills with links to suitable training materials that helps to promote compatible training.


#### Seal of endorsment

The seal of endorsement indicates that a specific training material covers certain skills from the competence standard.
<img src="/assets/img/endorsed-training.jpg" title="Example seal for endorsed training" style="width:30%; float:right"/>

The reference to the HPCCF and the seal can be used free of charge under the condition that the developer of the training material registers a link to the material (or course/event) on our webpage using our [online form](TODO).
To help you to create the appropriate formulation, you can select skills in the interactive skill tree and then navigate to the [selection tool](https://www.hpc-certification.org/wiki/selection/).
  * Rationale: the HPCCF maintains information about the usage of the seal.

Note that we are not verifing the correct usage of the seal explicitly.
However, in case the training material or course doesn’t deliver the expected material <span class="hint tpract">practitioners</span> may complain and we may be forced to remove the link to that training material from our webpage.

  </div>  
  </div>
</div>
</div>

<div class="card">
<div class="card-header"><h2 id="examination_"><a class="card-link" data-toggle="collapse" href="#examination">Certification/Examination</a></h2></div>
<div id="examination" class="collapse" data-parent="#accordion">
  <div class="card-body">
  <div id="exam-accordion">

A <span class="hint tpract">practitioner</span> can perform examinations to obtain certificates.

  <div class="card">
  <div class="card-header">
  <h3 id="exam_"><a class="card-link" data-toggle="collapse" href="#exam">Examination</a></h3></div>
  <div id="exam" class="collapse" data-parent="#exam-accordion">
  <div class="card-body">

In order to obtain a certificate, the <span class="hint tpract">practitioner</span> must perform an exam.
The examination process can be started at any time.

To clarify the behavior and privacy the process is documented as follows:
  1. The <span class="hint tpract">practitioner</span>  has to register on our exam page (TODO) using their name, email address and optionally an affiliation. On the page, the process is explained, together with policies for privacy and integrity.
  1. An encrypted token is created on the server that is send to the practitioner by email.
  Up to this stage, we do not store any information about the examinee on the server.
  1. The email contains a link that will start the examination. The examinee can choose to start the examination by clicking on it any time (within one week).
  1. The exam page is loaded and shown. The information about the user and the examination start time is stored in a temporary database.
  1. The practitioner completes the summative assessment which has a time limit. The assessment will consist of questions drawn from a pool for each of the skills examined.
  1. Once the examinee submits the completed exam, the selection of answers is transmitted to the server and stored until it is automatically assessed.
  1. The server performs the validation of the submitted answers at scheduled times. It either initiates certificate generation and sends the certificate to the examinee, or it informs the examinee that the pass criteria weren't met.
  1. If the exam wasn't passed, an estimate of achieved score is provided to the examinee for guidance. As the sole purpose of our service is the examination, the incorrectly answered questions will not be revealed.
  The practitioner may retry the exam after a cool-down period (typically one week), but not immediately afterwards to prevent success via brute force methods. Therefore, information about the exam attempt is stored in a database temporarily.
  Every day, the server will automatically purge information about attempts that exceeded the cool-down period. Note that the Forum's main concern is to provide the mechanism of certifying whether a learner posses specific knowledge or not and not pointing out gaps in understanding and support learning explicitly.
  1. If passed: The certificates are generated and send via email. All personal information about the examinee are deleted from the server.

  In either case, the affiliation and raw responses are preserved on the server.
  The affiliation is used for promotional purposes, while the raw responses will be analysed to optimise the questions.
  For example, if we identify that most users make the same mistake it may be an indication that either that question or its answer is too ambiguous and should be improved.
  Also, lecturers will profit from the feedback if particular questions reveal poor performance of practitioners of a particular HPC site.

  </div>
  </div>
  </div>


  <div class="card">
  <div class="card-header">
  <h3 id="exam-certificates_"><a class="card-link" data-toggle="collapse" href="#exam-certificates">Certificates</a></h3></div>
  <div id="exam-certificates" class="collapse" data-parent="#exam-accordion">
  <div class="card-body">

The examinees that pass the examination are awarded a corresponding certificate.
Such certificate consists of two parts: a PDF and a text file.
The PDF contains the key information making the certificate meaningful.
An example is given in the image.

<img src="/assets/img/jane-doe.jpg" title="Example certificate for Jane Doe" style="width:100%"/>

The name and identifier of the certificate is found in the centre, on our example these are "HPC Driving License" and ID 1, respectively.
Similar to a driving license, this particular certificate could provide the minimum set of knowledge required to understand and use a typical supercomputer.

The text file contains the same information, as well as a verification URL that can be given to a third-party to confirm the certificate's credibility.
Technically is is PGP signed using the private key of the HPC Certification Forum to allow verification with the public key.
An example file looks as follows:

      -----BEGIN PGP SIGNED MESSAGE-----
      Hash: SHA512
      HPC Certification Forum Certificate
      This text confirms that "Jane Doe" has
      successfully obtained the certificate
      "HPC driving license" (id: 1) on 02/2019.
      Verification URL: https://hpc-certification.org/[...]
      -----BEGIN PGP SIGNATURE-----
      [...]
      -----END PGP SIGNATURE-----


  </div>
  </div>
  </div>

  </div>    
  </div>
</div>
</div>


<div class="card">
<div class="card-header">
<h2 id="governance_"><a class="card-link" data-toggle="collapse" href="#governance">Governance</a></h2>
</div>
<div id="governance" class="collapse" data-parent="#accordion">
<div class="card-body">


<div id="gov-accordion">

  <div class="card">
  <div class="card-header">
  <h3 id="gov-conduct_"><a class="card-link" data-toggle="collapse" href="#gov-conduct">Code of Conduct</a></h3></div>
  <div id="gov-conduct" class="collapse" data-parent="#gov-accordion">
  <div class="card-body">
  We follow the [contributor covenant code of conduct](https://www.contributor-covenant.org/version/2/0/code_of_conduct/).

  In a nutshell, we as members, contributors, and leaders pledge to make participation in our community a harassment-free experience for everyone, regardless of age, body size, visible or invisible disability, ethnicity, sex characteristics, gender identity and expression, level of experience, education, socio-economic status, nationality, personal appearance, race, religion, or sexual identity and orientation.

  We pledge to act and interact in ways that contribute to an open, welcoming, diverse, inclusive, and healthy community.

  </div>
  </div>
  </div>

<div class="card">
<div class="card-header">
<h3 id="meetings_"><a class="card-link" data-toggle="collapse" href="#meetings">Meetings</a></h3>
</div>
<div id="meetings" class="collapse" data-parent="#gov-accordion">
<div class="card-body">

There are three types of meetings:

  - General assembly, organized on an annual basis, this gives all members the opportunity to meet and discuss the progress.
  - Monthly open meetings to verify and steer the progress, open for anyone interesting.
  - Monthly steering board meeting which is typically two weeks after the open meeting.

#### General assembly

The general assembly brings together all members of the forum to discuss the status and next steps. During ISC HPC and Supercomputing. Typically, we aim to introduce the new steering board during ISC HPC where the general assembly is a hand over between the previous steering board to the next.

#### Open meeting procedure

Open meetings are held on a monthly basis, anyone that is interested may participate.
We are using Slack for the meetings to allow everybody to read the progress of the discussion and to comment upon any action item.
The general chair will post calendar invites via the [members mailinglist](/mailman/listinfo/hpccf-members).

The meetings in Slack follow this procedure:

  - The PC will post the tentative agenda before the meeting.
    - You may respond to this with proposals for further agenda items by starting a thread as a response.  
  - Attendees should **like the agenda (thumbs up)** such that others can later see s/he attended.
  - We will then walk through the agenda but may **process several topics at the same time**.
    - The PC will post the next item providing a further description and thoughts.
    - Attendees are expected to comment on this item in a follow up message.
    - When an item is sufficiently settled, e.g. the response rate is low, the PC will move to the next item -- that does not necessarily mean we have completed the discussion but we increase parallelism!
  - Attendees can comment **anytime** upon **any agenda item** starting a thread underneath it.
  - Voting is done using thumbs up or thumbs down underneath the respective item that is to debate

</div>
</div>
</div>


<div class="card">
<div class="card-header">
<h3 id="gov-decisionmaking_"><a class="card-link" data-toggle="collapse" href="#gov-decisionmaking">Decision Making</a></h3></div>
<div id="gov-decisionmaking" class="collapse" data-parent="#gov-accordion">
<div class="card-body">

Decision making is lightweight at the moment: while we have defined roles for steering board members that include final authority in the event it is needed, thus far we have made decisions democratically without the need to rely on this formal mechanism.
Basically, any contribution is either accepted or discussed and modified until it is accepted.

</div>
</div>
</div>


<div class="card">
<div class="card-header">
<h3 id="mandate_"><a class="card-link" data-toggle="collapse" href="#mandate">Mandate and Election</a></h3>
</div>
<div id="mandate" class="collapse" data-parent="#gov-accordion">
<div class="card-body">

The [steering board](/about/#steering-board) is elected for one year (period of activity) where a period ends in June with a general assembly during ISC HPC.
The steering board attempts to submit a Bird-of-a-Feather session to ISC that serves as general assembly for the members.
In case the BoF was not accepted, we will organize an alternative meeting during ISC HPC.
The organization of the general assembly falls into the responsibility of the general chair and steering board but should involve the new steering board members.
A time slot should be reserved for the new steering board to present an updated roadmap.

Voting is done publicly on a Slack channel "election" in the HPC Certification forum.
Everyone can nominate someone at the channel or self-nominate themself by posting a message with the name of the nominee, the position, and a brief description.
The brief description should state why the nominee is qualified/seeks to become responsible for the given position.

At the end of the nomination phase, the general chair will compose a list of candidates for each position.
HPCCF members can then vote using thumbs up on the candidate(s) they like.
The members are encouraged to vote based on past contributions made by a nominee and the potential they see by appointing the member to chair the respective activity in the initiative.

Members can send any concern regarding a nominee privately to the board (either by Slack or by Email).
By providing good reasons that a candidate is not suitable, the steering board can exclude a candidate from the nominee list.
Before doing so, the steering board will contact the nominee and assess the situation.

**Timeline:**
  * Nomination until April 30st
  * Announcement of candidates by the general chair: first week of May
  * Voting takes place until May 31st

If a board members resigns/steps down during an election period having less than 6 month in the current period, the current steering board can elect a potential candidate to fulfill the role for the rest of the period.
If more than 6 month are remaining, an election for this position is announced.

</div>
</div>
</div>

</div>
</div>
</div>


<div class="card">
<div class="card-header"><h2 id="publicity_"><a class="card-link" data-toggle="collapse" href="#publicity">Publicity</a></h2></div>
<div id="publicity" class="collapse" data-parent="#accordion">
  <div class="card-body">
  </div>
</div>
</div>

</div>



<script>
<!-- Show accordion content -->

function detect_anchor(){
  var anchor = window.location.hash.substr(1);
  if(anchor != "") {
    $( ".show" ).toggleClass("show");
    var name = anchor.slice(-1);
    if(name == '_'){
      anchor = anchor.substring(0, anchor.length - 1);
    }
    var idx = $("#" + anchor);
    while(idx.length != 0){
      if(idx.hasClass("collapse")){
        idx.collapse('show');
      }

      idx = idx.parent();
    }
  }
}

window.onhashchange = function () {
  detect_anchor();
}

$(document).ready(function() {
  detect_anchor();
});
</script>
