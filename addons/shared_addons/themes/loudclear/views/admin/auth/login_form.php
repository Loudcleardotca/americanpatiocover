		<?php echo form_open('admin/login'); ?>
				<div class="form_inputs">
					<ul>
						<li>
							<div class="input animated fadeInDown" id="login-un"><input type="text" name="email" placeholder="<?php echo lang('global:email'); ?>"/></div>
						</li>

						<li>
							<div class="input animated fadeInDown" id="login-pw"><input type="password" name="password" placeholder="<?php echo lang('global:password'); ?>"/></div>
						</li>
						<li class="animated fadeInDown" id="login-save">
							<label for="remember-check" id="login-remember">
								<input type="checkbox" name="remember" id="remember-check"/>
								<?php echo lang('user:remember'); ?>
							</label>
						</li>
					</ul>
					<div class="animated fadeIn" id="login-action">
						<div class="buttons padding-top" id="login-buttons">
							<button id="login-submit" class="btn" ontouchstart="" type="submit" name="submit" value="<?php echo lang('login_label'); ?>">
								<span><?php echo lang('login_label'); ?></span>
							</button>
						</div>
                        
                    <a href="/admin/login?p=forgot_password" title="Forgot Password">Forgot password?</a></div>
					<!-- </div> -->
				<?php echo form_close(); ?>
			</div>