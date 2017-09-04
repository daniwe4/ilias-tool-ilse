ToDo:
UDF
AMD
Change user for directories after installation

* Introduce an interface to replace the dreaded `echo`s in the actions with something
  more appropriated. Could be something like `doing($what)->done()` or `doing($what)->failed()`.
* Factor out database connection from Action\DeleteILIAS;

= Restructure Config =

* Make one `paths` entry, that contains all required directories and pathes.
