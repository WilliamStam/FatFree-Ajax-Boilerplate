<?php
/*
 * Date: 2011/06/27
 * Time: 4:33 PM
 */

class template {
	private $config = array(), $vars = array();

	function __construct($template, $folder = "", $strictfolder = false) {
		$this->f3 = Base::instance();
		$this->config['cache_dir'] = $this->f3->get('TEMP');

		$this->vars['folder'] = $folder;
		$this->config['strictfolder'] = $strictfolder;

		$this->template = $template;

		$this->timer = new \timer();




	}
	function __destruct(){
		$page = $this->template;
		//test_array($page);
		if (isset($this->vars['page']['template'])){
			$page = $page . " -> " . $this->templatefolder . $this->vars['page']['template'];
		}
		$this->timer->stop("Template",  $page);
	}

	public function __get($name) {
		return $this->vars[$name];
	}

	public function __set($name, $value) {
		$this->vars[$name] = $value;
	}


	public function load() {

		$curPageFull = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
		$curPage = explode("?", $curPageFull);
		$_v = isset($_GET['v']) ? $_GET['v'] : $this->f3->get('v');
		$user = $this->f3->get('user');

		$cfg = $this->f3->get('cfg');
		unset($cfg['DB']);


		$this->vars['_uri'] = $_SERVER['REQUEST_URI'];
		$this->vars['_folder'] = $this->vars['folder'];
		$this->vars['_version'] = $this->f3->get('version');
		$this->vars['_v'] = $_v;
		$this->vars['_cfg'] = $cfg;
		$this->vars['_isLocal'] = isLocal();
		$this->vars['_httpdomain'] = siteURL();


		if (isset($this->vars['page'])) {
			$page = $this->vars['page'];
			$tfile = $page['template'];

			$folders = (array) $this->vars['folder'];


			$this->templatefolder = "";

			$usethisfolder = false;
			foreach ($folders as $folder){
				if (file_exists('' . $folder . '' . $tfile . '.tmpl')) {
					$page['template'] = $tfile . '.tmpl';
					$usethisfolder = true;
					$this->templatefolder = $folder;
				} else {
					$page['template'] = 'none';
				}

				if (file_exists('' . $folder . '_js/' . $tfile . '.js')) {
					$page['template_js'] = '/min/js_' . $_v . '?file=/' . $folder . '_js/' . $tfile . '.js';
				} else {
					$page['template_js'] = "";
				}
				if (file_exists('' . $folder . '_css/' . $tfile . '.css')) {
					$page['template_css'] = '/min/css_' . $_v . '?file=/' . $folder . '_css/' . $tfile . '.css';
				} else {
					$page['template_css'] = "";
				}
				if (file_exists('' . $folder . '_templates/' . $tfile . '.jtmpl')) {
					//exit('/tmpl?file=' . $tfile . '_templates.jtmpl');

					$file = '/' . $folder . '_templates/' . $tfile . '.jtmpl';

					$page['template_tmpl'] = '_templates/' . $tfile . '.jtmpl';
				} else {
					$page['template_tmpl'] = "";
				}

				if ($usethisfolder){
					break;
				}
			}


			$this->vars['page'] = $page;
			

			return $this->render_template();
		} else {
			return $this->render_string();
		}




	}

	public function render_template() {

		if (is_array($this->vars['folder'])){
			$folder = $this->vars['folder'];
		} else {
			$folder = array(
				"ui/",
				$this->vars['folder']
			);
		}

		if ($this->config['strictfolder']){
			$folder = $this->vars['folder'];
		}

		$loader = new Twig_Loader_Filesystem($folder);
		$twig = new Twig_Environment($loader, array(
			//'cache' => $this->config['cache_dir'],
		));


		//test_array($this->vars);

		return $twig->render($this->template, $this->vars);


	}

	public function render_string() {
		$loader = new Twig_Loader_String();
		$twig = new Twig_Environment($loader);
		return $twig->render($this->vars['template'], $this->vars);
	}


	public function output() {
		$this->f3->set("__runTemplate", true);
		echo $this->load();

	}

}
