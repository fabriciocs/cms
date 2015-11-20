<?php

use Model\DefaultModel;

namespace Model;

/**
 * @Entity @Table(name="sessoes")
 * */
class Sessao extends DefaultModel {

	/** @Id @Column(type="integer") @GeneratedValue * */
	protected $id;

	/** @Column(type="string") * */
	protected $titulo;

	/** @Column(type="string", nullable=true) * */
	protected $link;

	/** @Column(type="text") * */
	protected $conteudo;

	public function getId() {
		return $this->id;
	}

	public function getTitulo() {
		return $this->titulo;
	}

	public function getLink() {
		return $this->link;
	}

	public function getConteudo() {
		return $this->conteudo;
	}

	public function setTitulo($titulo) {
		$this->titulo = $titulo;
		return $this;
	}

	public function setLink($link) {
		$this->link = $link;
		return $this;
	}

	public function setConteudo($conteudo) {
		$this->conteudo = $conteudo;
		return $this;
	}
}
