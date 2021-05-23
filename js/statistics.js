function encodeFormData(formData, putOrder) {
    var s = '';
    for (const pair of formData.entries()) {
        if (putOrder || pair[0].match(/^order\[\d+\]\[[^\]]+\]$/) === null) {
            if (s !== '') {
                s += '&';
            }
            s += encodeURIComponent(pair[0]) + '=' + encodeURIComponent(pair[1]);
        }
    }
    return s;
}

var visualization = document.getElementById('visualization');

function updateTable(json) {
    const table = document.createElement('table');
    const headerRow = document.createElement('tr');
    table.appendChild(headerRow);
    for (const headerCell of json.header) {
        const th = document.createElement('th');
        th.appendChild(document.createTextNode(headerCell));
        headerRow.appendChild(th);
    }
    for (const row of json.body) {
        const tr = document.createElement('tr');
        for (const cell of row) {
            const td = document.createElement('td');
            td.appendChild(document.createTextNode(cell));
            tr.appendChild(td);
        }
        table.appendChild(tr);
    }
    visualization.replaceWith(table);
    visualization = table;
    table.id = 'visualization';
}

function updateBarChart(json) {
    const barChart = document.createElement('div');
    barChart.className = 'bar-chart';
    json.xPercentages.forEach((val, i) => {
        const button = document.createElement('button');
        button.className = 'bar-chart-column';
        button.style.height = val + '%';
        button.style.animationDelay = 0.025 * i + 's';
        button.style.setProperty('--value', '"' + json.xValues[i] + '"');
        button.style.setProperty('--tooltip', '"' + json.yValues[i] + '"');
        barChart.appendChild(button);
    });
    visualization.replaceWith(barChart);
    visualization = barChart;
    barChart.id = 'visualization';
}

function updateLineChart(json) {
    const lineChart = document.createElement('div');
    lineChart.className = 'line-chart';
    for (const [key, data] of Object.entries(json.dataSets)) {
        data.forEach((coord, i) => {
            if (i !== 0) {
                const segment = document.createElement('div');
                segment.className = 'line-chart-segment';
                segment.style.setProperty('--x1', data[i - 1].x + '%');
                segment.style.setProperty('--y1', data[i - 1].y + '%');
                segment.style.setProperty('--x2', coord.x + '%');
                segment.style.setProperty('--y2', coord.y + '%');
                lineChart.appendChild(segment);
            }
            const point = document.createElement('div');
            point.className = 'line-chart-point';
            point.style.setProperty('--info', '"' + coord.info + '"');
            point.style.setProperty('--x', coord.x + '%');
            point.style.setProperty('--y', coord.y + '%');
            lineChart.appendChild(point);
        });
    }
    visualization.replaceWith(lineChart);
    visualization = lineChart;
    lineChart.id = 'visualization';
}

const selectForm = document.getElementById('select-form');
const orderFieldset = document.getElementById('order-fieldset');
const exportFieldset = document.getElementById('export-fieldset');

function updateOrderFieldset() {
    const data = new FormData(selectForm);
    orderFieldset.style.display = data.get('Type') === 'Line chart' ? 'none' : 'block';
}

updateOrderFieldset();

function updateExportFieldset() {
    const data = new FormData(selectForm);
    const exportFields = exportFieldset.getElementsByTagName('input');
    const type = data.get('Type')
    for (const exportField of exportFields) {
        var show = true;
        if (exportField.value === 'CSV') {
            show = type === 'Table';
        } else if (exportField.value === 'SVG' || exportField.value === 'PNG') {
            show = type !== 'Table';
        }
        const listItem = exportField.parentElement.parentElement;
        listItem.style.display = show ? 'block' : 'none';
        if (exportField.checked && !show) {
            exportFields[0].click();
        }
    }
}

updateExportFieldset();

selectForm.onchange = () => {
    updateOrderFieldset();
    updateExportFieldset();
}

function updateVisualization(type, json) {
    if (type === 'Bar chart') {
        updateBarChart(json);
    } else if (type === 'Line chart') {
        updateLineChart(json);
    } else if (type === 'Table') {
        updateTable(json);
    }
}

selectForm.onsubmit = () => {
    const data = new FormData(selectForm);
    const request = new XMLHttpRequest();
    const type = data.get('Type');
    const locationAndQuery = window.location.pathname + '?' + encodeFormData(data, type !== 'Line chart');
    const url = 'api' + locationAndQuery;
    if (data.get('export') === 'None') {
        request.open('GET', url);
        request.send();
        request.addEventListener('load', () => {
            const response = JSON.parse(request.response);
            window.history.pushState([type, response], document.title, locationAndQuery);
            updateVisualization(type, response);
        });
    } else {
        const a = document.createElement('a');
        a.href = url;
        a.click();
    }
    return false;
};

window.onpopstate = (event) => {
    updateVisualization(event.state[0], event.state[1]);
};
