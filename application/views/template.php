<?php $this->load->view('layouts/header'); ?>
<div class="container">
	<div class="row">
		<?php if($this->session->userdata('userSessId') ): ?>

		
			<div class="col-md-3">
				<div class="list-group">
					<p class="menu">DASHBOARD</p>
					<?php if($this->session->userdata('userPositionSessId') == 0): // admin only ?>
						<a href="/" class="list-group-item <?php if($this->uri->segment(1) == '' || $this->uri->segment(1) == 'questions' && $this->uri->segment(1) != "questions"): ?>active<?php endif; ?>"><i class="fa fa-bar-chart"></i> Statistics</a>
						<a href="/questions/all" class="list-group-item <?php if($this->uri->segment(2) == 'all' || $this->uri->segment(2) == 'results' || $this->uri->segment(2) == 'manage' || $this->uri->segment(2) == 'edit'): ?>active<?php endif; ?>"><i class="fa fa-bookmark"></i> Questionnaires</a>
					<?php else: ?>
						<a href="/" class="list-group-item <?php if($this->uri->segment(1) == '' || $this->uri->segment(1) == 'questions' && $this->uri->segment(2) != "all"): ?>active<?php endif; ?>"><i class="fa fa-user"></i> My Profile</a>
						<a href="/questions/all" class="list-group-item <?php if($this->uri->segment(2) == 'all' || $this->uri->segment(2) == 'results' || $this->uri->segment(2) == 'manage' || $this->uri->segment(2) == 'edit'): ?>active<?php endif; ?>"><i class="fa fa-bookmark"></i> Questionnaires</a>
					<?php endif; ?>
					<a href="#" class="list-group-item"><i class="fa fa-folder"></i> Reports</a>
					<a href="#" class="list-group-item"><i class="fa fa-lock"></i> Change Password</a>
					<a href="#" class="list-group-item"><i class="fa fa-power-off"></i> Logout</a>
				</div>
			</div>
		<?php endif; ?>
		<div class="col-md-9">
	    	<div id="contents"><?php echo $contents ?></div>
	  	</div>
	</div>
</div>
<?php $this->load->view('layouts/footer'); ?>