---
layout: page
title: Common console errors
---

# SimpleMDE could not locate its HTML element

    TypeError: e is undefined

This happens when you create a SimpleMDE element, and use an invalid handler to locate
the HTML element.

Take a look at the code creating SimpleMDE, and double-check what HTML element you are
using.
