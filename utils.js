/* Just for visuals. */

var popupWindow = false;

popupButton.disabled = false;

function updateMenu(value) {
  const isEmpty = (textarea.value.length === 0);
  saveButton.disabled = isEmpty;
  if ('share' in navigator) shareButton.disabled = (cleanHTML.length === 0);
  clearButton.disabled = isEmpty;
}

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
  onChange();
}

function share() {
  navigator.share({
    text: cleanHTML
  });
}

function remove() {
  textarea.value = "";
  onChange();
}

function popup() {
  popupWindow = open("", "", "width=0,height=0");
  updateWindow();
}

function updateWindow() {
  popupWindow.document.body.innerHTML = cleanHTML;
}

function updatePopup() {
  if (popupWindow && popupWindow.closed === false) {
    try {
      updateWindow();
    } catch (e) {
      popupWindow = false;
    }
  }
}

function updateContents() {
  updateMenu();
  updatePopup();
}

window.addEventListener('load', () => {
  textarea.value = localStorage.getItem("html");
  onChange();
})
