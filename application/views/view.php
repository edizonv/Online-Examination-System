
<div class="panel panel-primary<?php if($topic->status == 0) echo " disable";  ?>" id="topicInfo">
	<div class="panel-heading text-uppercase"><strong><?php echo $topic->title . ' (' . $topic->duration.')'; ?></strong>
	
</div>
	<div class="panel-body">
		<div class="row">
			
			<div class="col-md-12">
				<?php if($questions[0]->status): ?>
					<?php $columns = array(); ?>
					<?php foreach($questions as $rows): ?>
						<?php if (!isset($columns[$rows->questions]) ): ?>
							<p class="hr <?php if($rows->qstat == 0): ?> disabled <?php endif; ?>"><strong><?php echo $rows->questions; ?></strong> <?php if($rows->answer): ?><strong class="pull-right"><?php echo $rows->answer; ?></strong><?php endif; ?></p>
							<?php $columns[$rows->questions] = true; ?>
						<?php endif; ?>
						
						<p class="<?php if($rows->qstat == 0): ?> disabled <?php endif; ?>"><?php echo $rows->choices . '. ' . $rows->choicesText; ?></p>
						
					<?php endforeach; ?>
				<?php else: ?>
					<div class="alert alert-warning"><p>Please enable first to add</p></div>
				<?php endif; ?>
			</div>
		</div>
	</div>
</div>
