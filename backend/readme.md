## Project Documentation

ToDo:
! Before production remove tailwind cdn and proper implement it.

Done:

- font and colors set in tw cd;
- set eng as default language;

## General Documentation

\***\*To set this environment you need to:\*\***

## 1: set all dependencies in .env file

---

- CURRENT_URL - **https ://domain.com/whatever**:
  - used when linking resources like pictures and so on

---

- CURRENT_PATH - **/path** :
  - used when linking resources like picture but also php require paths

---

- FILE_PUBLIC_PATH - **" "** ( can be left empty to get **https ://domain/reader?** or add something to get **https ://domain/whatever/reader?**) :
  - if you dont want to show your intern link then set a different path to acces your files from ( note you also need to actually set that path file yourself)
  - note, if you have an underwebsite you need to provide the path here, not just the _**"reader"**_ information but the entire path _**/whatever/reader**_

---

- UPLOAD_PATH - **"/whatever"** :
  - it is used for uploading files to public folder

---

- UPLOAD_PATH_BACKEND - **"/whatever"** :
  - it is used for uploading files to backend folder

---

- DEFAULTLANG - **ro** :
  - set the default language of the website

---

- LANGUAGES - **["ro","eng","span"]** :
  - add all the variations of languages accesible

---

- VIEWSFOLDER - **resources/pages** :
  - where the platform should look for the pages

---

\***\*To set this environment you need to:\*\***

## 2: make sure your redirect is set properly

**for htaccess:**

_NOTE THE EXISTENCE OF "whatever", this is the case for when your website is actually an underwebsite_

```
WIP for apache
```

**for web.config:**

```
WIP for IIS
```

## 3: inside your logistics.php you need to set the backend path

```
$BACKEND = "backendIntern";
require "../$BACKEND/bootstrap.php"; // load your functions
```

---

## Standards

All class files will have the name of the class and they need to be capitalised.

## Routes explained - dynamic slugs:

## Features

- **File-based routing:** Routes are determined by the structure and filenames in your API endpoints folder.
- **Dynamic routes:** Use dynamic segments with filenames like `[id].php` where the segment value is captured as a parameter.
- **Mixed literal/dynamic routes:** Files such as `admin[id].php` match URL segments starting with a literal (`admin`) followed by a dynamic part.
- **Catch-all routes:** Supports catch-all (`[...params].php`) and optional catch-all (`[[...params]].php`) routes to capture multiple URL segments.

[yourslug].php => yourslug arrives as parameter for your POST/GET/WHATEVER example

**Normal slugs**

**URL:** www.whatever.com/api/user/01

**FILE PATH:** routes/api/user/[yourslug].php

**Array in php:** ["yourslug"=>"01"]

---

**Characters before slug is used as URL path**

_characters before slug modify your file path_

**URL**: www.whatever.com/api/user/admin01

**FILE PATH:** routes/api/user/admin[yourslug].php => will **BE** used

**FILE PATH:** routes/api/user/editor[yourslug].php => will **NOT** be used

Array in php: ["yourslug"=>"01"]

admin[yourslug].php

ex: api/users/admin01 => (foler structure) api/users/admin[yourslug].php

--- 23.02.2025
**Fls update on middleware (API and Page):**
!Middleware files are executed before any other file.
!All middleware files require inside an middleware():bool function.

Working flow:

1.  middleware file not present on route -> auto Allow!
2.  middleware file present and middleware() inside returns true -> Allow!

Any other casses deny access any further!

1. middleware files exists but is empty -> Denied!
2. middleware file exists but the middleware function inside it doesn't return true -> Denied!
