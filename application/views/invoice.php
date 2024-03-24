<style>
	@page {
		size: A4
	}
</style>

<!-- begin #content -->
<div id="content" class="content">
	<!-- begin breadcrumb -->
	<ol class="breadcrumb hidden-print pull-right">
		<li class="breadcrumb-item"><a href="<?php echo base_url(); ?>"><?php echo $this->lang->line('dashboard'); ?></a></li>
		<li class="breadcrumb-item"><a href="<?php echo base_url(); ?>invoices"><?php echo $this->lang->line('all_rents'); ?></a></li>
		<li class="breadcrumb-item active"><?php echo $this->lang->line('invoice'); ?></li>
	</ol>
	<!-- end breadcrumb -->
	<!-- begin page-header -->
	<?php $currency = $this->db->get_where('setting', array('name' => 'currency'))->row()->content; ?>
	<h1 class="page-header hidden-print">
		<?php
			$invoice_query = $this->db->get_where('invoice', array('invoice_id' => $invoice_id));
			echo $this->lang->line('invoice'); ?>#<?php echo $invoice_number = $invoice_query->row()->invoice_number; 
		?>
	</h1>
	<!-- end page-header -->
	<?php $tenant_id = $this->db->get_where('tenant_rent', array('invoice_id' => $invoice_id))->row()->tenant_id; ?>
	<!-- begin invoice -->
	<div class="invoice print-body">
		<!-- begin invoice-company -->
		<div class="invoice-company text-inverse f-w-600">
			<span class="pull-right hidden-print">
				<a href="javascript:;" onclick="window.print()" class="btn btn-sm btn-white m-b-10 p-l-5 hidden-print">
					<i class="fa fa-print t-plus-1 fa-fw fa-lg"></i> <?php echo $this->lang->line('print'); ?>
				</a>
			</span>
			<span>
				<?php if ($this->db->get_where('setting', array('name' => 'invoice_logo'))->row()->content): ?>
				<img style="width: 80px" src="<?php echo base_url('uploads/website/' . $this->db->get_where('setting', array('name' => 'invoice_logo'))->row()->content); ?>" alt="">
				<?php endif; ?>
				<?php echo html_escape($this->db->get_where('setting', array('name' => 'tagline'))->row()->content); ?>
			</span>
		</div>
		<!-- end invoice-company -->
		<!-- begin invoice-header -->
		<div class="invoice-header">
			<div class="invoice-from">
				<small><?php echo $this->lang->line('from'); ?></small>
				<address class="m-t-5 m-b-5">
					<strong class="text-inverse">
						<?php echo html_escape($this->db->get_where('setting', array('name' => 'system_name'))->row()->content); ?>
					</strong><br />
					<?php echo $this->db->get_where('setting', array('name' => 'address'))->row()->content; ?>
				</address>
			</div>
			<div class="invoice-to">
				<small><?php echo $this->lang->line('to'); ?></small>
				<address class="m-t-5 m-b-5">
					<strong class="text-inverse">
						<?php echo $invoice_query->row()->tenant_name; ?>
					</strong><br />
					<?php echo $invoice_query->row()->room_number . '<br>' . $invoice_query->row()->property_name; ?>
				</address>
			</div>
			<div class="invoice-date">
				<small><?php echo $this->lang->line('details'); ?></small>
				<div class="date text-inverse m-t-5">
					#<?php echo $invoice_number; ?>
				</div>
				<div class="invoice-detail">
					<?php echo $this->lang->line('created_on'); ?>: <b><?php echo date('d M, Y', $invoice_query->row()->created_on); ?></b><br />
					<?php echo $this->lang->line('due_date'); ?>: <b><?php echo date('d M, Y', $invoice_query->row()->due_date); ?></b>
					<br />
					<?php $late_fee = $invoice_query->row()->late_fee; ?>
				</div>
			</div>
		</div>
		<!-- end invoice-header -->
		<!-- begin invoice-content -->
		<div class="invoice-content">
			<!-- begin table-responsive -->
			<div class="table-responsive">
				<table class="table table-invoice">
					<thead>
						<tr>
							<th><?php echo strtoupper($this->lang->line('description')); ?></th>
							<?php if ($invoice_query->row()->invoice_type == 1 || $invoice_query->row()->invoice_type == 3) : ?>
							<th class="text-center" width="20%"><?php echo strtoupper($this->lang->line('starting_date')); ?></th>
							<th class="text-center" width="20%"><?php echo strtoupper($this->lang->line('ending_date')); ?></th>
							<?php else : ?>
							<th class="text-center" width="20%"><?php echo strtoupper($this->lang->line('month')); ?></th>
							<th class="text-center" width="20%"><?php echo strtoupper($this->lang->line('year')); ?></th>
							<?php endif; ?>
							<th class="text-right" width="20%"><?php echo strtoupper($this->lang->line('row_total')); ?></th>
						</tr>
					</thead>
					<tbody>
						<?php if ($invoice_query->row()->invoice_type == 1 || $invoice_query->row()->invoice_type == 3): ?>
						<tr>
							<td><span class="text-inverse"><?php echo $invoice_query->row()->invoice_type == 1 ? $this->lang->line('date_range_rent') : $this->lang->line('yearly_rent'); ?></span></td>
							<td class="text-center"><?php echo date('d M, Y', $invoice_query->row()->start_date); ?></td>
							<td class="text-center"><?php echo date('d M, Y', $invoice_query->row()->end_date); ?></td>
							<td class="text-right">
								<?php echo $currency; ?>
								<?php
									$this->db->select_sum('amount');
									$this->db->from('tenant_rent');
									$this->db->where('invoice_id', $invoice_id);
									$query = $this->db->get();

									echo number_format($query->row()->amount);
								?>
							</td>
						</tr>
						<?php
							$invoice_services_total = 0;
							$invoice_services = $this->db->get_where('invoice_service', array('invoice_id' => $invoice_id))->result_array();
							foreach ($invoice_services as $invoice_service):
						?>
						<tr>
							<td>
								<span class="text-inverse">
									<?php
										$invoice_service_query = $this->db->get_where('service', array('service_id' => $invoice_service['service_id']));
										if ($invoice_service_query->num_rows() > 0):
											echo $invoice_service_query->row()->name;
										else:
											echo 'N/A';
										endif;
									?>
								</span>
							</td>
							<td class="text-center"><?php echo $invoice_service['month'] . ', ' . $invoice_service['year']; ?></td>
							<td class="text-center"><?php echo $invoice_service['month'] . ', ' . $invoice_service['year']; ?></td>
							<td class="text-right">
								<?php echo $currency; ?>
								<?php echo number_format($invoice_service['amount']); ?>
								<?php $invoice_services_total += $invoice_service['amount']; ?>
							</td>
						</tr>
						<?php endforeach; ?>
						<!-- Starts invoice custom items -->
						<?php
							$invoice_custom_services_total = 0;
							$invoice_custom_services = $this->db->get_where('invoice_custom_service', array('invoice_id' => $invoice_id))->result_array();
							foreach ($invoice_custom_services as $invoice_custom_service):
						?>
						<tr>
							<td><span class="text-inverse"><?php echo $invoice_custom_service['name']; ?></span></td>
							<td class="text-center"><?php echo $invoice_custom_service['month'] . ', ' . $invoice_custom_service['year']; ?></td>
							<td class="text-center"><?php echo $invoice_custom_service['month'] . ', ' . $invoice_custom_service['year']; ?></td>
							<td class="text-right">
								<?php echo $currency; ?>
								<?php echo number_format($invoice_custom_service['amount']); ?>
								<?php $invoice_custom_services_total += $invoice_custom_service['amount']; ?>
							</td>
						</tr>
						<?php endforeach; ?>
						<!-- Ends invoice custom items -->
						<?php
							else :
								$months_total = $this->db->get_where('tenant_rent', array('invoice_id' => $invoice_id))->result_array();
								foreach ($months_total as $month_total) :
						?>
						<tr>
							<td><span class="text-inverse"><?php echo $this->lang->line('monthly_rent'); ?></span></td>
							<td class="text-center"><?php echo $month_total['month']; ?></td>
							<td class="text-center"><?php echo $month_total['year']; ?></td>
							<td class="text-right">
								<?php echo $currency; ?>
								<?php echo number_format($month_total['amount']); ?>
							</td>
						</tr>
						<?php endforeach; ?>
						<?php
							$invoice_services_total = 0; 
							$invoice_services = $this->db->get_where('invoice_service', array('invoice_id' => $invoice_id))->result_array();
							foreach ($invoice_services as $invoice_service):
						?>
						<tr>
							<td>
								<span class="text-inverse">
									<?php
										$invoice_service_query = $this->db->get_where('service', array('service_id' => $invoice_service['service_id']));
										if ($invoice_service_query->num_rows() > 0):
											echo $invoice_service_query->row()->name;
										else:
											echo 'N/A';
										endif;
									?>
								</span>
							</td>
							<td class="text-center"><?php echo $invoice_service['month']; ?></td>
							<td class="text-center"><?php echo $invoice_service['year']; ?></td>
							<td class="text-right">
								<?php echo $currency; ?>
								<?php echo number_format($invoice_service['amount']); ?>
								<?php $invoice_services_total += $invoice_service['amount']; ?>
							</td>
						</tr>
						<?php endforeach; ?>
						<!-- Starts invoice custom items -->
						<?php
							$invoice_custom_services_total = 0;
							$invoice_custom_services = $this->db->get_where('invoice_custom_service', array('invoice_id' => $invoice_id))->result_array();
							foreach ($invoice_custom_services as $invoice_custom_service):
						?>
						<tr>
							<td><span class="text-inverse"><?php echo $invoice_custom_service['name']; ?></span></td>
							<td class="text-center"><?php echo $invoice_custom_service['month']; ?></td>
							<td class="text-center"><?php echo $invoice_custom_service['year']; ?></td>
							<td class="text-right">
								<?php echo $currency; ?>
								<?php echo number_format($invoice_custom_service['amount']); ?>
								<?php $invoice_custom_services_total += $invoice_custom_service['amount']; ?>
							</td>
						</tr>
						<?php endforeach; ?>
						<!-- Ends invoice custom items -->
						<?php endif; ?>
						
						<?php if ($late_fee > 0) : ?>
						<tr>
							<td><span class="text-inverse"><?php echo $this->lang->line('late_fee'); ?></span></td>
							<td class="text-center">-</td>
							<td class="text-center">-</td>
							<td class="text-right">
								<?php echo $currency; ?>
								<?php echo number_format($late_fee); ?>
							</td>
						</tr>
						<?php endif; ?>
					</tbody>
				</table>
				<!-- Payment history starts -->
				<table class="table table-invoice">
					<thead>
						<tr>
							<th><?php echo strtoupper($this->lang->line('paid_on')); ?></th>
							<th class="text-center" width="20%"><?php echo strtoupper($this->lang->line('payment_method')); ?></th>
							<th class="text-center" width="20%"><?php echo strtoupper($this->lang->line('amount')); ?></th>
							<th class="text-right" width="20%"><?php echo strtoupper($this->lang->line('notes')); ?></th>
						</tr>
					</thead>
					<tbody>
						<?php $total_paid = 0; ?>
						<?php if ($this->db->get_where('tenant_paid', array('invoice_id' => $invoice_id))->num_rows() > 0): ?>
						<?php foreach ($this->db->get_where('tenant_paid', array('invoice_id' => $invoice_id))->result_array() as $tenant_paid): ?>
						<tr>
							<td><?php echo date('d M, Y', $tenant_paid['paid_on']); ?></td>
							<td class="text-center">
								<?php
									if (($tenant_paid['payment_method_id'] > 0) && $this->db->get_where('payment_method', array('payment_method_id' => $tenant_paid['payment_method_id']))->num_rows() > 0) 
										echo $this->db->get_where('payment_method', array('payment_method_id' => $tenant_paid['payment_method_id']))->row()->name;
									else
										echo 'N/A';
								?>
							</td>
							<td class="text-center">
								<span>
									<?php
										$total_paid += $tenant_paid['amount']; 
										echo $tenant_paid['amount']; 
									?>
								</span>
							</td>
							<td class="text-right"><span><?php echo $tenant_paid['notes'] ? $tenant_paid['notes'] : '-'; ?></span></td>
						</tr>
						<?php endforeach; ?>
						<?php else: ?>
						<tr>
							<td colspan="4" class="text-center">N/A</td>
						</tr>	
						<?php endif; ?>
					</tbody>
				</table>
				<!-- Payment history ends -->
			</div>
			<!-- end table-responsive -->
			<!-- begin invoice-price -->
			<div class="invoice-price">
				<div class="invoice-price-left">
					<div class="invoice-price-row">
						<div class="sub-price">
							<small><?php echo strtoupper($this->lang->line('total')); ?></small>
							<span class="text-inverse">
								<?php echo $currency; ?>
								<?php
								$this->db->select_sum('amount');
								$this->db->from('tenant_rent');
								$this->db->where('invoice_id', $invoice_id);
								$query = $this->db->get();

								echo ($late_fee > 0) ? number_format($query->row()->amount + $invoice_services_total + $invoice_custom_services_total + $late_fee) : number_format($query->row()->amount + $invoice_services_total + $invoice_custom_services_total);
								?>
							</span>
						</div>
						<div class="sub-price">
							<small><?php echo strtoupper($this->lang->line('paid')); ?></small>
							<span class="text-inverse">
								<?php echo $currency; ?>
								<?php echo number_format($total_paid); ?>
							</span>
						</div>
					</div>
				</div>
				<div class="invoice-price-right">
					<small><?php echo strtoupper($this->lang->line('due')); ?></small>
					<span class="f-w-600">
						<?php echo $currency; ?>
						<?php
						$this->db->select_sum('amount');
						$this->db->from('tenant_rent');
						$this->db->where('invoice_id', $invoice_id);
						$query = $this->db->get();

						echo ($late_fee > 0) ? number_format($query->row()->amount + $invoice_services_total + $invoice_custom_services_total + $late_fee - $total_paid) : number_format($query->row()->amount + $invoice_services_total + $invoice_custom_services_total - $total_paid);
						?>
					</span>
				</div>
			</div>
			<!-- end invoice-price -->
		</div>
		<!-- end invoice-content -->
	</div>
	<!-- end invoice -->
</div>
<!-- end #content -->

<style>
	@media print {
		.hidden-print {
			display: none;
		}

		.invoice-header {
			display: grid;
			grid-template-columns: 1fr 1fr 1fr;
		}

		.invoice-to {
			margin-top: 0 !important;
			text-align: center !important;
		}

		.invoice-date {
			margin-top: 0 !important;
			text-align: right !important;
		}

		.invoice-price {
			display: grid;
			grid-template-columns: repeat(4, 1fr);
			grid-gap: 10px;
			grid-auto-rows: 100px;
			grid-template-areas:
				"a a a a b b b b"
				"c c c c d d d d";
			align-items: end;
		}

		.invoice-price-left {
			grid-area: b;
		}

		.invoice-price-right {
			grid-area: d;
		}
	}
</style>