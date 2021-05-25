const statistic = window.location.pathname.match(/[^/]+$/)[0];
const apiLocation = "/api/" + statistic;

function encodeFormData(formData, putOrder) {
  let s = "";
  for (const pair of formData.entries()) {
    if (putOrder || pair[0].match(/^order\[\d+\]\[[^\]]+\]$/) === null) {
      if (s !== "") {
        s += "&";
      }
      s += encodeURIComponent(pair[0]) + "=" + encodeURIComponent(pair[1]);
    }
  }
  return s;
}

const insertDataForm = document.getElementById("insert-data-form");

insertDataForm.addEventListener('submit', (event) => {
  event.preventDefault();
  const request = new XMLHttpRequest();
  request.open("POST", apiLocation);
  const body = {};
  for (const [column, value] of (new FormData(insertDataForm)).entries()) {
    body[column] = value;
  }
  request.send(JSON.stringify(body));
  request.addEventListener("load", () => {
    if (request.status === 200) {
      insertTable();
    }
    else {
      console.log(request.response);
    }
  });
});

let page = 0;
const getParam = window.location.search.match(/page=(\d+)/);
if (getParam !== null) {
  page = parseInt(getParam[1]) - 1;
}

function insertTable() {
  const table = document.getElementById('visualization');
  const header = table.children[0];
  table.textContent = '';
  table.appendChild(header);
  const request = new XMLHttpRequest();
  request.open("GET", apiLocation + "?page=" + page);
  request.send();
  request.addEventListener("load", () => {
    const response = JSON.parse(request.response);
    for (const row of response.body) {
      const tr = document.createElement("tr");
      for (const cell of row) {
        const td = document.createElement("td");
        const text = document.createTextNode(cell);
        td.appendChild(text);
        tr.appendChild(td);
      }
      table.appendChild(tr);
    }
    insertPageNumbers(parseInt(request.getResponseHeader("X-PageCount")));
  });
}

window.addEventListener('popstate', (event) => {
  page = event.state;
  insertTable();
});

insertTable();

function pageRange(page, pageCount) {
  if (page <= 2) {
    return [0, Math.min(4, pageCount - 1)];
  } else if (page >= pageCount - 3) {
    return [Math.max(0, pageCount - 5), pageCount - 1];
  } else {
    return [page - 2, page + 2];
  }
}

function insertPageNumbers(pageCount) {
  const pageNumbers = document.getElementById('page-numbers');
  pageNumbers.textContent = '';
  const addButton = (content, enabled, newPage) => {
    const text = document.createTextNode(content);
    const button = document.createElement('button');
    button.disabled = !enabled;
    button.addEventListener('click', () => {
      console.log(page);
      window.history.pushState(page, document.title, '/administration/' + statistic + '?page=' + (newPage + 1));
      page = newPage;
      insertTable();
    });
    button.appendChild(text);
    const li = document.createElement("li");
    li.appendChild(button);
    pageNumbers.appendChild(li);
  };
  const [start, end] = pageRange(page, pageCount);
  addButton('←', page !== 0, page - 1);
  for (let i = start; i <= end; ++i) {
    addButton(i + 1, i !== page, i);
  }
  addButton('→', pageCount !== 0 && page !== pageCount - 1, page + 1);
}

document.getElementById('clear-button').addEventListener('click', () => {
  const request = new XMLHttpRequest();
  request.open('DELETE', apiLocation);
  request.send();
  request.addEventListener('load', () => {
    if (request.status === 200) {
      insertTable();
    }
    else {
      console.log(request.response);
    }
  });
});

document.getElementById('insert-button').addEventListener('click', () => {
  const request = new XMLHttpRequest();
  request.open('POST', apiLocation + '?all');
  request.send();
  request.addEventListener('load', () => {
    if (request.status === 200) {
      insertTable();
    }
    else {
      console.log(request.response);
    }
  });
});

/*
  <?php foreach ($table->getBody() as $row): ?>
    <tr>
      <?php foreach (array_values($row) as $index => $cell): ?>
        <?php if($table->getHeader()[$index] !== 'Rowid'): ?>
          <td><?= $cell ?></td>
        <?php endif; ?>
      <?php endforeach; ?>
      <td class="td-delete">
        <a href=<?php echo '?row=' . $row[array_search('Rowid',$table->getHeader())] . '&' . $param ;?>> <img src='/assets/delete.svg' width='23' height='23'></a>
      </td>
    </tr>
  <?php endforeach; ?>
*/
