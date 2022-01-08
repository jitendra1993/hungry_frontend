<div class="page-header">
	<div class="row">
		<div class="col-md-6 col-sm-12">
			<div class="title">
				<h4>Dashboard</h4>
			</div>
			<nav aria-label="breadcrumb" role="navigation">
				<ol class="breadcrumb">
					<li class="breadcrumb-item"><a href="index.html">Home</a></li>
					<li class="breadcrumb-item active" aria-current="page">dashboard</li>
				</ol>
			</nav>
		</div>
		<div class="col-md-6 col-sm-12 text-right">
		</div>
	</div>
</div>

<div class="pd-20 bg-white border-radius-4 box-shadow mb-30">			    
	<div class="container">
    	<div class="row">
    		<div class="col-md-3">
      			<div class="card-counter primary">
        			<i class="fa fa-code-fork"></i>
       				 <span class="count-numbers newOrder"><?php echo $order_info['newOrder']; ?></span>
        			<span class="count-name">New Order</span>
      			</div>
    		</div>

			<div class="col-md-3">
				<div class="card-counter danger">
					<i class="fa fa-ticket"></i>
					<span class="count-numbers todayOrder"><?php echo $order_info['todayOrder']; ?></span>
					<span class="count-name">Today's Order</span>
				</div>
			</div>

			<div class="col-md-3">
				<div class="card-counter success">
					<i class="fa fa-database"></i>
					<span class="count-numbers monthOrder"><?php echo $order_info['monthOrder']; ?></span>
					<span class="count-name">Monthly Order</span>
				</div>
			</div>

			<div class="col-md-3">
				<div class="card-counter primary">
					<i class="fa fa-database"></i>
					<span class="count-numbers todaysales"><?php echo $order_info['todaySales']; ?></span>
					<span class="count-name">Today's Sales</span>
				</div>
			</div>
  		</div>
	</div>				    
</div>