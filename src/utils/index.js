const themeSetup = () => {
  const appTheme = localStorage.getItem('theme') || 'light';
  //document.querySelector('body').classList.add('bg-gray-50');
  if (
    appTheme === 'dark' &&
    // @ts-ignore: Object is possibly 'null'.
    document.querySelector('body').classList.contains('app-theme')
  ) {
    // @ts-ignore: Object is possibly 'null'.
    document.querySelector('body').classList.remove('bg-secondary-light');
    document.querySelector('body').classList.add('bg-primary-dark');
  } else {
    // @ts-ignore: Object is possibly 'null'.
    document.querySelector('body').classList.remove('bg-primary-dark');
    document.querySelector('body').classList.add('bg-secondary-light');
  }
}

const generatePaginationList = (pagination) => {
  let list = [];
  let current = pagination.current_page
  for (let i = 1; i <= pagination.last_page; i++) {
    if (i === current) {
      list.push(i);
    } else if (i <= 3 || i > pagination.last_page - 2 || i >= pagination.current_page - 1 && i <= pagination.current_page + 1) {
      list.push(i);
    } else if (i === 4 || i === pagination.last_page - 3) {
      list.push('...');
    }
  }
  list = list.filter((item, pos) => {
    return list.indexOf(item) === pos;
  });
  return list;
}

const openViewHelper = (id) => {
  let elem = document.getElementById(id);
  // Check if it has class hidden,
  // if it has remove it, else add it.
  if (elem.classList.contains('hidden')) {
    elem.classList.remove('hidden');
  } else {
    elem.classList.add('hidden');
  }
}

const tabViewHelper = (toShow, toHide) => {
  let elem1 = document.getElementById(toShow);
  let elem2 = document.getElementById(toHide);
  elem1.classList.remove('hidden');
  elem2.classList.add('hidden');
}

const toggelLabelHelper = (id, newLabel, oldLabel) => {
  let elem = document.getElementById(id);
  if (elem.innerHTML === newLabel) {
    elem.innerHTML = oldLabel;
  } else {
    elem.innerHTML = newLabel;
  }
}

const searchFiltersHelper = (filters) => {
  let newFilters = [];
  for (let i = 0; i < filters.length; i++) {
    newFilters.push({
      "id": filters[i],
      "text": filters[i].charAt(0).toUpperCase() + filters[i].slice(1)
    });
  }

  return newFilters;
}

const getError = (error) => {
  let e = null;
  if (error != null) {
    if (error.response != undefined && error.response.status === 422) {
      e = error.response.data || error.message;
    } else {
      e = error.message;
    }
  }
  return e;
}

const jobSkills = (job) => {
  if (job === null || job === undefined || job === '[]') {
    return JSON.parse("[\"No Skills\"]");
  }
  return JSON.parse(job);
}

export {
  themeSetup,
  generatePaginationList,
  openViewHelper,
  tabViewHelper,
  toggelLabelHelper,
  searchFiltersHelper,
  getError,
  jobSkills
}

