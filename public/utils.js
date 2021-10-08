/* Just for visuals. */

var popupWindow = false;

function updateMenu(value) {
  const isEmpty = (textarea.value.length === 0);
  saveButton.disabled = isEmpty;
  shareButton.disabled = (cleanHTML.length === 0 || !('share' in navigator));
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
  updateLeaderboard();
  setInterval(updateLeaderboard, 60000);
});

var leaderboardData = "";

async function updateLeaderboard() {
  let data = await fetch("https://so-xss-hof.terjanq.me/hof.json?"+Math.random(), {cache: "no-cache"}).then(e=>e.json());
  
  let text = JSON.stringify(data);
  
  if (leaderboardData === text) return
  leaderboardData = text;
  
  const hof = document.getElementById('hof');
  hof.textContent = '';
  for (let p of data) {
    let player = document.createElement("li");
    
    let a = document.createElement("a");
    a.innerText = p.name;
    if (p.handle) a.href = "https://twitter.com/"+ encodeURIComponent(p.handle);
    
    player.appendChild(a);

    if(p.comment){
      let comment = document.createElement("span");
      comment.className = "comment";
      comment.innerText = p.comment;
      player.appendChild(comment);
    }
    hof.appendChild(player);
  }
}
