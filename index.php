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
        <title>Notes</title>
        <script>
            const identifier = '<?=$identifier;?>';
         </script>
         <script src="https://cdnjs.cloudflare.com/ajax/libs/dompurify/2.3.3/purify.min.js" integrity="sha512-gtcrasYnyeB27+IejClswFlb/eggt+khRr+lLAeNcgg5x2ijlWaiBOPXZkwivNj15LaE6s9CzV57hsoTPrQ5xg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
         <link rel="stylesheet" href="main.css">
    </head>
    <body>
        <h1>Welcome to note creator!</h1>
        <div class="container">
            <div class="column">
                <h2>Your HTML code <input type="file" onchange="loadFile(this.files[0])"><button id="saveButton" disabled onclick="save()">Save</button><button id="shareButton" disabled onclick="share()">Share</button><button id="clearButton" disabled onclick="remove()">Clear</button></h2>
                <textarea id="textarea"></textarea>
            </div>
            <div class="column">
                <h2>Rendered page <button id="popup" onclick="popup()" disabled>Separate window</button></h2>
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
            var cleanHTML = "";

            function onChange() {
                const dirty = textarea.value;
                localStorage.setItem("html", dirty);
                cleanHTML = DOMPurify.sanitize(dirty);
                iframe.contentWindow.postMessage({
                    identifier,
                    type: 'render',
                    body: cleanHTML,
                }, window.origin);
            }
			
            textarea.onchange = textarea.oninput = () => {
                onChange();
                if (window.unrelated) unrelatedUpdate();
            }
        </script>
        <script src="unrelated.js"></script>
    </body>
</html>
