FAQ plugin 0.1.1
================
A FAQ plugin for [Yellow](https://github.com/datenstrom/yellow/). 

Users or customers have tons of common questions that have to be answered over and over again. This plugin was made to manage these *frequently asked questions* in a convenient way.

Thanks to the excellent work of the Yellow guys, putting this together was not much more than a copy and paste job.

How to install the FAQ plugin?
------------------------------
1. Download and install [Yellow](https://github.com/datenstrom/yellow/).  
2. Download [faq.php](faq.php?raw=true), copy it into your `system/plugins` folder.  
3. Download [faq.html](faq.html?raw=true) and [faqpages.html](faqpages.html?raw=true), copy them into your `system/themes/templates` folder.  
4. Download [content-faq.php](content-faq.php?raw=true) and [content-faqpages.php](content-faqpages.php?raw=true), copy them into your `system/themes/snippets` folder.  
5. Download [page-new-faq.txt](page-new-faq.txt?raw=true), copy it into your `system/config` folder.
6. Create a new folder '4-faq' in your `content` folder.
7. Add [page.txt](page.txt?raw=true), [question.txt](question.txt?raw=true) and [sidebar.txt](sidebar.txt?raw=true) to your `/content/4-faq` folder.
8. Download [language-faq-en.ini](language-faq-en.ini?raw=true), copy it into your `system/config` folder.<br>(As an alternative, you can add the keys `FaqFilter: FAQ`, `FaqListTitle: by Title`, `FaqListModified: by Date`, `FaqTag: Tags:` to your `system/config/language-*.ini` files.)


To uninstall delete those files.

How to use the FAQ plugin?
--------------------------
The main FAQ page will show the list of questions and is available on your website as `http://website/faq/`. To create a new question, add a new file to the faq folder.

How to configure the FAQ plugin?
--------------------------------
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

