<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Plugin for displaying images
 *
 * @author 		Patrick Kivits - Woodits Webbureau
 * @website		http://woodits.nl
 * @package 	PyroCMS
 * @subpackage 	Images Module
 */
class Plugin_Images extends Plugin
{
	/**
	 * Get image to display in template
	 * Usage:
	 * 
	 * {{ images:image page="{{ page:id }}" slug="slug" }}
	 *
	 * @return	Image
	 */
	function image()
	{
		$page = (int) $this->attribute('page');
		$slug = $this->attribute('slug');
		
		$image = $this->db
			->select('image.thumbnail, thumb.name, thumb.width, thumb.height')
			->from('images_image as image')
			->join('images_thumbnails as thumb', 'thumb.id = image.thumbnail_id')
			->where('image.page', $page)
			->where('thumb.slug', $slug)
			->limit(1)
			->get()
			->row();
		
		if(isset($image->thumbnail)) {
			return '<img src="'.BASE_URL.UPLOAD_PATH.'files/'.$image->thumbnail.'" alt="'.$image->name.'" width="'.$image->width.'" height="'.$image->height.'" />';
		} else {
			return '';	
		}
	}
	
	/**
	 * Get thumbnail to display in template
	 * Usage:
	 * 
	 * {{ images:thumbnail page="{{ page:id }}" slug="slug" }}
	 *
	 * @return	Thumbnail
	 */
	function thumbnail()
	{
		$page = (int) $this->attribute('page');
		$slug = $this->attribute('slug');
		
		$image = $this->db
			->select('image.thumbnail')
			->from('images_image as image')
			->join('images_thumbnails as thumb', 'thumb.id = image.thumbnail_id')
			->where('image.page', $page)
			->where('thumb.slug', $slug)
			->limit(1)
			->get()
			->row();
		
		if(isset($image->thumbnail)) {
			return $image->thumbnail;
		} else {
			return '';	
		}
	}
	
	/**
	 * Get original image to display in template
	 * Usage:
	 * 
	 * {{ images:original page="{{ page:id }}" slug="slug" }}
	 *
	 * @return	Original
	 */
	function original()
	{
		$page = (int) $this->attribute('page');
		$slug = $this->attribute('slug');
		
		$image = $this->db
			->select('image.image')
			->from('images_image as image')
			->join('images_thumbnails as thumb', 'thumb.id = image.thumbnail_id')
			->where('image.page', $page)
			->where('thumb.slug', $slug)
			->limit(1)
			->get()
			->row();
		
		if(isset($image->image)) {
			return $image->image;
		} else {
			return '';	
		}
	}
}

/* End of file plugin.php */