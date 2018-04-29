---
layout: page
title: Downloads
permalink: /download/
---
<div class="card-columns">
    {% comment %}
    Sort the downloads by date, putting those without dates last
    {% endcomment %}
    {% assign downloads_by_date = site.downloads | sort: 'last-updated', 'first' %}
    {% assign downloads_by_date = downloads_by_date | reverse %}
    {% for p in downloads_by_date %}
        {% if p.status != "inactive" %}
            {% include download-card.html download=p %}
        {% endif %}
    {% endfor %}
</div>
