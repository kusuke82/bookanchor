<?php
class MySmarty extends Smarty{
	function MySmarty(){
		$this->template_dir	= ROOT_DIR."/view";
		$this->compile_dir	= ROOT_DIR."/temp";
		
		$this->left_delimiter	= "{{";
		$this->right_delimiter	= "}}";
		
		$this->default_modifiers = array('escape');
		$this->Smarty();
	}
}
?>
