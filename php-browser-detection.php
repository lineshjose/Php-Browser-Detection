<?php
/*
	Name: Simple PHP Browser Detection script.
	Version : 13.08
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

-----------------------------------------------------------

	This function to get the current browser info
	@param $arg : returns current browser property. Eg: platform, name, version,
	@param $agent: it is the $_SERVER['HTTP_USER_AGENT'] value
*/
	
function get_browser_info($arg='',$agent='')
{
	//print_r($_SERVER['HTTP_USER_AGENT']);
	if(empty($agent) ) {
		$browser['agent'] = $_SERVER['HTTP_USER_AGENT'];
	}else{
		$browser['agent']=$agent;
	}

	
	/*----------------------------------------- Platform ---------------------------------------------*/
	if((bool) strpos( $browser['agent']  , 'iPad')){ 	// for iPad
		$browser['platform']='ipad';
	}elseif((bool) strpos( $browser['agent']  , 'iPhone')){ 	// for iPhone
		$browser['platform']='iphone';
	}elseif((bool) strpos($browser['agent']  , 'iPod')){ 	// for iPod
		$browser['platform']='ipod';
	}elseif(((bool) strpos( $browser['agent']  , 'Linux')) && ((bool)strpos( $browser['agent']  , 'Android')) ){ 	// for Android
		$browser['platform']='android';
	}elseif( ((bool) strpos( $browser['agent'] , 'Linux')) && (!(bool)strpos( $browser['agent'] , 'Android')) ){ 	// for Linux
		$browser['platform']='linux';
	}elseif( ((bool) strpos( $browser['agent']  , 'Windows')) ){	// for Windows
		$browser['platform']='windows';
	}elseif( ((bool) strpos($browser['agent']  , 'Macintosh')) ){	// for Macintosh
		$browser['platform']='mac';
	}else{
		$browser['platform']='others';
	}
	
	
	
	/*----------------------------------------- browser name ---------------------------------------------*/
	if((bool) strpos( $browser['agent'] , 'Firefox')){ 	// for Firefox
		$browser['name']='firefox';
	}elseif((bool) strpos( $browser['agent']  , 'Chrome')){ 	// for chrome
		$browser['name']='chrome';
	}elseif((bool) strpos( $browser['agent']  , 'MSIE')){ 	// for IE
		$browser['name']='ie';
	}elseif( ((bool) strpos( $browser['agent']  , 'Safari')) ){ // for Safari
		$browser['name']='safari';
	}elseif( ((bool) strpos( $browser['agent']  , 'Opera')) ){// for Opera
		$browser['name']='opera';
	}else{
		$browser['name']='others';
	}
	
	
	/* ------------------------------------------ version number ------------------------------------ */
	if($browser['name']=='ie'){
		$br='MSIE';
	}else{
		$br=ucfirst($browser['name']);
	}
	
	$known = array('Version', $br, 'other');
	$pattern = '#(?<browser>' . join('|', $known) .')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
	if (!preg_match_all($pattern,$browser['agent'], $matches)) {
		// we have no matching number just continue
	}
	
	// see how many we have
	$i = count($matches['browser']);
	if ($i != 1) {
		//we will have two since we are not using 'other' argument yet
		//see if version is before or after the name
		if (strripos($browser['agent'],"Version") < strripos($browser['agent'],$br)){	$version= $matches['version'][0];}
		else {$version= $matches['version'][1];	}
	}
	else {
		$ver=explode('.',$matches['version'][0]);
		$version=$ver[0];	
	}
	// check if we have a number
	if ($version==null || $version=="") {$version="?";}
	
	
	// Browser verion ------------>
	$browser['version']=$version;
	
	// Major version --------------->
	$browser['majorver']=(int)$version;
	
	if($arg){
		return $browser[$arg];
	}else{	
		return $browser;
	}
}



/* 
	This function to validate current browser. this function returns boolian value
	@param $name : browser name
	@param $version: browser version
*/
function is_browser($name, $version=''){
	$name=strtolower($name);
	$curr_brws=get_browser_info('name');
	$curr_version=get_browser_info('version');
	if($curr_brws==$name){
		$true[]=true;
	}
	if($curr_version==$version){
		$true[]=true;
	}
	if(!empty($true)){
		$true=array_filter($true,trim);
	}	
	if(count($true)>0){
		return true;
	}else{
		return false;
	}
}


/* 
	This function to validate current browser platform. this function returns boolian value
	@param $platform: browser platform (OS)
*/
function is_browser_platform($platform=''){
	if($platform){
		$platform=strtolower($platform);
		$curr_platform=get_browser_info('platform');
			if($curr_platform==$platform){
				$true=true;
			}else if( $platform=='ios' && in_array($curr_platform, array('iphone','ipod','ipad'))){
				$true=true;	
			}
		$true=trim($true);
		if(!empty($true)){
			return true;
		}else{
			return false;
		}
	}
}
?>
