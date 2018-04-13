# FUEL CMS - Opt-In Multi-Language branch - for TEST - do not fork/clone - often edited(commits)
This branch is focused on Multi-Language development of FUEL-CMS framework with heavy use of existing features of FUEL-CMS
and Opt-In Controller Development, based on 'master' branch.

The goal is to create FuelCMS configuration ready to produce single or multi language web page frontend with FuelCMS backend
that can configure most of needed thing, from normal user perspective.

I am developing source of this branch in NetBeans IDE project so there may be some specifics
(like 'nbproject/' line in .gitignore file) but I'll try to maintain it as clean as possible.
Feel free to add your input in issues.

#### Assumptions:

- use of Twig parsing engine (instead of dwoo)(but keeping dwoo cache folder for down-compatibility)
- use of national language (polish) as default language, english is second and there may be more other languages used.
- 'segment' method for page language determination (allows for cleaner friendly links in different languages)
- 
    
- use of CKEditor (in later time, in modules for clients that don't want to format text in html tags (Markdown editor)

## CHANGE LOG

#### DONE: (commits)
1. Preparing FUEL-CMS (master) for multi-language pages develop in a Opt-In Controller way (local-testing website)
   - 'master' branch as base (Fuel CMS v 1.4.2)
   - performed steps from 'welcome' page (.htaccess file, installed database, created database user, installed sql schema,
     made required folders writable (twig cache folder), 
   - fuel/application/config/database.php - changed to get access to installed SQL database 'fuel-oiml' with created user
   - fuel/application/config/config.php
     - `$config['encryption_key'] = '`[RandomKey](https://randomkeygen.com/)`'`
     - `$config['sess_save_path'] = APPPATH . 'cache'; //NULL;`
   - fuel/application/config/MY_fuel.php
     - `$config['admin_enabled'] = TRUE; //FALSE;`
     - `$config['fuel_mode'] = 'auto'; //views';`
     
    One should be able to login to [FUEL CMS](http://localhost/fuel-oiml/fuel) now.

2. Adding default language (other than 'english') as default CMS language
   - Downloaded and installed 'polish' CodeIgniter messages translations
     from [bcit-ci/codeigniter3-translations](https://github.com/bcit-ci/codeigniter3-translations)
     into `/fuel/application/language/` directory.
     (otherwise you'll get 'Unable to load the requested language file: language/polish/pagination_lang.php' error)
   - Downloaded and installed 'polish' FUEL-CMS messages translations for 'fuel' module,
     from main [FUEL-CMS-Languages](https://github.com/daylightstudio/FUEL-CMS-Languages) repository.
     Or (for newest updates, for polish language) from my [fork](https://github.com/TomZdulski/FUEL-CMS-Languages)
     into `/fuel/modules/fuel/language/` directory.
   - Change logged-in FUEL-CMS user language (user account setting), to polish. FUEL-CMS texts should now switch to
     installed language (most of them).
   - Change in fuel/application/config/config.php
     - `$config['language'] = 'polish'; //'english';` (Otherwise some CMS pages will still be displayed in english (except pages and page cache))
   - Add FUEL-CMS setting for languages, (as described in [docs.getfuelcms.com/general/localization](http://docs.getfuelcms.com/general/localization))
     in `fuel\application\config\My_fuel.php` :<br />
     `$config['settings'] = array();<br />
      $config['settings']['languages'] = array(<br />
        'type' => 'keyval',
        'fields' => array(
          'key'   => array('ignore_representative' => TRUE),
          'label' => array('ignore_representative' => TRUE),
        ),
        'class'                 => 'repeatable',
        'repeatable'            => TRUE,
        'ignore_representative' => TRUE
      );`
   - set used FUEL-CMS languages in FUEL Settings (en: MANAGE->Settings->FUEL, Languages to:
     pl:Polski
     en:English
     (...other languages)
3. lorem
   - lorem

#### TODO:
1. lorem
   - lorem

***Please ignore this file (README.MD) in Your merges - this one only describes changes in 'opt-in-multi-language' branch.
Below is its original content.***

# FUEL CMS
FUEL CMS is a [CodeIgniter](https://codeigniter.com) based content management system. To learn more about its features visit: http://www.getfuelcms.com

### Installation
To install FUEL CMS, copy the contents of this folder to a web accessible folder and browse to the index.php file. Next, follow the directions on the screen. 

### Upgrade
If you have a current installation and are wanting to upgrade, there are a few things to be aware of. FUEL 1.4 uses CodeIgniter 3.x which includes a number of changes, the most prominent being the capitalization of controller and model names. Additionally it is more strict on reporting errors. FUEL 1.4 includes a script to help automate most (and maybe all) of the updates that may be required in your own fuel/application and installed advanced module code. It is recommended you run the following command using a different branch to test if you are running on Mac OSX or a Unix flavor operating system and using Git:
``php index.php fuel/installer/update``

### Documentation
To access the documentation, you can visit it [here](http://docs.getfuelcms.com).

### Bugs
To file a bug report, go to the [issues](http://github.com/daylightstudio/FUEL-CMS/issues) page.

### License
FUEL CMS is licensed under [Apache 2](http://www.apache.org/licenses/LICENSE-2.0.html). The full text of the license can be found in the fuel/licenses/fuel_license.txt file.

___

__Developed by David McReynolds, of [Daylight Studio](http://www.thedaylightstudio.com/)__
