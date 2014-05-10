&nbsp;
<div class="content694 clear">
	<h6>&nbsp;</h6>
	<span class="leftFloat"><a name="top"></a><?php $this->loadview($Paginator->results()); ?></span>
	<span class="rightFloat"><?php $this->loadview($Paginator->pages()); ?></span>
</div>

<?php foreach($Stories as $Story): ?>
<div class="content694 storyItem clear">
	<h6>&nbsp;</h6>
	<a href="/article/<?php echo $Story->Story_id; ?>"><img src="/public/upload/preview/<?php echo $Story->Story_image; ?>" alt="" width="160" height="120" /></a>
	<h2><a href="/article/<?php echo $Story->Story_id; ?>"><?php echo $Story->Story_title; ?></a></h2>
	<div class="storyPreview clear">
		<p class="subtext postedBy">Posted by <a href="/profile/<?php echo $Story->User->User_id; ?>"><?php echo $Story->User->User_name; ?></a> on <?php echo Date::day($Story->Story_published); ?></p>
		<p><?php echo $Story->Story_tagline; ?></p>
		<p class="subtext tags">Tags: <?php $this->loadview('content/taglist', array('Links' => $Story->Links)); ?></p>
	</div>
</div>
<?php endforeach; ?>

<div class="content694 clear">
	<h6>&nbsp;</h6>
	<span class="leftFloat"><a href="#top">Back to top</a></span>
	<span class="rightFloat"><?php $this->loadview($Paginator->pages()); ?></span>
</div>

</div>
<div class="sideNav clear">

<h1>Articles</h1>

<p><a href="/articles">Browse all</a></p>
<p><a href="/contribute">Contribute</a></p>

<p>&nbsp;</p>
<form action="" enctype="multipart/form-data" method="get">
<p>Search articles:</p>
<p class="sideSearch"><input type="text" name="search" maxlength="50" value="<?php if (Request::get('search')) echo Request::get('search'); ?>" />&nbsp;<input type="submit" value="Go" /></p>
</form>

<h1>Browse By Tag</h1>

<?php foreach ($Tags as $Tag): ?>
<p><a href="?tag=<?php echo $Tag->Tag_id; ?>"><?php echo $Tag->Tag_name; ?></a></p>
<?php endforeach; ?>

<p>&nbsp;</p>

<h1>Browse By Author</h1>

<?php foreach ($Users as $User): ?>
<p><a href="?user=<?php echo $User->User_id; ?>"><?php echo $User->User_name; ?></a></p>
<?php endforeach; ?>