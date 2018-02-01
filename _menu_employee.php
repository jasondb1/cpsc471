			<nav id="nav" class="skel-panels-fixed">
				<ul>
					<li class="current_page_item"><a href="index.php">Home</a></li>
					<li><a href="_employee_menu.php">Employee Menu</a></li>
					<li><a href="_administration_menu.php">Admin Menu</a></li>
					<li><a href="phpauthent/phpauthentadmin/change_password.php">Change Password</a></li>
					<?php 
					if (isUserLogged()){
						echo '<li><a href="phpauthent/phpauthent_core.php?action=logout"><i class="fa fa-unlock"></i> Logout</a></li>';
					}
					else{
						echo '<li><a href="_login.php"><i class="fa fa-lock"></i> Login</a></li>';
					}
					?>
				</ul>
			</nav>
