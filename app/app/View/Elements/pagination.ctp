<?php if ($position == 'top') { ?>
	<?php
	echo $this->Paginator->prev('<span class="ico">&#xf04a;</span>', array('escape' => false), null, array('class' => 'prev disabled'));
	echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
	echo $this->Paginator->next('<span class="ico">&#xf04e;</span>', array('escape' => false), null, array('class' => 'next disabled'));
	?>
	<br>
	<?php
	echo $this->Paginator->counter(array(
	    'format' => __('Exibindo {:start} - {:end} de {:count}'))
	);
	?>
<?php } else if ($position == 'bottom') { ?>
	<br>
	<?php
	echo $this->Paginator->counter(array(
	    'format' => __('Exibindo {:start} - {:end} de {:count}'))
	);
	?>
	<br>
	<?php
	echo $this->Paginator->prev('<span class="ico">&#xf04a;</span>', array('escape' => false), null, array('class' => 'prev disabled'));
	echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
	echo $this->Paginator->next('<span class="ico">&#xf04e;</span>', array('escape' => false), null, array('class' => 'next disabled'));
	?>
<?php } else { ?>
	<?php
	echo $this->Paginator->counter(array(
	    'format' => __('Exibindo {:start} - {:end} de {:count}'))
	);
	?>
	&nbsp;&nbsp;&nbsp;
	<?php echo $this->Paginator->prev('<< ', array(), null, array('class' => 'prev disabled')); ?>
	| 
	<?php echo $this->Paginator->next(' >>', array(), null, array('class' => 'next disabled')); ?>

<?php } ?>
