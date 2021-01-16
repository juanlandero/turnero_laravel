<?php

namespace App\Library\Returns;

use Session;

class ActionReturn {

	private $successPath        = '';
	private $failPath           = '';
	private $success_title      = '';
	private $success_message    = '';
	private $error_title        = '';
	private $error_message      = '';
	private $action             = false;

	function __construct($failPath = '', $successPath = '') {
		$this->failPath     = $failPath;
	    $this->successPath  = $successPath;
	}

	public function setResult($action, $title, $message) {
		if($action) {
			$this->action           = true;
			$this->success_title    = $title;
			$this->success_message  = $message;
		} else {
			$this->action           = false;
			$this->error_title      = $title;
			$this->error_message    = $message;
		}
	}

	public function getResult() {
		return $this->action;
	}

	public function getPath() {
		$path = $this->failPath;
		if($this->action) {
			$path = $this->successPath;
			Session::flash("success_title", $this->success_title);
          	Session::flash("success_message", $this->success_message);
          	Session::flash("title", $this->success_title);
          	Session::flash("message", $this->success_message);
		} else {
			Session::flash("error_title", $this->error_title);
			Session::flash("error_message", $this->error_message);
			Session::flash("title", $this->error_title);
          	Session::flash("message", $this->error_message);
		}	

		return $path;
	}

	public function getRedirectPath() {
		return Redirect($this->getpath());
	}

}