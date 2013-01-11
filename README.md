FatFree Ajax Boilerplate
================


My F3 Ajax application with timers to monitor performance etc.




---



Setup stuff:
----------

make sure you have a users table with ID and last_activity (datetime) columns. the system uses it for the auto logout timer thing create a file (if it doesnt exist) config.inc.php and copy the relevant bits from config.default.inc.php into it and make your changes.



---



Tech
-----------
###	php
		FatFree v3
		Twig
###	css
		bootstrap
		jQuery UI
###	javascript
		Libs
			jQuery
			jQuery UI
			bootstrap
			modernizr
		plugins
			bootstrap datepicker
			autologout
			BBQ
			cookie
			hotkeys
			hoverintent
			jqote2
			jscrollpane
			mousewheel
			select2
###	html
		html5 boilerplate




---




Files (css / js includes like plugins / libraries)
---
controllers/general.php - place your included css files / js files into the array for them to be included in the combining script for css / js


some fun stuff
------------

### Timer()
if you want to add a\ timer (think profiler) to any bit of code.. use the timer class

```php
$timer = new \timer();
$timer->stop("<message to include here that describes this timer>", "arguments");
```

its recommended you use something like this right above your return in the models. it shows the class and function used.. as well as a backtrace to see where its used and how many times its called etc.

```php
$timer->stop(array( "Models" => array("Class"  => __CLASS__,"Method" => __FUNCTION__)), func_get_args());
```

### Template()

for the ajax apps ive found this to be the best. it loads template.tmpl (shell template, boilerplate style)
it then has a var called "page" which is an array.

template = the "page" template to use.. inside "ui/front/" it gets included inside the shell. any css / js page with the same name (in eg below its 'home' so /ui/front/home.tmpl will be loaded as well as /ui/front/_css/home.css and /ui/front/_js/home.js and /ui/front/_templates/home.jtmpl)

/ui/front/_templates/home.jtmpl - its the jQote2 templates to be included for ajax stuff.

no need to include any css / js pages in the html.. all gets handled automatically if you use "template.tmpl" as the template and make use of the page var


```php
$tmpl = new \template("template.tmpl", "ui/front/",true);
$tmpl->page = array(
	"template"    => "home",
	"meta"        => array(
		"title" => "Welcome to the framework",
	)
);
$tmpl->output();
```

*basic usage is*

```php
$tmpl = new \template("<template to use, filename>", "<path to template folder / array of paths, system will pick the path depending on if the file exists or not>",true/false for strict folder);
$tmpl->var = "<any variables you wish to include to the template>";
$tmpl->output();
```

