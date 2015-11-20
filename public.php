<?php

require_once 'cms/rest/bootstrap.php';
require_once 'cms/rest/Model/DefaultModel.php';
require_once 'cms/rest/Model/Imagem.php';
require_once 'cms/rest/Model/Album.php';
require_once 'cms/rest/Model/Pagina.php';
require_once 'cms/rest/Model/Produto.php';
require_once 'cms/rest/Model/Detalhe.php';
require_once 'cms/rest/Model/Sessao.php';
require_once 'cms/rest/Model/Empresa.php';
require_once 'cms/rest/Model/Carrousel.php';
require_once 'cms/rest/Model/Slide.php';
require_once 'cms/rest/Model/Parceiro.php';


require_once 'cms/rest/Controller/IController.php';
require_once 'cms/rest/Controller/Printer.php';
require_once 'cms/rest/Controller/EmpresaCtrl.php';

use Controller\Printer;
use Slim\Middleware\ContentTypes;
use Slim\Slim;

new Controller\EmpresaCtrl(null, $entityManager);
$empresa = $entityManager
		->createQueryBuilder()
		->select('e')
		->from('Model\Empresa', 'e')
		->where('e.id = 1')
		->getQuery()
		->getOneOrNullResult();


if ($empresa != NULL) {
	$templateName = $empresa->getNomeTema();
	$templateCollor = $empresa->getNomeCorTema();
	$templateStyle = $empresa->getTemaDark() ? 'dark' : '';
	$templateBoxed = !$empresa->getTemaFullWidth();
}
$loader = new Twig_Loader_Filesystem($templateName . '/templates');
$twig = new Twig_Environment($loader, array(
	'cache' => 'compilation_cache',
		));


$app = new Slim(array(
	'view' => new \Slim\Views\Twig(),
	'templates.path' => $templateName . '/templates'));
$app->add(new ContentTypes());

$printer = new Printer();

$menus = $entityManager
		->createQueryBuilder()
		->select('e.nomeMenu as texto, e.titulo as url')
		->from('Model\Pagina', 'e')
		->where('e.publicar = ?1 and e.postagem = ?2')
		->orderby('e.ordem')
		->setParameter(1, true)
		->setParameter(2, false)
		->getQuery()
		->getResult();

$slides = $entityManager
				->createQueryBuilder()
				->select('e')
				->from('Model\Slide', 'e')
				->orderby('e.ordem')
				->getQuery()->getResult();

$parceiros = $entityManager
		->createQueryBuilder()
		->select('e.nome, e.slogan, e.url, i.thumbnail as capa')
		->from('Model\Parceiro', 'e')->innerJoin('e.album', 'a', 'a.id = e.album_id')
		->leftJoin('a.imagens', 'i', 'a.id = i.album_id')
		->where('i.capa = true')
		->getQuery()
		->getResult();

$albuns = $entityManager->createQueryBuilder()
		->select('e')
		->from('Model\Album', 'e')
		->where('e.publicar = ?1')
		->setParameter(1, true)
		->getQuery()
		->getResult();

$posts = $entityManager
		->createQueryBuilder()
		->select('e.nomeMenu as nomeMenu, e.titulo, e.dataHora as date')
		->from('Model\Pagina', 'e')
		->where('e.publicar = ?1 and e.postagem = ?2')
		->orderby('e.dataHora', 'DESC')->setParameter(1, true)
		->setParameter(2, true)
		->getQuery()
		->getResult();

$produtos = $entityManager
		->createQueryBuilder()
		->select('p')
		->from('Model\Produto', 'p')
		->orderby('p.destaque', 'DESC')
		->getQuery()
		->getResult();
$produtosDestaque = $entityManager
		->createQueryBuilder()->select('e.nome, e.conteudo as conteudo, i.url as album, e.tags, e.resumo')
		->from('Model\Produto', 'e')->innerJoin('e.album', 'a', 'a.id = e.album_id')
		->leftJoin('a.imagens', 'i', 'a.id = i.album_id')
		->where('(i.capa = true or i.id is null) and e.destaque = true')
		->getQuery()
		->getResult();

$qb = $entityManager->createQueryBuilder();
$listSessoes = $qb->select('e')
		->from('Model\Sessao', 'e')
		->getQuery()
		->getResult();

$sessoes = [];
foreach ($listSessoes as $sessao) {
	$sessoes[$sessao->getCodigo()] = $sessao->getConteudo();
}


