![RSGallery logo with text](https://raw.githubusercontent.com/RSGallery2/RSGallery2_Project/refs/heads/master/RSG2_icons/RSG2_Logo/RSG2_logoText.svg)

# RSGallery2_Component

RSGallery2 is one of several gallery extensions/components for the well known CMS Joomla!

## About RSGallery2

RSGallery2 is around since the Mambo days (2003) and is originally developed by Ronald Smit. RSGallery2 is around since the Mambo days (2003) and is originally developed by Ronald Smit. Since then, RSGallery2 has continued to evolve and adapt to Joomla! versions.

RSGallery2 is released by the GPL license and will be **free to use** (as in free beer).

## **RSG2 restart for Joomla! 4.x, now for Joomla5x only**

This version is developed from scratch. The jump from J3.x to J4x needed to get rid of all the J1.5 code and template constructs still around in the version for J3x.

Actual state:
* Version for J4 is complete (see features below)
* Version for J5 is complete (see features below)  
  minor code fixed and features may still be be developed

## Features
The component and several plugins / modules feature following functions
* Gallery view with sub galleries
* User galleries on front end
* Slideshow(s)
* Latest images
* Several additional modules and plugins
* ...

## Downloads
First the component itself is needed. You will want to download the "RSGallery2_Component". The modules and plugins are optional. Please check out the various modules and plugins

The downloads can be found here on github under "releases" in every project


## Code repository for J6

Since 2026.10.29 the new code is developed in a separate repository [**RSGallery2_J6**](https://github.com/RSGallery2/RSGallery2_J6) here on github. Please check there for code, issues and releases

That repository will be used to restart the code from scratch 
an is in development








## **Upgrade from J3x RSG2 version**

Some steps may be necessary for the transfer of J3x configuration/galleries/images. In general if a J3x data is detected
then in maintenance a separate section is prepared for the conversion

- Config: It may be necessary to manually start the copy of matching variables from maintenance
- Gallery data: See configuration
- Image data: See configuration
- Image files: See configuration
- Menu references: the old format may be still valid. New more separate selection will be available
- Templates:
- Slideshows: In J3 version the slideshow were based on mootols and are therefore not available anymore. Similar
  slideshows will be available.

### Changes for J4

Each data type will be kept in a different table with different items. These are aligned to J4 standard tables. The
original J3x items must be copied to the new place. (Maybe automatic, may be not automatic. Can't tell actually)

**Gallery data:** The galleries are organized now in a joomla standard nested tree. This makes it easier to use Joomla
standard sorting features

**Image files:** On the J4 RSG2 version the folder structure of image files has changed. They have a new starting folder
...\images\rsgallery2 (instead of ...\images\rsgallery). Images are organized by gallery ID. They are kept in subfolders
beginning with the gallery ID and then each image destination resolution has its own folder. Examples:

- images\rsgallery2\2\600\DSC_5521.JPG
- images\rsgallery2\2\thumbs\DSC_5521.JPG Folder 600 keeps resized copy of width 600px

more will follow

## Documentation

First partsof the documentation (still a bit raw) can be found at http://rsgallery2.org/index.php/documentation
The menu "Documentation" may be used to reduce the information to one section of the documentation

## Site: Gallery view

![lovelocksgallery 01](https://github.com/RSGallery2/RSGallery2_Project/blob/master/Documentation/J!4x/images4Doc/SiteGalleryView.png/?raw=true)


## Backend: Control Panel

![rsgallery2controlpanel](https://github.com/RSGallery2/RSGallery2_Project/blob/master/Documentation/J!4x/images4Doc/Control_Panel.png?raw=true)


## Backend: Maintenance

![rsgallery2maintenance 02](https://github.com/RSGallery2/RSGallery2_Project/blob/master/Documentation/J!4x/images4Doc/maintenance_view.png?raw=true)

## Backend: Gallery list

![manageGalleries](https://github.com/RSGallery2/RSGallery2_Project/blob/master/Documentation/J!4x/images4Doc/GalleryList.png?raw=true)

## Backend: Image list

![manageImages](https://github.com/RSGallery2/RSGallery2_Project/blob/master/Documentation/J!4x/images4Doc/imageList.png?raw=true)

## Homepage

http://www.rsgallery2.org/

## Forum

There was a lot more garbage the conten. So it is closed.

## History
Incomplete (Gathered or imagined by me, Thomas Finnern)

The project started in the mambo days and got really big in J!1.5. Due to reasons unknown the access to the site www.rsgallery2.net got lost and it was not accessible for some time. Mirjam and "locutusweb" arranged for a restart on rsgallery2.nl but the connection to the former developers and their interest was gone.

Mirjam got the version running under J!2.5 and did a great Job supporting user on the forum. While starting to upgrade for J!3x she got a baby and does only user maintenance now. I finished her task. (First rsgallery2 version for J!3.x 16.04.2015)

In Oct. 2016 the forum was moved to forum.rsgallery2.org and a the web page rsgallery.org was created.
It is planned that from January 2018 on the backend runs completely on ¨new¨ software and is faster.

...   To be continued ;-)


# Help is welcome

If you like the project and want to give something back, please help out other users in the forums and share your code.
RSGallery2 is a project run by volunteers. People who just love Joomla! and this gallery component. So RSGallery2 needs you!
How? 
* By reporting bugs and problems in the [issues j4x/j5x](https://github.com/RSGallery2/RSGallery2_J4/issues)
* By submitting pullrequest for bugs, changes or new code 
* By writing documentation ... 

Simply send an email to team2@tomfinnern.de, and you will become a member of the RSGallery2 team. 
   ==> team2 at rsgallery2.org (correct address to match ...)
