# Leadpages Connector


A WordPress plugin to easily use your Leadpages pages and Leadboxes inside your WordPress site.

***Change Log***
* 2.1.4.6
    * Fix for page load slowness. Had to store the new page api id for pages created with old plugin
    * Allow pages with customer permalinks of something like /blog/pagename to load a leadpages with just pagename
    
* 2.1.4.5
    * Fixed issue where WelcomeGate pages would not allow a feed to be viewed
    * Fixed issue where a search page would return a Homepage Leadpage if it was setup.
    * Fix for Auto Draft showing up as url in Leadpage listing in admin
    * Fix for trying to resave an existing page shows a slug already exists error
* 2.1.4.4 
    * Drag and Drop Leadboxes in the Global Leadbox section.
    * Split Tested pages cookies have been added to track properly
    * Fix for 404 pages taking over the homepage
    * Removed UUID class that was causing errors with Moontoast\Math
    * Fixed double slashes in page path when upgrading to new plugin

* 2.1.4
    * Updated UI for Leadpages
    * Updated Ui for Leadboxes
    * Updated slug settings to allow for mutli level slugs (parent/child/grandchild)
    * Leadpages now work with all permalink structures including custom
    * Updated to latest version of Leadpages Auth, Pages, and Leadboxes to handle ConnectExpcetion in Guzzle for curl errors

* 2.1.2 
 	* Added support for 32 bit systems for UUID generation


