---
layout: page
title: Downloads
permalink: /download/
order: 4
backgroundID: rack
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

## Development

Our repositories are available on [GitHub](https://github.com/HPC-certification-forum).

## Publications

<ul class="list-group">
    {% assign downloads_by_date = site.publications | sort: 'last-updated', 'first' %}
    {% assign downloads_by_date = downloads_by_date | reverse %}
    {% for p in downloads_by_date %}
        {% if p.status != "inactive" %}
            {% include publication.html pub=p %}
        {% endif %}
    {% endfor %}
</ul>

## Talks

<ul class="list-group">
    {% assign downloads_by_date = site.talks | sort: 'last-updated', 'first' %}
    {% assign downloads_by_date = downloads_by_date | reverse %}
    {% for p in downloads_by_date %}
        {% if p.status != "inactive" %}
            {% include talk.html talk=p %}
        {% endif %}
    {% endfor %}
</ul>
