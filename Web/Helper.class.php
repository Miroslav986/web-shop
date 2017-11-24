<?php

class  Helper {
	// staticka metoda za kreiranje sesije
	public static function session_start() {
		if (!isset($_SESSION)) {
			return session_start();
		}
	}
	// staicka metoda za unistavanje sesije koj se koristi za log out
	public static function session_destroy() {
    Helper::session_start();
    $_SESSION = [];
    session_destroy();
  }

// poruke za uspeh, gresku, i upozorenje

 public static function success($message, $title = "Success!") {
		echo '<div class="alert alert-success" role="alert">
		<b>' . $title . ' </b>
		' . $message . '
		</div>';
	}

	public static function error($message, $title = "Error!") {
		if ( is_array($message) ) {
			echo '<div class="alert alert-danger" role="alert">
				<b>' . $title . ' </b><br />';
			
			foreach ($message as $m) {
				echo $m . '<br />';
			}

			echo '</div>';
		} else {
			echo '<div class="alert alert-danger" role="alert">
				<b>' . $title . ' </b>
				' . $message . '
				</div>';
		}
	}

	public static function warn($message, $title = "Warning!") {
		echo '<div class="alert alert-warning" role="alert">
			<b>' . $title . ' </b>
			' . $message . '
			</div>';
	}

}



