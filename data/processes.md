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


<div id="accordion">


<div class="card">
<div class="card-header">
<h2 id="skills_"><a class="card-link" data-toggle="collapse" href="#skills">Skills</a></h2></div>
<div id="skills" class="collapse" data-parent="#accordion">
  <div class="card-body">
  </div>
</div>
</div>





<div class="card">
<div class="card-header"><h2 id="training_"><a class="card-link" data-toggle="collapse" href="#training">Training material</a></h2></div>
<div id="training" class="collapse" data-parent="#accordion">
  <div class="card-body">
  </div>
</div>
</div>

<div class="card">
<div class="card-header"><h2 id="examination_"><a class="card-link" data-toggle="collapse" href="#examination">Certification/Examination</a></h2></div>
<div id="examination" class="collapse" data-parent="#accordion">
  <div class="card-body">
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
<h3 id="meetings_"><a class="card-link" data-toggle="collapse" href="#meetings">Meetings</a></h3>
</div>
<div id="meetings" class="collapse" data-parent="#gov-accordion">
<div class="card-body">

There are three types of meetings:

  - General assembly, organized on an annual basis, this gives all members the opportunity to meet and discuss the progress.
  - Monthly open meetings to verify and steer the progress, open for anyone interesting.
  - Monthly steering board meeting which is typically two weeks after the open meeting.

#### General assembly

The general assembly brings together all members of the forum to discuss the status and next steps. During ISC HPC and Supercomputing. The vote for the steering board takes place during ISC HPC.
It also serves as transition from one steering board to another.

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

last_hash = "";

function detect_anchor(){
  var anchor = window.location.hash.substr(1);
  if(anchor != "" && last_hash != anchor) {
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

    last_hash = anchor;
  }
}

window.onhashchange = function () {
  detect_anchor();
}

$(document).ready(function() {
  detect_anchor();
});
</script>
