<?php
/**
 *
 * Resource Loader for PHPixie (v0.1)
 * ----------------------------
 * Developed by ThePyramidSong, also known as aszkid
 * ----------------------------
 * Features:
 * - CSS files
 * - JS files
 * - JQuery
 * - Google WebFonts
 * To come:
 * - JQ/JS Plugins
 *
*/
class ResLoader
{
	private $_css;
	private	$_js;
	private	$_jq;
	private $_gfonts;
	private $_plugins;

	function __construct()
	{
		$this->_css = array();
		$this->_js = array();
		$this->_jq = NULL;
		$this->_gfonts = array();
		$this->_plugins = array();

		$file = file_get_contents(Misc::find_file("../web/js/plugins", "config", "json"));
		$json = json_decode($file);
		
		foreach ($json->plugins as $key => $value)
		{
			$this->_plugins[$value->name] = $value;
		}
	}

	public function addJSPlugin($name)
	{
		if(isset($this->_plugins[$name]))
		{
			$plugin = &$this->_plugins[$name];

			array_push($this->_js, array(
				"route" => "/web/js/plugins/$plugin->name/$plugin->name.js",
				"async"	=> "async"
			));

			if($plugin->css)
			{
				array_push($this->_css, array(
					"route" => "/web/js/plugins/$plugin->name/$plugin->name.css"
				));
			}
		}
	}
	public function addGFont($name, $options = "r")
	{
		array_push($this->_css, array(
			"route" => "http://fonts.googleapis.com/css?family=$name:$options"
		));
	}
	public function addCSS($name)
	{
		array_push($this->_css, array(
			"route" => Config::get('resloader.css_folder') . $name . ".css"
		));
	}
	public function addJS($name, $async = true)
	{
		array_push($this->_js, array(
			"route" => Config::get('resloader.js_folder') . $name . ".js",
			"async"	=> ($async) ? "async" : ''
		));
	}
	public function useJQ()
	{
		$this->_jq = Config::get('resloader.jq_folder') . "jquery_" . Config::get('resloader.jq_version') . ".js";
	}

	public function printCSS()
	{
		$final = null;
		foreach ($this->_css as $key => $value)
		{
			$final .= "<link rel=\"stylesheet\" href=\"$value[route]\">";
		}
		return $final;
	}
	public function printJS()
	{
		$final = null;
		if($this->_jq)
			$final .= "<script type=\"text/javascript\" src=\"$this->_jq\"></script>";
		foreach ($this->_js as $key => $value)
		{
			$final .= "<script $value[async] type=\"text/javascript\" src=\"$value[route]\"></script>";
		}
		return $final;
	}
}

?>