 /* Esta función recolectará la entrada del usuario
y tomando en cuenta dicha entrada mostrará u ocultará información 
de una fila en particular */

function filter() {
    let value = document.getElementById("searchInput").value.toUpperCase();
    let names = document.getElementById("names");
    let rows = names.getElementsByTagName("tr");
  
    for (i = 0; i < rows.length; i++) {
      let column = rows[i].getElementsByTagName("td")[0];
      let language = column.textContent;
  
      rows[i].style.display =
        language.toUpperCase().indexOf(value) > -1 ? "" : "none";
    }
  }
  
  document.getElementById("searchInput").addEventListener("keyup", filter);