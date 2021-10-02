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
                 resize: none;
             }
             .container iframe{
                 width: 100%;
                 height: 100%;
             }
             @media (prefers-color-scheme: dark){
                 body {
		    color: #eee;
		    background: #121212;
                 }
	     }
         </style>
    </head>
    <body>
        <h1>Welcome to note creator!</h1>
        <div class="container">
            <div class="column">
                <h2>Your HTML code <input type="file" onchange="loadFile(this.files[0])"><button id="saveButton" disabled onclick="save()">Save</button><button id="shareButton" disabled onclick="share()">Share</button><button id="clearButton" disabled onclick="remove()">Clear</button></h2>
                <textarea id="textarea"></textarea>
            </div>
            <div class="column">
                <h2>Rendered page<button id="popup" onclick="popup()">Separate window</button></h2>
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
            var popupWindow = false;
            var cleanHTML = "";

            function updateMenu(value) {
                const isEmpty = (textarea.value.length === 0);
                saveButton.disabled = isEmpty;
                shareButton.disabled = isEmpty;
                clearButton.disabled = isEmpty;
            }
			
            function onChange() {
                const dirty = textarea.value;
                updateMenu();
                localStorage.setItem("html", dirty);
                cleanHTML = DOMPurify.sanitize(dirty);
                if (popupWindow && popupWindow.closed === false) {
                    try {
                        updateWindow();
                    } catch (e) {
                        popupWindow = false;
                    }
                }
                iframe.contentWindow.postMessage({
                    identifier,
                    type: 'render',
                    body: cleanHTML,
                }, window.origin);
			}
			
            textarea.onchange = textarea.oninput = () => {
                onChange();
            }
			
            textarea.value = localStorage.getItem("html");
            onChange();
			
            function save() {
                const a = document.createElement('a');
                const file = new Blob([textarea.value]);
                a.href = URL.createObjectURL(file);
                a.download = "code.html";
                if (!a.href.startsWith("blob:")) return
                a.click();
                URL.revokeObjectURL(a.href);
            };
			
            async function loadFile(file) {
                let text = await file.text();
                textarea.value = text;
            }

            function share() {
                navigator.share({text: textarea.value})
            }

            function remove() {
                textarea.value = "";
                onChange();
                localStorage.removeItem("html")
            }
			
            function popup() {
                popupWindow = open("","","width=0,height=0");
                popupWindow.document.title = "Notes";
				updateWindow();
            }
            
            function updateWindow() {
                const isEmpty = (cleanHTML.length === 0);
                if (isEmpty) {
                    popupWindow.document.body.innerHTML = "<h1>Waiting for changes</h1>";
                } else {
                    popupWindow.document.body.innerHTML = cleanHTML;
                }
            }
        </script>
    </body>
</html>
