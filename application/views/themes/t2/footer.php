<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<footer class="footer">
	<div class="container">
		<div class="row">
			<div class="col-md-12 text-center">
				<span class="copyright-footer"><?php echo date('Y'); ?> Bản quyền thuộc về <a target="_blank" href="https://mycrm.vn">myCRM</a>.</span>
				<?php if(is_gdpr() && get_option('gdpr_show_terms_and_conditions_in_footer') == '1') { ?>
					- <a href="<?php echo terms_url(); ?>" class="terms-and-conditions-footer"><?php echo _l('terms_and_conditions'); ?></a>
				<?php } ?>
				<?php if(is_gdpr() && is_client_logged_in() && get_option('show_gdpr_link_in_footer') == '1') { ?>
					- <a href="<?php echo site_url('clients/gdpr'); ?>" class="gdpr-footer"><?php echo _l('gdpr_short'); ?></a>
				<?php } ?>
			</div>
		</div>
	</div>
</footer>