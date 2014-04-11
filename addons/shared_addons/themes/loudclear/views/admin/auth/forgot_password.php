
        
        
        
 
    
    
    	<?php echo form_open("admin/login?p=forgot_password");?>
				<div class="form_inputs">
					<ul>
                    
                    <li class="animated fadeInDown" id="login-save">
							<label for="remember-check" id="login-remember">
								
								FORGET PASSWORD
							</label>
						</li>
                    
						<li>
							<div class="input animated fadeInDown" id="login-un"><input type="text" name="email" placeholder="<?php echo lang('global:email'); ?>"/></div>
						</li>

					
						<li class="animated fadeInDown" id="login-save">
							<label for="remember-check" id="login-remember">
								
								Please enter your email address<br />After you arrive at the login screen please check your email.
							</label>
						</li>
					</ul>
					<div class="animated fadeIn" id="login-action">
						<div class="buttons padding-top" id="login-buttons">
							  <br />
                <button class="btn green pull-right" type="submit">Retrieve Password</button>
                <a class="btn gray cancel" href="<?= site_url('admin/login'); ?>">Cancel</a>
						</div>
                 
					</div>
					<!-- </div> -->
				<?php echo form_close(); ?>
			</div>