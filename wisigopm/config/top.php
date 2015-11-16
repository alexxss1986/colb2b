<?php
echo "<div class=\"top-navbar\">
				<div class=\"top-navbar-inner\">

					<div class=\"logo-brand warning-color\">";
					if ($_SESSION['livello']!=1) {
					echo "<a href=\"dashboard.php\"><img style=\"margin-top: 6px;margin-left: -20px;\" src=\"img/wisigo-logo.png\" alt=\"Wisigo Product Management\"></a>";
					}
					else {
						echo "<a href=\"ordini.php\"><img style=\"margin-top: 6px;margin-left: -20px;\" src=\"img/wisigo-logo.png\" alt=\"Wisigo Product Management\"></a>";
					}
					echo "</div>

					
					<div class=\"top-nav-content\">

						<ul class=\"nav-user navbar-right\">
							<li class=\"dropdown\">
							  <a href=\"#fakelink\" class=\"dropdown-toggle\" data-toggle=\"dropdown\">
								<img src=\"assets/img/avatar/avatar.jpg\" class=\"avatar img-circle\" alt=\"Avatar\">
								<strong>".$_SESSION['nome']."</strong>
							  </a>
							  <ul class=\"dropdown-menu square primary margin-list-rounded with-triangle\">";
								/*<li><a href=\"#fakelink\">Account setting</a></li>
								<li><a href=\"#fakelink\">Payment setting</a></li>
								<li><a href=\"#fakelink\">Change password</a></li>
								<li><a href=\"#fakelink\">My public profile</a></li>
								<li class=\"divider\"></li>
								<li><a href=\"lock-screen.html\">Lock screen</a></li>*/
								echo "<li><a href=\"config/logout.php\">Log out</a></li>
							  </ul>
							</li>
						</ul>
						

					</div>
				</div>
			</div>";