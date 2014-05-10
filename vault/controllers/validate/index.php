<?php

if (isset($_POST['rules'])) {
	$rules = explode('|', $_POST['rules']);
	$input = $_POST['input'];
	
	foreach ($rules as $rule) {
		if (preg_match("/(.*?)\[(.*?)\]/", $rule, $match)) {
			$rule = $match[1];
			$params = explode(',', $match[2]);
			if ($rule == 'confirm') array_push($params, $_POST['other']);
			$args = array_merge(array($input), $params);
		} else $args = array($input);
		if (!function_exists($rule)) {
			if (file_exists(ROOT.DS.'app'.DS.'controllers'.DS.'validate'.DS.$rule.'.php'))
				require(ROOT.DS.'app'.DS.'controllers'.DS.'validate'.DS.$rule.'.php');
			elseif (file_exists(ROOT.DS.'vault'.DS.'controllers'.DS.'validate'.DS.$rule.'.php'))
				require(ROOT.DS.'vault'.DS.'controllers'.DS.'validate'.DS.$rule.'.php');
		}
		if ($error = call_user_func_array($rule, $args)) {
			echo $error;
			break;
		}
	}
}

?>