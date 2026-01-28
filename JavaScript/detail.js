if (localStorage.getItem("show-favourites") === null) {
  localStorage.setItem("show-favourites", false);
}

if (localStorage.getItem("favouriteArticlesIDs") === null) {
  localStorage.setItem("favouriteArticlesIDs", JSON.stringify([]));
}

const articleCheckbox = document.querySelector(".filter-favourite");
if (
  JSON.parse(localStorage.getItem("favouriteArticlesIDs")).includes(
    articleCheckbox.id
  )
) {
  articleCheckbox.checked = true;
}

articleCheckbox.addEventListener("change", (e) => {
  let currentIDs = JSON.parse(localStorage.getItem("favouriteArticlesIDs"));
  if (articleCheckbox.checked) {
    currentIDs.push(articleCheckbox.id);
  } else {
    currentIDs = currentIDs.filter((id) => id !== articleCheckbox.id);
  }
  localStorage.setItem("favouriteArticlesIDs", JSON.stringify(currentIDs));
});
