=== YD WPMU Bloglist Widget ===
Contributors: ydubois
Donate link: http://www.yann.com/
Tags: blog list, blog listing, bloglist, WordPress MU, wpmu, mu, widget, plugin, sidebar, cache, automatic, admin, template function, template, administration, blogs, blog, sitemeta, subsite, Post, post count, posts, multiple, English, French, Spanish, Galician, Dutch, German, Ukrainian, drop-dow, drop-down blog list, dropdown
Requires at least: 2.9.1
Tested up to: 3.5.1
Stable tag: trunk

Sidebar widget and template function to display an ordered blog list of subsites (with post count) on a page of the WordPress MU main site.

== Description ==

= Show a list of all the WPMU sub-sites =

This WordPress MU plugin installs a **new sidebar widget** that can display the **list of children sites** of your main **mother site** in a single or multi-column format.
The **post count** is displayed for each sub-site / blog of the **blogs listing**.
It also creates a **new PHP function** that can be included in any template to **display an ordered list** that can be designed as a block of information using CSS.

The list can be ordered by blog name, post count, blog creation date or last update, in ascending or descending order. 
Blogs can be excluded from the list based on various criteria or individually by blog ID.

Since version 2.0.0, this plugin supports WPML multiple-language blogs: it will automatically list only blogs using the active language, except where WPML filters are deactivated, 
or a special 'in' (international) langiage is created.

If you don't like the widget or don't use sidebars, you can also **include the list in the content of any page or post** of your blog, 
by simply adding the special `[!YDWPMUBL]` special tag, or **include the blog listing in a template** with the `<?php yd_display_wpmu_bloglist() ?>` function.
The list design is **highly customizable** allowing different settings when displayed as a widget on the home page and other blog pages, and when used inside templates. 

All display parameters can be set in the settings (options) page, or overridden in the template function call.

The display style of each element of the listing can be individually customized using CSS.

The plugin uses **cache** and sitemeta table information to avoid multiple database query.
It has its own widget control pannel and admin settings page.
It is **fully internationalized**.

Base package includes .pot file for translation of the interface, and English, Spanish, Galician, French, Dutch and German versions.
The plugin can be used to display text in any WordPress compatible language and charset.

= Active support =

