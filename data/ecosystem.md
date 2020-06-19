---
layout: page
title : Ecosystem
permalink: /ecosystem/
order: 3
backgroundID: processes
---

One goal of HPCCF is supporting a vibrant ecosystem around the HPC competence standard.
On this page, we describe current and prospective tools that support practitioners/trainers.

Standalone tools are available in [GitHub](https://github.com/HPC-certification-forum/tools).


# Accessing the competence standard

There are various means to access [the standard](/cs/#availability).

## RESTful services
The REST API provides read-only access to the
  * Description of the skill as JSON (from the GitHub Markdown)
  * Teaching material available (imported from an extra GitHub repository)
  * Certificates a skill is part of
  * List of available skill (e.g., IDs) or full data

### API description

  * The API endpoint is [https://www.hpc-certification.org/api/](https://www.hpc-certification.org/api/)
  * The URL consists of endpoint and the hierarchy of the relevant subskill as defined in the certification standard and the skill level, e.g., [https://www.hpc-certification.org/api/use/1/1/b](https://www.hpc-certification.org/api/use/1/1/b)
  * Additional arguments are:
    - fields, i.e., which parts of the Markdown are available
    - version, i.e., the certification standard, e.g., latest
    - list, which generates only the list of subskills in the tree
  * Examples
    - Retrieve the particular skill: [https://www.hpc-certification.org/api/use/1/1/b?fields=all&version=latest](https://www.hpc-certification.org/api/use/1/1/b?fields=all&version=latest)
    - Retrieve the list of skills for a subtree: [https://www.hpc-certification.org/api/use/1/?list](https://www.hpc-certification.org/api/use/1/?list)
    - Retrieve all skills for a full subtree (except the extra training field): [https://www.hpc-certification.org/api/use/1/](https://www.hpc-certification.org/api/use/1/)
