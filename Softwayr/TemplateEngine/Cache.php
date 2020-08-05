<?php

namespace Softwayr\TemplateEngine;

class Cache {
	public function __construct() {}
	
	public static function exists( String $view ) {
		return file_exists( $view . ".view.html" );
	}
	
	public static function modified( String $view ) {
		return filemtime( $view . ".view.html" );
	}
	
	public static function outdated( String $view ) {
		return !Cache::exists( $view ) || filemtime( $view . ".view.html" ) - filemtime( $view . ".view.php" ) < 0;
	}
	
	public static function cache( String $view, String $data = "" ): String {
		if( Cache::outdated( $view ) && $data )
			file_put_contents( $view . ".view.html", $data );
		return file_get_contents( $view . ".view.html" );
	}
}

