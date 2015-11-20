<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Template
 *
 * @author fabricio
 */
class Template {

	private $templateName;
	private $templateCollor;
	private $templateStyle;
	private $templateBoxed;

	public function __construct($empresa) {
		$this->templateName = $empresa->getNomeTema();
		$this->templateCollor = $empresa->getNomeCorTema();
		$this->templateStyle = $empresa->getTemaDark() ? 'dark' : '';
		$this->templateBoxed = !$empresa->getTemaFullWidth();
	}

	public function getTemplateName() {
		return $this->templateName;
	}

	public function getTemplateCollor() {
		return $this->templateCollor;
	}

	public function getTemplateStyle() {
		return $this->templateStyle;
	}

	public function getTemplateBoxed() {
		return $this->templateBoxed;
	}

}
