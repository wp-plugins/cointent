=== CoinTent Pay Per Article ===
Contributors: gibboj
Tags: access-control, braintree, content, content monetization,ecommerce, earn money, make money, micropayments, monetize, monetization, paywall,  pay per view, payment, payments, paywall, premium, premium content,restrict access, sell, sell content, sell digital goods,stripe, widget
Version: 1.1
Requires at least: 3.0.1
Tested up to: 3.9
Stable tag: 1.1
>>>>>>> master
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Content monetization for the digital age

The digital wallet that enables you to sell individual pieces of content.

== Description ==

CoinTent let’s you sell individual pieces of content for small amounts ($0.05-$1.00).  You choose what content to sell and how to sell it. We handle the rest.

Start using CoinTent in 3 easy steps:
1) Install the plugin
2) Choose which posts to sell
3) Choose how much to sell them for

* Premium Content - Select what to sell. Pick your price.

* Customize - Easy, customizable, and flexible integration. Create a premium purchase experience that works with your brand.

* Secure and Easy Payment - We focus on security and privacy in handling payments, fraud, customer service and account management.

Wallet Optimized for Micropayments
* Increases revenue and payer engagement.
* Specialize in selling content from $0.05-$1.00.
* Consumers fund their wallet up front which helps lower credit card fees.
* Consumers are more likely to make multiple purchases versus one off payments

For an example please see:

http://techpinions.com/how-microsoft-could-hijack-android/28587
http://donnadubinsky.com/2014/01/28/a-kodak-moment/

For more on CoinTent, visit cointent.com

Reader Tracking

Help us improve the reader's experience by turning on anonymous reader tracking.  We track views of posts, clicks on our purchase buttons, and click on user's logging in. We use that tracking to compare against the number of readers that signup for our service, and we use that information to guide our choices on what appears in the box.  We relay this data back to you so you can test different prices and see what content people are most willing to pay for.

We also use this tracking to make sure we don't have any bugs in the product, changes in the numbers help us see bugs even before they are reported to get them fixed ASAP.  We don't use this data to sell to anyone else, it is used to make our product the best it can be and to help your business.


== Installation ==
Checkout full documentation at : https://cointent.com/docs/wordpress

1. Signup at https://cointent.com/p/signup
1. Install the plugin to the wp-content/plugins folder
1. Add your publisher ID to the admin panel
1. Wrap content that you want or pick a category to gate
	* For the shortcode
	A Wordpress shortcode should be used to wrap text you want gated for pay
	The format for this shortcode is:

	[cointent_lockedcontent] CONTENT HERE [/cointent_lockedcontent]

	Optional Arguments:
		title
		:	 Header on the widget before the user buys your article
		subtitle
		:	A message you would like to display to the user
		post_purchase_title
		:	Header on the widget after the user buys your article
		post_purchase_subtitle
		:	A message you would like to display to the user after they have purchased
		article_title
		:	Title used for emails to the user, and for reference in CoinTent's system
		image_url
		:	Image to be displayed on the widget
		* To pick a category, choose to include the category from the CoinTent admin


== Screenshots ==

1. The CoinTent widget integrated into a blog check out http://donnadubinsky.com/2014/01/28/a-kodak-moment/ to see it in action

2. The CoinTent widget on mobile displayed before purchase

3. The CoinTent widget on mobile after purchase


== Frequently Asked Questions ==

= What is CoinTent? =
 CoinTent is a digital wallet service that allows publishers to sell individual pieces of content to consumers for small amounts (down to $0.05).

= How does CoinTent work? =

Publishers integrate the CoinTent plugin and determine what content to sell and how much to sell it for.  This displays the button that allows users to pay for content.  To purchase content, end consumers with an account can purchase content with one click.  End consumers without an account can sign-up for an account and fund their wallet while staying on your website, and then continue to purchase content.

= What does the plugin handle? =

The plugin adds a widget to any post that you choose and the handles the user setting up an account, funding that account, buying the article and saving their purchase history.

= How do I integrate it? =
Check out our step-by-step guide at https://cointent.com/docs/wordpress or email support@cointent.com for more help

= What content can I sell? =
You can sell any content (articles/blog posts/videos etc.).  Just wrap the content you want to hide in the cointent_lockedcontent shortcode and put a preview or description before it to show your users before they buy.  More info available here: https://cointent.com/docs/wordpress

= How do I make an article available for sale. =
You can either pick a Wordpress category and make all of that category for sale or you can add the CoinTent shortcode to the article you want to sell

= Can I choose the prices? =
Yes the prices are defaulted to $0.25 and can be changed at https://cointent.com/p/account

= Can I see stats on how it is performing? =
Yes you can login at https://cointent.com/p/account to see your revenue, in total, by top articles for the last 7, 30, 60 or 90 days

= Do I need a CoinTent publisher Account? =
Yes, we need to know what site you are on, allow you to set your prices, monitor your sales, agree to the publisher terms of service, and contact you for your pay information.  You can signup here: https://cointent.com/p/signup

= How do I sign-up? =
Here:  https://cointent.com/p/signup

= Do I need a CoinTent Account? =
Yes, we need to track how much you charge for each of your articles, they default to $0.25 if you haven't picked a price. Here you can also view how much money you are making each day.  You can signup here: https://cointent.com/p/signup

= What is the cost structure? =
Publishers keep 75% of all money spent on their content.  The remaining percentage largely covers payment fees (which are often $0.30 +3% of the amount a user fills their wallet with), fraud protection, and security.

= How do I get paid? =
To receive payment for your content sales, be sure to completely fill out your contact information on your account page.  Assuming your information is properly filled out, we will pay your account monthly by check as long as you have accumulated a minimum of $10.  If you have any questions on payments, please contact publishers@cointent.com.


= What payment methods does it allow? =
We currently allow users to fill their wallets with Credit Card and will soon support PayPal.


= Do my readers need to create a CoinTent Account? =
Yes, in order for us to enable content prices lower than a $1.00 we need users to setup and fund a wallet. They can signup and manage their account here: https://cointent.com/u/signup


= Do my readers need to create a CoinTent Account? =
Yes in order to allow for content prices lower than a $1 users need to setup and fund a wallet. They can signup and manage their account here: https://cointent.com/u/signup

= I found a bug in the plugin. =
Please email us at support@cointent.com we will fix it right away. Thanks for your help!

== Changelog ==

<<<<<<< HEAD
=======
= 1.1 = 
* Fixed big bug with broke locking when using categories
* Added condense and full widget view toggle to admin
* Added default text fields to admin
* Disabled Sandbox mode

>>>>>>> master
= 1.0 =
* Release to Wordpress.org
* Fixing some formatting, and trying out tagging/ stable release

= 0.2 =
* Added tracking for article gating - tracks the difference between gated articles viewed vs those that are not
* Added time read widget in place of logo for "readTime" experiment

= 0.1 =
* Added emergency failure message state
* Server to server calls to check article gating
* Added excluding and including categories from the plugin
* Added tracking file to plugin - we now track user interaction, page views, user device type and performance metrics so we can see where our issues are with the plugin.
* General cleanup

= 0.0 =
* Prebuilt widget
* Sell individual posts on WP


== Upgrade Notice ==
Still an issue with multiple posts on a single page, cointent_response.js not updating appropriately
