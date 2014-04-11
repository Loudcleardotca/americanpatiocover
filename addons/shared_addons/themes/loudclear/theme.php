<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Theme_Loudclear extends Theme {

    public $name			= 'Loud+Clear - Admin Theme';
    public $author			= 'Loud+Clear Dev Team';
    public $author_website	= 'http://loudclear.ca/';
    public $website			= 'http://loudclear.ca/';
    public $description		= 'Loud+Clear admin theme. HTML5 and CSS3 styling.';
    public $version			= '1.0.0';
	public $type			= 'admin';
	public $options 		= array('pyrocms_recent_comments' => array('title' 		=> 'Recent Comments',
																'description'   => 'Would you like to display recent comments on the dashboard?',
																'default'       => 'yes',
																'type'          => 'radio',
																'options'       => 'yes=Yes|no=No',
																'is_required'   => true),
																
									'pyrocms_news_feed' => 			array('title' => 'News Feed',
																'description'   => 'Would you like to display the news feed on the dashboard?',
																'default'       => 'yes',
																'type'          => 'radio',
																'options'       => 'yes=Yes|no=No',
																'is_required'   => true),
																
									'pyrocms_quick_links' => 		array('title' => 'Quick Links',
																'description'   => 'Would you like to display quick links on the dashboard?',
																'default'       => 'yes',
																'type'          => 'radio',
																'options'       => 'yes=Yes|no=No',
																'is_required'   => true),
																
									'pyrocms_analytics_graph' => 	array('title' => 'Analytics Graph',
																'description'   => 'Would you like to display the graph on the dashboard?',
																'default'       => 'yes',
																'type'          => 'radio',
																'options'       => 'yes=Yes|no=No',
																'is_required'   => true),
																
								   );
	
	/**
	 * Run() is triggered when the theme is loaded for use
	 *
	 * This should contain the main logic for the theme.
	 *
	 * @access	public
	 * @return	void
	 */
	public function run()
	{
		// only load these items on the dashboard
		if ($this->module == '' && $this->method != 'login' && $this->method != 'help')
		{
			// don't bother fetching the data if it's turned off in the theme
			if ($this->theme_options->pyrocms_analytics_graph == 'yes')		self::get_analytics();
			if ($this->theme_options->pyrocms_news_feed == 'yes')			self::get_rss_feed();
			if ($this->theme_options->pyrocms_recent_comments == 'yes')		self::get_recent_comments();
		}
		//custom login and forget password logic
		elseif ( $this->method == 'login' )
		{
			$method = isset($_GET["p"]) ? $_GET["p"] : 'login';
			
			switch ($method) { 
				case 'forgot_password' : 
					self::forgot_password();
					break; 
				case 'reset_pass' : 
					$code = isset($_GET["code"]) ? $_GET["code"] : FALSE;
					self::reset_pass($code);
					break; 
					
				default :
					break;
			}
			
		}
		
		
		
	}
	
	public function get_analytics()
	{
		if ($this->settings->ga_email and $this->settings->ga_password and $this->settings->ga_profile)
		{
			// Not false? Return it
			if ($cached_response = $this->pyrocache->get('analytics'))
			{
				$data['analytic_visits'] = $cached_response['analytic_visits'];
				$data['analytic_views'] = $cached_response['analytic_views'];
			}

			else
			{
				try
				{
					$this->load->library('analytics', array(
						'username' => $this->settings->ga_email,
						'password' => $this->settings->ga_password
					));

					// Set by GA Profile ID if provided, else try and use the current domain
					$this->analytics->setProfileById('ga:'.$this->settings->ga_profile);

					$end_date = date('Y-m-d');
					$start_date = date('Y-m-d', strtotime('-1 month'));

					$this->analytics->setDateRange($start_date, $end_date);

					$visits = $this->analytics->getVisitors();
					$views = $this->analytics->getPageviews();

					/* build tables */
					if (count($visits))
					{
						foreach ($visits as $date => $visit)
						{
							$year = substr($date, 0, 4);
							$month = substr($date, 4, 2);
							$day = substr($date, 6, 2);

							$utc = mktime(date('h') + 1, null, null, $month, $day, $year) * 1000;

							$flot_datas_visits[] = '[' . $utc . ',' . $visit . ']';
							$flot_datas_views[] = '[' . $utc . ',' . $views[$date] . ']';
						}

						$flot_data_visits = '[' . implode(',', $flot_datas_visits) . ']';
						$flot_data_views = '[' . implode(',', $flot_datas_views) . ']';
					}

					$data['analytic_visits'] = $flot_data_visits;
					$data['analytic_views'] = $flot_data_views;

					// Call the model or library with the method provided and the same arguments
					$this->pyrocache->write(array('analytic_visits' => $flot_data_visits, 'analytic_views' => $flot_data_views), 'analytics', 60 * 60 * 6); // 6 hours
				}

				catch (Exception $e)
				{
					$data['messages']['notice'] = sprintf(lang('cp:google_analytics_no_connect'), anchor('admin/settings', lang('cp:nav_settings')));
				}
			}

			// make it available in the theme
			$this->template->set($data);
		}
	}
	
	public function get_rss_feed()
	{
		// Dashboard RSS feed (using SimplePie)
		$this->load->library('simplepie');
		$this->simplepie->set_cache_location($this->config->item('simplepie_cache_dir'));
		$this->simplepie->set_feed_url($this->settings->dashboard_rss);
		$this->simplepie->init();
		$this->simplepie->handle_content_type();

		// Store the feed items
		$data['rss_items'] = $this->simplepie->get_items(0, $this->settings->dashboard_rss_count);
		
		// you know
		$this->template->set($data);
	}
	
	public function get_recent_comments()
	{
		$this->load->library('comments/comments');
		$this->load->model('comments/comment_m');

		$this->load->model('users/user_m');

		$this->lang->load('comments/comments');

		$recent_comments = $this->comment_m->get_recent(5);
		$data['recent_comments'] = $this->comments->process($recent_comments);
		
		$this->template->set($data);
	}
	
	public function forgot_password()
	{
		// If the validation worked, or the user is already logged in
		if ($this->ion_auth->logged_in())
		{
			redirect('admin');
		}
		
		$this->form_validation->set_rules('email', 'Email Address', 'required|valid_email');
		if ($this->form_validation->run() == false)
		{
			//setup the input
			$data['email'] = array(
				'name' => 'email',
				'id' => 'email',
			);
			
			//set any errors and display the form
			if(validation_errors() != NULL) 
			{
				$this->session->set_flashdata('message', validation_errors());
				redirect("admin/login?p=forgot_password");
			}
			
		} else {
			//run the forgotten password method to email an activation code to the user
			$forgotten = $this->ion_auth->forgotten_password($this->input->post('email'), 'forgotten_password_admin');
			
			if ($forgotten)
			{ //if there were no errors
				//$this->session->set_flashdata('success', $this->ion_auth->messages());
				//redirect("admin/login", 'refresh'); //we should display a confirmation page here instead of the login page
				redirect('admin/login?p=forgot_pass_complete');
			}
			else
			{
				$this->session->set_flashdata('message', $this->ion_auth->errors());
				redirect("admin/login?p=reset_pass");
			}
		}
		
	}
	
	
	/**
	 * Reset a user's password
	 *
	 * @param bool $code
	 */
	public function reset_pass($code)
	{
				
		if (PYRO_DEMO)
		{
			show_error(lang('global:demo_restrictions'));
		}

		//if user is logged in they don't need to be here. and should use profile options
		if ($this->current_user)
		{
			$this->session->set_flashdata('error', lang('user_already_logged_in'));
			redirect('/admin');
		}
		
		//
		// code is supplied in url so lets try to reset the password
		//
		
		if ($code)
		{
			// verify reset_code against code stored in db
			$reset = $this->ion_auth->forgotten_password_complete($code);

			// did the password reset?
			if ($reset)
			{
				redirect('admin/login?p=reset_pass_complete');
			}
			else
			{
				// nope, set error message
				$this->session->set_flashdata('message', $this->ion_auth->errors());
				redirect('admin/login?p=reset_pass');
			}
		}
		
		//
		//if code is not set in url we will validate the form
		//
		
		// Set the validation rules
		$this->form_validation->set_rules('email', lang('email_label'), 'valid_email');
		
		//if form submitted
		if ($this->form_validation->run())
		{
			//if email and user_name are not set
			if ($this->input->post('email') == NULL && $this->input->post('user_name') == NULL)
			{
				//set error message
				$this->session->set_flashdata('message', '<p>Email or Username is required</p>');
				redirect('admin/login?p=reset_pass');
			} 
			else
			{
				$uname = $this->input->post('user_name');
				$email = $this->input->post('email');
	
				if ( ! ($user_meta = $this->ion_auth->get_user_by_email($email)))
				{
					$user_meta = $this->ion_auth->get_user_by_username($uname);
				}
				
				// have we found a user?
				if ($user_meta)
				{
					$new_password = $this->ion_auth->forgotten_password($user_meta->email);
	
					if ($new_password)
					{
						//redirect to success page
						redirect('admin/login?p=forgot_pass_complete');
					}
					else
					{
						// Set an error message explaining the reset failed
						$this->session->set_flashdata('message', $this->ion_auth->errors());
					}
				}
				else
				{
					//wrong username / email combination
					$this->session->set_flashdata('message', '<p>'.lang('user_forgot_incorrect').'<p>');
				}
				redirect('admin/login?p=reset_pass');
			} 
		}
		else 
		{
			//form did not validate
			$this->session->set_flashdata('message', validation_errors());
			unset($_POST['user_name'],$_POST['email']);
			//redirect('admin/login?p=reset_pass');
		}
	}
}

/* End of file theme.php */