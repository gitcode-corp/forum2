
        <div id="forum_footer_wrapper">
            <div id="forum_footer">
                <div class="cleaner"></div>
            </div>

            <div id="forum_copyright">
                Copyright © 2015 <a href="#">Lukasz Drapala
            </div>
        </div>

       </div> 
    </body>
</html>

<?php
require_once '/../DatabaseConnection.php';

$conn = DatabaseConnection::connect();
$conn->close();
die();