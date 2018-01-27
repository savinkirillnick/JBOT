/*
XML HTTP REQUEST
Funnymay version
*/
function getXMLHTTP() {
    var x = false;
    try {
        x = new XMLHttpRequest();
    }
    catch(e) {
        try {
            x = new ActiveXObject("Microsoft.XMLHTTP");
        }
        catch(ex) {
            try {
                req = new ActiveXObject("Msxml2.XMLHTTP");
            }
            catch(e1) {
                x = false;
            }
        }
    }
    return x;
}