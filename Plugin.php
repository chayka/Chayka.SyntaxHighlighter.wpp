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
	        $app->addSupport_PostProcessing(100);


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
        $this->addFilter('the_content', ['\\Chayka\\SyntaxHighlighter\\GeSHiHelper', 'theContent'], 1);
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
//        $this->addShortcode('code');

    	/* chayka: registerShortcodes */
    }
    
    /* postProcessing */

    /**
     * This is a hook for save_post
     *
     * @param integer $postId
     * @param WP_Post $post
     */
    public function savePost($postId, $post){
        $richPost = WP\Models\PostModel::unpackDbRecord($post);
        GeSHiHelper::generatePostHighlighting($richPost);
    }
    
    /**
     * This is a hook for delete_post
     *
     * @param integer $postId
     */
    public function deletePost($postId){
        
    }
    
    /**
     * This is a hook for trashed_post
     *
     * @param integer $postId
     */
    public function trashedPost($postId){
        $this->deletePost($postId);
    }
}