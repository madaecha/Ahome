<?php $query = $this->db->get_where('notice', array('notice_id' => $param2)); ?>
<?php if ($query->row()->image_link): ?>
<img width="100%" src="<?php echo base_url('uploads/notices/' . $query->row()->image_link);?>" alt="<?php echo $query->row()->title; ?>">
<br>
<br>
<?php endif; ?>
<h3><?php echo $query->row()->title; ?></h3>
<p>
<?php echo $this->lang->line('published_on'); ?>: <?php echo date('d M, Y', $query->row()->created_on); ?> 
&nbsp;&nbsp;&nbsp; 
<?php echo $this->lang->line('last_updated'); ?>: <?php echo date('d M, Y', $query->row()->timestamp); ?>
</p>
<hr>
<p><?php echo $query->row()->notice; ?></p>