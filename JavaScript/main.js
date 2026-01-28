if (localStorage.getItem("show-favourites") === null) {
  localStorage.setItem("show-favourites", false);
}

if (localStorage.getItem("favouriteArticlesIDs") === null) {
  localStorage.setItem("favouriteArticlesIDs", JSON.stringify([]));
}

// ----- VALIDATION ~ validating name
function validateName(name) {
  return name.length <= 32 && name.length > 0;
}

const errorMessage = document.getElementById("error-message");
const nameInputField = document.getElementById("article-name");
const buttonCreate = document.getElementById("button-create");

buttonCreate.addEventListener("click", (e) => {
  if (!validateName(nameInputField.value)) {
    e.preventDefault();
    errorMessage.classList.remove("hide");
  }
});

// ----- POPUP ~ opening popup
const popupWindow = document.getElementById("popup");
const buttonCreateArticle = document.getElementById("button-create-article");
const buttonCancel = document.getElementById("cancel");

// buttonCreateArticle opens popup
buttonCreateArticle.addEventListener("click", () => {
  popupWindow.classList.remove("hide");
  popupWindow.style.opacity = 1;
});

// buttonCancel hides popup & error message
buttonCancel.addEventListener("click", () => {
  popupWindow.style.opacity = 0;
  popupWindow.classList.add("hide");
  errorMessage.classList.add("hide");
});

// ----- PAGINATION ~ getting article data ([{id, name, content}]) from php & paginating them

// create List Item to be appended
function articlesToListItems(articles) {
  return articles.map((article) => {
    const li = document.createElement("li");
    const div = document.createElement("div");
    div.classList.add("article");

    const nameHolder = document.createElement("div");
    nameHolder.classList.add("article-nameholder");
    nameHolder.textContent = article.name;

    const buttonsDiv = document.createElement("div");
    buttonsDiv.classList.add("article-buttons");

    const buttonShow = document.createElement("a");
    buttonShow.href = `./article/${article.id}`;
    buttonShow.classList.add("button");
    buttonShow.textContent = "Show";

    const editButton = document.createElement("a");
    editButton.href = `./article-edit/${article.id}`;
    editButton.classList.add("button");
    editButton.textContent = "Edit";

    const buttonDelete = document.createElement("input");
    buttonDelete.type = "button";
    buttonDelete.value = "Delete";
    buttonDelete.id = article.id;
    buttonDelete.classList.add("button", "button-warning", "button-delete");

    const buttonCheckbox = document.createElement("input");
    buttonCheckbox.type = "checkbox";
    if (
      JSON.parse(localStorage.getItem("favouriteArticlesIDs")).includes(
        article.id
      )
    ) {
      buttonCheckbox.checked = true;
    }

    buttonCheckbox.id = article.id;
    buttonCheckbox.classList.add("filter-favourite");

    buttonsDiv.appendChild(buttonCheckbox);
    buttonsDiv.appendChild(buttonShow);
    buttonsDiv.appendChild(editButton);
    buttonsDiv.appendChild(buttonDelete);

    div.appendChild(nameHolder);
    div.appendChild(buttonsDiv);
    li.appendChild(div);

    return li;
  });
}

// including first exkluding last indices
function fillArticleList(startIndex, EndIndex, nodes, parent) {
  parent.innerHTML = "";
  for (let i = startIndex; i < EndIndex; i++) {
    if (nodes[i] === undefined) {
      break;
    }
    parent.appendChild(nodes[i]);
  }
  let checkboxes = document.querySelectorAll(".filter-favourite");
  addEventListenerToCheckboxes(checkboxes);
}

// paginator
function updatePaginator(current, total, paginator) {
  if (current === 0 && total === 0) {
    paginator.innerText = "Page: 1/1";
    return;
  }
  paginator.innerText = `Page: ${current}/${total}`;
}

// addingEventListeners to dynamically generated buttonDeletes
function addEventListenerToDeletes(buttonDeletes, callback) {
  for (let buttonDelete of buttonDeletes) {
    buttonDelete.addEventListener("click", () => {
      callback(url, buttonDelete.id);
    });
  }
}

function addEventListenerToCheckboxes(checkboxes) {
  for (let checkbox of checkboxes) {
    checkbox.addEventListener("change", (e) => {
      console.log(`Checkbox ${checkbox.id} is now ${checkbox.checked}`);
      let currentIDs = JSON.parse(localStorage.getItem("favouriteArticlesIDs"));
      if (checkbox.checked) {
        if (!currentIDs.includes(checkbox.id)) {
          currentIDs.push(checkbox.id);
        }
      } else {
        currentIDs = currentIDs.filter((id) => id !== checkbox.id);
      }
      localStorage.setItem("favouriteArticlesIDs", JSON.stringify(currentIDs));

      if (localStorage.getItem("show-favourites") === "true") {
        let favs = getFavourites();
        fillArticleList(0, 10, favs, articleList);
      }
    });
  }
}

const buttonPrev = document.getElementById("button-prev");
const buttonNext = document.getElementById("button-next");
const articleList = document.getElementById("article-list");
const paginator = document.getElementById("paginator");
let buttonDeletes;
let checkboxes;

// ! articlesData are declared in articles.php
let articles = articlesToListItems(articlesData);
let currentPageIndex = 0;
let lastPageIndex =
  articles.length % 10 === 0
    ? Math.max(0, Math.floor(articles.length / 10 - 1))
    : Math.floor(articles.length / 10);

