Project Euler RO
=======

Description

- Website dedicated to the Romanian translations of the Project Euler problems.
- Production version can be found at http://projecteuler.radumurzea.net/

Licensing

Please keep in mind that the content (translated and non-translated problems) falls under different copyright rules than the code:
- the code is licensed under the GPL v3 license.
- the content is licensed under the Creative Commons Attribution-NonCommercial-ShareAlike 2.0 license

Technical stuff

- Works with a MySQL database in the backend.
- Requires at least version 5.3.x of PHP to work.
- Main configuration options are found in the /config folder. They're mostly self-explanatory.
- Database structure and data can be found in the /sql folder.
- Uses composer's autoloader for handling namespaces.
