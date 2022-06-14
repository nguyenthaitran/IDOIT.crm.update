<div class="public-ticket mtop40">
	<?php hooks()->do_action('public_ticket_start', $ticket); ?>
	<?php if(is_staff_logged_in()) { ?>
		<div class="alert alert-warning mbot25">
			Bạn đang đăng nhập là nhân viên, nếu muốn trả lời yêu cầu hỗ trợ với tư cách nhân viên, bạn phải thực hiện trả lời qua khu vực quản trị.
		</div>
	<?php } ?>
	<h3 class="mbot25">
		#<?php echo $ticket->ticketid; ?> - <?php echo $ticket->subject; ?>
	</h3>
	<?php echo $single_ticket_view; ?>
	<?php hooks()->do_action('public_ticket_end', $ticket); ?>
</div>
