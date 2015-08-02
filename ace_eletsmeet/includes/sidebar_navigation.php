<?php 
?>
<div class="">
	<div class="main-container" id="main-container">
			<script type="text/javascript">
				try{ace.settings.check('main-container' , 'fixed')}catch(e){}
			</script>

			<!-- #section:basics/sidebar -->
			<div id="sidebar" class="sidebar                  responsive">
				<script type="text/javascript">
					try{ace.settings.check('sidebar' , 'fixed')}catch(e){}
				</script>

				<div class="sidebar-shortcuts" id="sidebar-shortcuts">
					<div class="sidebar-shortcuts-large" id="sidebar-shortcuts-large">
						<button class="btn btn-success">
							<i class="ace-icon fa fa-signal"></i>
						</button>

						<button class="btn btn-info">
							<i class="ace-icon fa fa-pencil"></i>
						</button>

						<!-- #section:basics/sidebar.layout.shortcuts -->
						<button class="btn btn-warning">
							<i class="ace-icon fa fa-users"></i>
						</button>

						<button class="btn btn-danger">
							<i class="ace-icon fa fa-cogs"></i>
						</button>

						<!-- /section:basics/sidebar.layout.shortcuts -->
					</div>

					<div class="sidebar-shortcuts-mini" id="sidebar-shortcuts-mini">
						<span class="btn btn-success"></span>

						<span class="btn btn-info"></span>

						<span class="btn btn-warning"></span>

						<span class="btn btn-danger"></span>
					</div>
				</div><!-- /.sidebar-shortcuts -->

				<ul class="nav nav-list">
					<li class="active">
						<a href="index.html">
							<i class="menu-icon fa fa-tachometer"></i>
							<span class="menu-text"> Dashboard </span>
						</a>

						<b class="arrow"></b>
					</li>
					<!-- Address book start-->
					<li class="">
						<a href="#" class="dropdown-toggle">
							<i class="menu-icon fa fa-star"></i>
							<span class="menu-text">
								Address Book
							</span>

							<b class="arrow fa fa-angle-down"></b>
						</a>

						<b class="arrow"></b>

						<ul class="submenu">
							<li class="">
								<a href="#" class="dropdown-toggle">
									<i class="menu-icon fa fa-caret-right"></i>

									Manage Contacts
									<b class="arrow fa fa-angle-down"></b>
								</a>

								<b class="arrow"></b>

								<ul class="submenu">
									<li class="">
										<a href="<?php echo $base_url."contacts/"?>">
											<i class="menu-icon fa fa-caret-right"></i>
											Show Contacts
										</a>

										<b class="arrow"></b>
									</li>

									<li class="">
										<a href="<?php echo $base_url."contacts/add"?>">
											<i class="menu-icon fa fa-caret-right"></i>
											Add Contact
										</a>

										<b class="arrow"></b>
									</li>

								</ul>
							</li>
							<li class="">
								<a href="#" class="dropdown-toggle">
									<i class="menu-icon fa fa-caret-right"></i>

									Manage Groups
									<b class="arrow fa fa-angle-down"></b>
								</a>

								<b class="arrow"></b>

								<ul class="submenu">
									<li class="">
										<a href="<?php echo $base_url."groups/"?>">
											<i class="menu-icon fa fa-caret-right"></i>
											Show Groups
										</a>

										<b class="arrow"></b>
									</li>

									<li class="">
										<a href="<?php echo $base_url."groups/add"?>">
											<i class="menu-icon fa fa-caret-right"></i>
											Add Group
										</a>

										<b class="arrow"></b>
									</li>

								</ul>
							</li>

							<li class="">
                                                                <a href="<?php echo $base_url."contacts/import";?>">
                                                                        <i class="menu-icon"></i>
                                                                        Import Addresses (csv)
                                                                </a>

							</li>
							<li class="">
                                                                <a href="<?php echo $base_url."contacts/exportcsv";?>">
                                                                        <i class="menu-icon"></i>

                                                                        Export Addresses (csv)
                                                                </a>

							</li>

						</ul>
					</li>
					<!-- Address book end -->
					<!-- Meetings book start-->

					<li class="">
						<a href="#" class="dropdown-toggle">
							<i class="menu-icon fa fa-star"></i>
							<span class="menu-text">
								Meetings
							</span>

							<b class="arrow fa fa-angle-down"></b>
						</a>

						<b class="arrow"></b>

						<ul class="submenu">
							<li class="">
								<a href="#" class="dropdown-toggle">
									<i class="menu-icon fa fa-caret-right"></i>

									Manage Meetings
									<b class="arrow fa fa-angle-down"></b>
								</a>

								<b class="arrow"></b>

								<ul class="submenu">
									<li class="">
										<a href="top-menu.html">
											<i class="menu-icon fa fa-caret-right"></i>
											Schedule Meeting
										</a>

										<b class="arrow"></b>
									</li>

									<li class="">
										<a href="two-menu-1.html">
											<i class="menu-icon fa fa-caret-right"></i>
											Cancel Meeting
										</a>

										<b class="arrow"></b>
									</li>
									<li class="">
										<a href="two-menu-1.html">
											<i class="menu-icon fa fa-caret-right"></i>
											Merge Meetings
										</a>

										<b class="arrow"></b>
									</li>


									<li class="">
										<a href="two-menu-2.html">
											<i class="menu-icon fa fa-caret-right"></i>
											Postpone Meeting
										</a>

										<b class="arrow"></b>
									</li>

								</ul>
							</li>	

						</ul>
					</li>
					<!-- Meetings book end-->
					<!-- Settings book start-->

					<li class="">
						<a href="#" class="dropdown-toggle">
							<i class="menu-icon fa fa-star"></i>
							<span class="menu-text">
								Settings
							</span>

							<b class="arrow fa fa-angle-down"></b>
						</a>

						<b class="arrow"></b>

						<ul class="submenu">
							<li class="">
								<a href="#" class="dropdown-toggle">
									<i class="menu-icon fa fa-caret-right"></i>

									Manage Account
									<b class="arrow fa fa-angle-down"></b>
								</a>

								<b class="arrow"></b>

								<ul class="submenu">
									<li class="">
										<a href="top-menu.html">
											<i class="menu-icon fa fa-caret-right"></i>
											Update Password
										</a>

										<b class="arrow"></b>
									</li>

									<li class="">
										<a href="two-menu-1.html">
											<i class="menu-icon fa fa-caret-right"></i>
											Edit Profile
										</a>

										<b class="arrow"></b>
									</li>

									<li class="">
										<a href="two-menu-2.html">
											<i class="menu-icon fa fa-caret-right"></i>
											Edit Company Profile
										</a>

										<b class="arrow"></b>
									</li>

								</ul>
							</li>
							<li class="">
                                                                <a href="#" class="dropdown-toggle">
                                                                        <i class="menu-icon"></i>

                                                                        Import Addresses (csv)
                                                                </a>

							</li>
							<li class="">
                                                                <a href="#" class="dropdown-toggle">
                                                                        <i class="menu-icon fa"></i>

                                                                        Export Addresses (csv)
                                                                </a>

							</li>

						</ul>
					</li>
					<!-- Settings book start-->
					<li class="">
						<a href="#" class="dropdown-toggle">
							<i class="menu-icon fa fa-star"></i>
							<span class="menu-text">
								Moderators
							</span>

							<b class="arrow fa fa-angle-down"></b>
						</a>

						<b class="arrow"></b>

						<ul class="submenu">
							<li class="">
								<a href="#" class="dropdown-toggle">
									<i class="menu-icon fa fa-caret-right"></i>

									Manage Moderators
									<b class="arrow fa fa-angle-down"></b>
								</a>

								<b class="arrow"></b>

								<ul class="submenu">
									<li class="">
										<a href="top-menu.html">
											<i class="menu-icon fa fa-caret-right"></i>
											Add Moderator
										</a>

										<b class="arrow"></b>
									</li>

									<li class="">
										<a href="two-menu-1.html">
											<i class="menu-icon fa fa-caret-right"></i>
											Edit Moderator Details
										</a>

										<b class="arrow"></b>
									</li>

									<li class="">
										<a href="two-menu-2.html">
											<i class="menu-icon fa fa-caret-right"></i>
											Disable Moderator
										</a>

										<b class="arrow"></b>
									</li>

								</ul>
							</li>
						</ul>
					</li>
					<li class="">
						<a href="#" class="dropdown-toggle">
							<i class="menu-icon fa fa-star"></i>
							<span class="menu-text">
								Inbox
							</span>

							<b class="arrow fa fa-angle-down"></b>
						</a>

						<b class="arrow"></b>

						<ul class="submenu">
							<li class="">
								<a href="#" class="dropdown-toggle">
									<i class="menu-icon fa fa-caret-right"></i>

									Message Box
									<b class="arrow fa fa-angle-down"></b>
								</a>

								<b class="arrow"></b>

								<ul class="submenu">
									<li class="">
										<a href="top-menu.html">
											<i class="menu-icon fa fa-caret-right"></i>
											Inbox
										</a>

										<b class="arrow"></b>
									</li>

									<li class="">
										<a href="two-menu-1.html">
											<i class="menu-icon fa fa-caret-right"></i>
											Contact Support
										</a>

										<b class="arrow"></b>
									</li>									
								</ul>
							</li>
						</ul>
					</li>
				</ul><!-- /.nav-list -->

				<!-- #section:basics/sidebar.layout.minimize -->
				<div class="sidebar-toggle sidebar-collapse" id="sidebar-collapse">
					<i class="ace-icon fa fa-angle-double-left" data-icon1="ace-icon fa fa-angle-double-left" data-icon2="ace-icon fa fa-angle-double-right"></i>
				</div>

				<!-- /section:basics/sidebar.layout.minimize -->
				<script type="text/javascript">
					try{ace.settings.check('sidebar' , 'collapsed')}catch(e){}
				</script>
			</div>
	</div>
</div>
