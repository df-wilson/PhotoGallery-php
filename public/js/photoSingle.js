let keywords = [];

window.addEventListener("load", function()
{
    setDescriptionUpdateCheck(false);
    fetchKeywords();
});

function fetchKeywords() 
{
    axios.get('/api/keywords')
       .then(({data}) => {
           keywords = data.keywords;
       });
}

function doneAddKeyword()
{
    let addKeywordForm = document.getElementById("add-keyword-form");
    addKeywordForm.style.display = "none";
}

function removeKeyword(photoId, keywordId)
{
    let keywordDivId = "keyword"+keywordId;
    
    axios.delete('/api/keywords/'+keywordId+'/photo/'+photoId)
       .then(({data}) => {
           let element = document.getElementById(keywordDivId);
           element.parentNode.removeChild(element);
       });
}

function showNextPhoto(photoId)
{
    window.location.href ='/photos/'+photoId+'/next';
}

function showPreviousPhoto(photoId)
{
    window.location.href ='/photos/'+photoId+'/prev';
}

function showUpdateButton()
{
    let updateButton = document.getElementById("desc-update-button");
    updateButton.style.display = "inline-block";
}

function showAddKeyword()
{
    let keywordList = document.getElementById('keyword-options');

    keywords.forEach(function(item) {
        var option = document.createElement('option');
        option.value = item.name;

        keywordList.appendChild(option);
    });
    let addKeywordForm = document.getElementById("add-keyword-form");
    addKeywordForm.style.display = "block";
}

function showKeywordEditBtns()
{
    const editLink = document.getElementById("keyword-edit-link");
    editLink.style.display = "none";
    
    const editButtons = document.querySelectorAll('.keyword-edit');
    editButtons.forEach(element => {
        element.style.display = "inline-block";
    });
}

function submitDescription(id)
{
    let description = document.getElementById("desc-text").value;
    
    axios.post('/api/photos/'+id+'/description', {
        description: description
    })
        .then(function (response) {
            console.log(response);
            setDescriptionUpdateCheck(true);
        })
        .catch(function (error) {
            console.log(error);
        });
}

function setDescriptionUpdateCheck(isShown)
{
    let updateCheck = document.getElementById("desc-update-check");

    if(isShown) {
        updateCheck.style.display = "inline-block";
    } else {
        updateCheck.style.display = "none";
    }
}

function checkKeywordInputForEnter(event, photoId)
{
    if(event.keyCode === 13){
        event.preventDefault();
        
        submitKeyword(photoId);
    }
}

function submitKeyword(photoId)
{
    let keywordElement = document.getElementById("keyword-input");
    let keyword = keywordElement.value.trim();

    if(keyword.length < 1) {
        return;
    }

    axios.post('/api/keywords/photo/' + photoId, {
        keyword: keyword
        })
        .then(function (response) {
            if(response.status == 201) {
                let keywordDiv = document.getElementById("keyword-div");
                keywordDiv.insertAdjacentHTML('beforeend',`<div class="mb-1"><button class="btn btn-xs">${keyword}</button></div>`);
            }
        })
        .catch(function (error) {
            console.log(error);
        });

    keywordElement.value = "";
}

function submitTitleOnEnter(event, photoId)
{
    if(event.keyCode === 13) {
        event.preventDefault();

        submitTitle(photoId);
    }
}

function submitTitle(id)
{
    let title = document.getElementById("img-title-input").value;
    axios.post('/api/photos/'+id+'/title', {
        title: title
    })
        .then(function (response) {
            console.log(response);
            document.getElementById("img-title-text").innerText=title;
        })
        .catch(function (error) {
            console.log(error);
        });

    cancelUpdateTitle();
}

function cancelUpdateTitle()
{
    let title = document.getElementById("img-title");
    title.style.display = "block";

    let title_edit = document.getElementById("img-title-edit");
    title_edit.style.display = "none";
}

function editTitle()
{
    let title = document.getElementById("img-title-text");

    let titleDiv = document.getElementById("img-title");
    titleDiv.style.display = "none";

    let title_edit = document.getElementById("img-title-edit");
    document.getElementById("img-title-input").value = title.innerText;
    title_edit.style.display = "block";
}

function submitTogglePublic(id)
{
    let checkbox = document.getElementById("public-checkbox");

    axios.post('/api/photos/'+id+'/public', {
        checked: checkbox.checked
    })
        .then(function (response) {
            console.log(response);
        })
        .catch(function (error) {
            console.log(error);
        });
}

function showAllForKeyword(keywordId)
{
    window.location.href = '/photos/keywords/'+keywordId;
}