<?php
file_put_contents('./hh.log', json_encode($_SERVER).PHP_EOL, FILE_APPEND);
exit(1);