fillArticleList(0, 10, articles, articleList);

// show next button if more than 1 page
if (currentPageIndex !== lastPageIndex) {
  buttonNext.classList.remove("hide");
}

// click on buttonPrev renders the articles on the previous page
buttonPrev.addEventListener("click", () => {
  // filling
  if (localStorage.getItem("show-favourites") === "true") {
    let prevFavs = getFavourites();
    fillArticleList(
      (currentPageIndex - 1) * 10,
      currentPageIndex * 10,
      prevFavs,
      articleList
    );
  } else {
    fillArticleList(
      (currentPageIndex - 1) * 10,
      currentPageIndex * 10,
      articles,
      articleList
    );
  }

  // updating pagination
  currentPageIndex -= 1;
  updatePaginator(currentPageIndex + 1, lastPageIndex + 1, paginator);

  // hiding || displaying buttonPrev/Next
  buttonNext.classList.remove("hide");
  if (currentPageIndex === 0) {
    buttonPrev.classList.add("hide");
  }

  // adding event listeners to deletes and checkboxes
  buttonDeletes = document.querySelectorAll(".button-delete");
  addEventListenerToDeletes(buttonDeletes, deleteArticle);
  checkboxes = document.querySelectorAll(".filter-favourite");
  addEventListenerToCheckboxes(checkboxes);
});

// click on buttonNext renders the articles on the previous page
buttonNext.addEventListener("click", () => {
  // filling
  if (localStorage.getItem("show-favourites") === "true") {
    let nextFavs = getFavourites();
    fillArticleList(
      (currentPageIndex + 1) * 10,
      (currentPageIndex + 2) * 10,
      nextFavs,
      articleList
    );
  } else {
    fillArticleList(
      (currentPageIndex + 1) * 10,
      (currentPageIndex + 2) * 10,
      articles,
      articleList
    );
  }
  //updating pagination
  currentPageIndex += 1;
  updatePaginator(currentPageIndex + 1, lastPageIndex + 1, paginator);

  // hiding || displaying buttonPrev/Next
  buttonPrev.classList.remove("hide");
  if (currentPageIndex === lastPageIndex) {
    buttonNext.classList.add("hide");
  }

  // adding event listeners to deletes and checkboxes
  buttonDeletes = document.querySelectorAll(".button-delete");
  addEventListenerToDeletes(buttonDeletes, deleteArticle);
  checkboxes = document.querySelectorAll(".filter-favourite");
  addEventListenerToCheckboxes(checkboxes);
});

// ajax delete request
const url = "/~89172187/cms/articles/delete/";

function deleteArticle(url, id) {
  fetch(url + id, {
    method: "DELETE",
    headers: {
      "Content-type": "application/json; charset=UTF-8",
    },
  })
    .then(() => {
      const articleIndex = articlesData.indexOf(
        articlesData.find((article) => article.id === id)
      );
      let currectPageIndex = Math.floor(articleIndex / 10);
      let lastArticleIndex = articlesData.length - 1;

      // deleting the only article on the page
      if (articleIndex === lastArticleIndex && articleIndex % 10 === 0) {
        currectPageIndex -= 1;
      }

      // filtering data and changing articles
      articlesData = articlesData.filter((article) => article.id !== id);
      lastArticleIndex -= 1;
      articles = articlesToListItems(articlesData);

      // filling after deletion
      fillArticleList(
        currectPageIndex * 10,
        (currectPageIndex + 1) * 10,
        articles,
        articleList
      );

      // adding event listener to deletes and checkboxes
      buttonDeletes = document.querySelectorAll(".button-delete");
      checkboxes = document.querySelectorAll(".filter-favourite");

      addEventListenerToCheckboxes(checkboxes);
      addEventListenerToDeletes(buttonDeletes, deleteArticle);

      // update paginator
      const lastPageIndex =
        articles.length % 10 === 0
          ? Math.floor(articles.length / 10 - 1)
          : Math.floor(articles.length / 10);
      updatePaginator(currectPageIndex + 1, lastPageIndex + 1, paginator);

      // hide prev when only one page
      if (currectPageIndex === 0) {
        buttonPrev.classList.add("hide");
      }
    })
    .catch((e) => console.log(e));
}

// adding event listener on first render
buttonDeletes = document.querySelectorAll(".button-delete");
addEventListenerToDeletes(buttonDeletes, deleteArticle);

// FILTERING
const checkboxShowFavourites = document.getElementById("show-favourites");
checkboxes = document.querySelectorAll(".filter-favourite");

if (localStorage.getItem("show-favourites") === "true") {
  checkboxShowFavourites.checked = true;
  let favs = getFavourites();
  fillArticleList(0, 10, favs, articleList);
}

function getFavourites() {
  return articlesToListItems(
    articlesData.filter((article) =>
      JSON.parse(localStorage.getItem("favouriteArticlesIDs")).includes(
        article.id
      )
    )
  );
}

checkboxShowFavourites.addEventListener("change", (e) => {
  localStorage.setItem(
    "show-favourites",
    Boolean(checkboxShowFavourites.checked)
  );
  if (checkboxShowFavourites.checked) {
    let favourites = getFavourites();
    fillArticleList(0, 10, favourites, articleList);
  } else {
    fillArticleList(0, 10, articles, articleList);
  }
  currentPageIndex = 0;
  buttonPrev.classList.add("hide");
});

// adding event listener on first render
addEventListenerToCheckboxes(checkboxes);
