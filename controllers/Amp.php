<?php

/**
* Post
*
* @author Daniel Milde <daniel@milde.cz>
* @package Nors
*/
class Amp extends Core_Controller
{
    public $headerTplFile = 'header_amp.tpl.php';
    public $footerTplFile = 'footer_amp.tpl.php';
	public $css = array(
	);
	public $helpers = array('Menu');

	public $js = array();

	public $cache = 0;

	public $views = array('Default', 'Rss');

	public function beforeFooter()
	{
		$menu_helper = new Core_Helper_Menu();

		$category = new Table_Category();
		$categories = $category->getAll('name', 'asc');
		$categories = $menu_helper->prepare($categories, 'category');
		$this->setData('categories', $menu_helper->render($categories, 4), TRUE);

		$table = new Table_Page();
		$pages = $table->getActive('position', 'asc');
		$pages = $menu_helper->prepare($pages, 'page');
		$this->setData('pages', $menu_helper->render($pages, 4), TRUE);

		$this->setData('administration', $this->router->genUrl('administration', FALSE, 'default'));
	}

	public function __default()
	{
		if (intval($this->request->getGet('post')) > 0) $this->view_post();
		else $this->list_items();
	}

	public function list_items()
	{
		$this->tplFile = 'posts.tpl.php';

		$page = $this->request->getPost('p');
		if ($page === FALSE) $page = $this->request->getGet('p');

		if ($page == '') {
			$page = $this->request->getCookie('page');
			$this->response->setPost('p', $page);
		}

		$max = $this->config->front_end->posts_per_page;
		$limit = ($page * $max) . ',' . $max;

		//setcookie
		$this->response->setCookie('page', $page);

		if ($this->request->getVar('view') == 'rss') {
			$limit = 20;
		}

		$table = new Table_Post();
		$posts = $table->getActive('date', 'desc', $limit);
		$count = $table->getCount();

		$text_obj = new Core_Text();

		if (iterable($posts)) {
			foreach ($posts as &$post) {
				$url = $text_obj->urlEncode($post->name);
				$curl = $text_obj->urlEncode($post->category_name);
				$text = $text_obj->getPerex(Core_Config::singleton()->front_end->perex_length, $post->text);
				$text = strip_tags($text);
				$text = $text_obj->clearAmpersand($text);

				$post->url            = $this->router->genUrl('post', FALSE, 'post', array('post' => $post->id_post . '-' . $url));
				$post->text           = $text;
				$post->category_url   = $this->router->genUrl('category', FALSE, 'category', array('category' => $post->id_category . '-' . $curl));
				$post->name           = clearOutput($post->name);
				$post->category_name  = clearOutput($post->category_name);
				$post->timestamp      = $text_obj->dateToTimeStamp($post->date);
				$post->date           = Core_Locale::factory()->decodeDatetime($post->date);
			}
		}

		$this->setData('items', $posts, TRUE);

		$helper = new Core_Helper_AjaxPaging('post');
		$paging = $helper->paging($count, $max, TRUE);
		$this->setData('paging', $paging, TRUE);
	}

