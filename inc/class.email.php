<?php
/*
 * Date: 2011/06/27
 * Time: 4:33 PM
 */

class email {
	private $config = array(), $vars = array();

	function __construct($template, $folder = "") {
		$this->config['cache_dir'] = F3::get('TEMP');

		$ui = ($folder) ? $folder : F3::get('UI');

		$this->config['template_dir'] = $ui;
		$this->vars['folder'] = $ui;


		Haanga::Configure($this->config);
		$this->template = $template;

	}

	public function __get($name) {
		return $this->vars[$name];
	}

	public function __set($name, $value) {
		$this->vars[$name] = $value;
	}

	public function send($to, $from="", $subject) {
		$timer = new timer();
		if ($to) $this->vars['to'] = $to;
		if ($from) $this->vars['from'] = $from;
		if ($subject) $this->vars['subject'] = $subject;





		F3::set("__runTemplate", true);
		ob_start();
		// all pages get these
		$curPageFull = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
		$curPage = explode("?", $curPageFull);

		$this->vars['version'] = F3::get('version');
		$this->vars['httpdomain'] = siteURL();
		$this->vars['devmode'] = F3::get('devmode');
		$this->vars['_user'] = F3::get('user');
		$this->vars['date'] = date("d M Y H:i:s");

		if (!isset($this->vars['from']) || !$this->vars['from']) $this->vars['from'] = \F3::get("EMAIL");


		//echo $this->config['template_dir'];

		Haanga::load($this->template, $this->vars);




		$t = ob_get_contents();
		ob_end_clean();

		$headers = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

		// Additional headers
		$headers .= 'To: <' . $this->vars['to'] . '>' . "\r\n";
		$headers .= 'From: MeetPad <' . $this->vars['from'] . '>' . "\r\n";
		$mail = @mail($this->vars['to'], $this->vars['subject'], $t, $headers);
		$arg = array(
			"To"=> $this->vars['to'],
			"Subject"=> $this->vars['subject'],
			"Status"=> ($mail) ? "Success" : "failed"
		);
		$timer->stop("Email", $arg);
		// Mail it
		return $mail;





	}


}
