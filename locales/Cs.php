<?php

/**
* Locale_Cs
*
* @author Daniel Milde <daniel@milde.cz>
* @package Nors
*/
class Locale_Cs extends Core_Locale
{
	public $data = array(

		/* ============ administrace ============ */

		//              Odpovedi systemu
		'DB_connection_failed'      => 'Připojení k databázi se nezdařilo',
		'DB_query_failed'           => 'Provedení SQL dotazu se nezdařilo',
		'DB_not_exists'             => 'Databáze neexistuje',
		'wrong_password'            => 'Zadané heslo není správné',
		'user_not_exists'           => 'Zadaný uživatel neexistuje',
		'we_are_very_sorry'         => 'Omlouváme se. Vámi požadovaná stránka není k dispozici.',
		'out_of_order'              => 'Omlouváme se. Aplikace je z důvodu údržby pozastavena.',
		'not_enough_rights'         => 'Pro tuto akci nemáte oprávnění.',

		//              Prihlaseni
		'username'                  => 'Uživatelské jméno',
		'password'                  => 'Heslo',
		'login'                     => 'Přihlášení',
		'log_in'                    => 'Přihlásit',
		'log_out'                   => 'Odhlásit se',

		'title'                     => 'titulek',
		'saved'                     => 'uloženo',

		//              Panel uzivatele
		'logged_in'                 => 'Přihlášen',
		'backup_db'                 => 'Zálohovat databázi',
		'show_web'                  => 'Zobrazit web',

		//              Zapati
		'gen_time'                  => 'Vygenerováno za',
		'seconds'                   => 'sekund',
		'yes'                       => 'ano',
		'no'                        => 'ne',
		'memory'                    => 'paměť',
		'included_files'            => 'vložených souborů',
		'sql_queries'               => 'dotazů na databázi',
		'stop_ie'                   => 'Používáte nevyhovující prohlížeč. Doporučujeme <a href="http://www.opera.com/download/">Operu</a> nebo <a href="http://www.mozilla-europe.org/cs/products/firefox/">Firefox</a>',
		'We use cookies. You agree by using this website.' => 'Využíváme soubory cookie. Používáním webu vyjadřujete souhlas.',

		//             Navigace administrace
		'homepage'                  => 'Úvod',
		'news'                      => 'Novinky',
		'content'                   => 'Obsah',
		'posts'                     => 'Články',
		'pages'                     => 'Stránky',
		'categories'                => 'Rubriky',
		'galleries'                 => 'Galerie',
		'anquettes'                 => 'Ankety',
		'citates'                   => 'Citáty',
		'comments'                  => 'Komentáře',
		'users'                     => 'Uživatelé',
		'groups'                    => 'Role',
		'settings'                  => 'Nastavení',

		//                Vypisy
		'filter'                    => 'Filtrovat',
		'add'                       => 'Přidat',
		'show'                      => 'Ukázat',
		'tree'                      => 'Strom',
		'dump'                      => 'Export',
		'action'                    => 'Akce',
		'open'                      => 'Otevřít',
		'edit'                      => 'Změnit',
		'activate'                  => 'Zapnout',
		'deactivate'                => 'Vypnout',
		'delete'                    => 'Smazat',
		'really_delete'             => 'Opravdu smazat',
		'id'                        => 'ID',
		'author'                    => 'Autor',
		'num_of_comments'           => 'Počet komentářů',
		'num_of_visits'             => 'Počet shlédnutí',
		'karma'                     => 'Karma',
		'actions'                   => 'Akce',
		'next'                      => 'další',
		'previous'                  => 'předchozí',
		'image'                     => 'obrázek',
		'update_label'              => 'Aktualizovat popisek',


		//                Formulare
		'name'                      => 'Název',
		'send_form'                 => 'Odeslat',
		'post'                      => 'Článek',
		'page'                      => 'Stránka',
		'pub_date'                  => 'Publikováno dne',
		'category'                  => 'Rubrika',
		'perex'                     => 'Perex',
		'date'                      => 'Datum',
		'active'                    => 'Aktivní',
		'name_of_web'               => 'Název webu',
		'sucessfully_saved'         => 'Ukládání dat proběhlo v pořádku...',
		'no_items'                  => 'Žádné položky',
		'save'                      => 'Uložit',
		'save_and_continue'         => 'Uložit a pokračovat',
		'save_file'                 => 'Uložit soubor',
		'photo'                     => 'Obrázek',
		'label'                     => 'Popisek',
		'logging'                   => 'Přihlášení',
		'password'                  => 'Heslo',
		'password_again'            => 'Heslo znovu',
		'text'                      => 'Text',
		'user'                      => 'Uživatel',
		'group'                     => 'Skupina',
		'fullname'                  => 'Celé jméno',
		'file'                      => 'soubor',
		'phone'                     => 'Telefon',
		'email'                     => 'E-mail',
		'created'                   => 'Vytvořen',
		'group'                     => 'Role',
		'exp_date'                  => 'Konec platnosti',
		'basic_settings'            => 'Základní nastavení',
		'description'               => 'Popis',
		'keywords'                  => 'Klíčová slova',
		'link'                      => 'Odkaz',
		'position'                  => 'Pozice',
		'basic'                     => 'Základní',
		'advanced'                  => 'Pokročilé',
		'comment'                   => 'Komentář',
		'id_category'               => 'Rubrika',
		'id_post'                   => 'Článek',
		'id_page'                   => 'Stránka',
		'id_comment'                => 'Komentář',
		'id_user'                   => 'Uživatel',
		'id_group'                  => 'Role',
		'ip'                        => 'IP adresa',
		'comments_allowed'          => 'Povolit komentáře',
		'comments_closed'           => 'Komentáře již nelze přidávat',

		//             Prava
		'category_list'             => 'Výpis rubrik',
		'category_edit'             => 'Editace rubrik',
		'category_del'              => 'Mazání rubrik',
		'post_list'                 => 'Výpis článků',
		'post_edit'                 => 'Editace článků',
		'post_del'                  => 'Mazání článků',
		'page_list'                 => 'Výpis stránek',
		'page_edit'                 => 'Editace stránek',
		'page_del'                  => 'Mazání stránek',
		'comment_list'              => 'Výpis komentářů',
		'comment_edit'              => 'Editace komentářů',
		'comment_del'               => 'Mazání komentářů',
		'user_list'                 => 'Výpis uživatelů',
		'user_edit'                 => 'Editace uživatelů',
		'user_del'                  => 'Mazání uživatelů',
		'group_list'                => 'Výpis rolí',
		'group_edit'                => 'Editace rolí',
		'group_del'                 => 'Mazání rolí',
		'basic_list'                => 'Základní nastavení',
		'advanced_list'             => 'Pokročilé nastavení',

		//         Nastaveni
		'enabled'                   => 'Povoleno',
		'encoding'                  => 'Kódování',
		'locale'                    => 'Jazyk',
		'style'                     => 'Styl',
		'upload_dir'                => 'Adresář pro upload',
		'timezone'                  => 'Časové pásmo',
		'db'                        => 'DB',
		'connector'                 => 'Konektor',
		'version'                   => 'Verze',
		'debug'                     => 'Ladění',
		'error_reporting'           => 'Chybová hlášení',
		'display_errors'            => 'Zobrazovat chyby',
		'time_management'           => 'Počítat čas',
		'die_on_error'              => 'Ukončit při chybě',
		'log'                       => 'Log',
		'cookie'                    => 'Cookie',
		'expiration'                => 'Platnost',
		'routes'                    => 'Routy',
		'format'                    => 'Formát',
		'defaults'                  => 'Výchozí hodnoty',
		'controller'                => 'Controller',
		'p'                         => 'Stránka',
		'default'                   => 'Výchozí',
		'front_end'                 => 'Uživatelská část',
		'perex_length'              => 'Délka perexu',
		'posts_per_page'            => 'Článků na stránku',
		'items_per_page'            => 'Položek na stránku',
		'default_event'             => 'Výchozí akce',


		/* ============ front-end ============ */

		//instalation
		'installation'              => 'Instalace',
		'database'                  => 'Databáze',
		'new_user'                  => 'Nový uživatel',
		'host'                      => 'Server',
		'table_prefix'              => 'Prefix tabulek',
		'adress_of_database_server' => 'Adresa databázového serveru',
		'name_of_database_user'     => 'Jméno uživatele databáze',
		'password_of_database_user' => 'Heslo uživatele databáze',
		'name_of_database'          => 'Název databáze',
		'prefix_of_nors_tables'     => 'Předpona tabulek NORSu (nutné měnit jen pokud provozujete více instancí NORSu v jedné databázi)',
		'name_of_new_nors_user'     => 'Jméno nového uživatele NORSu',
		'password_of_new_nors_user' => 'Heslo nového uživatele NORSu',
		'wrong_db_user'             => 'Špatný název uživatele nebo heslo',
		'wrong_db_name'             => 'Špatný název databáze',
		'environment check'         => 'Kontrola prostředí',
		'directory'                 => 'Složka',
		'needs to be writable'      => 'musí být zapisovatelný',
		'Pleae repair errors and refresh the page' => 'Prosím opravte chyby a načtěte stránku znovu',
		'evaluation'                => 'Hodnocení',
		'Database_is_higher_version_than_the_application' => 'Databáze je ve vyšší verzi než aplikace',

		//import
		'File db.php from "library" directory in NORS 3' => 'Soubor db.php ze složky "library" v NORS 3',
		'import'                    => 'Import',
		'from'                      => 'z',

		'jump_to_navigation'        => 'přeskočit na navigaci',
		'replied_by'                => 'Na tento komentář odpověděl',
		'reply'                     => 'Odpovědět',
		'other'                     => 'Ostatní',
		'seen'                      => 'zobrazen',
		'source'                    => 'zdroj',
		'administration'            => 'Administrace',

		);
	public function decodeDate($ymd_his)
	{
		$text_obj = new Core_Text($ymd_his);
		return date("d.m.Y",$text_obj->dateToTimeStamp());
	}

	public function decodeDatetime($ymd_his)
	{
		$text = new Core_Text($ymd_his);
		return date("d.m.Y v H:i:s", $text->dateToTimeStamp());
	}

	public function encodeDatetime($dmy_his)
	{
		if (!$dmy_his) return "0000-00-00 00:00:00";
		return $dmy_his;

		list($date, $time) = explode(' ', $dmy_his);
		list($d,$m,$y) = explode('.', $date);
		list($h,$i,$s) = explode(':', $time);
		return "$y-$m-$d $h:$i:$s";
	}

	public function encodeDate($dmy)
	{
		if (!$dmy) return "0000-00-00";

		list($d,$m,$y) = explode('.', $dmy);
		return "$y-$m-$d";
	}
}
?>
