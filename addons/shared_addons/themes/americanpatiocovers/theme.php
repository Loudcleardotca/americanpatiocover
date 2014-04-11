<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Theme_Americanpatiocovers extends Theme
{
    public $name			= 'American Patio Covers';
    public $author			= 'Loud+Clear';
    public $author_website	= 'http://loudclear.ca/';
    public $website			= 'http://loudclear.ca/';
    public $description		= 'American Patio Covers theme';
    public $version			= '1.0.0';
	public $options         =  array(
        	'show_breadcrumbs' 	=> array(
			'title'         => 'Do you want to show breadcrumbs?',
			'description'   => 'If selected it shows a string of breadcrumbs at the top of the page.',
			'default'       => 'yes',
			'type'          => 'radio',
			'options'       => 'yes=Yes|no=No',
			'is_required'   => true
		),
	);
 
}

/* End of file theme.php */