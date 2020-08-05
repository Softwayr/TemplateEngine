<?php

namespace Softwayr\TemplateEngine\Exceptions;

class TemplateViewNotFoundException extends \Exception {
	public function __construct( String $file_name ) {
		parent::__construct( "TemplateViewNotFoundException: " . $file_name );
	}
}

