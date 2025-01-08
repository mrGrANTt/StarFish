const adder = document.getElementById("adder");
const remover = document.getElementById("remover");
const list = document.getElementById("list");
const posts_title = document.getElementById("posts-title");
const removabel = document.getElementById("removabel");


function add() {
    if(list.childElementCount < 20) {
        let div = document.createElement("div");
        div.classList.add('post-item');

        let title = document.createElement("input");
        let text = document.createElement("textarea");

        title.type = "text";
        title.name = "title" + (list.childElementCount + 1);
        title.placeholder = "title";
        title.value = "";
        title.maxLength = 30;

        text.type = "text";
        text.name = "text" + (list.childElementCount + 1);
        text.placeholder = "text";
        title.maxLength = 255;

        
        div.appendChild(title);
        div.appendChild(text);

        list.appendChild(div);
    }
}

function remove() {
    if(list.hasChildNodes()) {
        for(let child of list.lastChild.childNodes) {
            if(child.type == "hidden") {
                removabel.value = removabel.value + " " + child.value;
            }
        }
        list.removeChild(list.lastChild);
    }
}

function updateTitle() {
    posts_title.innerHTML = "Posts(" + list.childElementCount + "/20)";
}

adder.onclick = () => {
    add();
    updateTitle();
}
remover.onclick = () => {
    remove();
    updateTitle();
}

updateTitle()