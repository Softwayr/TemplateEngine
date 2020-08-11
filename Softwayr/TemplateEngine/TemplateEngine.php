<?php

namespace Softwayr\TemplateEngine;

require_once 'Exceptions\TemplateTagRedefineException.php';
require_once 'Exceptions\TemplateViewNotFoundException.php';
require_once 'Cache.php';

use Softwayr\TemplateEngine\Exceptions\TemplateTagRedefineException;
use Softwayr\TemplateEngine\Exceptions\TemplateViewNotFoundException;

class TemplateEngine {
	
	private static $tags = [];
	private static $file_ext = ".view.html";
	
	public static function tag( String $tag_name, String $tag_value = "" ) {
		if( $tag_value && key_exists( $tag_name, TemplateEngine::$tags ) )
			throw new TemplateTagRedefineException( $tag_name, TemplateEngine::$tags[ $tag_name ], $tag_value );
			if( $tag_value && !key_exists( $tag_name, TemplateEngine::$tags ) )
				TemplateEngine::$tags[ $tag_name ] = $tag_value;
		
		if( key_exists( $tag_name, TemplateEngine::$tags ) )
			return TemplateEngine::$tags[ $tag_name ];
	}
	
	public static function render( String $text, array $tags = [] ): String {
		// Check for and prioritise the replacement of tags defined specifically with this request.
		if( count( $tags ) >= 1 )
			foreach ( $tags as $tag_name => $tag_value )
				$text = str_replace( "{{" . $tag_name . "}}", $tag_value, $text );
		
		// Check for and replace globally defined tags.
				if( count( TemplateEngine::$tags ) >= 1 )
					foreach ( TemplateEngine::$tags as $tag_name => $tag_value )
				$text = str_replace( "{{" . $tag_name . "}}", $tag_value, $text );
		
		// Return updated text with all replacements in place.
		return $text;
	}
	
	public static function view( String $view_name, array $tags = [] ) {
		if( file_exists( $view_name . TemplateEngine::file_ext() ) )
			if( ( Cache::exists( $view_name ) && Cache::outdated( $view_name ) ) || !Cache::exists( $view_name ) )
				return Cache::cache( $view_name, TemplateEngine::render( file_get_contents( $view_name . TemplateEngine::file_ext() ), $tags ) );
			return Cache::cache( $view_name );
		throw new TemplateViewNotFoundException( $view_name );
	}
	
	public static function viewModified( String $view_name ) {
		return filemtime( $view_name . TemplateEngine::file_ext() );
	}
	
	public static function file_ext():String { return TemplateEngine::$file_ext; }
}

