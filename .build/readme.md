## **".build"** folder

This folder contains scripts and files 
which supports the refinement of the
code to finally create the release (build)
of the product

**Start build with "phing" here.** No commandline arguments are needed
It will create .packages folder in component root with zip file
phing
phing -f build.xml
phing -logfile .\build.log
Needs setting of version in build.php before

**Update the project files** 
This will replace texts and change a lot of files in the project.
* Version and date in rsgallery2.xml
* Handles since php doc information
* ...

phing -f updateProject.xml

Needs setting of version in build.php before

