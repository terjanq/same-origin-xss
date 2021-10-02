// This code is optional and not related to the challenge.
var unrelated = true;
var popupWindow = false;

function updateMenu(value) {
  const isEmpty = (textarea.value.length === 0);
  saveButton.disabled = isEmpty;
  shareButton.disabled = isEmpty;
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
  navigator.share({text: textarea.value});
}

function remove() {
  textarea.value = "";
  onChange();
}
			
function popup() {
  popupWindow = open("","","width=0,height=0");
  updateWindow();
}

function updateWindow() {
  const isEmpty = (cleanHTML.length === 0);
  if (isEmpty) {
    popupWindow.document.title = "Notes";
    popupWindow.document.body.innerHTML = "<h1>Waiting for changes</h1>";
  } else {
    popupWindow.document.body.innerHTML = cleanHTML;
  }
}

function unrelatedPopup() {
  if (popupWindow && popupWindow.closed === false) {
    try {
      updateWindow();
    } catch (e) {
      popupWindow = false;
    }
  }
}

textarea.value = localStorage.getItem("html");
onChange();
