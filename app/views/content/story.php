&nbsp;
<div class="content694 clear">
	<h6>&nbsp;</h6>
	<div class="leftFloat clear">
	<p>Back to <a href="/articles">Articles</a></p>
	</div>
</div>

<div class="content694 clear">
	<h6>&nbsp;</h6>
	<div class="storyContent articleBody clear">
		<h1><?php echo $Story->Story_title; ?></h1>
		<div><p class="spaced subtext">Posted by <a href="/profile/<?php echo $User->User_id; ?>"><?php echo $User->User_name; ?></a> on <?php echo Date::long($Story->Story_published); ?></p></div>
		<?php echo nl2br_limit($Story->Story_content, 2); ?>
		<div class="social">
			<p class="small">SHARE THIS ARTICLE</p>
			<div class="twitter">
				<a href="http://twitter.com/share" class="twitter-share-button" data-url="http://mirrormatch.net/article/<?php echo $Story->Story_id; ?>" data-count="none" data-via="MirrorMatch">Tweet</a><script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>
			</div>
			<div class="facebook">
				<iframe src="http://www.facebook.com/plugins/like.php?href=mirrormatch.net%2Farticle%2F<?php echo $Story->Story_id; ?>&amp;layout=button_count&amp;show_faces=true&amp;width=450&amp;action=like&amp;font=lucida+grande&amp;colorscheme=light&amp;height=21" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:450px; height:21px;" allowTransparency="true"></iframe>
			</div>
		</div>
	</div>
</div>

<div class="content694 clear">
	<h6>&nbsp;</h6>
	<div class="leftFloat clear">
	<p>Back to <a href="/articles">Articles</a></p>
	</div>
</div>

<a name="comments"></a>
<?php if (isset($Form)): ?>
<?php 
echo script('article.js');
$this->loadview($Form->getheaderview());
?>
<div class="storyCommentBox">
<?php $this->loadview($Form->getfieldview()); ?>
</div>
<div class="storyButtons"><?php $this->loadview($Form->getbuttonview()); ?></div>
<?php $this->loadview($Form->getfooterview()); ?>
<?php else: ?>
<p class="spacedtop"><strong>Please <a href="/login?redirurl=/article/<?php echo $Story->Story_id; ?>#comments">login</a> or <a href="/register">register</a> to post comments.</strong></p>
<?php endif; ?>
<p>&nbsp;</p>

<?php foreach ($Comments as $Comment): ?>
<div class="content694 storyComment clear">
	<h6>&nbsp;</h6>
	<a name="comment<?php echo $Comment->Comment_id; ?>"></a>
	<p class="leftFloat clear">
		<span class="subtext"><a href="#<?php echo $Comment->Comment_id; ?>">#<?php echo $Comment->Comment_number; ?></a> | Posted by <a href="/profile/<?php echo $Comment->User->User_id; ?>"><?php echo $Comment->User->User_name; ?></a> on <?php echo Date::long($Comment->Comment_posted); ?></span>
	</p>
	<?php if ($Comment->Comment_editable): ?>
	<p class="rightFloat clear">
		<span class="subtext"><a href="/content/editcomment/<?php echo $Comment->Comment_id; ?>">Edit</a> | <a href="/content/deletecomment/<?php echo $Comment->Comment_id; ?>">Delete</a></span>
	</p>
	<?php endif; ?>
	<div class="storyContent clear">
		<?php echo nl2br($Comment->Comment_content); ?>
		<?php if (!empty($Comment->Comment_editby) && !empty($Comment->Comment_edittime)): ?>
		<p class="spacedtopabit unspacedbottom"><span class="subtext"><em>Edited by <?php echo $Comment->Comment_editby; ?> on <?php echo Date::long($Comment->Comment_edittime); ?></em></span></p>
		<?php endif; ?>
	</div>
</div>
<?php endforeach; ?>
<?php if ($Comments->count() == 0): ?>
<?php $this->loadview('emptytable', array('text' => 'No comments yet!')); ?>
<?php else: ?>
<p>&nbsp;</p>
<div class="content694 clear">
	<h6>&nbsp;</h6>
	<div class="leftFloat clear">
	<p>Back to <a href="/articles">Articles</a></p>
	</div>
</div>
<?php endif; ?>

</div>
<div class="sideNav clear">

<h1>Articles</h1>

<p><a href="/articles">Browse all</a></p>

<form action="/articles" enctype="multipart/form-data" method="get">
<p>Search articles:</p>
<p class="sideSearch"><input type="text" name="search" maxlength="50" value="" />&nbsp;<input type="submit" value="Go" /></p>
</form>

<?php if ($Stories->count() > 0): ?>
<h1>Related Stories</h1>

<?php foreach ($Stories as $Story): ?>
<p><a href="/article/<?php echo $Story->Story_id; ?>"><?php echo $Story->Story_title; ?></a></p>
<?php endforeach; ?>
<?php endif; ?>