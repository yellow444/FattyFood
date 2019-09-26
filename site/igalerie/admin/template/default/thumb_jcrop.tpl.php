<?php if (method_exists($tpl, 'getConf')) : ?>
<script type="text/javascript">
var jcrop = <?php echo $tpl->getCropValues(); ?>;
</script>
<?php endif; ?>
