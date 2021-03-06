<div class="container">
	<h1 class="page-header">Liste des users</h1>
	<div class="table-responsive">
		<table id="myTab" class="table table-hover table-striped">
			<thead>
				<tr>
					<th>ID</th>
					<th>Username</th>
					<th>Email</th>
					<th>Group</th>
					<th></th>
				</tr>
			</thead>

			<tbody>
				<?php foreach ($users as $user): ?>
					<tr>
						<td><?= $user->id ?></td>
						<td><?= $user->username ?></td>
						<td><?= $user->email ?></td>
						<td>
							<?php if ($user->group_id == 6): ?>
								<span class="fa-stack fa-lg">
									<i class="fa fa-square-o fa-stack-2x"></i>
									<i class="fa fa-asterisk fa-stack-1x"></i> 
								</span>
								Admin
							<?php endif; ?>
						<td>
							<?php if ($user->group_id != 6): ?>
								<a href="/users/admin/<?= $user->id ?>" class="btn btn-info"><i class="fa fa-asterisk"></i> Nommer admin</a>
							<?php endif; ?>
							<a href="/users/delete/<?= $user->id ?>" class="btn btn-danger"><i class="fa fa-trash"></i> Supprimer</a>
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>
</div>

<script type="text/javascript">
	$(document).ready(function(){ 
		$('#myTab').DataTable();  
	});
</script> 