Drop me a line on my [YD WPMU Bloglist plugin support site](http://www.yann.com/en/wp-plugins/yd-wpmu-bloglist-widget "Yann Dubois' Bloglist Widget for WordPress MU") to report bugs, ask for a specific feature or improvement, or just tell me how you're using the plugin.

= Description en Français : =

Ce plug-in WordPress installe un nouveau widget dans votre barre latérale qui peut afficher la liste des "sous-sites" de votre site principal WordPress MU, triés selons divers critères au choix.

Le nombre de billets publiés dans chaque blog est également indiqué.

La liste peut être triée notamment par nom de site, par nombre de billets publiés, par date de création ou de mise à jour.
On peut sélectionner quels blogs sont inclus ou exclus de la liste selon divers critères, et exclure individuellement des blogs par identifiant.

Depuis la version 2.0.0 ce plugin supporte les sites multi-lingues utilisant WPML. Seuls les blogs de la langue active seront listés.

La liste est affichée sous forme d'un bloc, sur une ou plusieurs colonnes et on peut choisir le nombre de colonnes à utiliser.
Si vous n'aimez pas le principe du widget ou n'utilisez pas de barres latérales, vous pouvez inclure la liste des blogs n'impore où dans le contenu des pages et billets de votre blog,
simplement en insérant un "tag" spécial.

Chaque élément affiché dispose d'un conteneur séparé permettant de complètement personnaliser le style d'affichage à l'aide de feuilles de style CSS.

Le plugin utilise un système de cache pour éviter les requêtes de base de données redondantes.

Il a son propre panneau de contrôle et sa page de réglages (options) dans l'administration.
Il est entièrement internationalisé.

La distribution standard inclut le fichier de traduction .pot et les versions française, anglaise, espagnole, galicienne, hollandaise et allemande.
Le plugin peut fonctionner avec n'importe quelle langue ou jeu de caractères y compris le chinois.
Pour toute aide ou information en français, laissez-moi un commentaire sur le [site de support du plugin YD WPMU Bloglist Widget](http://www.yann.com/en/wp-plugins/yd-wpmu-bloglist-widget "Yann Dubois' Bloglist Widget for WordPress").

= Funding Credits =

Original and additional developments of this plugin has been paid for by [Wellcom.fr](http://www.wellcom.fr "Wellcom"). Please visit their site!

Le développement d'origine et les améliorations de cette extension ont été financés par [Wellcom.fr](http://www.wellcom.fr "Wellcom"). Allez visiter leur site !

= Translation =

If you want to contribute to a translation of this plugin, please drop me a line by e-mail or leave a comment on the [plugin's page](http://www.yann.com/en/wp-plugins/yd-wpmu-bloglist-widget "Yann Dubois' Bloglist Widget for WordPress MU").
You will get credit for your translation in the plugin file and this documentation, as well as a link on this page and on my developers' blog.

= Translation credits =

* Spanish and Galician translation kindly provided by: [Arume](http://www.arumeinformatica.es/ "Arume")
* Dutch translation kindly provided by: [Rene](http://www.fethiyehotels.com "Rene")
* German translation by: [Rian Kramer](http://www.pangaea.nl/diensten/exact-webshop "Pangaea")
* Ukrainian translation by: [Mikalay Lisica](http://webhostinggeeks.com/ "Web Geek")

== Installation ==

1. Unzip yd-wpmu-bloglist-widget.zip
1. Upload the `yd-wpmu-bloglist-widget` directory and all its contents into the `/wp-content/plugins/` directory of your main WPMU site
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Use the widget admin page to add the widget to one of your sidebars and configure it
1. Use the option 'YDWPMUBL' settings page to clear the cache when you make changes.
1. If you want to include the list in your page content, use the `[!YDWPMUBL]` tag.
1. If you want to include it in your template, use the `yd_display_wpmu_bloglist()` function.
For specific installations, some more information might be found on the [YD WPMU Bloglist Widget plugin support page](http://www.yann.com/en/wp-plugins/yd-wpmu-bloglist-widget "Yann Dubois' Bloglist Widget for WordPress MU")

== Frequently Asked Questions ==

= Where should I ask questions? =

http://www.yann.com/en/wp-plugins/yd-wpmu-bloglist-widget

Use comments.

I will answer only on that page so that all users can benefit from the answer. 
So please come back to see the answer or subscribe to that page's post comments.

= Puis-je poser des questions et avoir des docs en français ? =

Oui, l'auteur est français.
("but alors... you are French?")

= How to display this in the template without using a widget? =

Insert this code into your template:

`<?php yd_display_wpmu_bloglist() ?>`

= How to display a drop-down list of blogs? =

Since version 2.1.0, you can use this syntax:

`<?php yd_wpmubl_dropdown_js(); ?>
<select name="select" id="select" onChange="ddjump(this);">
	<option selected="selected"><?php _e('Please choose an option below:'); ?></option>
	<?php yd_wpmu_bloglist_dropdown( 'show_count=0' ); ?>
</select>`

= If I don’t want to use the widget, how can I display it in php? =

Same answer as above.

= What are the function parameters? =

All the display parameters can be overloaded in the function call. Here are the available parameters:

* column_count
* before_block
* after_block
* before_column
* after_column
* before_list
* after_list
* before_item
* after_item
* before_count
* after_count
* plural_form
* alt_text
* title_text
* limit
* order_by
* order

**new since 2.0.0 **

* trailing_slash
* wpml_support
* only_public 
* skip_archived
* skip_mature 
* skip_spam 
* skip_deleted 
* to_skip (comma-delimited list of blog IDs that xill not be displayed)

**new since 2.1.0 **

* show_count (can be set to 0 or false to avoid displaying post count)
* group_by (needs an option in the blogs' options tables)
* before_groupby
* after_groupby


For example, here's how to call the function with a bunch of parameters:

`<?php yd_display_wpmu_bloglist( 'column_count=1&before_item=<li><b>&after_item=</b></li>' ) ?>`

Since version 2.1.0 you can also pass artguments in an array, like this for example:

`<?php yd_display_wpmu_bloglist( 
	true, //echo
	array( 
		'to_skip' 	=> '1,0',
		'order_by'	=> 'domain',
		'group_by'	=> 'continent',
		'column_count'	=> 3,
		'show_count'	=> 0,
		'before_block'	=> '<div>',
		'after_block'	=> '</div>',
		'before_column'	=> '<div class="columns coln">',
		'after_column'	=> '</div>' 
	)
); ?>`

= Can I make the function return HTML for further php processing without displaying it? =

Yes: just add a first parameter "false". You can add optionnal display customization overload parameters after that.

For example:

`<?php $my_html = yd_display_wpmu_bloglist( false, 'column_count=1&before_item=<li><b>&after_item=</b></li>' ) ?>`

= Can I include the blog list in my blog content? =

Yes you can include the list in the content of any page or post by using tis special tag:

`[!YDWPMUBL]`


== Screenshots ==

1. An example of the WPMU Bloglist Widget Plugin in action (2-columns blog list block)
1. The settings/options page (english version)
1. La page de réglages/options (version fançaise)

== Widget control pannel ==

The widget has its own control pannel for setting-up its look and feel. You can administer it from the widgets admin page.
Remember to clear the cache when you make changes, if you want to see them right away (see hereunder).


== Widget options page ==

Use the widget's own option page to clear the cache and reset default settings.
Otherwise, the cache expires only when content is added to the blog or widget control panel options are changed.


== Revisions ==

* 2.1.1 Indonesian translation by Syamsul Alam
* 2.1.0 New features: drop-down, sort by domain, filter hooks, show_count=false, wp-style arguments
* 2.0.0 Major new release : WPML support, blog exclusion options
* 1.0.2 Bugfix in postcount order; German version.
* 1.0.1 Bugfix in settings update.
* 1.0.0 Final release. Includes improved settings page and Dutch version.
* 0.2.2 Bug fixes in the options page (when saving settings) - still considered beta (check cache IRL).
* 0.2.1 Third debug ("duplicate link" issue for subdomains) - still considered beta (check cache IRL).
* 0.2.0 Second debug (thanks to TB@Wellcom) - still considered beta (check cache IRL).
* 0.1.1 First debug (thanks to Arume) - still considered beta (check cache IRL).
* 0.1.0 Original beta version.

== Changelog ==

= 2.1.1 =
* Indonesian translation by Syamsul Alam
= 2.1.0 =
* New features and improvements:
* Ability to build drop-down blog menu easily
* Ability to sort by domain
* Filter hook for external processing of the list by other plugins
* show_count = false parameter
* Regular wp-style arguments parsing (now allows using array of arguments)
* Ukrainian translation by Mikalay @ webhostinggeeks.com
= 2.0.0 =
* WPML language support
* Place longer column first
* Backlink disabled by default
* Use POST for form submission
* Blog selection options
* Blog exclude list option
* New YD Logo
= 1.0.2 =
* Bugfix: order by post count
* German translation by Rian Kramer @ Pangaea http://www.pangaea.nl
= 1.0.1 =
* Bugfix: settings update
= 1.0.0 =
* Considered stable, no complaint after more than 500 downloads
* Added Dutch version, translation credits go to Andre @ http://www.fethiyehotels.com
* Activated "linkbackware" mode by default
* Donate section in the settings page
* Improved settings page design
* Added settings page link in the short description
* (slightly) Revised documentation page
= 0.2.2 =
* Bugfix: Plural form was not saved (2010-04-22) (thanks to Thomas@Wellcom for noticing)
* Bugfix: html_entities_decode would cause utf-8 nightmare ans crash in options encoding (thanks TB again!)
= 0.2.1 =
* Bugfix: "Duplicate link" issue with sub-domain installations (2010-04-22)
= 0.2.0 =
* Bugfix: Alphabetical order was case-sensitive (2010-04-21) (thanks to TB@Wellcom for reporting)
* New feature: trailing slash option
* Compatibility: WP 2.9.2 & WPMU 2.9.2
= 0.1.1 =
* Corrected order-by and limit features which were non-functional (thanks to Luis @ Arume for reporting)
* Made non-xhtml compliant 'alt' parameter optional (thanks to Luis @ Arume for reporting)
* Completed translation file (thanks to Arume again)
* Added Spanish and Galician translation files (credits [Arume](http://www.arumeinformatica.es/ "Arume"))
* Completed doc + readme file
 
== Upgrade Notice ==

= 2.1.1 =
No special instruction for upgrade (see **changelog** section).
= 2.1.0 =
No special instruction for upgrade (see **changelog** section).
= 2.0.0 =
No special instruction for upgrade (see **changelog** section).
= 1.0.2 =
No special instruction for upgrade (see **changelog** section).
= 1.0.1 =
No special instruction for upgrade (see **changelog** section).
= 1.0.0 =
No special instruction for upgrade (see **changelog** section).
= 0.2.2 =
Options save settings bugfixes (see **changelog** section).
= 0.2.1 =
Multi-domain bugfix (see **changelog** section).
= 0.2.0 =
Minor bugfix + New features + 2.9.2 compatibility (see **changelog** section).
= 0.1.1 =
Important bug fixes + Spanish version (see **changelog** section).

== Did you like it? ==

Drop me a line on http://www.yann.com/en/wp-plugins/yd-wpmu-bloglist-widget

And... *please* rate this plugin --&gt;