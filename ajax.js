var xmlHttp = createXmlHttpRequestObject();
function createXmlHttpRequestObject() {
    var xmlHttp;
    if(window.ActiveXObject) {
        try {
            xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
        } catch (e) {
            xmlHttp = false;
        }
    } else {
        try {
            xmlHttp = new XMLHttpRequest();
        }
        catch (e) {
            xmlHttp = false;
        }
    }
if (!xmlHttp)
    alert("Error creating the XMLHttpRequest object.");
else
    return xmlHttp;
}
function ajax() {
    if (xmlHttp.readyState == 4 || xmlHttp.readyState == 0) {
        likes = encodeURIComponent(document.getElementById("likes").value);
        likes += 1;
        xmlHttp.open("GET", "ajax.php?likes=" + likes, true);
        xmlHttp.onreadystatechange = processServerResponse;
        xmlHttp.send(null);
    } else {
        setTimeout('ajax()', 1000);
    }
}
function processServerResponse() {
    if (xmlHttp.readyState == 4) {
        if (xmlHttp.status == 200) {
            xmlResponse = xmlHttp.responseXML;
            xmlDocumentElement = xmlResponse.documentElement;
            showlikes = xmlDocumentElement.firstChild.data;
            document.getElementById("likes").innerHTML = '<i>' + showlikes + '</i>';
            setTimeout('ajax()', 1000);
        } else {
            alert("Error accessing the server: " + xmlHttp.statusText);
        }
    }
}