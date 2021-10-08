<?php
isset($_GET['source']) && highlight_file(__FILE__) && die();

header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');

session_name('__Host-PHPSESSID');
session_set_cookie_params(60, '/; samesite=Lax', "", true, true);
session_start();

if (!isset($_SESSION['id'])) {
    $identifier = bin2hex(random_bytes(12));
    $_SESSION['id'] = $identifier;
} else {
    $identifier = $_SESSION['id'];
}

?>
<html>

<head>
    <title>soXSS</title>
    <meta name="charset" content="utf8">
    <script>
        const identifier = '<?= $identifier; ?>';
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dompurify/2.3.3/purify.min.js" integrity="sha512-gtcrasYnyeB27+IejClswFlb/eggt+khRr+lLAeNcgg5x2ijlWaiBOPXZkwivNj15LaE6s9CzV57hsoTPrQ5xg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <link rel="stylesheet" href="/main.css">
</head>

<body>
    <h1>It's time for some soXSS!</h1>
    <span class="subtitle">Delivered by <a href="https://twitter.com/terjanq">terjanq</a> & <a href="https://twitter.com/ndevtk">NDevTK</a></span>
    <p>
        Can you take up on the challenge and pop out an <code>alert()</code> on <code>so-xss.terjanq.me</code> origin? 
    </p>

    <div class="container">
        <div class="column">
            <div class="row header">
                <h2>Your HTML code</h2>
                <div class="panel">
                    <input type="file" onchange="loadFile(this.files[0])">
                    <button id="saveButton" disabled onclick="save()">Save</button>
                    <button id="clearButton" disabled onclick="remove()">Clear</button>
                </div>

            </div>
            <div class="row content">
                <textarea id="textarea" spellcheck="false"></textarea>
            </div>
        </div>
        <div class="column">
            <div class="row header">
                <h2>Rendered page </h2>
                <div class="panel">
                    <button id="popupButton" onclick="popup()">Open a popup</button>
                    <button id="shareButton" disabled onclick="share()">Share</button>
                </div>
            </div>
            <div class="row content">
                <iframe id="iframe" src="/iframe.php"></iframe>
            </div>
        </div>
        <div class="column">
            <div class="row header">
                <h2>Rules</h2>
            </div>
            <div class="row content rules">
                <strong> The solution:  </strong>
                    <ul>
                        <li>Must work in the latest version of Chrome or Firefox.</li>
                        <li>Can't make use of other domains from <code>*.terjanq.me</code> (including <code>terjanq.me</code>).</li>
                        <li>Can't be a self-XSS.</li>
                        <li>Must be submitted in a private message to <a href="https://twitter.com/terjanq">terjanq</a> or <a href="https://twitter.com/ndevtk">NDevTK</a>.</li>
                        <li>Must display contents of admin's file, i.e. <code>alert(_RAW_HTML_CONTENTS_)</code>.</li>
                    </ul>
               
            </div>
            <div class="row header">
                <h2>Hall of Fame</h2>
            </div>
            <div class="row content"><ul id="hof" class="hof"></ul></div>
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
            updateContents();
        }

        textarea.onchange = textarea.oninput = onChange;
    </script>
    <script src="/utils.js"></script>
</body>
<!-- index.php?source iframe.php?source -->
</html>
