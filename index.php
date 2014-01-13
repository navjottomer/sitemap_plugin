<?php
/*
Plugin Name: Sitemap Plugin
Plugin URI: http://tuffclassified.com
Description: Display browser and search engine friendly XML Sitemap
Version: 1.0.0
Author: Navjot Tomer 
Author URI: http://tuffclassified.com/
Short Name: sitemap_plugin
*/

function sitemap_plugin_call_after_install() {
            
            osc_set_preference('sitemap_number', 5000, 'sitemap_plugin');
            osc_set_preference('sitemap_categories', false, 'sitemap_plugin');
            osc_set_preference('sitemap_countries', false, 'sitemap_plugin');
            osc_set_preference('sitemap_regions', false, 'sitemap_plugin');
            osc_set_preference('sitemap_cities', false, 'sitemap_plugin');
            
            osc_reset_preferences();
        }
function sitemap_plugin_call_after_uninstall() {
            
            osc_delete_preference('sitemap_number', 'sitemap_plugin');
            osc_delete_preference('sitemap_categories', 'sitemap_plugin');
            osc_delete_preference('sitemap_countries', 'sitemap_plugin');
            osc_delete_preference('sitemap_regions',  'sitemap_plugin');
            osc_delete_preference('sitemap_cities', 'sitemap_plugin');
            
            osc_reset_preferences();
        }


 function sitemap_actions_admin() {

        switch( Params::getParam('action_specific') ) {

            case('sitemap'):
                $enabledcat  = Params::getParam('sitemap_categories');
                $enabledcountries  = Params::getParam('sitemap_countries');
                $enabledregions  = Params::getParam('sitemap_regions');
                $enabledcities = Params::getParam('sitemap_cities');
                osc_set_preference('sitemap_categories', ($enabledcat ? '1' : '0'), 'sitemap_plugin');
                osc_set_preference('sitemap_countries', ($enabledcountries ? '1' : '0'), 'sitemap_plugin');
                osc_set_preference('sitemap_regions', ($enabledregions ? '1' : '0'), 'sitemap_plugin');
                osc_set_preference('sitemap_cities', ($enabledcities ? '1' : '0'), 'sitemap_plugin');
                osc_set_preference('sitemap_number', Params::getParam('sitemap_number'), 'sitemap_plugin');
                
                osc_add_flash_ok_message(__('Sitemap settings updated correctly', 'sitemap_plugin'), 'admin');
                header('Location: ' . osc_admin_render_plugin_url('sitemap_plugin/admin.php')); exit;
            break;
            case('generate_sitemap'):
                generate_sitemap();
                osc_add_flash_ok_message(__('Your XML sitemap generated successfull', 'sitemap_plugin'), 'admin');
                header('Location: ' . osc_admin_render_plugin_url('sitemap_plugin/admin.php')); exit;
            break;

        }
    }
osc_add_hook('init_admin', 'sitemap_actions_admin');
	 
//Sitemap Function Start
    
function sitemap_add_url($url = '', $date = '', $freq = 'daily') {
    if( preg_match('|\?(.*)|', $url, $match) ) {
        $sub_url = $match[1];
        $param = explode('&', $sub_url);
        foreach($param as &$p) {
            list($key, $value) = explode('=', $p);
            $p = $key . '=' . urlencode($value);
        }
        $sub_url = implode('&', $param);
        $url = preg_replace('|\?.*|', '?' . $sub_url, $url);
    }

    $filename = osc_base_path() . 'sitemap.xml';
    $xml  = '    <url>' . PHP_EOL;
    $xml .= '        <loc>' . htmlentities($url, ENT_QUOTES, "UTF-8") . '</loc>' . PHP_EOL;
    $xml .= '        <lastmod>' . $date . '</lastmod>' . PHP_EOL;
    $xml .= '        <changefreq>' . $freq . '</changefreq>' . PHP_EOL;
    $xml .= '    </url>' . PHP_EOL;
    file_put_contents($filename, $xml, FILE_APPEND);
}



