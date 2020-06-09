let keywords = [];

window.addEventListener("load", function()
{
    fetchKeywords();
});

function fetchKeywords() {
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

function showUpdateButton()
{
    let updateButton = document.getElementById("desc-update-button");
    updateButton.style.display = "block";

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

function submitDescription(id)
{
    let description = document.getElementById("desc-text").value;
    
    axios.post('/api/photos/'+id+'/description', {
        description: description
    })
        .then(function (response) {
            console.log(response);
        })
        .catch(function (error) {
            console.log(error);
        });
}

function submitKeyword(photoId)
{
    let keywordElement = document.getElementById("keyword-input");
    let keyword = keywordElement.value;
    keyword.trim();

    if(keyword.length < 1) {
        return;
    }

    axios.post('/api/keywords/photo/' + photoId, {
        keyword: keyword
        })
        .then(function (response) {
            if(response.status == 201) {
                let keywordDiv = document.getElementById("keyword-div");
                keywordDiv.insertAdjacentHTML('beforeend',`<p><button class="btn">${keyword}</button></p>`);
            }
        })
        .catch(function (error) {
            console.log(error);
        });

    keywordElement.value = "";
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

function showAllForKeyword()
{
    console.log("In showAllForKeyword");
}