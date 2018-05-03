<?php Defined("BASE_PATH") or die("Dilarang Mengakses File Secara Langsung"); ?>

<!-- Header -->
<header class="main-header">
	<!-- logo -->
	<a href="<?= BASE_URL ?>" class="logo">
		<!-- logo mini -->
		<span class="logo-mini">69</span>
		<!-- logo default -->
		<span class="logo-lg">69 Design & Build</span>
	</a>

	<!-- header navbar -->
	<nav class="navbar navbar-static-top" role="navigation">
		<!-- sidebar toggle button -->
		<a href="javascript:void(0)" class="sidebar-toggle" data-toggle="push-menu" role="button">
			<span class="sr-only">Toggle navigation</span>
	  	</a>
	  	<!-- navbar menu notifikasi, profil -->
	  	<div class="navbar-custom-menu">
	  		<ul class="nav navbar-nav">

	  			<!-- Messages: style can be found in dropdown.less-->
	          	<li class="dropdown messages-menu">
	            	<!-- Menu toggle button -->
	            	<a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown">
	              		<i class="fa fa-envelope-o"></i>
	              		<span class="label label-success">4</span>
	            	</a>
	            	<ul class="dropdown-menu">
	              		<li class="header">You have 4 messages</li>
	              		<li>
	                		<!-- inner menu: contains the messages -->
	                		<ul class="menu">
	                  			<li><!-- start message -->
	                    			<a href="javascript:void(0)">
	                      				<div class="pull-left">
	                        				<!-- User Image -->
	                        				<img src="<?= BASE_URL."assets/dist/img/user2-160x160.jpg"; ?>" class="img-circle" alt="User Image">
	                      				</div>
	                      				<!-- Message title and timestamp -->
	                      				<h4>
		                        			Support Team
		                        			<small><i class="fa fa-clock-o"></i> 5 mins</small>
	                      				</h4>
	                      				<!-- The message -->
	                      				<p>Why not buy a new awesome theme?</p>
	                    			</a>
	                  			</li>
	                  			<!-- end message -->
	                		</ul>
	                		<!-- /.menu -->
	              		</li>
	              		<li class="footer"><a href="javascript:void(0)">See All Messages</a></li>
	            	</ul>
	          	</li>
	          	<!-- /.messages-menu -->

	          	<!-- notifikasi -->
	          	<li class="dropdown notifications-menu">
		            <!-- Menu toggle button -->
		            <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown">
		              	<i class="fa fa-bell-o"></i>
		              	<span class="label label-warning">10</span>
		            </a>
		            <ul class="dropdown-menu">
		              	<li class="header">You have 10 notifications</li>
		              	<li>
		                	<!-- Inner Menu: contains the notifications -->
		                	<ul class="menu">
		                  		<li><!-- start notification -->
		                    		<a href="javascript:void(0)">
		                      			<i class="fa fa-users text-aqua"></i> 5 new members joined today
		                    		</a>
		                  		</li><!-- end notification -->
		                	</ul>
		              	</li>
		              	<li class="footer"><a href="javascript:void(0)">View all</a></li>
            		</ul>
	          	</li>
	          	<!-- end notifikasi -->

	          	<!-- task menu -->
	          	<li class="dropdown tasks-menu">
		            <!-- Menu Toggle Button -->
		            <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown">
		              	<i class="fa fa-flag-o"></i>
		              	<span class="label label-danger">9</span>
		            </a>
		            <ul class="dropdown-menu">
		              	<li class="header">You have 9 tasks</li>
		              	<li>
		                	<!-- Inner menu: contains the tasks -->
		                	<ul class="menu">
		                  		<li><!-- Task item -->
		                    		<a href="javascript:void(0)">
		                      			<!-- Task title and progress text -->
		                      			<h3>
					                        Design some buttons
					                        <small class="pull-right">20%</small>
		                      			</h3>
		                      			<!-- The progress bar -->
		                      			<div class="progress xs">
		                        			<!-- Change the css width attribute to simulate progress -->
		                        			<div class="progress-bar progress-bar-aqua" style="width: 20%" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
		                          				<span class="sr-only">20% Complete</span>
		                        			</div>
		                      			</div>
		                    		</a>
		                  		</li>
		                  		<!-- end task item -->
		                	</ul>
		              	</li>
		              	<li class="footer">
		                	<a href="javascript:void(0)">View all tasks</a>
		              	</li>
		            </ul>
	          	</li>
	          	<!-- end task menu -->

	          	<!-- user account menu -->
	          	<li class="dropdown user user-menu">
		            <!-- Menu Toggle Button -->
		            <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown">
		              	<!-- The user image in the navbar-->
		              	<img src="<?= BASE_URL."assets/dist/img/user2-160x160.jpg"; ?>" class="user-image" alt="User Image">
		              	<!-- hidden-xs hides the username on small devices so only the image appears. -->
		              	<span class="hidden-xs">Alexander Pierce</span>
		            </a>
		            <ul class="dropdown-menu">
		              	<!-- The user image in the menu -->
		              	<li class="user-header">
		                	<img src="<?= BASE_URL."assets/dist/img/user2-160x160.jpg" ?>" class="img-circle" alt="User Image">
		                	<p>
		                  		Alexander Pierce - Web Developer
		                  		<small>Member since Nov. 2012</small>
		                	</p>
		              	</li>
		              	<!-- Menu Body -->
		              	<li class="user-body">
		                	<div class="row">
		                  		<div class="col-xs-4 text-center">
		                    		<a href="javascript:void(0)">Followers</a>
		                  		</div>
			                  	<div class="col-xs-4 text-center">
			                    	<a href="javascript:void(0)">Sales</a>
			                  	</div>
			                  	<div class="col-xs-4 text-center">
			                    	<a href="javascript:void(0)">Friends</a>
			                  	</div>
		                	</div>
		                	<!-- /.row -->
		              	</li>
	              		<!-- Menu Footer-->
	              		<li class="user-footer">
	                		<div class="pull-left">
	                  			<a href="javascript:void(0)" class="btn btn-default btn-flat">Profile</a>
	                		</div>
	                		<div class="pull-right">
	                  			<a href="<?= BASE_URL."login/logout" ?>" class="btn btn-default btn-flat">Logout</a>
	                		</div>
	              		</li>
		            </ul>
		    	</li>
		    	<!-- end user acoount menu -->

	  		</ul>
	  	</div>
	  	<!-- end navbar menu notifikasi, profil -->

	</nav>
	<!-- end navbar -->

</header>
<!-- end header -->