$empresa_tags = [];
if ($empresa != NULL) {
	$tags = explode(',', $empresa->getValores());
	foreach ($tags as $key => $value) {
		$empresa_tags[] = trim($value);
	}
	$empresa->setValores(array_unique($empresa_tags));
}
$baseUrl = $app->request->getScriptName();
foreach($menus as $menu){
	if(!filter_var($menu['url'], FILTER_VALIDATE_URL)){		
		$menu['url'] = $baseUrl . '/{{ menu.url }}';		
	}
}
$view = $app->view()->getEnvironment();
$view->addGlobal('base_url', $baseUrl );
$view->addGlobal('empresa', $empresa);
$view->addGlobal('menus', $menus);
$view->addGlobal('posts', $posts);
$view->addGlobal('produtos', $produtos);
$view->addGlobal('produtosDestaque', $produtosDestaque);
$view->addGlobal('sessoes', $sessoes);
$view->addGlobal('title', $empresa->getNome());
$view->addGlobal('time', time());
$view->addGlobal('slides', $slides);
$view->addGlobal('parceiros', $parceiros);
$view->addGlobal('albuns', $albuns);
$view->addGlobal('templateName', $templateName);
$view->addGlobal('templateCollor', $templateCollor);
$view->addGlobal('templateStyle', $templateStyle);
$view->addGlobal(
		'templateBoxed', $templateBoxed);

function getFullUrl($path, $url) {
	return $path . "/" . str_replace('%2F', '/', rawurlencode(utf8_decode($url)));
}

function gerarSiteMap($menus, $posts, $produtos, $qtdPaginas, $print = true) {
	#versao do encoding xml
	$dom = new DOMDocument("1.0", "UTF-8");

#retirar os espacos em branco
	$dom->preserveWhiteSpace = false;

#gerar o codigo
	$dom->formatOutput = true;

#criando o nó principal (root)
	$root = $dom->createElementNS("http://www.sitemaps.org/schemas/sitemap/0.9", "urlset");
	$root->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');
	$root->setAttributeNS('http://www.w3.org/2001/XMLSchema-instance', 'schemaLocation', 'http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd');
	$path = str_replace('/sitemap.xml', '', full_path());
	$menus[] = ['url' => ''];
	$menus[] = ['url' => 'contato'];
	$menus[] = ['url' => 'quemsomos'];
	$menus[] = ['url' => 'galeria'];
	for ($i = 1; $i <= $qtdPaginas; $i++) {
		$menus[] = ['url' => 'posts/' . $i];
	}
	foreach ($posts as $post) {
		$menus[] = ['url' => 'post/' . $post['titulo']];
	}
	foreach ($produtos as $produto) {
		$menus[] = ['url' => 'produto/' . $produto->getNome()];
	}
	foreach ($menus as $menu) {
		$url = $dom->createElement("url");
		$loc = $dom->createElement("loc", getFullUrl($path, $menu['url']));
		$frequency = $dom->createElement("changefreq", "weekly");
		$url->appendChild($loc);
		$url->appendChild($frequency);
		$root->appendChild($url);
	}


	$dom->appendChild($root);
# Para salvar o arquivo, descomente a linha
//$dom->save("contatos.xml");
#cabeçalho da página
	header("Content-Type: text/xml");
# imprime o xml na tela
	if ($print) {
		print $dom->saveXML();
	}
	return $menus;
}

$app->get('/', function() use($app) {
	$app->render('index.html');
});
$app->get('/sitemap.xml', function() use ($app, $printer, $entityManager, $menus, $posts, $produtos) {
	$qbCount = $entityManager->createQueryBuilder();
	$posts_count = $qbCount->select('count(e.id)')->from('Model\Pagina ', 'e')
			->where('e.publicar = ?1 and e.postagem = ?2')
			->setParameter(1, true)
			->setParameter(2, true)
			->getQuery()
			->getSingleScalarResult();
	gerarSiteMap($menus, $posts, $produtos, intval(($posts_count / 10) + 1));
});

