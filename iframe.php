<?php

isset($_GET['source']) && highlight_file(__FILE__) && die();

// To protect from unrelated attacks https://w3c.github.io/webappsec-post-spectre-webdev/#dynamic-subresources
// Excluding Cross-Origin-Opener-Policy and CSP sandbox.
header('Cross-Origin-Resource-Policy: same-origin');
header('Vary: Sec-Fetch-Dest, Sec-Fetch-Mode, Sec-Fetch-Site, Sec-Fetch-User');
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: SAMEORIGIN');

// https://github.com/mikewest/deprecating-document-domain
header('Feature-Policy: document-domain "none"');

session_set_cookie_params(60, '/; samesite=Lax', $_SERVER['HTTP_HOST'], true, true);
session_start();

if(!isset($_SESSION['id'])) {
    $identifier = bin2hex(random_bytes(12));
    $_SESSION['id'] = $identifier;
}else{
    $identifier = $_SESSION['id'];
}

?>
<html>
    <head>
<script>
const identifier = '<?=$identifier;?>';
onmessage = e => {
  const data = e.data;
  if(e.origin !== window.origin && data.identifier !== identifier) return;
  if(data.type === 'render'){
    renderContainer.innerHTML = data.body;
  }
}
</script>
    </head>
    <body>
            <div id="renderContainer"></div>
    </body>
</html>
