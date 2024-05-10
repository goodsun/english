console.log('storage.js');
function test(){
  console.log(text);
  console.log(quiz_no);
}

let storageData = {};

function saveData(val) {
  storageData[quiz_no] = val;
  localStorage.setItem(text, JSON.stringify(storageData));
  console.log(text);
  console.log(storageData);
  understandCheck();
}

function removeData() {
  delete storageData[quiz_no];
  localStorage.setItem(text, JSON.stringify(storageData));
  understandCheck();
}

function loadData() {
  const value = localStorage.getItem(text);
  if(value != null){
    storageData = JSON.parse(value);
  }
  console.log(text);
  console.log(storageData);
  understandCheck();
}

function removeItem() {
  if(confirm('本当に削除しますか？')){
    localStorage.removeItem(text);
    storageData = {};
  }
}

function storageReset() {
  if(confirm('すべてを削除しようとしています。本当に削除しますか？')){
    localStorage.clear();
  }
  console.log('全削除');
  console.log(text);
  console.log(storageData);
}

loadData();