$app->get('/cms/', function() use($app) {
	header("Location: index.html");
	die();
});
$app->get('/quemsomos/', function() use($app) {
	$app->render('quemsomos.html');
});
$app->get('/galeria/', function() use ($app, $albuns) {
	$app->redirect('galeria/' . $albuns[0]->getNome() . '/1');
});
$app->get('/galeria/:nome/:pagina', function($nome, $pagina) use ($app, $entityManager) {
	$maxResult = 10;
	$query = $entityManager->createQueryBuilder()
			->select('e')
			->from('Model\Imagem', 'e')->innerJoin('e.album', 'a', 'a.id = e.album_id')
			->where('a.publicar = ?1 and a.nome = ?2')
			->setParameter(1, true)
			->setParameter(2, $nome)
			->getQuery();
	$qtdImagens = $entityManager->createQueryBuilder()->select('count(e)')
			->from('Model\Imagem', 'e')->innerJoin('e.album', 'a', 'a.id = e.album_id')
			->where('a.publicar = ?1 and a.nome = ?2')
			->setParameter(1, true)
			->setParameter(2, $nome)
			->getQuery()
			->getSingleScalarResult();

	$imagens = $query
			->setFirstResult($maxResult * ($pagina - 1))
			->setMaxResults($maxResult)
			->getResult();

	$app->view()->setData([ 'imagens' => $imagens, 'album' => $imagens[0]->getAlbum(), 'qtdImagens' => $qtdImagens, 'pages' => intval(($qtdImagens / 10) + 1)]);
	$app->render('galeria.html');
});
$app->post('/email/', function() use ($app, $empresa) {
	session_start();
	$req = $app->request();
	if (strtoupper($req->post('captcha')) == $_SESSION['captcha_id']) {
		if ($req->post('nome')) {
			$to = $empresa->getEmailContato(); // Replace with your email	
			$subject = 'Contato do Site: ' . $empresa->getDominio(); // Replace with your $subject
			$headers = 'From: ' . $req->post('email') . "\r\n" . 'Reply-To: ' . $req->post('email');
			$headers = $headers . "\r\n" . 'Content-type: text/html; charset=UTF-8' . "\r\n";

			$message = '<strong>Nome: </strong>' . $req->post('nome') . "<br>" .
					'<strong>E-mail: </strong>' . $req->post('email') . "<br>" .
					'<strong>Assunto: </strong>' . $req->post('assunto') . "<br>" .
					'<strong>Mensagem: </strong>' . $req->post('mensagem') . "<br>";

			var_dump($empresa);
			mail($to, $subject, $message, $headers);
			if ($req->post('copy') == 'on') {
				mail($req->post('email'), $subject, $message, $headers);
			}
		}
	} else {
		echo 'captcha inválido';
	}
});
$app->get('/contato/', function() use ($app) {
// Make the page validate
	ini_set('session.use_trans_sid', '0');

// Create a random string, leaving out 'o' to avoid confusion with '0'
	$char = strtoupper(substr(str_shuffle('abcdefghjkmnpqrstuvwxyz'), 0, 4));

// Concatenate the random string onto the random numbers
// The font 'Anorexia' doesn't have a character for '8', so the numbers will only go up to 7
// '0' is left out to avoid confusion with 'O'
	$str = rand(1, 7) . rand(1, 7) . $char;

// Begin the session
	session_start();

// Set the session contents
	$_SESSION['captcha_id'] = $str;
	$app->render('contato.php');
});


