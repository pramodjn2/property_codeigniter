<?php $this->load->view('common/header');
$this->load->view('common/top_header');?>

<div class="main-container">
  <section class="wrapper">
    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <div class="blog-posts">
            <section class="page-top">
              <div class="container">
                <div class="col-md-4 col-sm-4">
                  <h1><?php echo $h1; ?></h1>
                </div>
              </div>
            </section>
            <?php foreach($query as $row):
		
                if(config_item('URL_ENCODE')){
					 $blog_id = safe_b64encode($row['blog_id']);	
				}else{
					 $blog_id = $row['blog_id'];	
				}
				
				if(config_item('URL_ENCODE')){
					 $user_id = safe_b64encode($row['user_id']);	
				}else{
					 $user_id = $row['user_id'];	
				}
				
				$blog_count=$row["blog_comment_count"]?$row["blog_comment_count"]:'0';
				
				$blog_image = $row['blog_image'];
				
				$class = 8;
				if(empty($blog_image)){
				$class = 12;
				}
				
	?>
            <article>
              <div class="row">
			  
			  <?php if(!empty($blog_image)){ ?>
			  <div class="col-md-4">
					<div class="post-image">
					<p>&nbsp;</p>
					<img src="<?=base_url('applicationMediaFiles/blogImage/'.$blog_image);?>"  />
					<p>&nbsp;</p>
					</div>
				</div>						
			  <?php } ?>
								
                <div class="col-md-<?=$class;?>">
                  <div class="post-content">
                    <h2> <a href="<?php echo base_url('blog/detail/'.$blog_id); ?>"> <?php echo ucwords($row['blog_title']); ?> </a></h2>
                    <p><?php echo $row['blog_description']; ?></p>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <div class="post-meta"> <span>
                  <i class="fa fa-calendar"></i> 
				  <?php echo date('M d, Y',strtotime($row['createdDate'])); ?>
                   </span> <span><i class="fa fa-user"></i> By <a href="<?php echo config_item('base_url');?>agent/details/<?php echo $user_id;?>"><?php echo ucwords($row['firstName'].' '.$row['lastName']);?></a> </span> <span><i class="fa fa-comments"></i> <?php echo anchor('blog/detail/'.$blog_id,' '.$blog_count.' Comments'); ?> </span> <?php echo anchor('blog/detail/'.$blog_id,' Read more...'); ?> </div>
                </div>
              </div>
            </article>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>
<?php $this->load->view('common/footer_content');?>
<?php $this->load->view('common/footer'); ?>
<?php $this->load->view('common/footer_end');?>
