=== Amazonify ===
Contributors: GaryKeorkunian
Donate link: http://www.gara.com/projects/amazonify/
Tags: widget, amazon, associate, affiliate, ad, ads, monitize
Requires at least: 2.0.2
Tested up to: 2.0.2
Stable tag: trunk
Version: 0.8.1

== Description ==

With Amazonify you can:

* Insert Amazon Product Links into your posts (image+text and inline text types).
* Insert Amazon Omakase Links into your posts (any supported size).
* Activate Amazon Context Links
* Activate Amazon Product Preview Scripts
* Use more than one Amazon Tracking ID

If you are an Amazon Associate, this is the plugin to have.

Amazonify Your Blog Today!!

== Installation ==

1.  Upload 'amazonify.php' to the '/wp-content/plugins/' directory
2.  Activate the plugin through the 'Plugins' menu in WordPress
3.  Enter your Amazon Associate Tracking ID on the 'Options' | 'Amazonify' page
4.  Insert Amazonify tags into your posts.

Review the Options page for details.

== Frequently Asked Questions ==

None.

== Screenshots ==

None.

== Amazonify Tags ==

To include an Amazon ad in your post simply add the following tag set in the location where you want the ad:

[amazonify]asn:align:type:tracking_id:width:height:text[/amazonify]

where ...

* asn is the Amazon product code (ex. B000EXRSVM) you wish to display
* align is the alignment of the ad block (ex. right or left); ignored when type=text
* type is the type of ad to be displayed, options include:
  o product - displays the "image and text" product box (the default)
  o text - creates an inline text link
  o omakase - displays the Omakase links in any size supported by Amazon
* tracking_id is your Amazon Tracking ID. The default ID - set above - is used when this left blank
* width is the width of the ad; only used for Omakase ads; default=120; ignored for text and product
* height is the height of the ad; only used for Omakase ads; default=240; ignored for text and product
* text is the text used in the inline text links; required for text links; ignored for other types

Each field in the tag is separated by a colon.

Examples

The simplest of Amazonify tags:

[amazonify]B000EXRSVM[/amazonify]

This example supplies the product ASN and accepts the defaults for all other properties. The result is a 120x240 text+image product ad that floats to the left.

--

A 120x240 image+text product box that floats to the right:

[amazonify]B000EXRSVM:right[/amazonify]

--

A 300x250 Omakase link that floats to the left:

[amazonify]::omakase::300:250[/amazonify]

Notice the ASN field is left empty. Amazon will decide which product(s) are displayed in Omakase ads. The align and Tracking ID properties are also blank so the defaults will be used.

--

A 300x250 Omakase link that floats to the right:

[amazonify]:right:omakase:free-software-catalog-20:300:250[/amazonify]

In this example an alternate Tracking Id - free-software-catalog-20 - has been inserted that will override the default. Use this feature if you work with multiple Tracking ID's and would like to use them in your blog.

--

An inline product link within the context of a sentence:

I recommend you buy the [amazonify]B0010TZR44::text::::320GB Passport[/amazonify] from Amazon.

The link text in this example is 320GB Passport. The value of the align, width and height properties are ignored for text links so they are left empty here. The Tracking ID is also left empty.