	/**
	*
	* @return void
	*/
	public function view_post()
	{
		$this->response->setGet('rss', FALSE);
		$id_post = intval($this->request->getGet('post'));
		$text_obj = new Core_Text();

		$table = new Table_Post();

		$posts = $table->findById($id_post);
		if (!iterable($posts)) throw new Exception('Post not found', 404);
		$post = $posts[0];

		//is URL canonical?
		$url = $this->request->getUrl();
		$url = str_replace('&','&amp;',$url);
		$url_name = $text_obj->urlEncode($post->name);
		$can_url = $this->router->genUrl('amp', FALSE, 'amp', array('post' => $post->id_post . '-' . $url_name));
		if ($this->request->view != 'Default') {
			$can_url .= '?' . strtolower($this->request->view);
		}
		if ($can_url != $url) {
			//echor($can_url . ' - ' . $url);
			$this->router->redirect($can_url, FALSE, FALSE, FALSE, FALSE, TRUE);
		}

		//increment seen
		$post->seen += 1;
		$post->save();

		list($post) = $table->findById($id_post); //reload

		$this->setData('title', $post->name);

		$url = $text_obj->urlEncode($post->name);
        $this->setData('postUrl', $this->router->genUrl('post', FALSE, 'post', array('post' => $post->id_post . '-' . $url)));

		$post->text = $text_obj->format_html($post->text);
		$post->name = clearOutput($post->name);

		if ($post) $this->setData('post', $post, TRUE);
		else $this->setData('post', '', TRUE);
		
		$this->setData('url', urlencode($this->request->getUrl()));

		$files = $post->getFiles();
		$i = 1;
		if (iterable($files)) {
			foreach ($files as &$file) {
				if (preg_match('/\[IMG '.$i.'\]/', $post->text)) {
					$img = '
<div class="in-text-thumbnail">
	<a href="' . $file->src . '" class="lightbox2" data-lightbox="lightbox" title="'. $file->label . '">
		<amp-img src="' . $file->thub . '" alt="' . $file->label . '" />
	</a>
</div>';
					$post->text = preg_replace('/\[IMG '.$i.'\]/', $img, $post->text);
					unset($files[$i-1]);
				}
				$i++;
			}
		}

        $post->text = preg_replace('/<img/', '<amp-img', $post->text);
		$this->setData('photos', $files);

		//karma
		$eval = '';
		$post_eval = base64_decode($this->request->getCookie('post_eval'));
		$post_evals = explode(';', $post_eval);
		if (in_array($post->id_post, $post_evals)) $eval = 'Karma: ' . round($post->karma, 2);
		else for ($i = 1; $i <= 10; $i++) $eval .= '<a href="#" title="' . $i . '">' . $i . '</a>';
		$this->setData('eval', $eval, TRUE);


		$table = new Table_Comment();
		$comments = $table->findById_Post($post->getID());
		$text = new Core_Text();

		$coms = array();
		if (iterable($comments)) {
			$i = 0;
			foreach ($comments as $comment) {
				++$i; //start from 1

				$coms[$i] = new StdClass();

				if ($comment->www) {
					$comment->www = htmlspecialchars($comment->www);
					$coms[$i]->href = (strpos($comment->www, 'http') === 0) ? $comment->www : 'http://' . $comment->www;
				} elseif ($comment->email) {
					$coms[$i]->href = $text->hideMail('mailto:' . htmlspecialchars($comment->email));
				} else {
					$coms[$i]->href = '';
				}

				//gravatar
				if ($comment->email) {
					$grav_url  = 'http://www.gravatar.com/avatar/';
					$grav_url .= md5(strtolower($comment->email));
					$grav_url .= '?s=50';
					$coms[$i]->gravatar = $grav_url;
				} else $coms[$i]->gravatar = '';

				$coms[$i]->user      = strip_tags($comment->user);
				$coms[$i]->text      = $text->format_comment($comment->text);
				$coms[$i]->id        = $comment->getID();
				$coms[$i]->date      = $comment->date;
				$coms[$i]->name      = $coms[$i]->user;
				$coms[$i]->url       =$this->router->genUrl('post', '__default', FALSE, FALSE, TRUE) . '#post' . $coms[$i]->id;
				$coms[$i]->timestamp = $text->dateToTimeStamp($comment->date);

				while(preg_match("/\[([[:digit:]]+)\]/",$coms[$i]->text)){
					$j = trim(preg_replace("/.*\[([[:digit:]]+)\].*/","\\1",$coms[$i]->text));
					if ($i > $j) {
						$reaction[$i][]    = $j;
						$inspiration[$j][] = $i;
					}
					$coms[$i]->text = preg_replace("/\[($j)\]/","#$j#",$coms[$i]->text);
				}
			}

			//bind reaction and inspiration
			foreach ($coms as $i => $com) {
				if (isset($reaction[$i]) && iterable($reaction[$i])) {
					foreach($reaction[$i] as $r){
						if (!isset($coms[$r])) continue;
						$span = '<span class="reaction"><a href="#post' . $coms[$r]->id . '">#' . $r . ' ' . $coms[$r]->user . '</a>:</span> ';
						$coms[$i]->text = str_replace("#$r#", $span, $coms[$i]->text);
					}
				}
				if (isset($inspiration[$i]) && iterable($inspiration[$i])) {
					foreach($inspiration[$i] as $insp){
						$coms[$i]->text .= '<div class="inspiration">' . __('replied_by') . ' <a href="#post' . $coms[$insp]->id . '">[' . $insp . '] ' . $coms[$insp]->user . '</a></div>';
					}
				}
			}
		}
		$this->setData('items', $coms, TRUE);

		$r = $this->request;

		$form = new Core_Helper_Form();
		$form->form(NULL, '#', __('add') . ' ' . __('comment'), __('ok'));
		$form->input(NULL, 'user', __('username'))->setValidation()->setParam('value', $r->getCookie('user'));
		$form->input(NULL, 'www', __('www'))->setParam('value', $r->getCookie('www'));
		$form->input(NULL, 'email', __('email'))->setParam('value', $r->getCookie('email'));
		$form->input(NULL, 'subject', __('subject'));
		$form->input(NULL, 'check', '2 * 5?');
		$form->textarea(NULL, 'text', 'text');
		$this->setData('comment_form', $form->render(1, TRUE), TRUE);
        $this->setData('name',        $this->config->name);
	}
}
