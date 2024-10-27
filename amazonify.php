<?php
/*
Plugin Name: Amazonify
Plugin URI: http://www.gara.com/projects/amazonify/
Description: Add the ability to drop product links into your blog posts
Author: Gary Keorkunian
Author URI: http://www.gara.com/
Version: 0.8.1

Copyright 2008 GARA Systems, Inc.

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.


HISTORY

Version		Date		Author		Description
--------	--------	-----------	------------------------------------------
0.1			20080121	Gary		Initial version to display product link.
0.2			20080121	Gary		Added support for multiple ads.
0.3			20080123	Gary		Added support for Omakase style ads
									in multiple sizes.
0.4			20080207	Gary		Added margin to Omakase box.
0.5			20080221	Gary		Added support for inline text product links.
0.6			20080301	Gary		Added the Admin Options page.
									Improved the Code Examples.
									Replace amazonifyAID with user modifiable
									setting.
0.7			20080301	Gary		Added amazonify_text, amazonify_product and
									amazonify_omakase class tags.			
0.8			20080302	Gary		Added option for including Context links.	
									Added option for including Product preview scripts.				
									Eliminated redundant function calls
									Added confirmation message on Option Save
									
0.8.1		20080313	Gary		Replaced short tags with long tags for max
									compatibility with PHP5

SEE THE README.TXT FOR INSTRUCTIONS

*/

// Replace Amazonify Tags in Content with Amazon Links
function amazonifyContent($content)
{
	$sp=0;
	$ep=0;
	
	$new_content=$content;
	
	while($sp=strpos($new_content, '[amazonify]'))		// find each amazonify tag
	{
		$ep=strpos($new_content, '[/amazonify]');
		
		if($sp>0 & $ep>0)
		{
			$sub=substr($new_content, $sp, $ep-$sp);	// extract the begin tag and parameters from contents
			$sub=str_replace('[amazonify]', '', $sub);	// remove the begin tag
			
			// extract the parameters 
			list($asn,$align,$type,$tracking_id,$width,$height,$text)=split(":", $sub, 7);	
			
			// set default values for missing parameters
			if($asn=='') $asn='';
			if($align=='') $align='left';
			if($type=='') $type='product';
			if($tracking_id=='') $tracking_id=get_option('amazonify_TrackingID');
			if($width=='') $width=120;
			if($height=='') $height=240;
			
			// build the link based on the ad type
			switch($type)
			{
				case 'omakase':
					$link = '<span class="amazonify_omakase" style="float:'.$align.';margin:5px;">'
							. '<script type="text/javascript">amazon_ad_tag = "'.$tracking_id.'";  amazon_ad_width = "'.$width.'";  amazon_ad_height = "'.$height.'";</script>'
							. '<script type="text/javascript" src="http://www.assoc-amazon.com/s/ads.js"></script>'
							. '</span>';
					break;
					
				case 'text':
					$link ='<span class="amazonify_text"><a href="http://www.amazon.com/gp/product/'.$asn.'?ie=UTF8&tag='.$tracking_id.'&linkCode=as2&camp=1789&creative=9325&creativeASIN='.$asn.'">'.$text.'</a><img src="http://www.assoc-amazon.com/e/ir?t='.$tracking_id.'&l=as2&o=1&a='.$asn.'" width="1" height="1" border="0" alt="" style="border:none !important; margin:0px !important;" /></span>';
					break;

				case 'both':	
				case 'product':
					$link = '<span class="amazonify_product"><iframe align="'.$align.'" '
							. ' src="http://rcm.amazon.com/e/cm?t='.$tracking_id.'&o=1&p=8&l=as1&asins='.$asn.'&fc1=000000&IS2=1&lt1=_blank&lc1=0000FF&bc1=000000&bg1=FFFFFF&f=ifr&nou=1"'
							. ' style="width:'.$width.'px;height:'.$height.'px;margin:7px;" scrolling="no" marginwidth="0" marginheight="0" frameborder="0">'
							. '</iframe></span>';
					break;
					
			}
	
			$new_content=str_replace('[amazonify]'.$sub.'[/amazonify]', $link, $new_content);
			
		}
	}
	
	return $new_content;
}

// Add Amazon Context Ads script
function amazonifyContextAds()
{
	if(get_option('amazonify_ContextLink')=="1")
	{
?>
<script type="text/javascript"><!-- 
amzn_cl_tag="<?php echo get_option('amazonify_TrackingID'); ?>";
//--></script>
<script type="text/javascript" src="http://cls.assoc-amazon.com/s/cls.js"></script>
<?php
	}
}

// Add Amazon Product Preview script
function amazonifyProductPreview()
{
	if(get_option('amazonify_ProductPreview')=="1")
	{
?>
<script type="text/javascript" src="http://www.assoc-amazon.com/s/link-enhancer?tag=<?php echo  get_option('amazonify_TrackingID'); ?>&o=1">
</script>
<noscript>
    <img src="http://www.assoc-amazon.com/s/noscript?tag=<?php echo  get_option('amazonify_TrackingID'); ?>" alt="" />
</noscript>
<?php
	}
}


