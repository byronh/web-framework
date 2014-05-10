<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="description" content="<?php echo SITE_DESCRIPTION; ?>" />
<meta name="keywords" content="<?php echo SITE_KEYWORDS; ?>" />
<meta name="title" content="<?php echo $title ? $title : SITE_NAME; ?>" />
<meta name="google-site-verification" content="3-W21YC4JzgB6FGKPMaPIXRVeHQ3ASoULWGjEYw35c8" />
<link rel="alternate" type="application/rss+xml" title="<?php echo SITE_NAME; ?>" href="/rss.xml" />
<title><?php echo SITE_NAME; ?><?php if ($title) echo ' | '.$title; ?></title>
<?php
echo style('forms');
echo style('global');
echo style('splitpage');
echo style('fullpage');
echo style('admin');
echo style('slider');
echo style('article');
?>
<script type="text/javascript">
  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-19023972-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();
</script>
</head>
<body id="www-mirrormatch-net" <?php if ($form) echo 'onload="document.mainform.field0.focus();"'; ?>>

<h6>&nbsp;</h6>
<div class="container clear">
<div class="header clear">
<div class="headerBanner"><a href="/"><img src="/img/banner.png" alt="" width="714" height="143" /></a></div>
<div class="headerLogin">

<?php if (!$hidelogin): ?>
<?php if ($User): ?>

<h1>User Controls</h1>
<p class="spacedabit">Welcome back, <a href="/profile/" style="color:<?php echo $User->Rank->Rank_color; ?>; text-decoration:none;"><?php echo $User->User_name; ?></a>!</p>
<p><a href="/profile/">My Profile</a></p>
<p class="spacedabit"><a href="/contribute/">My Articles</a></p>
<form enctype="multipart/form-data" action="/logout" method="post">
<input type="hidden" name="_csrf" value="<?php echo Factory::get('Session')->_csrf; ?>" />
<p id="submit"><input type="submit" name="submit" value="Logout" /></p>
</form>

<?php else: ?>

<h1>Login</h1>
<form enctype="multipart/form-data" action="/login" method="post">
<p><input type="text" name="field0" value="Email" onfocus="this.value='';" tabindex="1" /></p>
<p><input type="password" name="field1" value="Password" onfocus="this.value='';" tabindex="2"/></p>
<p>Remember me? <input type="checkbox" name="field2" tabindex="3" /></p>
<input type="hidden" name="redirurl" value="<?php echo Request::server('REQUEST_URI'); ?>" />
<input type="hidden" name="_csrf" value="<?php echo Factory::get('Session')->_csrf; ?>" />
<p id="submit"><input type="submit" name="submit" value="Login" tabindex="4"/> or <a href="/register">Register</a></p>
</form>

<?php endif; ?>
<?php endif; ?>

</div>
</div>
<div class="mainNav clear">
<ul>
<li><a href="/">Home</a></li>
<li><a href="/articles">Articles</a></li>
<li><a href="/contribute">Contribute</a></li>
<?php if ($User && $User->Rank_id >= 3): ?>
<li><a href="/admin">Admin</a></li>
<?php endif; ?>
</ul>
</div>
<div id="mainArea" class="mainArea clear">
<?php echo jquery().script('global.js'); ?>
