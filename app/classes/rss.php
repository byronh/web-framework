<?php

class RSS {
	
	// Constructor - sets path of output file.
	
	public function __construct($filepath) {
		$this->filepath = $filepath;
	}
	
	// Generates an RSS feed to the output file in XML format with the specified number of headlines.
	
	public function generate($numheadlines) {
		
		$Stories = new Set('Story');
		$Stories->where('Story_type=2')->sortdesc('Story_published')->limit($numheadlines)->load('Story_title,Story_published,Story_image,Story_tagline,Story_content,User_name');
		
		$data = '<?xml version="1.0" encoding="utf-8"?>';
		$data .= '<rss version="2.0" xmlns:dc="http://dublincore.org/documents/dcmi-namespace/">';
		$data .= '<channel>';
		$data .= '<title>'.SITE_NAME.'</title>';
		$data .= '<description>'.SITE_DESCRIPTION.'</description>';
		$data .= '<link>'.SITE_ADDRESS.'</link>';
		$data .= '<image>';
		$data .=	'<url>'.SITE_ADDRESS.'/img/logo72.png</url>';
		$data .=	'<title>'.SITE_NAME.'</title>';
		$data .=	'<link>'.SITE_ADDRESS.'</link>';
		$data .= '</image>';
		$data .= '<language>en</language>';
		$data .= '<copyright>Copyright 2010/2011 '.SITE_NAME.'</copyright>';
		$data .= '<webMaster>admin@mirrormatch.net ('.SITE_NAME.' Admin)</webMaster>';
		$data .= '<managingEditor>byronh@mirrormatch.net (Byron Henze)</managingEditor>';
		$data .= '<ttl>30</ttl>';
		$data .= '<pubDate>'.Date::rss(time()).'</pubDate>';
		$data .= '<lastBuildDate>'.Date::rss(time()).'</lastBuildDate>';
		
		foreach ($Stories as $Story) {
			$data .= '<item>';
			$data .= '<title>'.$Story->Story_title.'</title>';
			$data .= '<dc:creator>'.$Story->User->User_name.'</dc:creator>';
			$data .= '<description>'.$Story->Story_tagline.'</description>';
			$data .= '<link>'.SITE_ADDRESS.'/article/'.$Story->Story_id.'</link>';
			$data .= '<guid isPermaLink="true">'.SITE_ADDRESS.'/article/'.$Story->Story_id.'</guid>';
			$data .= '<comments>'.SITE_ADDRESS.'/article/'.$Story->Story_id.'#comments</comments>';
			$data .= '<pubDate>'.Date::rss($Story->Story_published).'</pubDate>';
			$data .= '</item>';
		}
		
		$data .= '</channel>';
		$data .= '</rss>';
		
		$File = new File($this->filepath);
		$File->write($data);
		
	}
	
	
	/* * * * *
	 PROTECTED
	* * * * */
	
	protected $filepath;
	
}

?>