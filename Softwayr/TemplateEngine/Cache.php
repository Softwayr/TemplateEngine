<?php

namespace Softwayr\TemplateEngine;

require_once 'Softwayr/TemplateEngine/TemplateEngine.php';

class Cache {
	private static $file_ext = ".cache.html";
	
	public static function exists( String $view ) {
		return file_exists( $view . Cache::file_ext() );
	}
	
	public static function modified( String $view ) {
		return filemtime( $view . Cache::file_ext() );
	}
	
	public static function outdated( String $view ) {
		return !Cache::exists( $view ) || filemtime( $view . Cache::file_ext() ) - filemtime( $view . TemplateEngine::file_ext() ) < 0;
	}
	
	public static function cache( String $view, String $data = "" ): String {
		if( Cache::outdated( $view ) && $data )
			file_put_contents( $view . Cache::file_ext(), $data );
		return file_get_contents( $view . Cache::file_ext() );
	}
	
	public static function file_ext():String { return Cache::$file_ext; }
}

