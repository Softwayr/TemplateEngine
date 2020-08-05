<?php

namespace Softwayr\TemplateEngine;

require_once 'Exceptions\TemplateTagRedefineException.php';
require_once 'Exceptions\TemplateViewNotFoundException.php';
require_once 'Cache.php';

use Softwayr\TemplateEngine\Exceptions\TemplateTagRedefineException;
use Softwayr\TemplateEngine\Exceptions\TemplateViewNotFoundException;

class TemplateEngine {
	
	private static $tags = [];
	
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
	
	public static function view( String $file_name, array $tags = [] ) {
		if( file_exists( $file_name . ".view.php" ) )
			if( ( Cache::exists( $file_name ) && Cache::outdated( $file_name ) ) || !Cache::exists( $file_name ) )
				return Cache::cache( $file_name, TemplateEngine::render( file_get_contents( $file_name . ".view.php" ), $tags ) );
			return Cache::cache( $file_name );
		throw new TemplateViewNotFoundException( $file_name );
	}
	
	public static function viewModified( String $file_name ) {
		return filemtime( $file_name . ".view.php" );
	}
}

