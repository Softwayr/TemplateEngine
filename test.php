<?php

require_once 'Softwayr/TemplateEngine/TemplateEngine.php';

use Softwayr\TemplateEngine\TemplateEngine;
use Softwayr\TemplateEngine\Exceptions\TemplateTagRedefineException;
use Softwayr\TemplateEngine\Exceptions\TemplateViewNotFoundException;

try { TemplateEngine::tag( "name", "David" ); TemplateEngine::tag( "fullname", "David Hunter" ); TemplateEngine::tag( "date", date("l jS \of F Y") ); TemplateEngine::tag( "year", date( "Y" ) ); }
catch ( TemplateTagRedefineException $e ) { echo $e->getMessage(); }

try { echo TemplateEngine::view( "test" ); }
catch ( TemplateViewNotFoundException $e ) { echo $e->getMessage(); }
