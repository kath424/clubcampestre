=== Booking Manager ===
Contributors: wpdevelop, oplugins
Donate link: http://oplugins.com/plugins/booking-manager
Tags: events, bookings, list events, sync bookings, booking calendar, synchronize events, import ics, export ics, import events, export events, ical, airbnb
Requires at least: 4.0
Requires PHP: 5.2.4
Tested up to: 4.9
Stable tag: 2.0.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Showing events listing from .ics feeds or sync bookings from different sources to your website

== Description =

Booking Manager plugin can easily show list of events in customizable way from external .ics feeds at your website.
Booking Manager have native integration with **[Booking Calendar](https://wordpress.org/plugins/booking/)** plugin.
It can sync bookings from **Booking Calendar** with different sources (Airbnb, Booking.com, HomeAway, TripAdvisor, VRBO, FlipKey and any other calendar that uses .ics format).

>[Plugin Homepage](http://oplugins.com/plugins/booking-manager/ "Booking Manager Homepage") | [Support](http://oplugins.com/plugins/booking-manager/#faq "Support")

= Booking Manager IS GREAT FOR =

* Listing of upcoming events at your website from .ics feeds
* Sync bookings from different sources with [Booking Calendar](https://wordpress.org/plugins/booking/) plugin

= FEATURES =

* List of events from external .ics feeds.
* Ability to upload .ics file(s) to your website and use it.
* Customization of events listing template - it's how events showing at front-end side of your website.
* Easily inserting shortcode for events listing into any post or page via popup dialog, where you can select different parameters.
* Setting different parameters for events listing, like "start from" and "finish to" dates, etc...
* Native integration with **Booking Calendar** plugin.
* **Sync bookings** from Booking Calendar with different sources (Airbnb, Booking.com, HomeAway, TripAdvisor, VRBO, FlipKey and any other calendar that uses .ics format).
* **Import .ics** feeds (files) into Booking Calendar. Its useful, if you need to import bookings from multiple external websites into one calendar in Booking Calendar plugin.
* **Export .ics** feeds (files) from Booking Calendar. You can publish bookings from Booking Calendar as .ics feeds at  different pages, and then import such  bookings in your other different website, like Airbnb.
* Configure URLs for pages where you want to publish your ics feeds.
* Mobile friendly.

== Installation ==

= Automatic installation =

To do an automatic install, log in to your WordPress admin panel, navigate to the Plugins menu and click Add New.
In the search field type "Booking Manager" and click Search Plugins.
Once you've found the plugin you can view details about it such as the point release, rating and description.
Now, you can install it by clicking "Install Now".

= Manual installation via WordPress admin panel =

* Download plugin zip file to your computer
* In your WordPress admin panel, navigate to the Plugins menu and click Add New.
* Click "Upload Plugin" button and hit "Choose File" button
* When the popup appears select your downloaded zip file of plugin
* Follow the on-screen instructions and wait as the upload completes.
* When it's finished, activate the plugin via the prompt. A message will show confirming activation was successful.

= Manual installation via FTP =

* Download plugin zip file to your computer and unzip it
* Using an FTP application, or your hosting control panel, upload the unzipped plugin folder to your WordPress installation's `wp-content/plugins/` directory.
* In your WordPress admin panel, navigate to the Plugins menu and find your uploaded plugin
* Click on Activate link under the plugin. A message will show confirming activation was successful.

That's it!

== Frequently Asked Questions ==

= How to start showing events from .ics feeds (files)? =

* Open "oPlugins Panel" menu page in WordPress admin panel
* Upload .ics file via this page or simply use URL to .ics feed from external website
* Insert into page or post the shortcode for listing events from .ics feed. Please click on insert shortcode button in edit content toolbar at edit post page. Then in popup dialog select your parameters for showing events and click on Insert button. Save changes. Test it.

= How to start import of .ics feeds (files)? =

* Install [Booking Calendar](https://wordpress.org/plugins/booking/) plugin.
* Insert [booking-manager-import ...] shortcode into some post(s) or page(s) easily via configuration popup window. Please click on insert shortcode button in edit content toolbar at edit post page.
* Using such shortcodes in pages give a great flexibility to import from different .ics feeds (sources) into the same resource (calendar). Also it's possible to define different CRON parameters for accessing such different pages with different time intervals.
* Or you can import .ics feed or file directly at Booking > Settings > Sync > Import page.

= How to start export of .ics feeds (files)? =

* Install [Booking Calendar](https://wordpress.org/plugins/booking/) plugin.
* Configure ULR feed(s) at the Booking > Settings > Sync > Export page.
* Using such URL(s) you can import .ics feeds, from interface of other websites. Check more info about how to import .ics feeds into other websites at the support pages of specific website.
* Visit these (previously configured URL feeds) pages for downloading .ics files (for example by configuring CRON at your server).

= Support Languages =

- English
- Dutch [63% Completed]
- German [62% Completed]
- Italian [62% Completed]
- Norwegian [62% Completed]
- Swedish [62% Completed]
- Hungarian [62% Completed]
- Ukrainian [62% Completed]
- Russian [62% Completed]
- French [62% Completed]
- Chinese [62% Completed]
- Chinese (Taiwan) [61% Completed]
- Hebrew [61% Completed]
- Danish [61% Completed]
- Finnish [61% Completed]
- Brazilian Portuguese [61% Completed]    
- Polish [61% Completed] 
- Portugal [37% Completed]
- Spanish [37% Completed]
- Greece [37% Completed]
- Czech [37% Completed]
- Slovak [37% Completed]
- Croatian [37% Completed]    
- Turkish [37% Completed]
- Catalan [37% Completed]
- Bulgarian [37% Completed]
- Arabic [58% Completed]
- Belarussian [12% Completed]

= Requirements =

- PHP 5.2.4 or newer,
- MySQL version 5.0 or newer,
- WordPress 4.0 or newer,
- jQuery 1.7.1 or newer

== Screenshots ==

1. **Events Listing** - showing events listing at front-end side of website.
2. **Manage ics** - upload .ics file(s) to your server
3. **Settings** - configure different settings of plugin
4. **Events Listing Template** - customize template how to show events at your website
5. **Inserting shortcode** - popup dialog for easy configuring and inserting plugin shortcode into content of post

== Changelog ==

= 2.0.2 =
* **Impovement** Export to  .ics feed bookings from  Booking Calendar that  does not inside of Trash folder (2.0.2.3)
* **Fix** issue of showing warning "parsererror ~ SyntaxError: JSON.parse: unexpected character at line 1 column 1 of the JSON data" (2.0.2.1)
* **Fix** issue of showing Fatal error: "Uncaught Error: Call to a member function get_error_message()"  (2.0.2.2)

= 2.0.1 =
* **Impovement** Do not show 'Import XX bookings' message, if parameter silence=1 exist in import shortcode (2.0.1.2)
* **Impovement** Show error description if plugin  can  not download .ics file by some reason (2.0.1.3)
* **Fix** issue of not importing events, if end date set  more than 20 years from today date (2.0.1.1)
* **Fix** issue of showing error in PHP 7,  at  the Settings General page  (2.0.1.4)
* **Fix** showing "Deprecated" warnings in PHP 7 environment (2.0.1.5)
* **Fix** correctly  showing single and double quotes (' and ") symbols during export bookings to .ics feed (2.0.1.6)

= 2.0 =
* Fully redeveloping version of plugin

== Upgrade Notice ==
= 2.0 =
List events at your website. Sync bookings from Booking Calendar with different sources (like Airbnb or Booking.com and any other calendar that uses .ics format).