$app->get('/:titulo', function ($titulo) use ($app, $entityManager) {
	$qb = $entityManager->createQueryBuilder();
	$pagina = $qb->select('e.titulo as titulo, e.conteudo as conteudo, e.nomeMenu as nomeMenu, e.tags')
			->from('Model\Pagina', 'e')
			->where('e.publicar = ?1 and e.postagem = ?2 and e.titulo = ?3')
			->setParameter(1, true)
			->setParameter(2, false)
			->setParameter(3, $titulo)
			->getQuery()
			->getResult();
	$app->view()->setData([ 'pagina' => $pagina[0], 'tags' => $pagina[0]['tags'], 'title' => $titulo, 'url' => full_path()]);

	$app->render('pagina.html');
});
$app->get('/post/:titulo', function ($titulo) use ($app, $entityManager) {
	$qb = $entityManager->createQueryBuilder();
	$post = $qb->select('e.titulo as titulo, e.conteudo as conteudo, e.nomeMenu as nomeMenu, IDENTITY(e.album) as album, e.dataHora as date, e.tags')
			->from('Model\Pagina', 'e')
			->where('e.publicar = ?1 and e.postagem = ?2 and e.titulo = ?3')
			->setParameter(1, true)
			->setParameter(2, true)
			->setParameter(3, $titulo)
			->getQuery()
			->getResult();


	$qbImg = $entityManager->createQueryBuilder();
	$imagens = $qbImg->select('e.url')
			->from('Model\Imagem', 'e')
			->where('e.album = ?1')
			->setParameter(1, $post[0]['album'])
			->getQuery()
			->getResult();
	$post[0]["images"] = $imagens;
	$app->view()->setData([ 'post' => $post[0], 'url' => full_path(), 'tags' => $post[0]['tags'], 'title' => $titulo]);
	$app->render('post.html');
});
$app->get('/produto/:nome', function ($nome) use ($app, $entityManager) {
	$qb = $entityManager->createQueryBuilder();
	$produto = $qb->select('e')->from('Model\Produto', 'e')
			->where('e.nome = ?1')
			->setParameter(1, $nome)
			->getQuery()
			->getOneOrNullResult();


	$qbImg = $entityManager->createQueryBuilder();
	if ($produto != NULL) {
		$imagens = $qbImg->select('e.url')->from('Model\Imagem', 'e')
				->where('e.album = ?1')
				->setParameter(1, $produto->getAlbum())
				->orderby('e.capa', 'DESC')
				->getQuery()
				->getResult();
		$tags = $produto->getTags();
	}
//	$produto[0]["images"] = $imagens;
	$app->view()->setData([ 'produto' => $produto, 'imagens' => $imagens, 'url' => full_path(), 'tags' => $tags, 'title' => $nome]);
	$app->render('produto.html');
});
$app->get('/produtos/:pagina', function ($pagina) use ($app, $entityManager) {
	$maxResult = 10;
	$qb = $entityManager->createQueryBuilder();
	$query = $qb->select('e.nome, e.conteudo as conteudo, i.thumbnail as album, e.tags, e.resumo')->from('Model\Produto', 'e')->innerJoin('e.album', 'a', 'a.id = e.album_id')->leftJoin('a.imagens', 'i', 'a.id = i.album_id')
			->where('(i.capa = true or i.id is null)')
			->getQuery();

	$produtos = $query
			->setFirstResult($maxResult * ($pagina - 1))
			->setMaxResults($maxResult)
			->getResult();

	$qbCount = $entityManager->createQueryBuilder();
	$prods_count = $qbCount->select('count(e.id)')->from('Model\Produto ', 'e')
			->getQuery()
			->getSingleScalarResult();

	$all_tags = [];
	foreach ($produtos as $prod) {
		$tags = explode(',', $prod['tags']);
		foreach ($tags as $key => $value) {
			$all_tags[] = trim($value);
		}
	}
	$app->view()->setData([ 'produtos' => $produtos, 'tags' => array_unique($all_tags), 'produtos_count' => $prods_count, 'pages' => intval(($prods_count / 10) + 1), 'title' => 'produtos - página ' . $pagina]);
	$app->render('produtos.html');
});
$app->get('/posts/:pagina', function ($pagina) use ($app, $entityManager) {
	$maxResult = 10;
	$qb = $entityManager->createQueryBuilder();
	$query = $qb->select('e.titulo as titulo, e.conteudo as conteudo, e.nomeMenu as nomeMenu, i.thumbnail as album, e.dataHora as date, e.tags, e.resumo')
			->from('Model\Pagina', 'e')->innerJoin('e.album', 'a', 'a.id = e.album_id')->leftJoin('a.imagens', 'i', 'a.id = i.album_id')
			->where('e.publicar = ?1 and e.postagem = ?2 and (i.capa = true or i.id is null)')->orderby('e.dataHora', 'DESC')
			->setParameter(1, true)
			->setParameter(2, true)
			->getQuery();

	$posts = $query
			->setFirstResult($maxResult * ($pagina - 1))
			->setMaxResults($maxResult)
			->getResult();

	$qbCount = $entityManager->createQueryBuilder();
	$posts_count = $qbCount->select('count(e.id)')->from('Model\Pagina ', 'e')
			->where('e.publicar = ?1 and e.postagem = ?2')
			->setParameter(1, true)
			->setParameter(2, true)
			->getQuery()
			->getSingleScalarResult();
	$app->view()->setData([ 'posts' => $posts, 'posts_count' => $posts_count, 'pages' => intval(($posts_count / 10) + 1), 'title' => 'posts - página ' . $pagina]);
	$app->render('posts.html');
});

$app->run();

