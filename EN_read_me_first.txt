Srawberry (c) 2011
Mr.Miksar (c) 2011

______________________

Happy New Year!
Thank you for staying with us and watch over the development of the system!
We wish you all success and happiness in the new year!
______________________

Script name: Strawberry 1.2.0 beta 4
Type: Semi CMS
Cost: Free
Description: The test version to catch errors and defects.


System requirements:
Desirable php-interpreter at least 5 versions.
Database MySQL at least version 4
The presence of host SendMail () (not required, but with nicer)
The minimum amount of disk space not less than 5 MB.
Library PHP GD: at least 2 versions
Version of the Zend Engine: not required
Mod Rewrite: optional
Register globals (global): Disabled (optional - the system will handle both off)


Before we proceed to the how or the actions you should read through this page to the end and only then,
Guided by these instructions, ready to install.


What's new in Beta 4 (briefly).

Reworked-paged comments.
-Fixed the calendar. Now, in the references provide a list of categories, which include news on the site ..
-Fixed ustanovochnik. + Multi.
Fixed-output blocks on the page. Added free blocks.
-The algorithm output for additional fields. Now, an additional request to the table with the fields being done, if there are active in the field of news ..
-Fixed the output page numbers in the news.
Updated-style video player UPPOD
-Updated plug-ins.
-Updated the modules administration panel.
-The new file manager based on AdeptoFastload.
And something else, a lot of things - just do not remember them ...


Instructions for installation.

When installed on the server, set the right cmod 444 for all index.html and. Htaccess folder inside the system, images, and attach
(Yes a lot of them - it's a security issue, but still up to you, it's just advice)
The folder system / data set permissions 777 (or the maximum allowable for your hosting company)
The files inside that folder (the types of php and txt) - 666 (or the maximum allowable for your hosting company)
At. Htaccess file inside this folder - 444
To the folder where supposed download files, set the permissions 777 (or the maximum allowable for your hosting company)
(This folder, images, attach, system / backup and system / cache)
To the main index.php file and system / index.php law 555
At all *. php files inside the system / put rights - 444
PLEASE! Rename the file system / dumper.php by any other name or upload files to the server at all.
It is necessary to restore your database from an archive created with built-in dumper.
Ie if your database will be destroyed and authorization in the system can not be implemented, while the use of this file.
Edit. Htaccess file in the root of the site. There are no comments. CNC is disabled by default.
To correct the error output you need to edit lines like ErrorDocument 401 / index.php? Error = 401.
These lines are generated automatically when you create. Htaccess file for each mode NC (Office URLami).
If your site is installed in the root folder, a site (such as / html / or / www /), then these lines must be of the form ErrorDocument 401 / index.php? Error = 401
If the script is installed in an extra folder on the server (such as / html / strawberry / or / www / strawberry /), then the string must be of the form ErrorDocument 401 / strawberry / index.php? Error = 401
As it is necessary to edit the robots.txt file in the same plane. (Currently there is given two options from being indexed)


After login go to system settings and check the all at once!
Resave the settings.

The demo version is still:
  Possible problems with the CNC
  No documentation: (


  WARNING!
No updates from beta 4 on the next version will not.
Ie you have (if anything) to independently carry out an upgrade script.
New plug-ins and updated plug-ins will not work with older versions.


Installing over the previous beta versions is strongly discouraged because many files are removed and reassembled!
Also, the installation of the database should be new.


_______________________________________________________________________________________
Of all the errors and omissions write here miksar@mail.ru
Or guestbook on the site http://strawberry.su/index.php?mod=gb

Mr.Miksar (c) 2011
miksar@mail.ru
http://mgcorp.ru/
_______________________________________________________________________________________

HAPPY NEW YEAR!