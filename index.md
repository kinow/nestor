---
layout: bare
title: Welcome
description: Nestor-QA project web page.
categories: none
---

<h1>Nestor QA &mdash; Open source test management tool</h1>
<div class="left">
  <p>This is Nestor QA official web page. Nestor is an Open Source test management
  tool that was created with focus on simplicity, extensibility and integration with other
  tools.</p>

  <p>The project is still in beta, but we already have a lot of ideas to put in practice.
  If you are interested in helping, feel free to browse the [open issues](https://github.com/nestor-qa/nestor/issues?state=open) or
  send us a message in our [mailing list](https://groups.google.com/forum/#!forum/nestor-dev).</p>

  <h2><span class="pictos">U</span>Team</h2>
  <ul>
    <li>Peter Florijn <a href="https://twitter.com/peterflorijn">@peterflorijn</a></li>
    <li>Bruno P. Kinoshita <a href="https://twitter.com/kinow">@kinow</a></li>
  </ul>

  <h2><span class="pictos">N</span>License</h2>
  <p>The project is licensed under the MIT License.</p>

</div>

<div class="right">
  <h2 class="no-top-border"><span class="pictos">o</span>Downloads</h2>
  <div id="downloads">
  	<p><a href="https://github.com/nestor-qa/nestor/releases">GitHub releases</a></p>
  </div>

  <h2><span class="pictos">\</span>Docs</h2>
  <div id="pages">
    <ul>
      {% for page in site.html_pages %}
        {% if page.title %}
          {% if page.showinnav == true %}
            <li><a href="{{ page.url | remove:'index.html' }}">{{ page.title }}</a></li>
          {% endif %}
        {% endif %}
      {% endfor %}
    </ul>
  </div>

  <h2><span class="pictos">\</span>Blog Posts</h2>
  <div id="posts">
    <ul>
      {% for post in site.categories.blog %}
        <li><a href="{{ post.url }}/">{{ post.title }}</a><br/><span class="blogpostdate">{{ post.date | date_to_string }}</span></li>
      {% endfor %}
    </ul>
  </div>
</div>
