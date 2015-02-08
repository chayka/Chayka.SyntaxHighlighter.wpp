<?php

namespace Chayka\SyntaxHighlighter;

use Chayka\WP;

/**
 * Class Plugin
 * @package Chayka\SyntaxHighlighter
 */
class Plugin extends WP\Plugin{

    /* chayka: constants */
    
    public static $instance = null;

    public static function init(){
        if(!static::$instance){
            static::$instance = $app = new self(__FILE__, [
                /* chayka: init-controllers */
            ]);
            $app->dbUpdate( [ ] );

            /* chayka: init-addSupport */
        }
    }


    /**
     * Register your action hooks here using $this->addAction();
     */
    public function registerActions() {
    	/* chayka: registerActions */
    }

    /**
     * Register your action hooks here using $this->addFilter();
     */
    public function registerFilters() {
		/* chayka: registerFilters */
    }

    /**
     * Register scripts and styles here using $this->registerScript() and $this->registerStyle()
     *
     * @param bool $minimize
     */
    public function registerResources($minimize = false) {
        $this->registerBowerResources(true);

        $this->setResSrcDir('src/');
        $this->setResDistDir('dist/');

		/* chayka: registerResources */
    }

    /**
     * Implement to add addShortcodes() calls;
     */
    public function registerShortcodes(){
        $this->addShortcode('code');

    	/* chayka: registerShortcodes */
    }
}