function ping_engines() {
    
    // GOOGLE
    osc_doRequest( 'http://www.google.com/webmasters/sitemaps/ping?sitemap='.urlencode(osc_base_url() . 'sitemap.xml.gz'), array());
    // BING
    osc_doRequest( 'http://www.bing.com/webmaster/ping.aspx?siteMap='.urlencode(osc_base_url() . 'sitemap.xml.gz'), array());
    // YAHOO!
    osc_doRequest( 'http://search.yahooapis.com/SiteExplorerService/V1/updateNotification?appid='.osc_page_title().'&url='.urlencode(osc_base_url() . 'sitemap.xml.gz'), array());
}    

function generate_sitemap() {

    $min = 1;
    $numurl = osc_get_preference('sitemap_number', 'sitemap_plugin');
    $locales = osc_get_locales();

    $filename = osc_base_path() . 'sitemap.xml';
    @unlink($filename);
    $start_xml = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL . '<?xml-stylesheet type="text/xsl" href="' . osc_base_url() .'oc-content/plugins/sitemap_plugin/xmlsitemap.xsl"?>' . PHP_EOL . '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . PHP_EOL;
    file_put_contents($filename, $start_xml);
    
    // INDEX
    sitemap_add_url(osc_base_url(), date('Y-m-d'), 'always');
    
    // Category
    if(osc_count_categories () > 0) {
    while ( osc_has_categories() ) {
        if ( osc_category_total_items() > 0 ) {
                sitemap_add_url(osc_search_category_url(), date('Y-m-d'), 'hourly');
            	}
        if ( osc_count_subcategories() > 0 ) {
           while ( osc_has_subcategories() ) {
                if ( osc_category_total_items() > 0 ) {
                      	sitemap_add_url(osc_search_category_url(), date('Y-m-d'), 'hourly');
                  }
    	   	}
    	}
    }
    }
    // countries
    if( osc_get_preference('sitemap_countries', 'sitemap_plugin') ) {
    if(osc_count_list_countries() > 0) {
        while ( osc_has_list_countries() ) {
            sitemap_add_url(osc_list_country_url(), date('Y-m-d'), 'weekly');
         }
    }
    }
    // Regions
    if( osc_get_preference('sitemap_regions', 'sitemap_plugin') ) {
    if(osc_count_list_regions() > 0) {
        while ( osc_has_list_regions() ) {
            sitemap_add_url(osc_list_region_url(), date('Y-m-d'), 'weekly');
         }
    }
    }
    // Cities
    if( osc_get_preference('sitemap_cities', 'sitemap_plugin') ) {
    if(osc_count_list_cities() > 0) {
        while ( osc_has_list_cities() ) {
            sitemap_add_url(osc_list_city_url(), date('Y-m-d'), 'weekly');
         }
    }
    }
    
    // ITEMS
    $mSearch = new Search() ;
    $mSearch->limit(0,$numurl) ; // fetch number of item for sitemap
    $aItems = $mSearch->doSearch(); 
	View::newInstance()->_exportVariableToView('items', $aItems); //exporting our searched item array

    if(osc_count_items() > 0) {
        while(osc_has_items()) {
            
                    sitemap_add_url(osc_item_url(), substr(osc_item_mod_date()!=''?osc_item_mod_date():osc_item_pub_date(), 0, 10), 'daily');
            
        }
    }   

    
    

    $end_xml = '</urlset>';
    file_put_contents($filename, $end_xml, FILE_APPEND); //create sitemap.xml 
    @unlink(osc_base_path() . 'sitemap.xml.gz');      //remove old sitemap.xml.gz
    file_put_contents(osc_base_path() .'sitemap.xml.gz', gzencode( file_get_contents(osc_base_path() . 'sitemap.xml'),9)); //create sitemap.xml gzip version
    // PING SEARCH ENGINES
    ping_engines();
    
}


function sitemap_admin() {
        osc_admin_render_plugin('sitemap_plugin/admin.php') ;
    }


    osc_admin_menu_plugins('Sitemap Plugin', osc_admin_render_plugin_url('sitemap_plugin/admin.php'), 'sitemap_plugin_submenu');
    // This is needed in order to be able to activate the plugin
    osc_register_plugin(osc_plugin_path(__FILE__), 'sitemap_plugin_call_after_install');
    // This is a hack to show a Uninstall link at plugins table (you could also use some other hook to show a custom option panel)
    osc_add_hook(osc_plugin_path(__FILE__)."_uninstall", 'sitemap_plugin_call_after_uninstall');
    osc_add_hook(osc_plugin_path(__FILE__)."_configure", 'sitemap_admin');
       
    
?>
