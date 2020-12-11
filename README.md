![RSGallery logo with text](https://github.com/RSGallery2/RSGallery2_Project/blob/master/RSGallery2_Logo/RSG2_logoText.svg)

# RSGallery2_Component

RSGallery2 is one of several gallery extensions/components for the well known CMS Joomla!

## About RSGallery2

RSGallery2 is around since the Mambo days (2003) and is originally developed by Ronald Smit. Since then RSGallery2 has evolved with the Joomla! content management into what it is nowadays.

RSGallery2 is released by the GPL license and will be **free to use** (as in free beer).

## **joomla! 4.x RSG2 restart**

This version is developed from scratch. The jump from J3.x to J4x needed to get rid of all the J1.5 code and template constructs still around in the version for J3x.

Actual state:
* J3 files will be used in backend
* Backend structure ready (some features are missing)

## **Upgrade from J3x RSG2 version**

Some steps may be necessary for the transfer of J3x configuration/galleries/images. In general if a J3x data is detected then in maintenance a separate section is prepared for the conversion

- Config: It may be necessary to manually start the copy of matching variables from maintenance
- Gallery data: See configuration
- Image data: See configuration
- Image files: See configuration
- Menu references: the old format may be still valid. New more separate selection will be available
- Templates:
- Slideshows: In J3 version the slideshow were based on mootols and are therefore not available any more. Similar slideshows will be available.

### Changes for J4

Each data type will be kept in a different table with different items. These are aligned to J4 standard tables. The original J3x items must be copied to the new place. (May be automatic, may be not automatic. Can't tell actually)

**Gallery data:** The galleries are organized now in a joomla standard nested tree. This makes it more easy to use the standard joomla sorting functions

**Image files:** On the J4 RSG2 version the folder structure of image files has changed. They have a new starting folder ...\images\rsgallery2 (instead of ...\images\rsgallery). Images are organized by gallery ID. They are kept in subfolders beginning with the gallery ID and then each image destination resolution has its won folder. Examples:

- images\rsgallery2\2\600\DSC_5521.JPG
- images\rsgallery2\2\thumbs\DSC_5521.JPG Folder 600 keeps resized copy of width 600px

more will follow

### Install instruction to test the backend

1. Copy project from github. Github->Code->Download->Zip
2. Extract zip
3. Copy following files to root of project (-> folder RSGallery2_J4-master)

  - administrator\components\com_rsgallery2\rsgallery2.xml
  - administrator\components\com_rsgallery2\install_rsg2.php
  - administrator\components\com_rsgallery2\changelog.xml

4. Zip the **contents** of folder RSGallery2_J4-master\
5. Install in Joomla 4x (beta or nightly build)

--------------------------------------------------------------------------------

## **This software is in middle development**

State:

- Gallery can be created and edited
- Images can be uploaded to gallery
- Image can be edited

Otherwise the software is **not ready** to be used yet

Missing:

- Site:Menu links
- Site:Gallery thumb display
- Site:Gallery images display
- Site:Slideshow

--------------------------------------------------------------------------------
