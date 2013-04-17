<h2 class="render-title"><?php _e('Sitemap Plugin Settings', 'sitemap_plugin'); ?></h2>
<p>This plugin is created by <a href="http://tuffclassified.com">Navjot Tomer</a> for OSclass community.</p>             
<div id="left-side" class="well ui-rounded-corners" style="width:410px;">
<form action="<?php echo osc_admin_render_plugin_url('oc-content/plugins/sitemap_plugin/admin.php'); ?>" method="post">
    <input type="hidden" name="action_specific" value="sitemap" />
    <fieldset>
        <div class="form-horizontal">
            <div class="form-row">
                <div class="form-label"><?php _e('Sitemap URL number', 'sitemap_plugin'); ?></div>
                <div class="form-controls"><input type="text" class="xlarge" name="sitemap_number" value="<?php echo osc_esc_html( osc_get_preference('sitemap_number', 'sitemap_plugin') ); ?>"></div>
            	<div class="help-box"><?php _e('Please enter number of URLs you want to show in your xml sitemap', 'sitemap_plugin'); ?></div>
            </div>
            <br />
            <div class="form-row">
                <div class="form-label"><?php _e('Include Categories','sitemap_plugin'); ?></div>
                <div class="form-label-checkbox"><input type="checkbox" name="sitemap_categories" value="1" <?php echo (osc_get_preference('sitemap_categories', 'sitemap_plugin') ? 'checked' : ''); ?> > </div>
            	<div class="help-box"><?php _e('Please Check if you want to include Categories in your list', 'sitemap_plugin'); ?></div>
            </div>
            <br />
            <div class="form-row">
                <div class="form-label"><?php _e('Include Countries','sitemap_plugin'); ?></div>
                <div class="form-label-checkbox"><input type="checkbox" name="sitemap_countries" value="1" <?php echo (osc_get_preference('sitemap_countries', 'sitemap_plugin') ? 'checked' : ''); ?> > </div>
            	<div class="help-box"><?php _e('Please Check if you want to include Countries in your list', 'sitemap_plugin'); ?></div>
            </div>
            <br />
            <div class="form-row">
                <div class="form-label"><?php _e('Include Regions','sitemap_plugin'); ?></div>
                <div class="form-label-checkbox"><input type="checkbox" name="sitemap_regions" value="1" <?php echo (osc_get_preference('sitemap_regions', 'sitemap_plugin') ? 'checked' : ''); ?> > </div>
            	<div class="help-box"><?php _e('Please Check if you want to include Regions in your list', 'sitemap_plugin'); ?></div>
            </div>
            <br />
            <div class="form-row">
                <div class="form-label"><?php _e('Include Cities','sitemap_plugin'); ?></div>
                <div class="form-label-checkbox"><input type="checkbox" name="sitemap_cities" value="1" <?php echo (osc_get_preference('sitemap_cities', 'sitemap_plugin') ? 'checked' : ''); ?> > </div>
            	<div class="help-box"><?php _e('Please Check if you want to include Cities in your list', 'sitemap_plugin'); ?></div>
            </div>
            </div>
                 
            
                <input type="submit" value="<?php _e('Save changes', 'sitemap_plugin'); ?>" class="btn btn-submit" />
            
        </div>
    </fieldset>
</form>
</div>
<div style="margin-top:10px">
<form action="<?php echo osc_admin_render_plugin_url('oc-content/plugins/sitemap_plugin/admin.php'); ?>" method="post">
    <input type="hidden" name="action_specific" value="generate_sitemap" />
    <fieldset>
               <input type="submit" value="<?php _e('Generate Sitemap', 'sitemap_plugin'); ?>" class="btn btn-submit" />
    </fieldset>
</form></div>