// Admin Options Page
function amazonifyOptionsPage()
{

	if(isset($_POST['AmazonifyUpdate']))
	{
		$tracking_id=$_POST["TrackingID"];
		$context_link=$_POST["ContextLink"];
		$product_preview=$_POST["ProductPreview"];
		update_option('amazonify_TrackingID', $tracking_id);
		update_option('amazonify_ContextLink', $context_link);
		update_option('amazonify_ProductPreview', $product_preview);
?>
<div class="updated fade" id="message" style="background-color: rgb(207, 235, 247);"><p><strong>Options saved.</strong></p></div>
<?php
	}
	else
	{
		$tracking_id=get_option('amazonify_TrackingID');
		$context_link=get_option('amazonify_ContextLink');
		$product_preview=get_option('amazonify_ProductPreview');
	}

?>
	<div class="wrap">
		<h2>Amazonify</h2>
		<form method="POST">
			<table class="optiontable">
				<tr valign="top">
					<th>Default Amazon Tracking ID:</th>
					<td><input id="TrackingID" name="TrackingID" type="text" value="<?php echo $tracking_id; ?>"><br>
					You can override this Tracking ID for specific ads</td>
				</tr>
				<tr>
					<th>&nbsp;</th>
					<td>
					<input type="checkbox" id="ProductPreview" name="ProductPreview" value="1" <?php echo $product_preview ? 'checked' : ''; ?>>
					Include Product Preview Scripts</td>
				</tr>
				<tr valign="top">
					<th>&nbsp;</th>
					<td>
					<input type="checkbox" id="ContextLink" name="ContextLink" value="1" <?php echo $context_link ? 'checked' : ''; ?>>
					Include Context Links</td>
				</tr>
				</table>
			<p class="submit"><input name="AmazonifyUpdate" type="submit" value="Update Options &raquo;"></p>
		</form>
		<h3>Markup Tags</h3>
		<p>To include an Amazon ad in your post simply add the following tag set in the location where you want the ad:</p>
		<code>[amazonify]asn:align:type:tracking_id:width:height:text[/amazonify]</code>
		<p>where ... </p>
		<ul>
			<li><b>asn</b> is the Amazon product code (ex. B000EXRSVM) you wish to display</li>
			<li><b>align</b> is the alignment of the ad block (ex. right or left); ignored when type=text</li>
			<li><b>type</b> is the type of ad to be displayed, options include:
				<ul>
					<li><b>product</b> - displays the &quot;image and text&quot; product box (the default)<br></li>
					<li><b>text</b> - creates an inline text link<br></li>
					<li><b>omakase</b> - displays the Omakase links in any size supported by Amazon</li>
				</ul>
			</li>
			<li><b>tracking_id</b> is your Amazon Tracking ID.  The default ID - set above - is used when this left blank</li>
			<li><b>width</b> is the width of the ad; only used for Omakase ads; default=120; ignored for text and product</li>
			<li><b>height</b> is the height of the ad; only used for Omakase ads; default=240; ignored for text and product</li>
			<li><b>text</b> is the text used in the inline text links; required for text links; ignored for other types</li>
		</ul>
		<p>Each field in the tag is separated by a colon.</p>
		<h4>Examples</h4>

		<p>The simplest of Amazonify tags:</p>
		<code>[amazonify]B000EXRSVM[/amazonify]</code>
		<p>This example supplies the product ASN and accepts the defaults for all other properties.  The result is a 120x240 text+image product ad that floats to the left.</p><hr>
		
		<p>A 120x240 image+text product box that floats to the right: </p>
		<code>[amazonify]B000EXRSVM:right[/amazonify]</code><hr>

		<p>A 300x250 Omakase link that floats to the left: </p>
		<code>[amazonify]::omakase::300:250[/amazonify]</code>
		<p>Notice the ASN field is left empty.  Amazon will decide which product(s) are displayed in Omakase ads. The align and Tracking ID properties are also blank so the defaults will be used.</p><hr>

		<p>A 300x250 Omakase link that floats to the right: </p>
		<code>[amazonify]:right:omakase:free-software-catalog-20:300:250[/amazonify]</code>
		<p>In this example an alternate Tracking Id - <b>free-software-catalog-20</b> - has been inserted that will override the default.  Use this feature if you work with multiple Tracking ID's and would like to use them in your blog.</p><hr>
		
		<p>An inline product link within the context of a sentence: </p>
		<code>I recommend you buy the [amazonify]B0010TZR44::text::::320GB Passport[/amazonify] from Amazon.</code>
		<p>The link text in this example is <b>320GB Passport</b>. The value of the align, width and height properties are ignored for text links so they are left empty here. The Tracking ID is also left empty.</p>
	</div>
	<div class="wrap">
		<h2>More Information</h2>
		<p>Check for the latest information on Amazonify here:  <a href="http://www.gara.com/projects/amazonify/">http://www.gara.com/projects/amazonify/</a></p>
		<p>Subscribe to Amazonify Updates via RSS or Email here:  <a href="http://feeds.feedburner.com/Amazonify">http://feeds.feedburner.com/Amazonify</a></p>
		<p>If you like Amazonify, then you might also like <a href="http://www.gara.com/projects/googmonify/">Googmonify</a> and <a href="http://www.gara.com/projects/bookmarkify/">Bookmarkify</a>, also by <a href="http://www.gara.com/">GARA Systems</a>.</p>
	</div>
<?php
}

// Add Options Page
function amazonifyAdminSetup()
{
	add_options_page('Amazonify', 'Amazonify', 8, basename(__FILE__), 'amazonifyOptionsPage');	
}

// Load Amazonify Actions
if (function_exists('add_action'))
{
	add_action('the_content', 'amazonifyContent');
	add_action('wp_footer', 'amazonifyContextAds');
	add_action('wp_footer', 'amazonifyProductPreview');
	add_action('admin_menu', 'amazonifyAdminSetup');
}

?>