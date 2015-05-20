<div class="wrap" style="">
	<?php screen_icon('generic'); ?><h2>SEO Auto Links</h2>
				
	<div class="metabox-holder">
		<form name="SEOAutoLinks" action="<?php echo $action_url;?>" method="post" id="seoautoform">
			<input type="hidden" id="_wpnonce" name="_wpnonce" value="<?php echo $nonce; ?>" />
			<input type="hidden" name="submitted" value="1" /> 
			
			<p><?php _e('With SEO Auto Links you can easily add (automatically) links for keywords and phrases in posts, pages and comments and link them to corresponding posts, pages, categories, tags or any URL. Set the following settings to your own needs and let SEO Auto Links do the work for you.','seo-auto-links'); ?></p>
			<p><?php _e('If you find any bugs or have ideas for the plugin, please let me know at:','seo-auto-links'); ?> <a href="http://wordpress.org/support/plugin/seo-auto-links" title="<?php _e('Post your found bug or idea at WordPress.org','seo-auto-links'); ?>" target="_blank">http://wordpress.org/support/plugin/seo-auto-links</a>.</p>
			
			<div id="page_internal_links" class="postbox">
				<h3><?php _e('Internal Links','seo-auto-links'); ?></h3>
				<div class="inside">
					<p><?php _e('SEO Auto Links can process your posts, pages and comments in search for keywords to automatically interlink.','seo-auto-links'); ?></p>
					<table class="form-table">
						<tbody>
							<tr>
								<th><?php _e('Posts','seo-auto-links'); ?>:</th>
								<td><label><input type="checkbox" name="post" <?php echo $post; ?>/> <strong><?php _e('Enable','seo-auto-links'); ?></strong></label><br/><label><input type="checkbox" name="postself" <?php echo $postself; ?>/> <?php _e('Allow self linking','seo-auto-links'); ?></label></td>
							</tr>
							<tr>
								<th><?php _e('Pages','seo-auto-links'); ?>:</th>
								<td><label><input type="checkbox" name="page" <?php echo $page; ?>/> <strong><?php _e('Enable','seo-auto-links'); ?></strong></label><br/><label><input type="checkbox" name="pageself" <?php echo $pageself; ?>/> <?php _e('Allow self linking','seo-auto-links'); ?></label></td>
							</tr>
							<tr>
								<th><?php _e('Comments','seo-auto-links'); ?>:</th>
								<td><label><input type="checkbox" name="comment" <?php echo $comment; ?> /> <strong><?php _e('Enable','seo-auto-links'); ?></strong></label><br/><span class="description"><?php _e('May slow down performance.','seo-auto-links'); ?></span></td>
							</tr>
							<tr>
								<th><?php _e('RSS feeds','seo-auto-links'); ?>:</th>
								<td><label><input type="checkbox" name="allowfeed" <?php echo $allowfeed;?> /> <strong><?php _e('Enable','seo-auto-links'); ?></strong></label><br/><span class="description"><?php _e('Allow processing of RSS feeds. SEO Auto links will embed links in all posts in your RSS feed (according to other options).','seo-auto-links'); ?></span></td>
							</tr>
							<tr>
								<th><?php _e('Max Links','seo-auto-links'); ?>:</th>
								<td><input type="text" name="maxlinks" size="2" value="<?php echo $maxlinks; ?>"/><br/><span class="description"><?php _e('You can limit the maximum number of different links that will be generated per post. Set to 0 for no limit.','seo-auto-links'); ?></span></td>
							</tr>
							<tr>
								<th><?php _e('Max Keyword Links','seo-auto-links'); ?>:</th>
								<td><input type="text" name="maxsingle" size="2" value="<?php echo $maxsingle; ?>"/><br/><span class="description"><?php _e('You can limit the maximum number of links created with the same keyword. Set to 0 for no limit.','seo-auto-links'); ?></span></td>
							</tr>
							<tr>
								<th><?php _e('Max Same URLs','seo-auto-links'); ?>:</th>
								<td><input type="text" name="maxsingleurl" size="2" value="<?php echo $maxsingleurl; ?>"/><br/><span class="description"><?php _e('Limit number of same URLs the plugin will link to. Works only when Max Keyword Links above is set to 1. Set to 0 for no limit.','seo-auto-links'); ?></span></td>
							</tr>
							<tr>
								<th><?php _e('Case sensitive','seo-auto-links'); ?>:</th>
								<td><label><input type="checkbox" name="casesens" <?php echo $casesens;?> /> <strong><?php _e('Enable','seo-auto-links'); ?></strong></label><br/><span class="description"><?php _e('Set whether matching should be case sensitive.','seo-auto-links'); ?></span></td>
							</tr>
						</tbody>
					</table>
					<input class="button-primary" type="submit" name="Submit1" value="<?php _e('Save all settings','seo-auto-links'); ?>" />
				</div>
			</div>
			
			<div id="page_settings" class="postbox">
				<h3><?php _e('Custom Keywords','seo-auto-links');?></h3>
				<div class="inside">
					<p><?php _e('Here you can enter manually the extra keywords you want to automaticaly link. Use comma to seperate keywords and add target url at the end. Use a new line for new url and set of keywords. You can have these keywords link to any url, not only your site.','seo-auto-links');?></p>
					<table class="form-table">
						<tbody>
							<tr>
								<th><?php _e('Keywords & links','seo-auto-links'); ?>:</th>
								<td><textarea name="customkey" id="customkey" rows="10" cols="90"  ><?php echo $customkey; ?></textarea><br/><span class="description"><?php _e('Example')?>:<br/><?php _e('google webmaster, http://www.google.com/webmasters/','seo-auto-links');?><br /><?php _e('wiki, wikipedia, http://wikipedia.org','seo-auto-links');?></span></td>
							</tr>
							<tr>
								<th><?php _e('Grouped keywords','seo-auto-links'); ?>:</th>
								<td><label><input type="checkbox" name="customkey_preventduplicatelink" <?php echo $customkey_preventduplicatelink;?> /> <strong><?php _e('Prevent duplicate','seo-auto-links'); ?></strong></label><br/><span class="description"><?php _e('Prevent Duplicate links for grouped keywords (will link only first of the keywords found in text).','seo-auto-links'); ?></span></td>
							</tr>
							<tr>
								<th><?php _e('Load from URL','seo-auto-links'); ?>:</th>
								<td><input type="text" name="customkey_url" size="90" value="<?php echo $customkey_url;?>" /><br/><span class="description"><?php _e('Load custom keywords from a URL. This appends to the list above.','seo-auto-links');?></span></td>
							</tr>
						</tbody>
					</table>					
					<input class="button-primary" type="submit" name="Submit2" value="<?php _e('Save all settings','seo-auto-links'); ?>" />
				</div>
			</div>
			
			<div id="page_internal_links" class="postbox">
				<h3><?php _e('Excluding','seo-auto-links');?></h3>
				<div class="inside">
					<table class="form-table">
						<tbody>
							<tr>
								<th><?php _e('Heading','seo-auto-links'); ?>:</th>
								<td><label><input type="checkbox" name="excludeheading" <?php echo $excludeheading; ?>/> <strong><?php _e('Exclude','seo-auto-links'); ?></strong></label><br/><span class="description"><?php _e('Prevent linking in heading tags (h1, h2, h3, h4, h5 and h6).','seo-auto-links'); ?></span></td>
							</tr>
							<tr>
								<th><?php _e('Ignore posts / pages','seo-auto-links'); ?>:</th>
								<td><div class="tags"><input id="ign_pp" type="text" name="ignorepost" size="90" value="<?php echo $ignorepost;?>" placeholder="<?php _e('Add a tag','seo-auto-links'); ?>" /></div><br/><br/><span class="description"><?php _e('You may wish to forbid automatic linking on certain posts or pages. Separate them by comma (ID, slug or name).','seo-auto-links'); ?></span></td>
							</tr>
							<tr>
								<th><?php _e('Ignore keywords','seo-auto-links'); ?>:</th>
								<td><div class="tags"><input id="ign_key" type="text" name="ignore" size="90" value="<?php echo $ignore;?>" placeholder="<?php _e('Add a tag','seo-auto-links'); ?>"/></div><br/><br/><span class="description"><?php _e('You may wish to ignore certain words or phrases from automatic linking. Separate them by comma.','seo-auto-links'); ?></span></td>
							</tr>
							<tr>
								<th><?php _e('Single posts / pages only','seo-auto-links'); ?>:</th>
								<td><label><input type="checkbox" name="onlysingle" <?php echo $onlysingle; ?>/> <strong><?php _e('Enable','seo-auto-links'); ?></strong></label><br/><span class="description"><?php _e('To reduce database load you can choose to have SEO Auto links work only on single posts and pages (for example not on main page or archives).','seo-auto-links'); ?></span></td>
							</tr>
						</tbody>
					</table>
					<input class="button-primary" type="submit" name="Submit3" value="<?php _e('Save all settings','seo-auto-links'); ?>" />
				</div>
			</div>
		
			<div id="page_internal_links" class="postbox">
				<h3><?php _e('Targeting','seo-auto-links'); ?></h3>
				<div class="inside">
					<p><?php _e('The targets SEO Auto links should consider. The match will be based on post/page title or category/tag name, case insensitive.','seo-auto-links'); ?></p>
					<table class="form-table">
						<tbody>
							<tr>
								<th><?php _e('Posts','seo-auto-links'); ?>:</th>
								<td><label><input type="checkbox" name="lposts" <?php echo $lposts; ?> /> <strong><?php _e('Enable','seo-auto-links'); ?></strong></label><br/><span class="description"><?php _e('Enable to link internal links to posts.','seo-auto-links'); ?></span></td>
							</tr>
							<tr>
								<th><?php _e('Pages','seo-auto-links'); ?>:</th>
								<td><label><input type="checkbox" name="lpages" <?php echo $lpages; ?> /> <strong><?php _e('Enable','seo-auto-links'); ?></strong></label><br/><span class="description"><?php _e('Enable to link internal links to pages.','seo-auto-links'); ?></span></td>
							</tr>
							<tr>
								<th><?php _e('Categories','seo-auto-links'); ?>:</th>
								<td><label><input type="checkbox" name="lcats" <?php echo $lcats; ?> /> <strong><?php _e('Enable','seo-auto-links'); ?></strong></label><br/><span class="description"><?php _e('Enable to link internal links to categories.','seo-auto-links'); ?> <?php _e('May slow down performance.','seo-auto-links'); ?></span></td>
							</tr>
							<tr>
								<th><?php _e('Tags','seo-auto-links'); ?>:</th>
								<td><label><input type="checkbox" name="ltags" <?php echo $ltags; ?> /> <strong><?php _e('Enable','seo-auto-links'); ?></strong></label><br/><span class="description"><?php _e('Enable to link internal links to tags.','seo-auto-links'); ?> <?php _e('May slow down performance.','seo-auto-links'); ?></span></td>
							</tr>
							<tr>
								<th><?php _e('Minimum categories / tags','seo-auto-links'); ?>:</th>
								<td><label><input type="text" name="minusage" size="2" value="<?php echo $minusage; ?>"/> </label><br/><span class="description"><?php _e('Only link categories and tags that have been used the above number of times or more.','seo-auto-links'); ?></span></td>
							</tr>
							<tr>
								<th><?php _e('Add nofollow','seo-auto-links'); ?>:</th>
								<td><label><input type="checkbox" name="nofolo" <?php echo $nofolo;?>/> <strong><?php _e('Enable','seo-auto-links'); ?></strong></label><br/><span class="description"><?php _e('Add a nofollow attribute to the external links.','seo-auto-links'); ?></span></td>
							</tr>
							<tr>
								<th><?php _e('Open in new window','seo-auto-links'); ?>:</th>
								<td><label><input type="checkbox" name="blanko" <?php echo $blanko;?>/> <strong><?php _e('Enable','seo-auto-links'); ?></strong></label><br/><span class="description"><?php _e('Open the external links in a new window.','seo-auto-links'); ?></span></td>
							</tr>
						</tbody>
					</table>
					<input class="button-primary" type="submit" name="Submit4" value="<?php _e('Save all settings','seo-auto-links'); ?>" />
				</div>
			</div>
		</form>
	 </div>
</div>