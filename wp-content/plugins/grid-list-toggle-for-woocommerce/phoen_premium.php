<?php

$plugin_dir_url = plugin_dir_url( __FILE__ );

?>
<style>
					
						.upgrade{background:#f4f4f9;padding: 50px 0; width:100%; clear: both;}
						.upgrade .upgrade-box{ background-color: #808a97;
							color: #fff;
							margin: 0 auto;
						   min-height: 110px;
							position: relative;
							width: 60%;}

						.upgrade .upgrade-box p{ font-size: 15px;
							 padding: 19px 20px;
							text-align: center;}

						.upgrade .upgrade-box a{background: none repeat scroll 0 0 #6cab3d;
							border-color: #ff643f;
							color: #fff;
							display: inline-block;
							font-size: 17px;
							left: 50%;
							margin-left: -150px;
							outline: medium none;
							padding: 11px 6px;
							position: absolute;
							text-align: center;
							text-decoration: none;
							top: 36%;
							width: 277px;}

						.upgrade .upgrade-box a:hover{background: none repeat scroll 0 0 #72b93c;}
                       
					   /**premium box**/    
						.premium-box{ width:100%; height:auto; background:#fff; float:left; }
						.premium-features{}
						.premium-heading{color:#484747;font-size: 40px; padding-top:35px;text-align:center;text-transform:uppercase;}
						.premium-features li{ width:100%; float:left;  padding: 80px 0; margin: 0; }
						.premium-features li .detail{ width:50%; }
						.premium-features li .img-box{ width:50%;box-sizing:border-box; }
						

						.premium-features li:nth-child(odd) { background:#f4f4f9; }
						.premium-features li:nth-child(odd) .detail{float:right; text-align:left; }
						.premium-features li:nth-child(odd) .detail .inner-detail{}
						.premium-features li:nth-child(odd) .detail p{ }
						.premium-features li:nth-child(odd) .img-box{ float:left; text-align:right; padding-right:30px;}

						.premium-features li:nth-child(even){  }
						.premium-features li:nth-child(even) .detail{ float:left; text-align:right;}
						.premium-features li:nth-child(even) .detail .inner-detail{ margin-right: 46px;}
						.premium-features li:nth-child(even) .detail p{ float:right;} 
						.premium-features li:nth-child(even) .img-box{ float:right;}

						.premium-features .detail{}
						.premium-features .detail h2{ color: #484747;  font-size: 24px; font-weight: 700; padding: 0; line-height:1.1;}
						.premium-features .detail p{  color: #484747;  font-size: 13px;  max-width: 327px;}
					 
					 /**images**/
					 
					 .pd_prm_option1 { background:url("<?php echo plugin_dir_url( __FILE__ ); ?>../assets/images/prm_option1.png") no-repeat; width:100%; max-width:496px; height:98px; display:inline-block; background-size:100% auto;}
					  
					 .prm_option2{background:url("<?php echo plugin_dir_url( __FILE__ ); ?>../assets/images/prm_option2.png") no-repeat; width:100%;max-width:409px; height:82px; display:inline-block;  background-size:100% auto; }
					
                     .prm_option3{background:url("<?php echo plugin_dir_url( __FILE__ ); ?>../assets/images/prm_option3.png") no-repeat; width:100%;max-width:452px;   height:118px; display:inline-block;background-size:100% auto;}

					 .prm_option4{background:url("<?php echo plugin_dir_url( __FILE__ ); ?>../assets/images/prm_option4.png") no-repeat; width:100%;max-width:264px;  height:73px; display:inline-block;  background-size:100% auto;}					
					 .prm_option5{background:url("<?php echo plugin_dir_url( __FILE__ ); ?>../assets/images/prm_option5.png") no-repeat; width:100%;max-width:658px; height:265px; display:inline-block; background-size:100% auto;}	
					 .prm_option6{background:url("<?php echo plugin_dir_url( __FILE__ ); ?>../assets/images/prm_option6.png") no-repeat; width:100%; max-width:514px; height: 138px; display:inline-block; background-size:100% auto;}  					
					 .prm_option7{background:url("<?php echo plugin_dir_url( __FILE__ ); ?>../assets/images/prm_option7.png") no-repeat; width:100%; max-width:670px; height: 864px; display:inline-block; background-size:100% auto;}  					
					 .prm_option8{background:url("<?php echo plugin_dir_url( __FILE__ ); ?>../assets/images/prm_option8.png") no-repeat; width:100%; max-width:743px; height: 955px; display:inline-block; background-size:100% auto;}  					
					 .prm_option9{background:url("<?php echo plugin_dir_url( __FILE__ ); ?>../assets/images/prm_option9.png") no-repeat; width:100%; max-width:689px; height: 346px; display:inline-block; background-size:100% auto;}  					
					 .prm_option10{background:url("<?php echo plugin_dir_url( __FILE__ ); ?>../assets/images/prm_option10.png") no-repeat; width:100%; max-width:600px; height: 279px; display:inline-block; background-size:100% auto;}  					
				     .prm_option11{background:url("<?php echo plugin_dir_url( __FILE__ ); ?>../assets/images/prm_option11.png") no-repeat; width:100%; max-width:395px; height: 462px; display:inline-block; background-size:100% auto;}
					 .prm_option12{background:url("<?php echo plugin_dir_url( __FILE__ ); ?>../assets/images/prm_option12.png") no-repeat; width:100%; max-width:405px; height: 450px; display:inline-block; background-size:100% auto;}
					 .prm_option13{background:url("<?php echo plugin_dir_url( __FILE__ ); ?>../assets/images/prm_option13.png") no-repeat; width:100%; max-width:323px; height: 70px; display:inline-block; background-size:100% auto;}					 
					 .prm_option14{background:url("<?php echo plugin_dir_url( __FILE__ ); ?>../assets/images/prm_option14.png") no-repeat; width:100%; max-width:421px; height: 68px; display:inline-block; background-size:100% auto;}
					 .prm_option14_front{background:url("<?php echo plugin_dir_url( __FILE__ ); ?>../assets/images/prm_option14-front.png") no-repeat; width:100%; max-width:270px; height: 133px; display:inline-block; background-size:100% auto;}
					 .prm_option15{background:url("<?php echo plugin_dir_url( __FILE__ ); ?>../assets/images/prm_option15.png") no-repeat; width:100%; max-width:647px; height: 231px; display:inline-block; background-size:100% auto;}					 
					 .prm_option15_front{background:url("<?php echo plugin_dir_url( __FILE__ ); ?>../assets/images/prm_option15-front.png") no-repeat; width:100%; max-width:790px; height: 654px; display:inline-block; background-size:100% auto;}
                     
   
.premium-box .premium-box-head {
    background: #eae8e7 none repeat scroll 0 0;
    height: 500px;
    text-align: center;
    width: 100%;
}
.premium-box .premium-box-head .pho-upgrade-btn {
    display: block;
    text-align: center;
}

.premium-box .premium-box-head .pho-upgrade-btn a {
    display: inline-block;
    margin-top: 75px;
}


.main-heading {
    background: #fff none repeat scroll 0 0;
    margin-bottom: -70px;
    text-align: center;
}
.main-heading img {
    margin-top: -200px;
}

.premium-box-container {
    margin: 0 auto;
}
.premium-box-container .description:nth-child(2n+1) {
    background: #fff none repeat scroll 0 0;
}
.premium-box-container .description {
    display: block;
    padding: 35px 0;
    text-align: center;
}

.premium-box-container .pho-desc-head::after {
    background: rgba(0, 0, 0, 0) url("imges/head-arrow.png") no-repeat scroll 0 0;
    content: "";
    height: 98px;
    position: absolute;
    right: -30px;
    top: -6px;
    width: 69px;
}
.premium-box-container .pho-desc-head {
    margin: 0 auto;
    position: relative;
    width: 768px;
}
.premium-box-container .description {
    text-align: center;
}

.pho-plugin-content p {
    color: #212121;
    font-size: 18px;
    line-height: 32px;
}

.premium-box-container .pho-desc-head h2 {
    color: #02c277;
    font-size: 28px;
    font-weight: bolder;
    margin: 0;
    text-transform: capitalize;
    line-height: 30px;
}

.premium-box-container .description:nth-child(2n+1) .pho-img-bg {
     background: #f1f1f1 url("<?php echo $plugin_dir_url; ?>images/image-frame-odd.png") no-repeat scroll 100% top;
}
.description .pho-plugin-content .pho-img-bg {
    border-radius: 5px 5px 0 0;
    height: auto;
    margin: 0 auto;
    padding: 70px 0 40px;
    width: 750px;
}

.premium-box-container .description:nth-child(2n) .pho-img-bg {
    background: #f1f1f1 url("<?php echo $plugin_dir_url; ?>images/image-frame-even.png") no-repeat scroll 100% top;
}

.premium-box-container .description:nth-child(2n) {
    background: #eae8e7 none repeat scroll 0 0;
}
 
 
.premium-box-container .pho-desc-head::after {
    background: rgba(0, 0, 0, 0) url("<?php echo $plugin_dir_url; ?>images/head-arrow.png") no-repeat scroll 0 0;
    content: "";
    height: 98px;
    position: absolute;
    right: -30px;
    top: 0;
    width: 69px;
} 

.pho-plugin-content {
    margin: 0 auto;
    overflow: hidden;
    width: 768px;
} 

.pho-upgrade-btn {
    display: block;
    text-align: center;
}


.pho-upgrade-btn a {
    display: inline-block;
    margin-top: 75px;
}
           
img {
	height: auto;
	max-width: 100%;
}

a:focus {
	box-shadow: none;
}
		   
				</style>
		<div class="premium-box">	
			<div class="premium-box-head">
					<div class="pho-upgrade-btn">
						<a href="https://www.phoeniixx.com/product/grid-list-toggle-woocommerce/" target="_blank"><img src="<?php echo $plugin_dir_url; ?>images/premium-btn.png" /></a>
						<a href="http://gridlist.phoeniixxdemo.com/shop/" target="_blank"><img src="<?php echo $plugin_dir_url; ?>images/button2.png" /></a>						
					</div>
			</div>
									
			<ul class="premium-features">
					<div class="main-heading"><h1><img src="<?php echo $plugin_dir_url; ?>images/premium-head.png" /></h1></div>
										
				<div class="premium-box-container">
	
						<div class="description">
							<div class="pho-desc-head"><h2> <?php _e('Global settings', 'woocommerce-grid-list-toggle'); ?></h2></div>
							<div class="pho-plugin-content">
								<p>
									 <?php _e(' With this option you could customize the tab alignment , active color , BG color , hover bg color , padding , active border color. ', 'woocommerce-grid-list-toggle'); ?>
										</p>
								<div class="pho-img-bg">
									<img src="<?php echo $plugin_dir_url; ?>images/Global-settings.jpeg" />
								</div>
							</div>
						</div><!-- description end -->
						
						<div class="description">
							<div class="pho-desc-head"><h2><?php _e('Tab styling settings ', 'woocommerce-grid-list-toggle'); ?></h2></div>
							<div class="pho-plugin-content">
								<p>
									   <?php _e(' With this feature you could set the tab icon , size , color. Can enable / disable tab icon.', 'woocommerce-grid-list-toggle'); ?>
										</p>
								<div class="pho-img-bg">
									<img src="<?php echo $plugin_dir_url; ?>images/tab-styling-settings.jpeg" />
								</div>
							</div>
						</div><!-- description end -->
						
						<div class="description">
							<div class="pho-desc-head"><h2> <?php _e('Style 1', 'woocommerce-grid-list-toggle'); ?></h2></div>
							<div class="pho-plugin-content">
								<p>
										 <?php _e('Frontend view of the style 1. ', 'woocommerce-grid-list-toggle'); ?>
										</p>
								<div class="pho-img-bg">
									<img src="<?php echo $plugin_dir_url; ?>images/style-1.jpeg" />
								</div>
							</div>
						</div><!-- description end -->
						
						<div class="description">
							<div class="pho-desc-head"><h2><?php _e('Style 2 ', 'woocommerce-grid-list-toggle'); ?></h2></div>
							<div class="pho-plugin-content">
								<p>
										<?php _e(' Frontend view of the style 2. ', 'woocommerce-grid-list-toggle'); ?>
										</p>
								<div class="pho-img-bg">
									<img src="<?php echo $plugin_dir_url; ?>images/Style-2.jpeg" />
								</div>
							</div>
						</div><!-- description end -->
						
						<div class="description">
							<div class="pho-desc-head"><h2> <?php _e('Style 3', 'woocommerce-grid-list-toggle'); ?></h2></div>
							<div class="pho-plugin-content">
								<p>
										 <?php _e(' Frontend view of the style 3.', 'woocommerce-grid-list-toggle'); ?>
										</p>
								<div class="pho-img-bg">
									<img src="<?php echo $plugin_dir_url; ?>images/style-3.jpeg" />
								</div>
							</div>
						</div><!-- description end -->
						
						<div class="description">
							<div class="pho-desc-head"><h2><?php _e('Style 4 ', 'woocommerce-grid-list-toggle'); ?></h2></div>
							<div class="pho-plugin-content">
								<p>
										  <?php _e('Frontend view of the style 4.', 'woocommerce-grid-list-toggle'); ?>
										</p>
								<div class="pho-img-bg">
									<img src="<?php echo $plugin_dir_url; ?>images/style-4.jpeg" />
								</div>
							</div>
						</div><!-- description end -->
						
						<div class="description">
							<div class="pho-desc-head"><h2> <?php _e('Style 5', 'woocommerce-grid-list-toggle'); ?></h2></div>
							<div class="pho-plugin-content">
								<p>
										  <?php _e('Frontend view of the style 5.', 'woocommerce-grid-list-toggle'); ?>
										</p>
								<div class="pho-img-bg">
									<img src="<?php echo $plugin_dir_url; ?>images/style-5.jpeg" />
								</div>
							</div>
						</div><!-- description end -->
						
						<div class="description">
							<div class="pho-desc-head"><h2> <?php _e('Style 6', 'woocommerce-grid-list-toggle'); ?></h2></div>
							<div class="pho-plugin-content">
								<p>
										<?php _e(' Frontend view of the style 6. ', 'woocommerce-grid-list-toggle'); ?>
										</p>
								<div class="pho-img-bg">
									<img src="<?php echo $plugin_dir_url; ?>images/style-6.jpeg" />
								</div>
							</div>
						</div><!-- description end -->
						
						<div class="description">
							<div class="pho-desc-head"><h2> <?php _e('Style 7', 'woocommerce-grid-list-toggle'); ?></h2></div>
							<div class="pho-plugin-content">
								<p>
										<?php _e(' Frontend view of the style 7. ', 'woocommerce-grid-list-toggle'); ?>
										</p>
								<div class="pho-img-bg">
									<img src="<?php echo $plugin_dir_url; ?>images/style-7.jpeg" />
								</div>
							</div>
						</div><!-- description end -->
						
						<div class="description">
							<div class="pho-desc-head"><h2> <?php _e('Style 8', 'woocommerce-grid-list-toggle'); ?></h2></div>
							<div class="pho-plugin-content">
								<p>
										  <?php _e('Frontend view of the style 8.', 'woocommerce-grid-list-toggle'); ?>
										</p>
								<div class="pho-img-bg">
									<img src="<?php echo $plugin_dir_url; ?>images/style-8.jpeg" />
								</div>
							</div>
						</div><!-- description end -->
						
						<div class="description">
							<div class="pho-desc-head"><h2> <?php _e('Masonry style 1', 'woocommerce-grid-list-toggle'); ?></h2></div>
							<div class="pho-plugin-content">
								<p>
									   <?php _e('Frontend view of the masonry style 1.', 'woocommerce-grid-list-toggle'); ?>
										</p>
								<div class="pho-img-bg">
									<img src="<?php echo $plugin_dir_url; ?>images/masonry-style 1.jpeg" />
								</div>
							</div>
						</div><!-- description end -->
						
						<div class="description">
							<div class="pho-desc-head"><h2> <?php _e('Masonry style 2', 'woocommerce-grid-list-toggle'); ?></h2></div>
							<div class="pho-plugin-content">
								<p>
										   <?php _e('Frontend view of the masonry style 2.', 'woocommerce-grid-list-toggle'); ?>
										</p>
								<div class="pho-img-bg">
									<img src="<?php echo $plugin_dir_url; ?>images/masonry-style 2.jpeg" />
								</div>
							</div>
						</div><!-- description end -->
						
						<div class="description">
							<div class="pho-desc-head"><h2><?php _e('Masonry style 3 ', 'woocommerce-grid-list-toggle'); ?></h2></div>
							<div class="pho-plugin-content">
								<p>
										 <?php _e(' Frontend view of the masonry style 3.', 'woocommerce-grid-list-toggle'); ?>
										</p>
								<div class="pho-img-bg">
									<img src="<?php echo $plugin_dir_url; ?>images/masonry-style 3.jpeg" />
								</div>
							</div>
						</div><!-- description end -->
						
						
						
				   
					<div class="pho-upgrade-btn">
						<a href="https://www.phoeniixx.com/product/grid-list-toggle-woocommerce/" target="_blank"><img src="<?php echo $plugin_dir_url; ?>images/premium-btn.png" /></a>
						<a href="http://gridlist.phoeniixxdemo.com/shop/" target="_blank"><img src="<?php echo $plugin_dir_url; ?>images/button2.png" /></a>
					</div>

				</div><!-- premium-box-container -->
										
									 
			</ul>
	
		</div>