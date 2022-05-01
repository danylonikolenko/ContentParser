
function downloadTxt(array) {

    var csvContent = '';
    array.forEach(function (infoArray, index) {
        let text = infoArray.text + ";";
        let title = infoArray.title + " : ";
        let dataString = title + text;
        csvContent +=  dataString + '\n';
    });

    var hiddenElement = document.createElement('a');
    hiddenElement.href = 'data:text/text;charset=windows-1251,' + (csvContent);
    hiddenElement.target = '_blank';

    //provide the name for the CSV file to be downloaded
    hiddenElement.download = 'articles.txt';
    hiddenElement.click();
    // Content is the csv generated string above
}



