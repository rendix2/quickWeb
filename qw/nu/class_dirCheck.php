<?php

namespace nu;

class dirCheck
{
    public function __destruct(){ // yes, destruct!
        $this->createCacheDirs();
    }

	private function createCacheDirs()
	{
		if ( !file_exists('./cache/') )
		mkdir('./cache/');


		if ( !file_exists('./cache/users') )
		mkdir('./cache/users');

		if ( !file_exists('./cache/pages') )
		mkdir('./cache/pages');

		if ( !file_exists('./cache/articles') )
		mkdir('./cache/articles');

		if ( !file_exists('./cache/template_c') )
		mkdir('./cache/template_c');

		if ( !file_exists('./cache/template') )
		mkdir('./cache/template');


		if ( !file_exists('./cache/template_c/admin') )
		mkdir('./cache/template_c/admin');

		if ( !file_exists('./cache/template_c/web/') )
		mkdir('./cache/template_c/web');

		if ( !file_exists('./cache/template/admin') )
		mkdir('./cache/template/admin');

		if ( !file_exists('./cache/template/web/') )
		mkdir('./cache/template/web');


		if ( !file_exists('./cache/template_c/web/'.$this->templateDir.'/') )
		mkdir('./cache/template_c/web/'.$this->templateDir.'/');

		if ( !file_exists('./cache/template/web/'.$this->templateDir.'/') )
		mkdir('./cache/template/web/'.$this->templateDir.'/');
	}
}


