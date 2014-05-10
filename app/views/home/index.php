<?php echo script('home.js').script('jquery.easing.js').script('slider.js'); ?>

<div id="lofslidecontent45" class="lof-slidecontent">
	<div class="preload">
		<div></div>
	</div>
	<div class="lof-main-outer">
		<ul class="lof-main-wapper">
			<?php foreach ($Features as $Feature): ?>
			<li>
				<img src="/upload/feature/full/<?php echo $Feature->Feature_image; ?>" alt="<?php echo $Feature->Story->Story_title; ?>" height="300" width="704" />
				<div class="lof-main-item-desc">
					<h3><a title="<?php echo $Feature->Story->Story_title; ?>" href="/article/<?php echo $Feature->Story->Story_id; ?>" class="articleLink"><?php echo $Feature->Story->Story_title; ?></a> <?php echo Date::day($Feature->Story->Story_published); ?></h3>
					<p><?php echo $Feature->Story->Story_tagline; ?></p>
				</div>
			</li>
			<?php endforeach; ?>
		</ul>  	
	</div>

	<div class="lof-navigator-outer">
		<ul class="lof-navigator">
			<?php foreach ($Features as $Feature): ?>
			<li>
				<div style="background-image: url('/upload/feature/thumb/<?php echo $Feature->Feature_thumb; ?>');"></div>
			</li>
			<?php endforeach; ?>
		</ul>
	</div>
</div>

<div class="leftColumn clear">
	<div class="redBox clear">Recent Articles</div>
	<?php foreach ($Stories as $Story): ?>
	<div class="content694 storyItem clear">
		<h6>&nbsp;</h6>
		<a href="/article/<?php echo $Story->Story_id; ?>"><img src="/public/upload/preview/<?php echo $Story->Story_image; ?>" alt="<?php echo $Story->Story_title; ?>" width="160" height="120" /></a>
		<h2><a href="/article/<?php echo $Story->Story_id; ?>"><?php echo $Story->Story_title; ?></a></h2>
		<div class="storyPreview clear">
			<p class="subtext postedBy">Posted by <a href="/profile/<?php echo $Story->User->User_id; ?>"><?php echo $Story->User->User_name; ?></a> on <?php echo Date::short($Story->Story_published); ?></p>
			<p><?php echo $Story->Story_tagline; ?></p>
			<p class="subtext tags">Tags: <?php $this->loadview('content/taglist', array('Links' => $Story->Links)); ?></p>
		</div>
	</div>
	<?php endforeach; ?>
	<div class="content694 clear">
		<h6>&nbsp;</h6>
		<span class="leftFloat clear"><a href="/articles">Older articles...</a></span>
	</div>
</div>
<div class="rightColumn clear">
	<div class="redBox clear">About Us</div>
	<div class="content220 clear">
		<h6>&nbsp;</h6>
		<p class="spaced">At <?php echo SITE_NAME; ?> we promote healthy discussion surrounding all forms of entertainment media. </p>
		<p class="spaced">We strive to create a user generated experience to spark intelligent conversation concerning today's hot topics in gaming, film, and other mediums. <a href="/contribute">Read more...</a></p>
	</div>
	<div class="redBox clear">Poll of the Week</div>
	<div class="content220 clear">
		<h6>&nbsp;</h6>
		<p class="spaced">Question?</p>
		<p><input type="radio" /> Choice 1</p>
		<p><input type="radio" /> Choice 2</p>
		<p><input type="radio" /> Choice 3</p>
		<p><input type="radio" /> Choice 4</p>
		<p><input type="radio" /> Choice 5</p>
	</div>
	<div class="content220 clear">
		<h6>&nbsp;</h6>
		<p id="social">
			<a href="http://feeds.feedburner.com/MirrorMatch" rel="external"><img src="/social/rss.png" alt="" /></a>
			<a href="http://www.facebook.com/pages/Mirror-Match/158612817492104" rel="external"><img src="/social/facebook.png" alt="" /></a>
			<a href="http://twitter.com/MirrorMatch" rel="external"><img src="/social/twitter.png" alt="" /></a>
		</p>
	</div>
</div>