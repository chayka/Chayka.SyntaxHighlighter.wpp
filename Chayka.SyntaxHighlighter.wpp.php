<?php
/**
 * Plugin Name: Chayka.SyntaxHighlighter
 * Plugin URI: git@github.com:chayka/Chayka.SyntaxHighlighter.wpp.git
 * Description: Source code syntax highlighter WP plugin
 * Version: 0.0.1
 * Author: Boris Mossounov <borix@tut.by>
 * Author URI: http://anotherguru.me/
 * License: MIT
 */

require_once __DIR__.'/vendor/autoload.php';

if(!class_exists('Chayka\WP\Plugin')){
    add_action( 'admin_notices', function () {
?>
    <div class="error">
        <p>Chayka.Core plugin is required in order for Chayka.SyntaxHighlighter to work properly</p>
    </div>
<?php
	});
}else{
	add_action('init', ['Chayka\SyntaxHighlighter\Plugin', 'init']);
}
