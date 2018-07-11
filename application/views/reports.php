<div class="panel panel-primary">
	<div class="panel-heading text-uppercase"><strong>Reports</strong></div>
	<div class="panel-body">
		<div class="table-responsive">
			<table class="table table-hover table-bordered">
				<thead>
					<tr>
						<th>Topic</th>
						<th class="text-center">User</th>
						<th class="text-center">Date Taken</th>
						<th class="text-center">Score</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach($reports as $report): ?>
						<tr>
							<td><?php echo $report->title ?></td>
							<td><?php echo $report->name ?></td>
							<td class="text-center"><?php echo $report->dateTaken ?></td>
							<td class="text-center"><?php echo $report->score ?></td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	</div>
	<?php echo $links; ?>
</div>