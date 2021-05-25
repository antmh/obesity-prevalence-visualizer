const url = "/api/" + window.location.pathname.match(/[^/]+$/)[0];

function encodeFormData(formData, putOrder) {
  var s = "";
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

insertDataForm.onsubmit = () => {
  const request = new XMLHttpRequest();
  request.open("POST", url);
  const body = {};
  for (const [column, value] of (new FormData(insertDataForm)).entries()) {
    body[column] = value;
  }
  request.send(JSON.stringify(body));
  request.addEventListener("load", () => {
    if (request.status === 200) {
      console.log("ok");
    }
    else {
      console.log(request.response);
    }
  }); 
  return false;
}

var page = 0;

function insertTable() {
  const table = document.getElementById('visualization');
  const request = new XMLHttpRequest();
  console.log(url);
  request.open("GET", url + "?page=" + page);
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

insertTable();

function insertPageNumbers(pageCount) {
  const pageNumbers = document.getElementById("page-numbers");
  const insertPageNumber = (page) => {
    const li = document.createElement("li");
    const text = document.createTextNode(page);
    li.appendChild(text);
    pageNumbers.appendChild(li);
  };
  if(pageCount > 1) {
    insertPageNumber(1);
    if (page !== 0) {
      insertPageNumber("...");
      insertPageNumber(page);
      insertPageNumber(page + 1);
      insertPageNumber(page + 2);
    }
  }
}

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