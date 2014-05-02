==============
CMCM Template v.1
==============

For more information please see http://cmcm.io

Install
========
1. To install, drag the contents* of this folder into your server root (or where ever you want your site to be).
2. Your admin panel, by default, will be [site_url]/cmcm. Point your browser to that directory to setup

*Make sure hidden files are transferred (.htaccess)







To edit CSS,HTML,JS
===================

We use PHP frunt sdk for templates as its easier and more functional. Contact us if you absolutely need to use the JS sdk.
Below is the general template structure. If you are novice and need to edit css, html, or js.. templates/ hold html fragments, css is in css/ folder and js/ is in js folder. We use twig for the template engine to easily create reusable components.  (See twig.sensiolabs.org/doc for more info)

Template Structure
-------------------

[root]/
|
├── cmcm/          <------ admin panel
├── css/           <------ css files (style.css = main, splash.css = home)
├── js/            <------ js files (script.js = main)
├── templates/     <------ actual html files, with Twig placeholders (See twig.sensiolabs.org/doc for more info)
|
|
├── config.php                 <------ used as setup for each page, usally also menu widgets and stuff
├── index.php + other files   <------ widget and twig setup, generally their real html counterpart will be found in templates/ folder






Troubleshooting
================


Q. Wait I don't have hosting or a server
---------------------------------------

A. If you don't want to invest just yet, try a free host that offers php 5.3.
Try googling 'free php hosting'. There are tons online, It may take a few tries to find a good one. bytehost is ok, just a little slow. Once you get comfortable, consider buying a domain name and paid hosting. I use surpasshosting.com shared hosting to host, if you would like the same:
http://www.surpasshosting.com/hosting-shared-solutions.php/echo/33758

Q. How do I transfer to my server?
---------------------------------

A. Get an FTP application, and use the credentials provided by your server. I'd use Fetch (OS X) or Filezilla (OS X / Win ).

Q. Why do my images take so long to load?
-----------------------------------------

A. Likely they're too big. Remember to shrink your images down to web format. Save for web.. in photoshop or I recommend 'shrink-o-matic', a small app, to shrink in batch.


Q. My projects page isn't working!
-----------------------------------

A. Likely you didn't transfer over the '.htaccess' file..a hidden file the server uses to make pretty urls. We rewrite project.php?id=[cleanURL] to act become the url [site]/projects/[cleanUrl attribute]. Copy that file over OR create a '.htaccess' files with the following 2 lines:

RewriteEngine on
RewriteRule ^projects/([^/\.]+)/?$ project.php?id=$1 [L]


Q. I don't have .htaccess permissions!!
---------------------------------------

A. No worries. If your host doesn't give you the ability to add/edit .htaccess on their server, Then you will just have to change the way your menu renders the url. Go to 'config.php' of your template and add the following option. For any other menu widgets, do the same.

 "url_rewrite" => "project.php?id="
