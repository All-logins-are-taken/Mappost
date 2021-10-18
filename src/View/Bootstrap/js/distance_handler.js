function handler() {
    let action = 'addresses';
    let form = document.getElementById("addresses_form");
    let formData = new FormData(form)


    let messageBox = document.getElementById("message")
    messageBox.innerText = "";

    for (var value of formData.values()) {
        if(value.length < 10 || value.length > 200)
        {
            alert(value.replaceAll(/ /g,'').length);
            messageBox.setAttribute("class", "alert alert-danger");
            messageBox.innerText  = "Min 10 symbols and Max 200";
            return true;
        }
    }
    formData.append("action", action);
    const request = new XMLHttpRequest();
    const url = "/";

    request.open("POST", url);

    request.addEventListener("readystatechange", () => {
        if(request.readyState === 4 && request.status === 200) {
            messageBox.innerText = request.responseText;
            messageBox.setAttribute("class", "alert alert-success");
        }
        else {
            messageBox.setAttribute("class", "alert alert-danger");
            messageBox.innerText  = "Server error";
        }
    });

    request.send(formData);
}

function add() {
    let inputs = document.getElementById("inputs");
    let button = document.getElementById("add_button");
    let count = inputs.childElementCount;
    let addition = '<div class="mb-3">' +
        '<input type="text" class="form-control" placeholder="dravnieku 10 riga" aria-label="Address'+(count+1)+'" minlength="10" maxlength="200" required>' +
        '<button type="button" class="btn-close" aria-label="Close" style="position: absolute; margin: -30px 0 0 -30px;" onclick="this.parentElement.remove()"></button>' +
        '</div>'

    if (count === 10) {
        alert("Max elements exceeded");
        button.disabled = true;
    }
    else if (count === 9) {
        button.disabled = true;
        inputs.insertAdjacentHTML('beforeend', addition);
    }
    else {
        inputs.insertAdjacentHTML('beforeend', addition);
    }
}
