<script type="text/javascript">
    <?php if ($this->session->tempdata('success')) { ?>
    toastr.success('<?php echo $this->session->tempdata('success'); ?>', 'Success', {positionClass: 'toast-bottom-center', timeOut: '3000'});
    <?php } else if ($this->session->tempdata('error')) {  ?>
    toastr.error('<?php echo $this->session->tempdata('error'); ?>', 'Error', {positionClass: 'toast-bottom-center', timeOut: '3000'});
    <?php } else if ($this->session->tempdata('warning')) {  ?>
    toastr.warning('<?php echo $this->session->tempdata('warning'); ?>', 'Warning', {positionClass: 'toast-bottom-center', timeOut: '3000'});
    <?php } else if ($this->session->tempdata('info')) {  ?>
    toastr.info('<?php echo $this->session->tempdata('info'); ?>', 'Info', {positionClass: 'toast-bottom-center', timeOut: '3000'});
    <?php } ?>
</script>