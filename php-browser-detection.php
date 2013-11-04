<?php
/*
	Name: Simple PHP Browser Detection script.
	Version : 13.11
	Author: Linesh Jose
	Url: http://lineshjose.com
	Email: lineshjose@gmail.com
	Donate:  http://bit.ly/donate-linesh
	github: https://github.com/lineshjose
	Copyright: Copyright (c) 2013 LineshJose.com
	
	Note: This script is free; you can redistribute it and/or modify  it under the terms of the GNU General Public License as published by 
		the Free Software Foundation; either version 2 of the License, or (at your option) any later version.This script is distributed in the hope 
		that it will be useful,    but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. 
		See the  GNU General Public License for more details.
*/

/* List of popular web browsers ---------- */
function browsers(){
	return array(
		0=>	'Avant Browser','Arora', 'Flock', 'Konqueror','OmniWeb','Phoenix','Firebird','Mobile Explorer',	'Opera Mini','Netscape',
			'Iceweasel','KMLite', 'Midori', 'SeaMonkey', 'Lynx', 'Fluid', 'chimera', 'NokiaBrowser',
			'Firefox','Chrome','MSIE','Internet Explorer','Opera','Safari','Mozilla','trident'
		);	
}
/* List of popular web robots ---------- */
function robots(){
	return  array(
		0=>	'Googlebot', 'Googlebot-Image', 'MSNBot', 'Yahoo! Slurp', 'Yahoo', 'AskJeeves','FastCrawler','InfoSeek Robot 1.0', 'Lycos',
			'YandexBot','YahooSeeker'
		);	
}
/* List of popular os platforms ---------- */
function platforms(){
	return  array(
		0=>	'iPad', 'iPhone', 'iPod', 'Mac OS X', 'Macintosh', 'Power PC Mac', 'Windows', 'Windows CE',
			'Symbian', 'SymbianOS', 'Symbian S60', 'Ubuntu', 'Debian', 'NetBSD', 'GNU/Linux', 'OpenBSD', 'Android', 'Linux',
			'Mobile','Tablet',
		);	
}


/*
	This function to get the current browser info
	@param $arg : returns current browser property as an array. Eg: platform, name, version,
	@param $agent: it is the $_SERVER['HTTP_USER_AGENT'] value
*/
function get_browser_info($arg='',$agent='')
{
	if(empty($agent) ) {
		$agent = strtolower($_SERVER['HTTP_USER_AGENT']);
	}
	
	/*----------------------------------------- browser name ---------------------------------------------*/
	foreach( browsers() as $key){
		if(strpos($agent, strtolower(trim($key))) ){ 	
			$name= trim($key);
			break;  
		}else{
			continue;
		}
	}
	
	/*----------------------------------------- robot name ---------------------------------------------*/
	foreach(robots() as $key){
		if (preg_match("|".preg_quote(strtolower(trim($key)))."|i", $agent)){
			$is_bot = TRUE;
			$name= trim($key);
			break;  
		}else{
			$is_bot = false;
			continue;
		}
	}
	
	/*----------------------------------------- robot name ---------------------------------------------*/
	$known = array('version',strtolower($name), 'other');
	$pattern = '#(?<browser>' . join('|', $known) .')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
	if (preg_match_all($pattern,$agent, $matches)) 
	{
		if (count($matches['browser'])>0)
		{
			if (strripos($agent,"version") < strripos($agent,strtolower($name)) ){	
				$version= $matches['version'][0];
			}else {
				$version= $matches['version'][1];	
			}
		}else{
			$version=0;	
		}
		if ($version==null || $version=="") {$version="?";}
		$version=(int)round($version);
	}

	/*----------------------------------------- Platform ---------------------------------------------*/
	foreach(platforms() as $key){
		if (preg_match("|".preg_quote(trim($key))."|i", $agent)){
			$platform=trim($key);
			break;  
		}else{
			continue;
		}
	}

	/*----------------------------------------- Browser Info ---------------------------------------------*/
	$browser['agent']=$agent;
	if($name=='trident'){
		$browser['name']='Internet Explorer';
		$browser['version']='11';
	}elseif(empty($name)){
		$browser['name']='Unknown';
		$browser['version']=0;	
	}else{
		$browser['name']=$name;
		$browser['version']=$version;
	}
	$browser['is_bot']=$is_bot;
	$browser['platform']=$platform;
	
	if($arg){
		return $browser[$arg];
	}else{	
		return $browser;
	}
}



/* 
	This function to validate current browser. this function returns boolian value
	@param $name : browser name
*/
function is_browser($name){
	$name=strtolower(trim($name));
	$curr_brws=strtolower(get_browser_info('name'));
	if($curr_brws==$name){
		return true;
	}else{
		return false;
	}
}


/* 
	This function to validate current browser version. this function returns boolian value
	@param $version: browser version
*/
function is_browser_version($version){
	$version=strtolower(trim($version));
	$curr_version=strtolower(get_browser_info('version'));
	if($version==$curr_version){
		return true;
	}else{
		return false;
	}
}


/* 
	This function to validate current browser platform. this function returns boolian value
	@param $platform: browser platform (OS)
*/
function is_browser_platform($platform){
	$platform=strtolower(trim($platform));
	$curr_platform=strtolower(get_browser_info('platform'));
	if($curr_platform==$platform){
		return true;
	}else if( $platform=='ios' && in_array($curr_platform, array('iphone','ipod','ipad'))){
		return true;
	}else{
		return false;
	}
}


/* 
	This function to validate current browser is a robot. this function returns boolian value
*/
function is_robot(){
	if(get_browser_info('is_bot')){
		return true;
	}else{
		return false;
	}
}

?>
