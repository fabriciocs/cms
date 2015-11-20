<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Empresa
 *
 * @author fabricio
 */
class Dao {

	protected $entityManager;
	protected $empresa;
	protected $menus;
	protected $slides;
	protected $parceiros;
	protected $albuns;
	protected $posts;
	protected $produtos;
	protected $produtosDestaque;
	protected $sessoes;
	protected $tagsEmpresa;

	public function __construct($entityManager) {
		$this->entityManager = $entityManager;
	}

	public function getEmpresa() {
		$this->empresa = $this->entityManager
				->createQueryBuilder()
				->select('e')
				->from('Model\Empresa', 'e')
				->where('e.id = 1')
				->getQuery()
				->getOneOrNullResult();
		return $this->empresa;
	}

	public function getMenus() {
		$this->menus = $this->entityManager
				->createQueryBuilder()
				->select('e.nomeMenu as texto, e.titulo as url')
				->from('Model\Pagina', 'e')
				->where('e.publicar = ?1 and e.postagem = ?2')
				->orderby('e.ordem')
				->setParameter(1, true)
				->setParameter(2, false)
				->getQuery()
				->getResult();
		return $this->menus;
	}

	public function getSlides() {
		$this->slides = $this->entityManager
						->createQueryBuilder()
						->select('e')
						->from('Model\Slide', 'e')
						->orderby('e.ordem')
						->getQuery()->getResult();
		if (!$this->slides) {
			$this->slides = [];
			$carrousel = new Model\Carrousel();
			$this->entityManager->persist($carrousel);
			$this->entityManager->flush();
			$imagens = $this->entityManager->createQueryBuilder()
					->select('e.url')
					->from('Model\Imagem', 'e')
					->where('e.album = ?1')
					->setParameter(1, 2)
					->getQuery()
					->getResult();
			$linkImagem = $imagens[array_rand($imagens)]['url'];

			$slide = (new Model\Slide())
					->setCarrousel($carrousel)
					->setOrdem(0)
					->setRedirect('quemsomos')
					->setTexto($this->empresa->getSlogan())
					->setLinkImagem($linkImagem)
					->setTitulo($this->empresa->getNome());
			$this->entityManager->persist($slide);
			$this->entityManager->flush();
			$this->slides[] = $slide;
		}

		return $this->slides;
	}

	public function getTemplate() {
		if (!$this->empresa) {
			$this->getEmpresa();
		}
		return new Template($this->empresa);
	}

	public function getParceiros() {
		$this->parceiros = $this->entityManager
				->createQueryBuilder()
				->select('e.nome, e.slogan, e.url, i.thumbnail as capa')
				->from('Model\Parceiro', 'e')
				->innerJoin('e.album', 'a', 'a.id = e.album_id')
				->leftJoin('a.imagens', 'i', 'a.id = i.album_id')
				->where('i.capa = true')
				->getQuery()
				->getResult();
		return $this->parceiros;
	}

	public function getAlbuns() {
		$this->albuns = $this->entityManager->createQueryBuilder()
				->select('e')
				->from('Model\Album', 'e')
				->where('e.publicar = ?1')
				->setParameter(1, true)
				->getQuery()
				->getResult();
		return $this->albuns;
	}

	public function getPosts() {
		$this->posts = $this->entityManager
				->createQueryBuilder()
				->select('e.nomeMenu as nomeMenu, e.titulo, e.dataHora as date')
				->from('Model\Pagina', 'e')
				->where('e.publicar = ?1 and e.postagem = ?2')
				->orderby('e.dataHora', 'DESC')
				->setParameter(1, true)
				->setParameter(2, true)
				->getQuery()
				->getResult();
		return $this->posts;
	}

	public function getProdutos() {
		$this->produtos = $this->entityManager
				->createQueryBuilder()
				->select('p')
				->from('Model\Produto', 'p')
				->orderby('p.destaque', 'DESC')
				->getQuery()
				->getResult();
		return $this->produtos;
	}

	public function getProdutosDestaque() {
		$this->produtosDestaque = $this->entityManager
				->createQueryBuilder()->select('e.nome, e.conteudo as conteudo, i.url as album, e.tags, e.resumo')
				->from('Model\Produto', 'e')->innerJoin('e.album', 'a', 'a.id = e.album_id')
				->leftJoin('a.imagens', 'i', 'a.id = i.album_id')
				->where('(i.capa = true or i.id is null) and e.destaque = true')
				->getQuery()
				->getResult();
		return $this->produtosDestaque;
	}

	public function getSessoes() {
		$qb = $this->entityManager->createQueryBuilder();
		$listSessoes = $qb->select('e')
				->from('Model\Sessao', 'e')
				->getQuery()
				->getResult();
		$this->sessoes = [];
		foreach ($listSessoes as $sessao) {
			$this->sessoes[$sessao->getCodigo()] = $sessao->getConteudo();
		}
		return $this->sessoes;
	}

	public function getTagsEmpresa() {
		$this->tagsEmpresa = [];
		if ($this->empresa != NULL) {
			$tags = explode(',', $this->empresa->getValores());
			foreach ($tags as $key => $value) {
				$this->tagsEmpresa[] = trim($value);
			}
			$this->empresa->setValores(array_unique($this->tagsEmpresa));
		}
		return $this->tagsEmpresa;
	}

}
