&nbsp;
<div class="content694 clear">
	<h6>&nbsp;</h6>
	<div class="leftFloat clear">
	<?php if ($ref): ?>
	<p><a href="<?php echo $ref; ?>">Go Back</a></p>
	<?php else: ?>
	<p>Back to <a href="/contribute">My Articles</a></p>
	<?php endif; ?>
	</div>
	<div class="rightFloat clear">
	</div>
</div>

<div class="content694 storyItem clear">
	<h6>&nbsp;</h6>
	<img src="/public/upload/preview/<?php echo $Story->Story_image; ?>" alt="" />
	<h2><span class="red"><?php echo $Story->Story_title; ?></span></h2>
	<div class="storyPreview clear">
		<p class="subtext postedBy">Posted by <a href="/profile/<?php echo $Story->User->User_id; ?>"><?php echo $Story->User->User_name; ?></a> on <?php echo Date::short($Story->Story_edited); ?></p>
		<p><?php echo $Story->Story_tagline; ?></p>
		<p class="subtext tags">Tags: <span class="red">Example Tag 1</span>, <span class="red">Example Tag 2</span></p>
	</div>
</div>

<div class="content694 clear">
	<h6>&nbsp;</h6>
	<div class="storyContent articleBody clear">
	<h1><?php echo $Story->Story_title; ?></h1>
	<div><p class="spaced subtext">Posted by <a href="/profile/<?php echo $User->User_id; ?>"><?php echo $User->User_name; ?></a> on <?php echo Date::long($Story->Story_edited); ?></p></div>
	<?php echo nl2br_limit($Story->Story_content, 2); ?>
	</div>
</div>

<div class="content694 clear">
	<h6>&nbsp;</h6>
	<div class="leftFloat clear">
	<?php if ($ref): ?>
	<p><a href="<?php echo $ref; ?>">Go Back</a></p>
	<?php else: ?>
	<p>Back to <a href="/contribute">My Articles</a></p>
	<?php endif; ?>
	</div>
	<div class="rightFloat clear">
	</div>
</div>

</div>
<div class="sideNav clear">

<h1>Articles</h1>

<p><a href="/articles">Browse all</a></p>

<form action="/articles" enctype="multipart/form-data" method="get">
<p>Search articles:</p>
<p class="sideSearch"><input type="text" name="search" maxlength="50" value="" />&nbsp;<input type="submit" value="Go" /></p>
</form>

