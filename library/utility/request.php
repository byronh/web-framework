<?php

// Static class

class Request {
	
	// Accesses a GET variable.
	
	public static function get($name, $outputsafe = false) {
		return isset($_GET[$name]) ? $outputsafe ? self::outputsafe(self::filterdata($_GET[$name])):self::filterdata($_GET[$name]):NULL;
	}
	
	// Accesses a POST variable.
	
	public static function post($name, $outputsafe = false) {
		return isset($_POST[$name]) ? $outputsafe ? self::outputsafe(self::filterdata($_POST[$name])):self::filterdata($_POST[$name]):NULL;
	}
	
	// Accesses a SERVER variable.
	
	public static function server($name, $outputsafe = false) {
		return isset($_SERVER[$name]) ? $outputsafe ? self::outputsafe(self::filterdata($_SERVER[$name])):self::filterdata($_SERVER[$name]):NULL;
	}
	
	
	/* * * *
	 PRIVATE
	* * * */
	
	// Cleans a variable so it is safe for HTML output.
	
	private static function outputsafe($str) {
		return str_replace(array("'", '"', '<', '>'), array("&#39;", "&quot;", '&lt;', '&gt;'), $str);
	}
	
	// Prevents XSS attacks.
	
	private static function filterdata($str) {
		if (is_array($str)) {
			$new = array();
			foreach ($str as $key => $val) {
				$new[$key] = self::filterdata($val);
			}
			return $new;
		}
		if (get_magic_quotes_gpc()) {
			$str = stripslashes($str);
		}
		$str = self::xss_clean($str);
		if (strpos($str, "\r") !== false) {
			$str = str_replace(array("\r\n", "\r"), "\n", $str);
		}
		return $str;
	}
	
// Stolen from CodeIgniter (http://codeigniter.com):
private static $xss_hash='';private static $never_allowed_str=array('document.cookie'=>'[removed]','document.write'=>'[removed]','.parentNode'=>'[removed]','.innerHTML'=>'[removed]','window.location'=>'[removed]','-moz-binding'=>'[removed]','<!--'=>'&lt;!--','-->'=>'-->','<![CDATA['=>'<![CDATA[');private static $never_allowed_regex=array("javascript\s*:"=>'[removed]',"expression\s*(\(|&\#40;)"=>'[removed]',"vbscript\s*:"=>'[removed]',"Redirect\s+302"=>'[removed]');private static function xss_clean($str,$is_image=FALSE){if(is_array($str)){while(list($key)=each($str)){$str[$key]=self::xss_clean($str[$key]);}return $str;}$str=self::_remove_invisible_characters($str);$str=preg_replace('|\&([a-z\_0-9]+)\=([a-z\_0-9]+)|i',self::xss_hash()."\\1=\\2",$str);$str=preg_replace('#(&\#?[0-9a-z]{2,})([\x00-\x20])*;?#i',"\\1;\\2",$str);$str=preg_replace('#(&\#x?)([0-9A-F]+);?#i',"\\1\\2;",$str);$str=str_replace(self::xss_hash(),'&',$str);$str=rawurldecode($str);$str=preg_replace_callback("/[a-z]+=([\'\"]).*?\\1/si",array('Request','_convert_attribute'),$str);$str=preg_replace_callback("/<\w+.*?(?=>|<|$)/si",array('Request','_html_entity_decode_callback'),$str);$str=self::_remove_invisible_characters($str);if(strpos($str,"\t")!==FALSE){$str=str_replace("\t",' ',$str);}$converted_string=$str;foreach(self::$never_allowed_str as $key=>$val){$str=str_replace($key,$val,$str);}foreach(self::$never_allowed_regex as $key=>$val){$str=preg_replace("#".$key."#i",$val,$str);}if($is_image===TRUE){$str=preg_replace('/<\?(php)/i',"<?\\1",$str);}else{$str=str_replace(array('<?','?'.'>'),array('<?','?>'),$str);}$words=array('javascript','expression','vbscript','script','applet','alert','document','write','cookie','window');foreach($words as $word){$temp='';for($i=0,$wordlen=strlen($word);$i<$wordlen;$i++){$temp .=substr($word,$i,1)."\s*";}$str=preg_replace_callback('#('.substr($temp,0,-3).')(\W)#is',array('Request','_compact_exploded_words'),$str);}do{$original=$str;if(preg_match("/<a/i",$str)){$str=preg_replace_callback("#<a\s+([^>]*?)(>|$)#si",array('Request','_js_link_removal'),$str);}if(preg_match("/<img/i",$str)){$str=preg_replace_callback("#<img\s+([^>]*?)(\s?/?>|$)#si",array('Request','_js_img_removal'),$str);}if(preg_match("/script/i",$str)OR preg_match("/xss/i",$str)){$str=preg_replace("#<(/*)(script|xss)(.*?)\>#si",'[removed]',$str);}}while($original!=$str);unset($original);$event_handlers=array('[^a-z_\-]on\w*','xmlns');if($is_image===TRUE){unset($event_handlers[array_search('xmlns',$event_handlers)]);}$str=preg_replace("#<([^><]+?)(".implode('|',$event_handlers).")(\s*=\s*[^><]*)([><]*)#i","<\\1\\4",$str);$naughty='alert|applet|audio|basefont|base|behavior|bgsound|blink|body|embed|expression|form|frameset|frame|head|html|ilayer|iframe|input|isindex|layer|link|meta|object|plaintext|style|script|textarea|title|video|xml|xss';$str=preg_replace_callback('#<(/*\s*)('.$naughty.')([^><]*)([><]*)#is',array('Request','_sanitize_naughty_html'),$str);$str=preg_replace('#(alert|cmd|passthru|eval|exec|expression|system|fopen|fsockopen|file|file_get_contents|readfile|unlink)(\s*)\((.*?)\)#si',"\\1\\2(\\3)",$str);foreach(self::$never_allowed_str as $key=>$val){$str=str_replace($key,$val,$str);}foreach(self::$never_allowed_regex as $key=>$val){$str=preg_replace("#".$key."#i",$val,$str);}if($is_image===TRUE){if($str==$converted_string){return TRUE;}else{return FALSE;}}return $str;}private static function xss_hash(){if(self::$xss_hash==''){if(phpversion()>=4.2)mt_srand();else mt_srand(hexdec(substr(md5(microtime()),-8))&0x7fffffff);self::$xss_hash=md5(time()+mt_rand(0,1999999999));}return self::$xss_hash;}private static function _remove_invisible_characters($str){static $non_displayables;if(!isset($non_displayables)){$non_displayables=array('/%0[0-8bcef]/','/%1[0-9a-f]/','/[\x00-\x08]/','/\x0b/','/\x0c/','/[\x0e-\x1f]/');}do{$cleaned=$str;$str=preg_replace($non_displayables,'',$str);}while($cleaned!=$str);return $str;}private static function _compact_exploded_words($matches){return preg_replace('/\s+/s','',$matches[1]).$matches[2];}private static function _sanitize_naughty_html($matches){$str='<'.$matches[1].$matches[2].$matches[3];$str .=str_replace(array('>','<'),array('>','<'),$matches[4]);return $str;}private static function _js_link_removal($match){$attributes=self::_filter_attributes(str_replace(array('<','>'),'',$match[1]));return str_replace($match[1],preg_replace("#href=.*?(alert\(|alert&\#40;|javascript\:|charset\=|window\.|document\.|\.cookie|<script|<xss|base64\s*,)#si","",$attributes),$match[0]);}private static function _js_img_removal($match){$attributes=self::_filter_attributes(str_replace(array('<','>'),'',$match[1]));return str_replace($match[1],preg_replace("#src=.*?(alert\(|alert&\#40;|javascript\:|charset\=|window\.|document\.|\.cookie|<script|<xss|base64\s*,)#si","",$attributes),$match[0]);}private static function _convert_attribute($match){return str_replace(array('>','<','\\'),array('>','<','\\\\'),$match[0]);}private static function _html_entity_decode_callback($match){return self::_html_entity_decode($match[0],"UTF-8");}private static function _html_entity_decode($str,$charset='UTF-8'){if(stristr($str,'&')===FALSE)return $str;if(function_exists('html_entity_decode')&&(strtolower($charset)!='utf-8' OR version_compare(phpversion(),'5.0.0','>='))){$str=html_entity_decode($str,ENT_COMPAT,$charset);$str=preg_replace('~&#x(0*[0-9a-f]{2,5})~ei','chr(hexdec("\\1"))',$str);return preg_replace('~&#([0-9]{2,4})~e','chr(\\1)',$str);}$str=preg_replace('~&#x(0*[0-9a-f]{2,5});{0,1}~ei','chr(hexdec("\\1"))',$str);$str=preg_replace('~&#([0-9]{2,4});{0,1}~e','chr(\\1)',$str);if(stristr($str,'&')===FALSE){$str=strtr($str,array_flip(get_html_translation_table(HTML_ENTITIES)));}return $str;}private static function _filter_attributes($str){$out='';if(preg_match_all('#\s*[a-z\-]+\s*=\s*(\042|\047)([^\\1]*?)\\1#is',$str,$matches)){foreach($matches[0]as $match){$out .=preg_replace("#/\*.*?\*/#s",'',$match);}}return $out;}
}
?>