<?php 
$num_comments = get_comments_number(); // get_comments_number returns only a numeric value
$write_comments = "";

if ( comments_open() ) {
	if ( $num_comments == 0 ) {
		$comments = __('No Comments', 'ENTREPRENEUR');
	} elseif ( $num_comments > 1 ) {
		$comments = $num_comments . __(' Comments', 'ENTREPRENEUR');
	} else {
		$comments = __('1 Comment', 'ENTREPRENEUR');
	}
	$write_comments = '| <a href="' . get_comments_link() .'">'. $comments.'</a>';
}
$title = get_the_title();
$perma = get_permalink();
$link_the_date_open = false;
$link_the_date_close = false;

if(!$title > '' && $perma > ""){
    $link_the_date_open = '<a href="'.$perma.'">';
    $link_the_date_close = '</a>';
}

?>
<div class="post-meta"><span class="show-author"><?php echo __('Posted by', 'ENTREPRENEUR'); ?> <?php echo the_author_posts_link(); ?></span> <span class="show-date"><span class="pre-date"><?php echo __('on', 'ENTREPRENEUR'); ?></span> <time class="published" datetime="<?php echo get_the_time('c'); ?>"><?php echo $link_the_date_open;?><?php echo get_the_date(); ?><?php echo $link_the_date_close;?></time></span> <span class="is-sticky">| <?php echo __('Featured', 'ENTREPRENEUR'); ?></span> <span class="show-comments"><?php echo $write_comments; ?></span></div>
