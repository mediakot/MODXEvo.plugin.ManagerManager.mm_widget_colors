<?php
/**
 * mm_widget_colors
 * @version 1.1 (2012-11-13)
 *
 * Adds a color selection widget to the specified TVs.
 *
 * @uses ManagerManager plugin 0.6.
 * 
 * @event OnDocFormPrerender
 * @event OnDocFormRender
 *
 * @link http://code.divandesign.biz/modx/mm_widget_colors/1.1
 *
 * @copyright 2012
 */

function mm_widget_colors($fields, $default = '#ffffff', $roles = '', $templates = ''){
	if (!useThisRule($roles, $templates)){return;}
	
	global $modx;
	$e = &$modx->Event;
	
	$output = '';
	
	if ($e->name == 'OnDocFormPrerender'){
		$output .= includeJsCss($modx->config['base_url'] .'assets/plugins/managermanager/widgets/colors/farbtastic.js', 'html', 'farbtastic', '1.2');
		$output .= includeJsCss($modx->config['base_url'] .'assets/plugins/managermanager/widgets/colors/farbtastic.css', 'html');
		
		$e->output($output);
	}else if ($e->name == 'OnDocFormRender'){
		global $mm_current_page, $mm_fields;
		
		// if we've been supplied with a string, convert it into an array
		$fields = makeArray($fields);
		
		// Does this page's template use any of these TVs? If not, quit.
		$tv_count = tplUseTvs($mm_current_page['template'], $fields);
		
		if ($tv_count === false){
			return;
		}
		
		// Go through each of the fields supplied
		foreach ($fields as $tv){
				$tv_id = $mm_fields[$tv]['fieldname'];
				
				$output .= '
				// ----------- Color widget for  '.$tv_id.'  --------------
				$j("#'.$tv_id.'").css("background-image","none");
				$j("#'.$tv_id.'").after(\'<div id="colorpicker'.$tv_id.'"></div>\');
				if ($j("#'.$tv_id.'").val() == ""){
					$j("#'.$tv_id.'").val("'.$default.'");
				}
				$j("#colorpicker'.$tv_id.'").farbtastic("#'.$tv_id.'");
				$j("#colorpicker'.$tv_id.'").mouseup(function(){
					// mark the document as dirty, or the value wont be saved
					$j("#'.$tv_id.'").trigger("change");
				});
				';
		}
		
		$e->output($output . "\n");
	}
}
?>