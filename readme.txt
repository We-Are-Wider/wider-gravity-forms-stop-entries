=== Wider Gravity Forms Stop Entries ===
Contributors: jonnyauk, wearewider
Tags: Gravity Forms, privacy, GDPR
Requires at least: 4.5
Tested up to: 4.8
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Selectively stop Gravity Forms entries being stored on your web server to comply with privacy and the GDPR.

== Description ==
Gravity Forms is a wonderful plugin and each form submission is stored on your web server and is accessible through the admin area - which can be great if you have problems with the email address you have setup to receive form submissions.

However, there is no easy way in the admin area to selectively stop entries being stored on your web server, it has to be done in code and is a bit of hassle - this plugin makes it easy to stop this potentially sensitive data being stored.

Improve the privacy of your visitors form submissions and make your website comply with the GDPR - this plugin allows you to select individual Gravity Forms you have setup and stop these entries being stored through easy to use admin options.

You will find the options under `Settings > Gravity Forms Stop Entries`.

NOTE: Requires Gravity Forms v1.8 or newer!

== Installation ==
The easiest way is to add the plugin through your admin area:
1) Go to Plugins > Add new
2) Search for `wider gravity forms stop entries`
3) Click Install

Alternatively, you can directly upload to your server:
1) Upload the entire `wider-gravity-forms-stop-entries` folder to the `/wp-content/plugins/` directory
2) Activate the plugin through the `Plugins` menu in WordPress

== Frequently Asked Questions ==
= I am not receiving notifications via email from Gravity Forms form submissions =
This plugin does not do anything to the notifications you have setup. Please ensure you have your notifications correctly configured. If you want to double make sure of this, deactivate this plugin and check your email/notifications.

= Does it really stop form submission data being stored anywhere on the server? =
 Yup, the data is temporarily stored for a second or two to dispatch the notification you have setup (I'm afriad that's unavoidable!) then it\'s deleted forever after the notification has triggered.

= I am doing some fancy stuff with Gravity Forms, will this break it?
It really shouldn\'t - if you are using the Gravity Forms API correctly using the correct action hooks and priorities everything should be fine, but you should ensure that you test your custom functionality after installing this plugin. If you are using data from stored submissions, you will of-course want to keep those submissions and this plugin is not for you obviously!

= Aggghhhh, my email has broken or is down and I am not receiving form submissions
Ah - sadly they are lost then, the whole point of this plugin is to stop form submissions being stored on your server. Sadly, all we can say is please ensure you are using a good quality email service!

= I have changed the name of my form, will this break things?
Nope, it will carry on working just fine so feel free to change the names of your forms to anything you like... we got you covered!

= What if I'm using third-party integrations with Gravity Forms?
Ah - well some of those may rely on referencing data entries in the stored form submissions, so you may end up breaking things if this is the case (this would likely be the case if you are using Gravity Forms to store payments or transaction data maybe). I am afriad your mileage may vary, so please ensure that you test everything if you have third-party integration plugins.

= I've got a bit hacky with my forms configuration and messed around with the form ID values - now what?
You will need to visit the options page and resave your options again to ensure the correct options are saved.

= What happens when I deactivate the plugin?
Your saved options will be deleted, we don't want to clutter up your options table in your database now do we! If you re-activate the plugin after deactivation, you will need to visit the options page and setup your options again.

== Changelog ==
= 1.0 =
* Initial release