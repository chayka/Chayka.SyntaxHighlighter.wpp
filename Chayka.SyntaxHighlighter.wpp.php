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

require_once 'vendor/autoload.php';

if(!class_exists("Chayka\\WP\\Plugin")){
    add_action( 'admin_notices', function () {
?>
    <div class="error">
        <p>Chayka.Core plugin is required in order for Chayka.SyntaxHighlighter to work properly</p>
    </div>
<?php
	});
}else{
    require_once dirname(__FILE__).'/Plugin.php';
	add_action('init', array("Chayka\\SyntaxHighlighter\\Plugin", "init"));
}
