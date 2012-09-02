<?php get_header(); ?>

	<?php query_posts(array('post_type'=>'apps'));?>

	<table border="0" bordercolor="#FFCC00" style="background-color:#FFFFFF" width="100%" cellpadding="0" cellspacing="0">
	
	<?php $i = 0; ?>

	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
			<?php $i++ ?>
			
			<?php if($i % 2 != 0) { ?>
				<tr style="background-color: #F5F5F5;height:70px;">
			<?php } else {?>
				<tr style="background-color: #EBEBEB;height:70px;">
			<?php } ?>
				<td width="65px">
				<?php if( has_post_thumbnail() ){
				    $thumbnail = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'thumbnail' );
				} ?>
				<div style="background-image: url(<?php echo $thumbnail[0]; ?>);background-size:57px 57px;background-repeat:no-repeat;height:57px;width:57px;-webkit-border-radius: 9px;margin-left: 10px;margin-right:10px;"></div>
				</td>
				<td valign="middle"><?php the_title(); ?> - <?php the_content(); ?></td>
				<td ><a style="float:right;margin-right:10px;" href="itms-services://?action=download-manifest&url=itms-services://?action=download-manifest&url=<?php echo $img['url']?>" class="installButton">INSTALL APP</a>
			</tr>
	<?php endwhile; ?>

	<?php endif; ?>
	
	</table>

<?php get_footer(); ?>
