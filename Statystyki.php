<?php
/*
Plugin Name: Statystyki

Description: Statystyki kliknięć w dane URL
Version: 1.0
Author: Dawid Kowalczyk

License: GPL
*/
$pluginURI = get_option('siteurl').'/wp-content/plugins/'.dirname(plugin_basename(__FILE__));  //pobranie sciezki katalogu wtyczki
//register_activation_hook(__FILE__,'instalacja');
add_action('wp_head', 'pobranie');
add_action('admin_menu', 'dodajdoadmin');



function pobranie() {
    global $wpdb;

	$url = get_the_ID (); //pobranie id strony
        if ($url == 5676){
            $data = date('Y-m-d');
            $ip = $_SERVER['REMOTE_ADDR'];
            $url=$_GET["temat"];
            $nazwatabeli = $wpdb->prefix . "statystyki_szkolenia";
            $sqls = "SELECT * FROM ".$nazwatabeli." WHERE nazwa = ".$url." AND wejscie_data = '".$data."' AND ip = ".ip2long($ip)."";
            $wynik = $wpdb->query($sqls);

            if($wynik == 0){
                $sql = "INSERT INTO `$nazwatabeli` (`nazwa`,`wejscie_data`,`ip`) VALUES (".$url.",CURRENT_TIMESTAMP,".ip2long($ip).")";
                $wynik = $wpdb->query( $sql );

            }
        }

        $data = date('Y-m-d');
        $ip = $_SERVER['REMOTE_ADDR'];

        $nazwatabeli = $wpdb->prefix . "statystyki_dzienny";
        $sqls = "SELECT * FROM ".$nazwatabeli." WHERE strona_id = ".$url." AND wejscie_data = '".$data."' AND ip = ".ip2long($ip)."";
        $wynik = $wpdb->query($sqls);

            if($wynik == 0){
                $sql = "INSERT INTO `$nazwatabeli` (`strona_id`,`wejscie_data`,`ip`) VALUES (".$url.",CURRENT_TIMESTAMP,".ip2long($ip).")";
                $wynik = $wpdb->query( $sql );

            }

}

function adminstyl() {
	global $pluginURI;
	wp_register_style( 'admincss', $pluginURI . '/admin-style.css', false );
	wp_enqueue_style( 'admincss' );

}
add_action( 'admin_enqueue_style', 'adminstyl' );

function adminscript() {
	global $pluginURI;
	wp_enqueue_script( 'chart', $pluginURI . '/chart.js', false );
        wp_enqueue_script( 'jquery', $pluginURI . '/jquery.js', false );
        wp_enqueue_script( 'jquerybase', $pluginURI . '/jquery.base64.js', false );
        wp_enqueue_script( 'jquerysorter', $pluginURI . '/jquery.tablesorter.js', false );

}
add_action( 'admin_enqueue_scripts', 'adminscript' );

function pokazadmin() {
	ob_start();
	include_once('view.php');
	$out1 = ob_get_contents();
	ob_end_clean();
	echo $out1;
}

function szkoleniastatystyki() {
	ob_start();
	include_once('view-sk.php');
	$out1 = ob_get_contents();
	ob_end_clean();
	echo $out1;
}

function dodajdoadmin() {
	global $pluginURI;
	add_menu_page('Statystyki', 'Statystyki', 'manage_options', 'pokazadmin', 'pokazadmin',$pluginURI.'/icon.png' );
        add_submenu_page( 'pokazadmin', 'Statystyki', 'Szkolenia', 'manage_options', 'szkoleniastatystyki', 'szkoleniastatystyki');
}



/*
function instalacja () {
	global $wpdb; //generowanie tabel w sql. Gdy już istnieją nie są nadpisywane!

            for ($i = 0; $i<1; $i++){
                switch ($i){
                    case 0: $nazwatabeli = $wpdb->prefix . "statystyki_dzienny"; break;

                }
                    if($wpdb->get_var("show tables like '$nazwatabeli'") != $nazwatabeli) {

                    $sql2 = "CREATE TABLE `$nazwatabeli` (
                    `id` bigint(20) NOT NULL auto_increment,
                    `strona_id` int(11) NOT NULL,
                    `wejscie_data` date NOT NULL,
                    `ip` bigint(20) NOT NULL,
                    PRIMARY KEY  (`id`)
                    ) ";
                    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
                    dbDelta($sql2);
                    }
            }
}

*/
?>
