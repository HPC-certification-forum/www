---
layout: page
title: Skill Map
permalink: /skills/map/
category: "skills"
order: 1
backgroundID: books
hidden: true
noContainer: true
---

<script src="/assets/certification/libs/skill-tree.min.js"></script>
<script src="/assets/certification/libs/jquery.min.js"></script>
<script src="/assets/certification/libs/raphael.min.js"></script>
<script src="/assets/certification/libs/Treant.js"></script>
<link type="text/css" rel="stylesheet" href="/assets/certification/treant.css">
<link type="text/css" rel="stylesheet" href="/assets/certification/skill-tree.css">

<div class="links" id="pfad"></div>
<script>
      var dirname = window.location.origin + "/assets/certification/"
      SkillTreeViewer("#pfad", {
          "all": [
              dirname + "skill-data.json",
              dirname + "skill-links.json",
              dirname + "skill-tree-structure.json"
          ],
          "reduced": [
              dirname + "skill-data.json",
              dirname + "skill-descriptions.json",
              dirname + "skill-tree-structure.json"
          ],
      });
</script>
