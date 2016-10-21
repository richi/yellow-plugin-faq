FAQ plugin 0.1.3
================
A FAQ plugin for [Yellow](https://github.com/datenstrom/yellow/). 

Users or customers have tons of common questions that have to be answered over and over again. This plugin was made to manage these *frequently asked questions* in a convenient way.

Thanks to the excellent work of the Yellow guys, putting this together was not much more than a copy and paste job.

How to install?
---------------
1. [Download and install Yellow](https://github.com/datenstrom/yellow/).
2. [Download plugin](https://github.com/richi/yellow-plugin-faq/archive/master.zip). If you are using Safari, right click and select 'Download file as'.
3. Copy `master.zip` into your `system/plugins` folder.

To uninstall delete the plugin files.

How to use?
-----------
The main FAQ page will show the list of questions and is available on your website as `http://website/faq/`. To create a new question, add a new file to the faq folder.

How to configure?
-----------------
You can use shortcuts to show information about the FAQ:

`[faqarchive LOCATION]` for a list of months  
`[faqrecent LOCATION PAGESMAX]` for recently changed pages  
`[faqrelated LOCATION PAGESMAX]` for related pages to current page  
`[faqtags LOCATION]` for a list of tags  

The following arguments are available, all but the first argument are optional:

`LOCATION` = faq location  
`PAGESMAX` = number of pages

Example
-------
Showing recently changed pages:

    [faqrecent]
    [faqrecent /faq/ 10]
    [faqrecent / 10]

By default, the questions are sorted by title. To overwrite this behaviour use `list:xxx`
    
    [by Date](/faq/list:modified/)
    [by Title](/faq/list:title/)

