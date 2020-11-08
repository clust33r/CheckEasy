<?php

require_once __DIR__."/init.php";

$chk = new Chk("https://www.meuip.com.br/");
$chk->addFunctions(["cookies", "verify"]);

echo $chk->chk("get", false);
