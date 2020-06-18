---
layout: page
title: Skills
permalink: /skills/
category: "skills"
order: 1
backgroundID: books
---

A key goal of HPCC is the fine-grained standardization of the HPC knowledge representation leading into a HPC competence standard.
This is achieved by mapping competences into skills, organize them into a tree, and make them navigatable using tools.

## Definition

### Skill

A HPC-relevant competence of theoretical and/or practical nature.

The definition of one skill includes the sections:
  * ID: Identifier according to its position in the skill tree. Each skill may have three levels indicated by the last character of the ID: Basic, Intermediate, or Advanced
  * Name: A name capturing the essence of the skill
  * Background: Provides brief information motivating the need for the skill and how it fits into the bigger picture with other skills. The background is easily understood by a wide audience.
  * Aim: Describe the purpose of the skills, but doesn't really include a list of what a practitioner will learn or do. Explaining what a skill is trying to achieve is not the same as saying how it should be done. Typically a skill has 1-2 aims.
  * Learning Outcomes (LOs):  List of items that define briefly what practitioners will learn. The objectives are statements that prospective learners are able to do. They should clearly describe or define an action bringing about a measurable/quantifiable increase in understanding of that skill. On the leaf-level, they must clearly provide examinable learning objectives. A learning objective can use subitems to define this learning objective further. No other structural element is allowed.

The definition of aims and outcomes follows literature for higher education, see [learning outcomes](https://www.heacademy.ac.uk/system/files/assessment-learning-outcomes.pdf).

### Skill tree

The hierarchical organization of the competences in a tree. The tree can contain references to a skill in another section.

We are currently working on the classification of the HPC competences and have initially identified major topics for the HPC Certification Program as “HPC Knowledge”, “Use of the HPC Environment”, “Performance Engineering”, and “Software Engineering”.
We are in the process to extend these topics with two sub-trees about “Administration” and “Big Data Analytics”.

The **top levels** of the current skill tree are shown here:

![Skill tree](/assets/img/skill-tree.jpg "Skill tree"){:style="width:100%;"}

The tree has the following properties:
  * The skill tree is version controlled representing one HPC **competence standard (CS)**. We refer to a standard using a phrase like **CS-1.0** (version 1.0). There exists a current development version which allows for changes and previous (released) versions. Details about the release process are found [here](/processes/#skills).
  * Skills on leaf-level are generally organized such that they encapsulate a single narrow topic, knowledge or technology. **High-level overview skills** are possible as well but then focus on the overview instead of introducing too many details for specific solutions.
  * On the leaf level, a skill is fine-grained and orthogonal to other skills -- their narrowed scope means they can be taught in sessions ranging from a 1-hour lecture up to a 4-hour workshop. We believe this granularity allows practitioners to cherry-pick the skills relevant to their circumstances, and lecturers and examiners to prepare small lectures with well-defined content.
  * For technology-dependent skills on the leaf level (e.g., a specific file system or workload manager) the introductory skills are often provided, as they contribute to the foundation of many specialized skills representing a specific hardware or software technology.

## An example skill

The following is a virtual example that illustrates how an inner node may look like, in particular if it is an overview skill:

  * Note: This is an overview skill
  * ID: USE4.2.1-B
  * Name: Workload manager introduction
  * Background: A workload manager controls the execution of compute-jobs on a supercomputer as the supercomputer is a resource shared among a large number of users. There is a wide range of different workload managers in use. This skill explains how to use a workload manager on a conceptual level to allocate resources on a supercomputer that may be used for arbitrary computation.
  * Aim: To enable practitioners to comprehend the basic architecture and concepts of resource allocation on an HPC system.
  * Learning outcomes
     - Comprehend the exclusive and shared usage model in HPC
     - Explain the generic steps required to submit, run and monitor a single job and wallclock time
     - Differentiate between the batch and interactive job submission
     - Comprehend the generic concepts and structure of resource manager and define the relevant concepts: scheduler, job and job script
     - Explain the role of environment variables as a mean to communicate certain settings of your job
     - Comprehend job budgeting and accounting principles
     - Describe a schedulers backfilling strategy to utilize resources

More details can be found in the actual competence standard.

## Competence standard

The HPC competence standard is available in various forms:

  * The markdown sources are available in [GitHub](https://github.com/HPC-certification-forum/skill-tree)
  * A wiki for the skills (editable for members) is [available here](https://www.hpc-certification.org/wiki/) -- it is synchronized with the GitHub sources.
  * A mindmap version for freeplane is available in [GitHub](https://github.com/HPC-certification-forum/skill-tree/blob/master/skill-tree.mm) -- it is synchronized with the GitHub sources.
  * An interactive map of the skill tree is [available here](/skills/map).
  * A [RESTful service](/ecosystem/#rest) allows convenient remote access to the information of the competence and supplementary information.

Information about contributing to the competence standard is available in [processes](/processes/#skills).
