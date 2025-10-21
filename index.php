<?php get_header(); 

?>
<main id="content">
	
	<!-- OVERVIEW MAP --> 
	<div id="overview-map-container" class="">

		<div class="compass">
			<img src="/wp-content/themes/compete-interactive-map/assets/images/compass.png" class="img-fluid">
		</div>
		
		<div id="overview-map-svg-container">
						
			<div class="logo-wrap">
				<img src="/wp-content/themes/compete-interactive-map/assets/images/logo-ccsd-dedicated-to-excellence.png" class="logo" width="300" height="76">
			</div>
			
			<!-- INTRO TEXT -->
			<div class="intro">
				
				<div class="text">
					<h1><?php the_field('title', 'option'); ?></h1>
					<?php the_field('description', 'option'); ?>
				</div>
				
				<?php // Investments Section
				
				if ( have_rows('major_investments', 'option' ) ) {			
					
					echo '<div class="major-investments">';
						
						echo '<h2>' . get_field('investments_title', 'option') . '</h2>';
						
						echo '<div class="investments-intro">';
							
							echo get_field('investments_intro', 'option');
							
						echo '</div>'; // .investments-intro
						
						echo '<ul class="list-investments">';
							
							while ( have_rows('major_investments', 'option') ) { the_row('major_investments', 'option');
								
								$title = get_sub_field('project_title');
								$icon  = get_sub_field('project_icon');
								
								echo '<li class="modal-toggle" data-project="' . sanitize_title($title) . '">';
									echo '<span class="icon">' . wp_get_attachment_image($icon, 'thumbnail') . '</span>';
							    	echo $title;
								echo '</li>';
								
							}
							
						echo '</ul>'; // .list-investments
						
					echo '</div>'; // .major-investments
					
				}
				
				?>
				
			</div> 
			
			<!-- OVERVIEW MAP SVG  -->
			<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 2500 1500" style="enable-background:new 0 0 2500 1500; background-image:url(<?php the_field('map_image', 'option'); ?>)" xml:space="preserve" class="overview-map-svg-full" id="us_full_map" name="us_full_map">

				<!-- REGION PATHS -->
				<?php $terms = get_terms('regions', array('hide_empty' => 0, 'parent' =>0)); foreach($terms as $term) : ?>
					<g class="map-region map-region-<?php echo $term->slug; ?>" id="<?php echo $term->slug; ?>">
						<?php $map_paths = get_field('map_paths', $term); echo $map_paths; ?>
					</g>
				<?php endforeach; ?>

			</svg>
			
			<div id="copyright">
				<p>Copyright &copy;<?php echo date('Y'); ?> Cherry Creek School Districts <a class="youtube" href="https://www.youtube.com/embed/_g3cGgseGqY">Questions? View Tutorial Video</a></p>
			</div>
			
		</div>
		
	</div>
	<!-- REGION MAPS AND CONTENT -->
	<div class="zoom-div region-map hide">
		
		<div class="close-button close-regions">
			<img src="/wp-content/themes/compete-interactive-map/assets/images/return.svg" class="img-fluid"> Overview Map
		</div>
		
		<div class="compass">
			<img src="/wp-content/themes/compete-interactive-map/assets/images/compass.png" class="img-fluid">
		</div>
		
		<?php
		$_terms = get_terms( array('regions') );

		foreach ($_terms as $term) :
			
			$term_id          = $term->term_id;
			$term_slug        = $term->slug;	
			$term_name        = $term->name;
			//$video_id       = get_field('video_id', $term);
			$region_intro     = get_field('region_intro', $term);
			$region_content   = get_field('region_content', $term);
			$edit_region_link = $term_id ? '<div class="edit-link"><a href="' . get_edit_term_link($term_id) . '" target="_blank">Edit ' . $term_name . '</a></div>' : null;
			

			$_posts = new WP_Query( array(
						'post_type'         => 'schools',
						'posts_per_page'    => -1, //important for a PHP memory limit warning
						'tax_query' => array(
							array(
								'taxonomy' => 'regions',
								'field'    => 'slug',
								'terms'    => $term_slug,
							),
						),
					)); ?>
		
			<?php if( $_posts->have_posts() ) : ?>					
		
			<div class="region-container hide" id="region-container-<?php echo $term_slug; ?>">
				<div class="region-map-svg-container">
					<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 2500 1500" style="enable-background:new 0 0 2500 1500; background-image:url(<?php the_field('region_image', $term); ?>)" xml:space="preserve" class="map-svg-region hide" id="map-svg-region-<?php echo $term->slug; ?>">
						
						<?php while ( $_posts->have_posts() ) : $_posts->the_post();
							global $post;
							$post_slug = $post->post_name;
							
							$marker_override    = get_field('marker_override');
							$link_external      = $marker_override['link_external'] == true ? true : false;
							$external_link      = isset($marker_override['link']) ? $marker_override['link'] : null;
							$external_link_tgt  = !empty($external_link['target']) ? ' target="_blank" rel="noopener"' : null; 
							$external_class     = $external_link ? ' external' : null;
							$marker_coordinates = get_field('marker_coordinates');
							$title_attributes   = get_field('title_attributes');
							$title_alignment    = get_field('title_alignment') == 'left' ? ' school-title--left' : null;
				
							if( get_field('school_type') == 'elementary_school' ) {
								$marker       = '/wp-content/uploads/2019/11/gray-marker.png';
								$marker_hover = '/wp-content/uploads/2019/11/gray-marker-hover.png';
								$school_class = 'elementary-school';
							} else if( get_field('school_type') == 'middle_school' ) {
								$marker       = '/wp-content/uploads/2019/11/blue-marker.png';
								$marker_hover = '/wp-content/uploads/2019/11/blue-marker-hover.png';
								$school_class = 'middle-school';
							} else if( get_field('school_type') == 'k_8' ) {
								$marker       = '/wp-content/uploads/2020/01/blue.png';
								$marker_hover = '/wp-content/uploads/2020/01/blue-hover.png';
								$school_class = 'k-8-school';
							} else if( get_field('school_type') == 'charter_school' ) {
								$marker       = '/wp-content/uploads/2020/01/orange.png';
								$marker_hover = '/wp-content/uploads/2020/01/orange-hover.png';
								$school_class = 'charter-school';
							} else if( get_field('school_type') == 'mental_health' ) {
								$marker       = '/wp-content/uploads/2023/02/purple.png';
								$marker_hover = '/wp-content/uploads/2023/02/purple-hover.png';
								$school_class = 'mental-health';
							} else {
								$marker       = '/wp-content/uploads/2019/11/red-marker.png';
								$marker_hover = '/wp-content/uploads/2019/11/red-marker-hover.png';
								$school_class = 'high-school';
							}; ?>
							<g id="marker-<?php echo $post_slug; ?>" class="map-marker-container<?php echo $external_class; ?> <?php echo $school_class; ?> hide" name="<?php echo $post_slug; ?>">
								
								<?php if ( $link_external ) { ?>
									<a href="<?php echo $external_link['url']; ?>"<?php echo $external_link_tgt; ?> aria-label="<?php echo $external_link['title']; ?>">
								<?php } ?>
										
										<image style="overflow:visible;enable-background:new;" width="136" height="205" xlink:href=" <?php echo $marker_hover; ?>"  transform="<?php echo $marker_coordinates; ?>" class="map-marker-hover"></image>
						
										<image style="overflow:visible;enable-background:new;" width="136" height="205" xlink:href=" <?php echo $marker; ?>"  transform="<?php echo $marker_coordinates; ?>" class="map-marker"></image>
						
										<?php echo '<foreignObject ' . $title_attributes . '>' ?>
											<div class="school-title<?php echo $title_alignment; ?>" xmlns="http://www.w3.org/1999/xhtml"><?php the_title(); ?></div>
										</foreignObject>
										
								<?php if ( $link_external ) { ?>	
									</a>
								<?php } ?>
							</g>
			
						<?php endwhile; ?>
			
								
					</svg>
				
				</div> <!-- .region-map-svg-container -->
				
				<!-- REGION KEY -->
				<div class="region-key hide" id="region-key-<?php echo $term_slug; ?>">
					
					<?php if ( current_user_can( 'edit_term', $term_id ) ) echo $edit_region_link; ?>
					
					<?php if( $region_intro ) { ?>
						
						<div class="region-content region-intro">
							<?php echo $region_intro; ?>
						</div>	
						
					<?php } ?>
					
					
					<?php 
					
					$facility_types = [];
					
					$i = 0;
					while ( $_posts->have_posts() ) : $_posts->the_post();
						//global $post;
						
						$school_type = get_field('school_type', $post->ID);
						
						if ( !in_array($school_type, $facility_types) ) {
							$facility_types[$i] = $school_type;
						}
						
						$i++;
						
					endwhile;
					
					?>
					<h3>Map Color Key</h3>
					<hr>
					<ul class="markers">
						<li class="high-school">
							<img src="/wp-content/uploads/2019/11/red-marker.png">
							High School
						</li>
						<li class="middle-school">
							<img src="/wp-content/uploads/2019/11/blue-marker.png">
							Middle School
						</li>
						<li class="elementary-school">
							<img src="/wp-content/uploads/2019/11/gray-marker.png">
							Elementary School
						</li>
						
						<?php if ( in_array('charter_school', $facility_types) ) { ?>
						<li class="charter-school">
							<img src="/wp-content/uploads/2020/01/orange.png">
							Charter School
						</li>
						<?php } ?>
						
						<?php if ( in_array('mental_health', $facility_types) ) { ?>
						<li class="mental-health">
							<img src="/wp-content/uploads/2023/02/purple.png">
							Mental Health Facility
						</li>
						<?php } ?>
						
						<?php if ( in_array('k_8', $facility_types) ) { ?>
						<li class="k-8-school">
							<img src="/wp-content/uploads/2020/01/blue.png">
							K-8
						</li>
						<?php } ?>
						
					</ul>
					
					<?php if( $region_content ) { ?>
						<div class="region-key-inner">
							<div class="region-content">
								<?php echo $region_content; ?>
							</div>
						</div><?php // .region-key-inner ?>
					<?php } ?>
					
				</div><?php // .region-key ?>
				
			</div><?php // .region-container ?>
			
			<?php 
			
			endif;
			wp_reset_postdata();

		endforeach;
		?>
	</div>
	<!-- SCHOOL CONTENT  -->
	<div class="zoom-div schools hide">
		<div class="close-button close-schools">
			<img src="/wp-content/themes/compete-interactive-map/assets/images/return.svg" class="img-fluid">
		</div>
		<?php
		$_posts = new WP_Query( array(
			'post_type' => 'schools',
			'posts_per_page' => -1
		)
		);
		?>

		<?php while ( $_posts->have_posts() ) : $_posts->the_post();			
			
			//echo '<pre>';
			//var_dump($post);
			//echo '</pre>';
			
			
			//$post_slug      = $post->post_name;
			$post_id        = $post->ID;
			$post_slug      = get_post_field( 'post_name' );
			//$school_title   = $post->post_title;
			$school_title   = get_the_title();
			$logo           = get_field('logo');
			$contact_info   = get_field('contact_information');
			$address        = $contact_info['street_address'];
			$email          = $contact_info['email'];
			$phone          = $contact_info['phone'];
			$website        = $contact_info['website'];
			$twitter_url    = $contact_info['twitter'];
			$facebook_url   = $contact_info['facebook'];
			$instagram_url  = $contact_info['instagram'];
			$hide_socials   = get_field('schools_hide_socials', 'option');
			$principal_info = get_field('principal_information');
			$principal      = $principal_info['first_name'] . ' ' . $principal_info['last_name'];	
			$stats          = get_field('stats');
			$staff          = number_format(intval($stats['staff']));
			$students       = number_format(intval($stats['students']));
			$grad_rate      = $stats['grad_rate'];
			$languages      = $stats['languages'];
			$awards         = get_field('awards');
			$awards_display = $awards['list_display'];
			$awards_class   = $awards_display == 'awards' ? ' class="awards-list"' : null;
			$awards_title   = $awards['list_title'];
			$awards_list    = $awards['list_text'];			
			$list_items     = explode("\r\n", $awards_list);
			$edit_link      = $post_id ? '<div class="edit-link"><a href="' . get_edit_post_link($post_id) . '" target="_blank">Edit ' . $school_title . '</a></div>' : null;
			
			?>
			
			<div class="school-container hide" id="school-<?php echo $post_slug; ?>">
				<div class="school-container-inner">
					
					
					<div class="school-container--top">
						
						<div class="title-block">
							
							<h2><?php the_title(); ?></h2>
							
							<?php if ( current_user_can( 'edit_post', $post_id ) ) echo $edit_link; ?>
							
							<p class="address"><img class="icon img-fluid" src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/icon-contact-location.svg" alt="Location Marker Icon" width="20" height="28"> <span><?php echo $address; ?></span></p>
							
							<ul class="core-values">
								<li class="gm"><img class="icon img-fluid" src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/icon-core-values-growth-mindset.svg" alt="Growth Mindset Icon" width="80" height="80"> Growth Mindset</li>
								<li class="eq"><img class="icon img-fluid" src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/icon-core-values-equity.svg" alt="Equity Icon" width="80" height="80"> Equity</li>
								<li class="ww"><img class="icon img-fluid" src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/icon-core-values-whole-wellbeing.svg" alt="Whole Wellbeing Icon" width="80" height="80"> Whole Wellbeing</li>
								<li class="eng"><img class="icon img-fluid" src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/icon-core-values-engagement.svg" alt="Engagement Icon" width="80" height="80"> Engagement</li>
								<li class="rel"><img class="icon img-fluid" src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/icon-core-values-relationships.svg" alt="Relationships Icon" width="80" height="80"> Relationships</li>
							</ul>
							
						</div> <?php // .title-block ?>
						
						<div class="contact-block">
							
							<?php the_post_thumbnail('full', ['class' => 'img-fluid']); ?>
							
							
							<div class="contact-block--main">

								<?php
									
									if( !empty( $logo ) )
										echo wp_get_attachment_image($logo, 'full', false, array('class'=>'school-logo') );
									
									/*
									if ($logo): 
										$logo_url    = $logo['url'];
										$logo_size   = 'school-logo';
										$school_logo = $logo['sizes'][ $logo_size ];
									endif;
									
									if( !empty( $logo ) ): ?>
										<img src="<?php echo esc_url($school_logo); ?>" class="school-logo" alt="<?php echo esc_attr($logo['alt']); ?>" class="img-fluid" />
									<?php endif;
									*/
								
								?>
								
								<ul class="contact-points">
								
									<?php if( $email ): ?>	
										<li><div class="info-icon"><img src="/wp-content/themes/compete-interactive-map/assets/images/icon-contact-envelope.svg" class="img-fluid" width="20" height="15"></div><a href="mailto:<?php echo $email; ?>"><?php echo $email; ?></a></li>
									<?php endif; ?>
									
									<?php if( $website ): ?>	
										<li><div class="info-icon"><img src="/wp-content/themes/compete-interactive-map/assets/images/icon-contact-monitor.svg" class="img-fluid" width="21" height="19"></div><a href="<?php echo $website; ?>" target="_blank" rel="noopener">Visit School Website</a></li>
									<?php endif; ?>
									<?php if( !empty($principal) ): ?>	
										<li><div class="info-icon"><img src="/wp-content/themes/compete-interactive-map/assets/images/icon-contact-principal.svg" class="img-fluid" width="20" height="20"></div><span>Principal <?php echo $principal; ?></span></li>
									<?php endif; ?>
									<?php if( $phone ): ?>	
										<li><div class="info-icon"><img src="/wp-content/themes/compete-interactive-map/assets/images/icon-contact-phone.svg" class="img-fluid" width="16" height="27"></div><span><?php echo $phone; ?></span></li>
									<?php endif; ?>

								</ul>
								
								<?php if ( $hide_socials === false && ($twitter_url || $facebook_url || $instagram_url) ) { ?>
								
									<ul class="contact-social">
										<?php if ( $twitter_url ): ?>
											<li class="tw"><a href="<?php echo $twitter_url; ?>" target="_blank" rel="noopener" aria-label="View the <?php echo $school_title; ?> Twitter account"><img src="/wp-content/themes/compete-interactive-map/assets/images/icon-social-tw.svg" alt="Twitter icon"></a></li>
										<?php endif; ?>
										<?php if( $facebook_url ): ?>	
											<li class="fb"><a href="<?php echo $facebook_url; ?>" target="_blank" rel="noopener" aria-label="View the <?php echo $school_title; ?> Facebook account"><img src="/wp-content/themes/compete-interactive-map/assets/images/icon-social-fb.svg" alt="Facebook icon"></a></li>
										<?php endif; ?>
										<?php if( $instagram_url ): ?>	
											<li class="ig"><a href="<?php echo $instagram_url; ?>" target="_blank" rel="noopener" aria-label="View the <?php echo $school_title; ?> Instagram account"><img src="/wp-content/themes/compete-interactive-map/assets/images/icon-social-ig.svg" alt="Instagram icon"></a></li>
										<?php endif; ?>
									</ul>
								
								<?php }	?>
								
							</div> <?php // .contact-block--main ?>
						
						</div> <?php // .contact-block ?>
						
					</div> <?php // .school-container--top ?>
					
					<div class="school-container--mid">
						
						<?php
						
						//$timeline = get_field('timeline');
						
						?>
						<div class="timeline-header">
							
							<h2>Your Tax Dollars at Work in Cherry Creek</h2>
							
							<ul class="timeline-key timeline-icons">
								
								<li class="health">Student Health</li>
								<li class="safety">Safety &amp; Security</li>
								<li class="maintenance">Maintenance</li>
								<li class="innovation">Innovation</li>
								<li class="technology">Technology</li>
								<li class="renewal">Renewal/Rebuild</li>
								
							</ul>
							
						</div>
						
						<?php if ( have_rows('timeline') ) { ?>
							
							<ul class="timeline">
								
								
								<?php while ( have_rows('timeline') ) { the_row(); 
								
									$block_label    = get_sub_field('label');
									$block_sublabel = get_sub_field('sublabel');
									$block_list     = get_sub_field('text');
									?>
									
									
									<li class="timeline-block">
										
										<div class="labels">
											<h3><?php echo $block_label; ?></h3>
											<?php /*<h3><?php echo $block_label; ?><?php if ( $block_sublabel ) { ?><span><?php echo $block_sublabel; ?></span><?php } ?></h3> */ ?>
											<?php if ( $block_sublabel ) { ?><h4><?php echo $block_sublabel; ?></h4><?php } ?>
										</div> <?php // .labels ?>
										
										<?php if ( have_rows('list') ) { ?>
											
											<ul class="timeline-block--list timeline-icons">
												
												<?php
												while ( have_rows('list') ) { the_row();
													
													$item_cat  = get_sub_field('category');
													$item_text = get_sub_field('text');
													$item_link = get_sub_field('link');
													
													if ( !empty($item_link) ) {
														echo '<li class="' . $item_cat . '"><a href="' . $item_link . '" target="_blank" rel="noopener">' . $item_text . '</a></li>';
													} else {
														echo '<li class="' . $item_cat . '">' . $item_text . '</li>';
													}
													
												}
												?>
												
											</ul>
											
										<?php } ?>
										
									</li>
									
									
								<?php } ?>
								
								
							</ul>
							
						<?php } ?>
						
						
					</div> <?php // .school-container--mid ?>
					
					<div class="school-container--btm">
					
						<div class="community">
							
							<div class="icon-wrap"><img src="/wp-content/themes/compete-interactive-map/assets/images/icon-stats-community.svg" alt="Community Icon"></div>
							<h2>Our Community</h2>
							
							<div class="inner">
								
								<div class="col">
									<h3>Teachers &amp; Staff</h3>
									<span class="stat"><?php echo $staff; ?></span>
								</div>
								
								<div class="col">
									<h3>Students</h3>
									<span class="stat"><?php echo $students; ?></span>
								</div>
							</div>
							
						</div>
						
						<?php if ( $grad_rate ) { ?>
							<div class="grad-rate">
								<div class="icon-wrap"><img src="/wp-content/themes/compete-interactive-map/assets/images/icon-stats-grad-rate.svg" alt="Graduation Rate Icon"></div>
								<h2>Graduation Rate</h2>
								<span class="stat"><?php echo $grad_rate; ?><sup>%</sup></span>
							</div>
						<?php } ?>
					
						<div class="languages">
							<div class="icon-wrap"><img src="/wp-content/themes/compete-interactive-map/assets/images/icon-stats-languages.svg" alt="Languages Icon"></div>
							<h2># of Languages Spoken</h2>
							<span class="stat"><?php echo $languages; ?></span>
						</div>

						<div class="awards">
							
							<h2><?php echo $awards_title; ?></h2>
							<ul<?php echo $awards_class; ?>>
								<?php foreach ( $list_items as $list_item ) { ?>
									<li><?php echo $list_item; ?></li>
								<?php } ?>
							</ul>
							
						</div>
					
					</div> <?php // .school-container--btm ?>
					
				
					<?php
					
					/* Old Backup
					
					<div class="school-info">
	
					<?php
					
					
					$logo = get_field('logo');
					if ($logo): 
						$logo_url = $logo['url'];
						$logo_size = 'school-logo';
						$school_logo = $logo['sizes'][ $logo_size ];
					endif;
					
					if( !empty( $logo ) ): ?>
						<img src="<?php echo esc_url($school_logo); ?>" class="school-logo" alt="<?php echo esc_attr($logo['alt']); ?>" class="img-fluid" />
					<?php endif; ?>
	
				
					<?php if( have_rows('principal_information') ): ?>
	
						
						<div class="principal-info">
							<h3>A Message From the Principal</h3>
							<div class="principal-content">
								<ul>
									<?php while( have_rows('principal_information') ): the_row(); 
	
										// Get sub field values.
										$first_name = get_sub_field('first_name');
										$last_name = get_sub_field('last_name');
										$email = get_sub_field('email');
										$phone = get_sub_field('phone');
										$video_id = get_sub_field('video_id');
										$photo = get_sub_field('photo');
										if ($photo): 
											$photo_url = $photo['url'];
											$photo_size = 'principal-photo';
											$principal_photo = $photo['sizes'][ $photo_size ];
										endif;
	
										if( !empty( $photo ) ): ?>
											<li>
												<div class="principal-photo img-fluid">
													<img src="<?php echo esc_url($principal_photo); ?>" alt="<?php echo esc_attr($photo['alt']); ?>" class="" />
												</div>
											</li>
										<?php endif; ?>
	
										<?php if( $first_name || $last_name ): ?>
											<li><h4><?php echo $first_name; ?> <?php echo $last_name; ?></h4></li>
										<?php endif; ?>
										<?php if( $email ): ?>	
											<li><a href="mailto:<?php echo $email; ?>">Email <?php echo $first_name; ?></a></li>
										<?php endif; ?>
										<?php if( $phone ): ?>	
											<li><?php echo $phone; ?></li>
										<?php endif; ?>
										
									<?php endwhile; ?>
								</ul>
								<?php if($video_id): ?>
								<div class="principal-video">
									<div class="youtube" data-embed="<?php echo $video_id; ?>">
										<div class="play-button"></div>
									</div>
								</div>
								<?php endif; ?>
							</div>
						</div>
						<?php endif; ?>
						<div class="contact">
							<div class="school-name">
								<h2><?php the_title();?></h2>
							</div>
							
							<div class="school-photo">
								<?php the_post_thumbnail('school-image', ['class' => 'img-fluid']); ?>
							</div>
	
							<?php if( have_rows('contact_information') ): ?>
	
							<ul class="school-contact-info">
							<?php while( have_rows('contact_information') ): the_row(); 
	
								// Get sub field values.
								$street_address = get_sub_field('street_address');
								$email = get_sub_field('email');
								$phone = get_sub_field('phone');
								$website = get_sub_field('website');
	
								?>
								<?php if( $street_address ): ?>
									<li><div class="info-icon"><img src="/wp-content/themes/compete-interactive-map/assets/images/placeholder.png" class="img-fluid"></div> <?php echo $street_address; ?></li>
								<?php endif; ?>
								<?php if( $email ): ?>	
									<li><div class="info-icon"><img src="/wp-content/themes/compete-interactive-map/assets/images/envelope.png" class="img-fluid"></div><a href="mailto:<?php echo $email; ?>"><?php echo $email; ?></a></li>
								<?php endif; ?>
								<?php if( $phone ): ?>	
									<li><div class="info-icon"><img src="/wp-content/themes/compete-interactive-map/assets/images/iphone.png" class="img-fluid"></div><?php echo $phone; ?></li>
								<?php endif; ?>
								<?php if( $website ): ?>	
									<li><div class="info-icon"><img src="/wp-content/themes/compete-interactive-map/assets/images/monitor.png" class="img-fluid"></div><a href="<?php echo $website; ?>">School Website</a></li>
								<?php endif; ?>
							<?php endwhile; ?>
							</ul>
	
							<?php endif; ?>
						
						</div> <!-- .contact -->
						
					</div>
				
					<div class="school-data">
					
						<div class="info-row">
						<?php if( have_rows('school_demographics') ): ?>
	
							<div class="demographics">
									
								<h3>School Demographics</h3>
								<?php while( have_rows('school_demographics') ): the_row(); 
	
								// vars
								$population = get_sub_field('population');
	
								?>
	
								<h4><span>Serves:</span> <?php echo $population; ?> Students</h4>
	
								<h4>Student Race-Ethnicity Data</h4>
								
								<?php if( have_rows('demographics') ): ?>
									<script type="text/javascript">
									google.charts.load('current', {'packages':['corechart']});
									google.charts.setOnLoadCallback(drawChart);
	
									function drawChart() {
	
										var data = google.visualization.arrayToDataTable([
											['Race', 'Percentage'],
											
											<?php while( have_rows( 'demographics' ) ): the_row();
											
											$race = get_sub_field('race');
											$percentage = get_sub_field('percentage');
											
											?>
											
											['<?php echo $race; ?>',     <?php echo $percentage; ?>],
	
											<?php endwhile; ?>
	
										]);
	
									var options = {
										slices: {
											0: {color: '#455660'},
											1: {color: '#98052f'},
											2: {color: '#0f7b97'},
											3: {color: '#d50a41'},
											4: {color: '#98a5ac'},
										},
										pieSliceText: 'none',
										pieSliceTextStyle: {color: '#fff', fontName: 'Work Sans', fontSize: 14, bold: 1},
										backgroundColor: 'transparent',
										pieSliceBorderColor: 'transparent',
										tooltip: {
											ignoreBounds:false,
											text: 'percentage',
										},
										legend: {
											position: 'none',
										},
										chartArea:{width:'90%',height:'90%'}
									};
	
									var chart = new google.visualization.PieChart(document.getElementById('piechart-<?php echo $post_slug; ?>'));
	
									chart.draw(data, options);
									}
									</script>
									
									<div class="piechart" id="piechart-<?php echo $post_slug; ?>" width="100%"></div>
								<?php endif; ?>
								
	
								<?php endwhile; ?>
							</div>
	
							<?php endif; ?>
							
							<?php if( have_rows('school_points_of_pride') ): ?>
	
							<div class="points-of-pride">
									
								<h3>Points of Pride</h3>
								<?php while( have_rows('school_points_of_pride') ): the_row(); 
	
								// vars
								$year_built = get_sub_field('year_built');
	
								?>
	
								<h4><span>Built:</span> <?php echo $year_built; ?></h4>
								
								<?php if( have_rows('points_of_pride') ): ?>
									<ul>
										<?php while( have_rows( 'points_of_pride' ) ): the_row();
											
										$point = get_sub_field('point');
											
										?>
										
										<li><?php echo $point; ?></li>
	
										<?php endwhile; ?>
									</ul>
	
								<?php endif; ?>
								
	
								<?php endwhile; ?>
							</div>
	
							<?php endif; ?>
						</div>
	
                    	<?php if( get_field('recommended_improvements') ): ?>
                        	
						<div class="recommended_improvements">
									
							<h3>2020 Bond Improvements</h3>
								
							<?php $value = get_field( "recommended_improvements" );
	
								echo $value;
								
							?>
	
						</div>
						<?php endif; ?>	
							
	
	
	
						<?php if( have_rows('bond_improvements') ): ?>
	
						<div class="bond-improvements">
							<h3>2016 Bond Improvements:</h3>
							
							<ul>
							<?php while( have_rows('bond_improvements') ): the_row(); 
	
							// vars
							$improvement = get_sub_field('improvement');
							$date = get_sub_field('date');
	
							?>
								<li>
									<div class="improvement"><?php echo $improvement; ?></div>
									<div class="date"><?php echo $date; ?></div>
								</li>
	
							<?php endwhile; ?>
							
	
							</ul>
						</div>
	
						<?php endif; ?>
					</div>
					
					*/
					
					?>
					
				</div>
			</div>

		<?php endwhile; wp_reset_query(); ?>
	</div>
		
