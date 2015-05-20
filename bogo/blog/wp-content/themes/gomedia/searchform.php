<div id="nav-search" class="col-md-3 visible-md visible-lg">
	<form method="get" class="searchform" id="searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>" role="search">
		<div class="input-group">
			<input type="text" name="s" id="s" class="form-control" placeholder="<?php esc_attr_e( 'Search', 'gomedia' ); ?>">
			<span class="input-group-btn">
				<button class="btn btn-muted" type="submit" name="submit" id="searchsubmit"><i class="fa fa-search"></i></button>
			</span>
		</div><!-- .input-group -->		
	</form>	
</div><!-- .nav-search .col-md-3 -->