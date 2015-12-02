<?php

namespace Model;

require_once 'DefaultModel.php';

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\OneToOne;

/**
 * @Entity @Table(name="empresas")
 * */
class Empresa extends DefaultModel {

	/** @Id @Column(type="integer") @GeneratedValue * */
	protected $id;

	/** @Column(type="string") * */
	protected $nome;

	/**
	 * @OneToOne(targetEntity="Album")
	 * @JoinColumn(name="album_id", referencedColumnName="id")
	 * */
	protected $album;

	/** @Column(type="text") * */
	protected $historia;

	/** @Column(type="text") * */
	protected $resumo;

	/** @Column(type="text") * */
	protected $missao;

	/** @Column(type="text") * */
	protected $visao;

	/** @Column(type="text") * */
	protected $valores;

	/** @Column(type="text", nullable=true) * */
	protected $slogan;

	/** @Column(type="string") * */
	protected $dominio;

	/** @Column(type="string", nullable=true) * */
	protected $urlLogo;

	/** @Column(type="text", nullable=true) * */
	protected $iframeGoogleMaps;

	/** @Column(type="string") * */
	protected $endereco;

	/** @Column(type="string", nullable=true) * */
	protected $telefone;

	/** @Column(type="string") * */
	protected $emailContato;

	/** @Column(type="string", nullable=true) * */
	protected $idGoogleAnalytics;

	/** @Column(type="string") * */
	protected $nomeTema;

	/** @Column(type="boolean") * */
	protected $temaDark;

	/** @Column(type="string", nullable=true) * */
	protected $nomeCorTema;

	/** @Column(type="boolean") * */
	protected $temaFullWidth;

	/** @Column(type="string", nullable=true) * */
	protected $facebookPageUrl;

	/**
	 * @ManyToMany(targetEntity="Detalhe", cascade={"all"})
	 * @JoinTable(name="empresas_detalhes",
	 *      joinColumns={@JoinColumn(name="empresa_id", referencedColumnName="id")},
	 *      inverseJoinColumns={@JoinColumn(name="detalhe_id", referencedColumnName="id")}
	 *      )
	 * */
	protected $detalhes;

	public function __construct() {
		parent::__construct();
		$this->detalhes = new ArrayCollection();
	}

	public function addDetalhe($detalhe) {
		$this->detalhes[] = $detalhe;
	}

	public function getId() {
		return $this->id;
	}

	public function getNome() {
		return $this->nome;
	}

	public function getAlbum() {
		return $this->album;
	}

	public function getHistoria() {
		return $this->historia;
	}

	public function getResumo() {
		return $this->resumo;
	}

	public function getMissao() {
		return $this->missao;
	}

	public function getVisao() {
		return $this->visao;
	}

	public function getValores() {
		return $this->valores;
	}

	public function getDominio() {
		return $this->dominio;
	}

	public function getEndereco() {
		return $this->endereco;
	}

	public function getTelefone() {
		return $this->telefone;
	}

	public function getEmailContato() {
		return $this->emailContato;
	}

	public function getDetalhes() {
		return $this->detalhes;
	}

	public function setNome($nome) {
		$this->nome = $nome;
		return $this;
	}

	public function getIdGoogleAnalytics() {
		return $this->idGoogleAnalytics;
	}

	public function setIdGoogleAnalytics($idGoogleAnalytics) {
		$this->idGoogleAnalytics = $idGoogleAnalytics;
		return $this;
	}

	public function setAlbum($album) {
		$this->album = $album;
		return $this;
	}

	public function setHistoria($historia) {
		$this->historia = $historia;
		return $this;
	}

	public function setResumo($resumo) {
		$this->resumo = $resumo;
		return $this;
	}

	public function setMissao($missao) {
		$this->missao = $missao;
		return $this;
	}

	public function setVisao($visao) {
		$this->visao = $visao;
		return $this;
	}

	public function setValores($valores) {
		$this->valores = $valores;
		return $this;
	}

	public function setDominio($dominio) {
		$this->dominio = $dominio;
		return $this;
	}

	public function getIframeGoogleMaps() {
		return $this->iframeGoogleMaps;
	}

	public function setIframeGoogleMaps($iframeGoogleMaps) {
		$this->iframeGoogleMaps = $iframeGoogleMaps;
		return $this;
	}

	public function setEndereco($endereco) {
		$this->endereco = $endereco;
		return $this;
	}

	public function setTelefone($telefone) {
		$this->telefone = str_replace('_', '', $telefone);
		return $this;
	}

	public function setEmailContato($emailContato) {
		$this->emailContato = $emailContato;
		return $this;
	}

	public function setDetalhes($detalhes) {
		$this->detalhes = $detalhes;
		return $this;
	}

	public function getSlogan() {
		return $this->slogan;
	}

	public function setSlogan($slogan) {
		$this->slogan = $slogan;
		return $this;
	}

	public function getUrlLogo() {
		return $this->urlLogo;
	}

	public function setUrlLogo($urlLogo) {
		$this->urlLogo = $urlLogo;
		return $this;
	}

	public function getNomeTema() {
		return $this->nomeTema;
	}

	public function getTemaDark() {
		return $this->temaDark;
	}

	public function getNomeCorTema() {
		return $this->nomeCorTema;
	}

	public function getTemaFullWidth() {
		return $this->temaFullWidth;
	}

	public function setNomeTema($nomeTema) {
		$this->nomeTema = $nomeTema;
		return $this;
	}

	public function setTemaDark($temaDark) {
		$this->temaDark = $temaDark;
		return $this;
	}

	public function setNomeCorTema($nomeCorTema) {
		$this->nomeCorTema = $nomeCorTema;
		return $this;
	}

	public function setTemaFullWidth($temaFullWidth) {
		$this->temaFullWidth = $temaFullWidth;
		return $this;
	}

	public function getFacebookPageUrl() {
		return $this->facebookPageUrl;
	}

	public function setFacebookPageUrl($facebookPageUrl) {
		$this->facebookPageUrl = $facebookPageUrl;
		return $this;
	}

	public function expose() {
		$empresa = parent::expose();
		$empresa["detalhes"] = [];
		foreach ($this->detalhes as $detalhe) {
			$empresa["detalhes"][] = $detalhe->expose();
		}
		return $empresa;
	}

	public function fromArray($array) {
		unset($array['detalhes']);
		return parent::fromArray($array);
	}

}