</main>

<div class="modal">
	<div class="modal-overlay modal-toggle"></div>
	<div class="modal-wrapper modal-transition">
		<div class="modal-header">
			<button class="modal-close modal-toggle"><img src="/wp-content/themes/compete-interactive-map/assets/images/close.png" class="img-fluid"></button>
		</div>
		<div class="modal-body">
			<div class="modal-content embed-fluid">
			</div>
		</div>
	</div>
</div> <!-- .modal -->

<div class="investment-projects">
	
	
	
	<?php 
	if ( !have_rows('major_investments', 'option') ) return;
	
	while ( have_rows('major_investments', 'option') ) { the_row('major_investments', 'option');
	
		$title    = get_sub_field('project_title');
		$subtitle = get_sub_field('subtitle');
		$text     = get_sub_field('text');
		$image    = get_sub_field('project_image');
		
		echo '<div class="project" id="' . sanitize_title($title) . '">';
			
			echo '<div class="image-wrap">';
				echo wp_get_attachment_image($image, 'full', true);
			echo '</div>';
			
			echo '<div class="text-wrap">';
				echo '<h2 class="project-title">' . $title . '</h2>';
				echo '<p class="subtitle">' . $subtitle . '</p>';
				echo $text;
			echo '</div>'; // .text-wrap
			
		echo '</div>';
	
	}
	
	?>
	


</div>

<?php get_footer(); ?>