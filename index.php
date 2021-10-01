<?php

isset($_GET['source']) && highlight_file(__FILE__) && die();
header('X-Frame-Options: SAMEORIGIN');

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
         </script>
         <script src="https://cdnjs.cloudflare.com/ajax/libs/dompurify/2.3.3/purify.min.js" integrity="sha512-gtcrasYnyeB27+IejClswFlb/eggt+khRr+lLAeNcgg5x2ijlWaiBOPXZkwivNj15LaE6s9CzV57hsoTPrQ5xg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
         <style>
             .container {
                 width: 90vw;
                 height: 80vh;
                 padding: 0;
                 margin: 0;
                 display: inline-block;
             }
             .container .column{
                 width: 30vw;
                 float: left;
             }
             .container textarea{
                 width: 100%;
                 height: 100%;
             }
             .container iframe{
                 width: 100%;
                 height: 100%;
             }
         </style>
    </head>
    <body>
        <h1>Welcome to note creator!</h1>
        <div class="container">
            <div class="column">
                <h2>Your HTML code</h2>
                <textarea id="textarea"></textarea>
            </div>
            <div class="column">
                <h2>Rendered page</h2>
                <iframe id="iframe" src="/iframe.php"></iframe>
            </div>
            <div class="column">
                <h2>Hall of Fame</h2>
                <ul>
                    <li>
                        You?
                    </li>
                </ul>
            </div>
        </div>
        
        <script>
            textarea.onchange = textarea.oninput = () => {
                const dirty = textarea.value;
                const clean = DOMPurify.sanitize(dirty);
                iframe.contentWindow.postMessage({
                    identifier,
                    type: 'render',
                    body: clean,
                }, window.origin);
            }
        </script>
    </body>
</html>
