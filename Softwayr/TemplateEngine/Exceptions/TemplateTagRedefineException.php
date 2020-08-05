<?php

namespace Softwayr\TemplateEngine\Exceptions;

class TemplateTagRedefineException extends \Exception {
	public function __construct( String $tag_name, String $tag_defined_value, String $tag_attempted_value ) {
		parent::__construct("TemplateTagRedefineException: Cannot define tag \"" . $tag_name . "\" as \"" . $tag_attempted_value . "\", tag already defined as \"" . $tag_defined_value . "\"!");
	}
}

